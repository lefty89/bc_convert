(function () {
    "use strict";

    const BYTES_PER_CHUNK = 1024 * 1024; // 1MB chunk sizes.

    self.importScripts(
        "/typo3conf/ext/bc_convert/Resources/Public/js/cryptojs/rollups/sha1.js",
        "/typo3conf/ext/bc_convert/Resources/Public/js/cryptojs/components/lib-typedarrays-min.js");

    /**
     * split file into small pieces
     * @param ret
     * @param blob
     */
    self.splitAndHashFile = function(ret, blob)
    {
        ret.chunks = [];
        var start  = 0;
        var sha1   = CryptoJS.algo.SHA1.create();

        while (start < blob.size) {

            var chunk = null;

            // Note: blob.slice has changed semantics and been prefixed.
            // See http://goo.gl/U9mE5.
            if ('mozSlice' in blob)
                chunk = blob.mozSlice(start, start + BYTES_PER_CHUNK);
            else if ('webkitSlice' in blob)
                chunk = blob.webkitSlice(start, start + BYTES_PER_CHUNK);
            else
                chunk = blob.slice(start, start + BYTES_PER_CHUNK);

            start += BYTES_PER_CHUNK;

            // hash chunk blob
            if (chunk !== null) {
                // set info message
                self.postMessage({returnMethod: "updateInfoMessage", text: "Hashing... (" + (start/BYTES_PER_CHUNK) + "/" + Math.ceil(blob.size/BYTES_PER_CHUNK) + ")", progress: start/blob.size});

                var reader = new FileReaderSync();
                // read file as array buffer
                var arrayBuffer = reader.readAsArrayBuffer(chunk);
                var wordArray   = CryptoJS.lib.WordArray.create(arrayBuffer);

                // updates the incremental hashing
                sha1.update(wordArray);

                // add chunk hash to list
                ret.chunks.push({blob: chunk, hash: CryptoJS.SHA1(wordArray).toString(CryptoJS.enc.Hex)});
            }
        }
        // calculate the complete file hash
        ret.hash = sha1.finalize().toString(CryptoJS.enc.Hex);
    };

    /**
     * main / worker interface
     */
    self.addEventListener('message', function(e) {

        // return value
        var r = {returnMethod: e.data.returnMethod};

        // switch function
        switch(e.data.method) {
            case 'splitAndHashFile': {
                self.splitAndHashFile(r, e.data.parameter.blob);
                break;
            }
        }

        self.postMessage(r);

    }, false);

}());