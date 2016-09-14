<!DOCTYPE html>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Cache-Control" content="max-age=0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="Expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <title><?php echo $OUTPUT->page_title(); ?></title>

    <link rel="stylesheet" href="styles/main.css">

    <!-- can't build this in due to font imports -->
    <link rel="stylesheet" href="preview_export/reveal/css/theme/default.css">

    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <link rel="stylesheet" type="text/css" href="styles/built.css">
    <script type="text/javascript" src="preview_export/download_assist/swfobject.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/KaTeX/0.6.0/katex.min.css">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/ui-lightness/jquery-ui.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/KaTeX/0.6.0/katex.min.js"></script>
</head>
<body class="bg-default">
<?php
echo $OUTPUT->course_content_header();
echo $OUTPUT->main_content();
echo $OUTPUT->course_content_footer();
?>
    <!--[IF IE]>
    <div class="container">
        <div class="alert alert-success">
            Internet Explorer does not support the 3-D transitions required by <strong>Strut</strong>.
            <br/>
            <br/>
            <strong>Strut</strong> currenly only works in <a href="http://www.mozilla.org/en-US/firefox/new/">Firefox</a>, <a href="https://www.google.com/intl/en/chrome/browser/">Chrome</a> and <a href="http://support.apple.com/kb/DL1531">Safari</a>.
            <br/>
            We do hope to support IE 10 sometime in the future.
            <br/><br/>
            Sorry for the inconvenience.
        </div>
    </div>
    <![endif]-->
    <script>
        window.isOptimized = true;
        if (!Function.prototype.bind) {
            Function.prototype.bind = function (oThis) {
                if (typeof this !== "function") {
                    // closest thing possible to the ECMAScript 5 internal IsCallable function
                    throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
                }

                var aArgs = Array.prototype.slice.call(arguments, 1),
                    fToBind = this,
                    fNOP = function () {},
                    fBound = function () {
                        return fToBind.apply(this instanceof fNOP && oThis
                                ? this
                                : oThis,
                            aArgs.concat(Array.prototype.slice.call(arguments)));
                    };

                fNOP.prototype = this.prototype;
                fBound.prototype = new fNOP();

                return fBound;
            };
        }

        if (!Array.prototype.some) {
            Array.prototype.some = function(fun /*, thisp */) {
                'use strict';

                if (this == null) {
                    throw new TypeError();
                }

                var thisp, i,
                    t = Object(this),
                    len = t.length >>> 0;
                if (typeof fun !== 'function') {
                    throw new TypeError();
                }

                thisp = arguments[1];
                for (i = 0; i < len; i++) {
                    if (i in t && fun.call(thisp, t[i], i, t)) {
                        return true;
                    }
                }

                return false;
            };
        }

        if (!Array.prototype.forEach) {
            Array.prototype.forEach = function (fn, scope) {
                'use strict';
                var i, len;
                for (i = 0, len = this.length; i < len; ++i) {
                    if (i in this) {
                        fn.call(scope, this[i], i, this);
                    }
                }
            };
        }

        var head = document.getElementsByTagName('head')[0];
        function appendScript(src) {
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.src = src;
            head.appendChild(s);
        }

        if (window.location.href.indexOf("preview=true") == -1) {
            window.dlSupported = 'download' in document.createElement('a');
            window.hasFlash = swfobject.hasFlashPlayerVersion(9);
            if (!dlSupported && window.hasFlash) {
                appendScript('preview_export/download_assist/downloadify.min.js');
            }
        }
    </script>
    <script data-main="scripts/amd-app" src="scripts/libs/require.js"></script>
    <div id="modals">
    </div>
</body>
</html>
