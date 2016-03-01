jQuery(document).ready(function() {
    // tabs switcher
    jQuery('#bc-uploader .bc-controls li').click(switchTabs);
    // send queue
    jQuery('#bc-uploader .transcode-template form').submit(sendQueue);
    // circle lib
    jQuery('.circle-container').circleProgress({
        animation: false,
        startAngle: -Math.PI/2,
        value: 0,
        size: 1000,
        fill: {
            gradient: ['#ff1e41', '#ff5f43']
        }
    });
});

/**
 * @type {null}
 * @private
 */
var _TRANSCODE_TIMEOUT = null;

/**
 * send new queue
 */
function sendQueue(event)
{
    event.preventDefault();

    var url  = jQuery('.transcode-template form').attr('action');
    var data = jQuery('.transcode-template form').serializeArray().reduce(function(obj, item) {obj[item.name] = item.value; return obj;}, {});

    jQuery.getJSON(url, data, transcodeResponse);
}

/**
 * callback for transcode request
 * @param {array} json
 */
function transcodeResponse(json)
{
    switch(json.state) {
        case 'transcodeable': showTranscodeForm(json); break;
        case 'converting': showConvertingState(json); break;
        case 'waiting': showConvertingPosition(json); break;
        case 'finalize': showFinalisationMessage(json); break;
    }

    // restart checking
    _TRANSCODE_TIMEOUT = setTimeout(function(){ getQueueState(); }, 10000);
}

/**
 * asks the server for the current transcoding state
 */
function getQueueState()
{
    jQuery.getJSON( '/', {
        'tx_bcconvert_file[action]':                'queueState',
        'tx_bcconvert_file[controller]':            'File',
        'tx_bcconvert_file[hash]':                  jQuery('#bc-uploader .bc-banner').attr('title'),
        'type':                                     165237
    }, transcodeResponse);
}

/**
 * gets a list of mirrored videos
 */
function getTranscodeList()
{
    jQuery.getJSON( '/', {
        'tx_bcconvert_file[action]':                'transcodeList',
        'tx_bcconvert_file[controller]':            'File',
        'tx_bcconvert_file[hash]':                  jQuery('#bc-uploader .bc-banner').attr('title'),
        'type':                                     165237
    }, function(data) {

        // clears the list
        var list = jQuery('.bc-content .bc-list');
        list.children().remove();

        if (data.length) {
            var ul = jQuery("<ul />").appendTo(list);

            for (k in data) {
                ul.append(jQuery("<li />").append(jQuery("<a />", {href : data[k]['path'], text : data[k]['name'], target: '_blank'})));
            }
        }
        else {
            jQuery("<p />", {text : "No Mirrors found"}).appendTo(list);
        }

    });
}

/**
 * show finalisation message
 * @param {array} json
 */
function showFinalisationMessage(json) {

    jQuery('.bc-content .bc-transcode .spinner').hide();
    jQuery('.bc-content .bc-transcode .form').hide();
    jQuery('.bc-content .bc-transcode .progress').show();

    // currently waiting in queue
    jQuery('#bugcluster-video-converter .bc-content .bc-transcode .progress .circle-container strong').text(function(){
        return  "Waiting for file finalisation";

    });
    jQuery('#bugcluster-video-converter .bc-content .bc-transcode .progress .circle-container').circleProgress('value', 100);
}

/**
 * show current conversion position
 * @param {array} json
 */
function showConvertingPosition(json) {

    jQuery('.bc-content .bc-transcode .spinner').hide();
    jQuery('.bc-content .bc-transcode .form').hide();
    jQuery('.bc-content .bc-transcode .progress').show();

    // currently waiting in queue
    jQuery('#bugcluster-video-converter .bc-content .bc-transcode .progress .circle-container strong').text(function(){
        return  ('position' in json) ? ("Position: " + json.position) : "Get queue position";

    });
    jQuery('#bugcluster-video-converter .bc-content .bc-transcode .progress .circle-container').circleProgress('value', 0);
}

/**
 * show current conversion state
 * @param {array} json
 */
function showConvertingState(json)
{
    jQuery('.bc-content .bc-transcode .spinner').hide();
    jQuery('.bc-content .bc-transcode .form').hide();
    jQuery('.bc-content .bc-transcode .progress').show();

    // currently converting my file
    jQuery('#bugcluster-video-converter .bc-content .bc-transcode .progress .circle-container strong').html(function(){
        return  "Progress: " + json.progress + " % <br> Duration: " + secondsToHms(parseFloat(json.duration)) + " <br> Time: "  + secondsToHms(parseFloat(json.ctime));
    });
    jQuery('#bugcluster-video-converter .bc-content .bc-transcode .progress .circle-container').circleProgress('value', (parseInt(json.progress)/100));
}

/**
 * show default transcode form
 */
function showTranscodeForm()
{
    jQuery('.bc-content .bc-transcode .spinner').hide();
    jQuery('.bc-content .bc-transcode .progress').hide();
    jQuery('.bc-content .bc-transcode .form').show();
}

/**
 * tabs switcher
 * @param {event} e
 */
function switchTabs(e)
{
    // return of control is disabled
    if (jQuery(e.target).hasClass('disabled')) return;

    var index = jQuery(e.target).attr('data-index');

    jQuery('#bc-uploader .bc-content > div').hide();
    jQuery('#bc-uploader .bc-content > div[data-index="'+index+'"]').show();

    // if transcode tab check the current status
    if (index == "2") {
        getQueueState();
    }
    // clears trancode on every other tab
    if ((index != "2") && (_TRANSCODE_TIMEOUT != null)) {
        clearTimeout(_TRANSCODE_TIMEOUT);
    }
    // gets the mirror list
    if (index == "3") {
        getTranscodeList();
    }
}

/**
 * enables or disables the different tabs
 * @param {bool} uploadTab
 * @param {bool} transcodeTab
 * @param {bool} listTab
 */
function enableTabs(uploadTab, transcodeTab, listTab)
{
    jQuery('.bc-controls .upload').toggleClass('disabled', !uploadTab);
    jQuery('.bc-controls .transcode').toggleClass('disabled', !transcodeTab);
    jQuery('.bc-controls .list').toggleClass('disabled', !listTab);
}

/**
 * sets a link on the banner file name
 * @param {string} path
 * @param {string} hash
 */
function setBannerLink(path, hash)
{
    var banner = jQuery('#bc-uploader .bc-banner');

    banner.show();
    banner.attr('title', hash);
    banner.find('.filelink').attr('href', path);


    // temporarry
    jQuery('#bc-uploader .transcode-template .hash ').val(hash);
}

/**
 * sets the header data
 * @param {string} name
 * @param {int} size
 */
function fillBannerData(name, size)
{
    var banner = jQuery('#bc-uploader .bc-banner');

    banner.show();
    banner.find('.filename').text(name);
    banner.find('.filesize').text(bytesToSize(size, 2));
}


/**
 * hides the upload box
 */
function prepareLoadingBar() {
    jQuery('#bugcluster-video-converter .bc-content .bc-upload .bc-file-drop').hide();
}

/**
 * shows the loading circle
 * @param {number} progress
 */
function updateLoadingBar(progress)
{
    jQuery('#bugcluster-video-converter .bc-content .bc-upload .progress').show();
    jQuery('#bugcluster-video-converter .bc-content .bc-upload .progress .circle-container').circleProgress('value', progress);
}

/**
 * sets the loading circle text
 * @param {string} text
 */
function setLoadingBarText(text)
{
    jQuery('#bugcluster-video-converter .bc-content .bc-upload .progress .circle-container strong').text(text);
}

/**
 * Convert number of bytes into human readable format
 * FROM: http://codeaid.net/javascript/convert-size-in-bytes-to-human-readable-format-%28javascript%29
 *
 * @param {int} bytes     Number of bytes to convert
 * @param {int} precision Number of digits after the decimal separator
 * @return {string}
 */
function bytesToSize(bytes, precision)
{
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;

    if ((bytes >= 0) && (bytes < kilobyte)) {
        return bytes + ' B';

    } else if ((bytes >= kilobyte) && (bytes < megabyte)) {
        return (bytes / kilobyte).toFixed(precision) + ' KB';

    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
        return (bytes / megabyte).toFixed(precision) + ' MB';

    } else if ((bytes >= gigabyte) && (bytes < terabyte)) {
        return (bytes / gigabyte).toFixed(precision) + ' GB';

    } else if (bytes >= terabyte) {
        return (bytes / terabyte).toFixed(precision) + ' TB';

    } else {
        return bytes + ' B';
    }
}

/**
 * Formats the given seconds in the hms format
 * FROM: http://stackoverflow.com/a/5539081
 * @param {int} d
 * @returns {string}
 */
function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);
    return ((h > 0 ? h + ":" + (m < 10 ? "0" : "") : "") + m + ":" + (s < 10 ? "0" : "") + s); }


