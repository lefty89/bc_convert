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
        var xhr = new XMLHttpRequest();
        var msg = JSON.stringify(_MANIFEST);

        xhr.open('POST', TYPO3_BCCONVERT.MANIFEST_URL, true);
        xhr.responseType = 'text';

        // custom header
        xhr.setRequestHeader("X-Message-Size", msg.length);
        xhr.setRequestHeader("X-File-Hash", _FILE.hash);

        // error handler
        xhr.upload.addEventListener('error', function(e) {
            alert(e.target.responseText);
        });
        // upload progress
        xhr.upload.addEventListener('progress', function(e) {
        });
        // notice that the event handler is on xhr and not xhr.upload
        xhr.addEventListener('readystatechange', function(e) {
            if ((xhr.readyState == 4) && (xhr.status == 200)) {
                // process result
                onUploadFinished(xhr.responseText);
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

        // ui stuff
        prepareLoadingBar();
        fillBannerData(blob.name, blob.size);

        // hashes the given blob and split it into pieces
        worker.postMessage({method: 'splitAndHashFile', returnMethod: 'createManifest', parameter: {blob: _FILE.blob}});
    }

    function uploadChunk()
    {
        var chunk = getRandomChunk();
        if (chunk === null) return;

        var xhr = new XMLHttpRequest();

        xhr.open('POST', TYPO3_BCCONVERT.CHUNK_URL, true);
        xhr.responseType = 'text';

        // custom header
        xhr.setRequestHeader("X-Message-Size", chunk.blob.size);
        xhr.setRequestHeader("X-File-Hash",  _FILE.hash);

        // return value
        xhr.upload.addEventListener('load', function(e) {
        });
        // error handler
        xhr.upload.addEventListener('error', function(e) {
        });
        // upload progress
        xhr.upload.addEventListener('progress', function(e) {
        });

        // notice that the event handler is on xhr and not xhr.upload
        xhr.addEventListener('readystatechange', function(e) {
            if ((xhr.readyState == 4) && (xhr.status == 200)) {
                // process result
                onUploadFinished(xhr.responseText);
            }
        });

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
     * handle positive response from xhr upload
     * @param responseText
     */
    function onUploadFinished(responseText)
    {
        var data = JSON.parse(responseText);
        _MANIFEST.chunks = data.chunks;

        // ui update
        var progress = (_FILE.chunks.length-_MANIFEST.chunks.length)/_FILE.chunks.length;
        updateLoadingBar(progress);

        if (_MANIFEST.chunks.length > 0) {
            setLoadingBarText("Uploading: "+Math.round(progress*100)+" %");
            uploadChunk();
        }
        else {
            setLoadingBarText("Upload finished");
            setBannerLink(data.link, _FILE.hash);
            enableTabs(true, Boolean(data.cable), true);
        }
    }


    /**
     * WEBWORKER PART
     */
    var workerUrl = TYPO3_BCCONVERT.WORKER_JS;
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
                // ui stuff
                updateLoadingBar(e.data.progress);
                setLoadingBarText(e.data.text);
                break;
            }
        }
    };

});
