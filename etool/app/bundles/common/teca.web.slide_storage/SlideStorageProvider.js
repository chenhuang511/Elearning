define(function() {
    function SlideStorageProvider() {
        this.impl = localStorage;
        this.name = "Slide Storage";
        this.id = "slidestorage";
    }
    var alerted = false;

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    SlideStorageProvider.prototype = {
        ready: function() {
            return true;
        },

        bg: function() {

        },

        ls: function(path, regex, cb) {
            // $.ajax({
            //     url: '/contentcreator/slidestorage.php',
            //     type: 'POST',
            //     data: {
            //         action: 'listPresentations',
            //         filename: prefix + path,
            //     },
            //     success: function (response) {
            //         var list = JSON.parse(response);
            //         if (typeof list === 'object'){
            //             var fnames = [];
            //             for (var i = 0; i < list.length; ++i) {
            //                 var fname = list[i];
            //                 if (fname.indexOf(prefix) == 0 &&
            //                     (regex == null || regex.exec(fname) != null)) {
            //                     fnames.push(fname.substring(prefix.length));
            //                 }
            //             }
            //             cb(fnames);
            //         } else {
            //             console.log(list);
            //         }
            //     },
            //     error: function (e) {
            //         console.log(e.message);
            //     }
            // });

            return this;
        },

        rm: function(path, cb) {
            // $.ajax({
            //     url: '/contentcreator/slidestorage.php',
            //     type: 'POST',
            //     data: {
            //         action: 'removePresentations',
            //         filename: prefix + path,
            //         userid: userid
            //     },
            //     success: function (response) {
            //         console.log(response);
            //         if(cb) {
            //             cb(true);
            //         }
            //     },
            //     error: function (e) {
            //         console.log(e.message);
            //     }
            // });

            return this;
        },

        getContents: function(path, cb) {
            // var userid = getParameterByName('userid');
            // $.ajax({
            //     url: '/contentcreator/slidestorage.php',
            //     type: 'POST',
            //     data: {
            //         action: 'getContents',
            //         filename: prefix + path,
            //         userid: userid
            //     },
            //     success: function (response) {
            //         if (response != null){
            //             try {
            //                 var data = JSON.parse(response);
            //                 if (typeof data === 'string') {
            //                     console.log(data);
            //                 } else {
            //                     cb(data);
            //                 }
            //             } catch (e) {
            //                 cb(null, e);
            //             }
            //         }
            //     },
            //     error: function (e) {
            //         console.log(e.message);
            //     }
            // });
            return this;
        },

        setContents: function(path, senddata, cb) {
            $.ajax({
                url: '/contentcreator/slidestorage.php',
                type: 'POST',
                data: {
                    action: 'setContents',
                    filename: path,
                    data: JSON.stringify(senddata),
                },
                success: function (response) {
                    var resp = JSON.parse(response);
                    console.log(resp);
                    window.presentationId = parseInt(resp.slideid) || undefined;
                },
                error: function (e) {
                    console.log(e.message);
                }
            });

            return this;
        }
    };

    return SlideStorageProvider;
});
