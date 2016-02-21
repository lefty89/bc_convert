jQuery(document).ready(function() {

    var _FILE = null;
    var _MANIFEST = null;

    jQuery('.file-selctor').change(function(e) {

    });

    jQuery('.file-drop').on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
    }).on('dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
    }).on('drop', function(event) {
        // stop the browser from opening the file
        event.preventDefault();
        event.stopPropagation();

        // get the selected files
        var files = event.originalEvent.dataTransfer.files;

        // preparing upload
        prepareFile(files[0]);
    });

    function createManifest()
    {
        _MANIFEST = {
            name:   _FILE.blob.name,
            size:   _FILE.blob.size,
            mime:   _FILE.blob.type,
            chunks: []
        };

        // add hashes to manifest file
        for (k in _FILE.chunks) {
            _MANIFEST.chunks[k] = _FILE.chunks[k].hash;
        }

        setLoadingBarText("Upload Manifest");
        sendManifest();
    }

    function sendManifest()
    {
        var url = jQuery('.file-drop').attr('data-url');
        var xhr = new XMLHttpRequest();
        var msg = JSON.stringify(_MANIFEST);

        xhr.open('POST', url, true);
        xhr.responseType = 'text';

        // custom header
        //xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-Message-Size", msg.length);
        xhr.setRequestHeader("X-File-Hash", _FILE.hash);
        xhr.setRequestHeader("X-Method", "1");

        // error handler
        xhr.upload.addEventListener('error', function(e) {
            alert(e.target.responseText);
        });

        // upload progress
        xhr.upload.addEventListener('progress', function(e) {
            console.log("upload manifest");
        });

        // notice that the event handler is on xhr and not xhr.upload
        xhr.addEventListener('readystatechange', function(e) {
            if ((xhr.readyState == 4) && (xhr.status == 200)) {
                console.log("manifest upload completed");

                // update manifest
                _MANIFEST.chunks = JSON.parse(xhr.responseText);

                // upload chunks
                uploadChunk();
            }
        });

        xhr.send(msg);
    }

    function prepareFile(blob)
    {
        // create file container object
        _FILE = {
            blob   : blob,
            chunks : [],
            parts  : 0,
            hash   : ''
        };

        prepareLoadingBar();
        // hashes the given blob and split it into pieces
        worker.postMessage({method: 'splitAndHashFile', returnMethod: 'createManifest', parameter: {blob: _FILE.blob}});
    }

    function uploadChunk()
    {
        var chunk = getRandomChunk();
        if (chunk === null) {
            setLoadingBarText("Upload completed");
            return;
        }

        var url = jQuery('.file-drop').attr('data-url');
        var xhr = new XMLHttpRequest();

        xhr.open('POST', url, true);
        xhr.responseType = 'text';

        xhr.setRequestHeader("X-Message-Size", chunk.blob.size);
        xhr.setRequestHeader("X-File-Hash",  _FILE.hash);
        xhr.setRequestHeader("X-Method",  "2");

        // return value
        xhr.upload.addEventListener('load', function(e) {
        });

        xhr.upload.addEventListener('error', function(e) {
        });

        // upload progress
        xhr.upload.addEventListener('progress', function(e) {
        });

        // notice that the event handler is on xhr and not xhr.upload
        xhr.addEventListener('readystatechange', function(e) {
            if ((xhr.readyState == 4) && (xhr.status == 200)) {
                console.log("chunk upload completed");

                // update manifest
                _MANIFEST.chunks = JSON.parse(xhr.responseText);

                updateLoadingBar();
                // upload chunks
                uploadChunk();
            }
        });

        setLoadingBarText("Upload chunk");
        xhr.send(chunk.blob);
    }

    function getRandomChunk(o)
    {
        var chunk = null;
        var hash = (!_MANIFEST.chunks.length) ? '' : _MANIFEST.chunks[(Math.floor(Math.random() * (_MANIFEST.chunks.length - 1)))];

        if (hash) {

            // push to temp array
            for ( var k in _FILE.chunks ) {
                chunk = (_FILE.chunks[k].hash === hash) ? _FILE.chunks[k] : chunk;
            }
        }

        return chunk;
    }

    /**
     * hides the upload box
     */
    function prepareLoadingBar() {
        jQuery('#bugcluster-video-converter .bc-content .bc-upload .bc-file-drop').hide();
    }

    /**
     * shows the loading circle
     */
    function updateLoadingBar(progress)
    {
        var pro = (progress) || (_FILE.chunks.length-_MANIFEST.chunks.length)/_FILE.chunks.length;

        jQuery('#bugcluster-video-converter .bc-content .bc-upload .progress').show();
        jQuery('#bugcluster-video-converter .bc-content .bc-upload .progress .circle-container').circleProgress('value', pro);
    }

    /**
     * sets the loading circle text
     */
    function setLoadingBarText(text)
    {
        jQuery('#bugcluster-video-converter .bc-content .bc-upload .progress .circle-container strong').text(function(){
            return text;
        });
    }

    /**
     * WEBWORKER PART
     */
    var workerUrl = jQuery('.file-drop').attr('data-worker');
    var worker = new Worker(workerUrl);

    // webworker callback
    worker.onmessage = function(e) {
        // switch function
        switch(e.data.returnMethod) {
            case 'createManifest': {
                // save file hash
                _FILE.hash = e.data.hash;
                // save chunks
                _FILE.chunks = e.data.chunks;
                // creates the manifest
                createManifest();
                break;
            }
            case 'updateInfoMessage': {
                // update info text
                updateLoadingBar(e.data.progress);
                setLoadingBarText(e.data.text);
                break;
            }
        }
    };

});
