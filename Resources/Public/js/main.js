jQuery(document).ready(function() {

        const BYTES_PER_CHUNK = 1024 * 1024; // 1MB chunk sizes.

        var _FILE = null;
        var _MANIFEST = null;

        // switch between drop field and form
        // if (Modernizr.touch) jQuery('.file-form').show(); else
        // jQuery('.file-drop').show();

        // get blob builder
        window.BlobBuilder = window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder;

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

            // Now we need to get the files that were dropped
            // The normal method would be to use event.dataTransfer.files
            // but as jquery creates its own event object you ave to access
            // the browser even through originalEvent. which looks like this
            var files = event.originalEvent.dataTransfer.files;

            // split files into multiple chunks
            prepareFile(files[0]);
        });

        jQuery('.file-form').submit(function(e) {

            e.preventDefault();

            // get input files
            var files = this[0].files;

            // split files into multiple chunks
            createContainer(files[0]);
        });

        function createManifest(o)
        {
            _MANIFEST = {
                name:   o.blob.name,
                size:   o.blob.size,
                mime:   o.blob.type,
                chunks: []
            };

            // add hashes to manifest file
            for (k in o.chunks) {
                _MANIFEST.chunks[k] = o.chunks[k].hash;
            }

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


                    prepareLoadingBar();
                    // upload chunks
                    uploadChunk();
                }
            });

            xhr.send(msg);
        }

        function prepareFile(blob)
        {
            // crate container object
            var o = _FILE = {};

            // add vars
            o.blob    = blob;
            o.chunks  = [];
            o.parts   = 0;
            o.hash    = '';

            // AFTER HASHING: split whole file into small pieces
            o.hashingFinished = splitFile;

            // hash blobs
            hashBlob(o);
        }

        function hashBlob(o)
        {
            var fileReader = new FileReader();

            fileReader.onload = function(event){

                // get buffers
                var arrayBuffer = event.target.result;
                var wordArray   = CryptoJS.lib.WordArray.create(arrayBuffer);

                // assign hash
                o.hash = CryptoJS.MD5(wordArray).toString(CryptoJS.enc.Hex);

                // return
                o.hashingFinished.call(this, o);
            };
            fileReader.readAsArrayBuffer(o.blob);
        }

        function splitFile(o)
        {
            const SIZE = o.blob.size;

            var start = 0;

            while (start < SIZE) {

                // add chunk obj to tmp and add callback
                var co = o.chunks[(start / BYTES_PER_CHUNK)] = {
                    // AFTER HASHING: create manifest
                    hashingFinished : function(){
                        if ((++o.parts) * BYTES_PER_CHUNK >= SIZE) {
                            createManifest(o);
                        }
                    }
                };

                // Note: blob.slice has changed semantics and been prefixed.
                // See http://goo.gl/U9mE5.
                if ('mozSlice' in o.blob)
                    co.blob = o.blob.mozSlice(start, start + BYTES_PER_CHUNK);
                else
                    co.blob = o.blob.slice(start, start + BYTES_PER_CHUNK);

                start += BYTES_PER_CHUNK;

                // hash chunk blob
                hashBlob(co);
            }
        }

        function uploadChunk()
        {
            var chunk = getRandomChunk();
            if (chunk === null) return;

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



        function prepareLoadingBar()
        {
            var container = jQuery('#bugcluster-video-converter .progress-bar');

            // remove children
            container.children().remove();

            for (var i=0; i<_FILE.chunks.length; i++) {
                container.append(
                    jQuery('<span>', {class: "chunk-"+_FILE.chunks[i].hash}).css({
                        left: (100/_FILE.chunks.length)*i+"%",
                        width: 0
                    })
                );
            }
        }

        function updateLoadingBar()
        {
            var container = jQuery('#bugcluster-video-converter .progress-bar');

            for (var i=0; i<_MANIFEST.chunks.length; i++) {
                jQuery(container).find(".chunk-"+_FILE.chunks[i].hash).css('width', function(){
                    return (100/_FILE.chunks.length) + "%";
                });
            }
        }


});
