<div id="slide_player">
<div class="slider" id="slide_body">
<?php echo $this->element("slide_div", array("slide" => $slide)); ?>
</div>

<div class="slide_control" style="display:none;font-size:16px;">
    <i id="slide_control_fullscreen" class="fa fa-television"></i>
    <?php echo $this->Html->link('<i id="slide_control_home_link" class="fa fa-link"></i>', array("controller" => "slides", "action" => "view", $slide["id"], "full_base" => true), array('escape' => false)); ?>&nbsp;
    <?php if($slide["downloadable"]): ?>
    <?php echo $this->Html->link('<i id="slide_control_download_link" class="fa fa-download"></i>', array("controller" => "slides", "action" => "download", $slide["id"], "full_base" => true), array('escape' => false)); ?>&nbsp;
    <?php endif; ?>
    <a href="javascript:void(0);return false;"><i id="slide_control_fast_backward" class="fa fa-fast-backward"></i></a>&nbsp;
    <span class="slide_prev slide_control_link" /></span>&nbsp;&nbsp;
    <span id="pager" class="small"></span>&nbsp;&nbsp;
    <span class="slide_next slide_control_link"></span>&nbsp;
    <a href="javascript:void(0);return false;"><i id="slide_control_fast_forward" class="fa fa-fast-forward"></i></a>
    <div id="slide_progress"></div>
</div>
</div>

<script type="text/javascript">
$1102(document).ready(function(){
    $1102(".openslideshare_body img.lazy").lazyload({
        threshold : 200,
        effect: "fadeIn"
    });

    $1102(".openslideshare_body .bxslider_<?php echo $slide["key"]; ?>").show();
    $1102(".openslideshare_body .slide_control").show();

    // Initialize jQuery UI slider
    $1102('#slide_progress').slider({
        min: 1,
        max: <?php echo count($file_list); ?>,
        step: 1,
        value: 1,
        create: function(e, ui) {
        }
    });
    var start_position = <?php echo $start_position; ?>;
    if (start_position != 0) {
        var current_img = $1102(".openslideshare_body ul.bxslider_<?php echo $slide["key"]; ?> img.image-" +  (start_position));
        var current_ds = current_img.attr("data-src");
        current_img.attr("src", current_ds).removeClass("lazy");
    }

    function bxslider_init() {
        var slider_config = {
            mode: 'horizontal',
            controls: true,
            responsive:true,
            pager:true,
            startSlide: start_position,
            pagerType:'short',
            prevText: '◀',
            nextText: '▶',
            prevSelector: ".slide_prev",
            nextSelector: ".slide_next",
            pagerSelector: "#pager",
            adaptiveHeight: false,
            infiniteLoop: false,
            onSlideBefore: function($slideElement, oldIndex, newIndex){
                var $lazy_next2 = $1102(".openslideshare_body ul.bxslider_<?php echo $slide["key"]; ?> img.image-" +  (newIndex + 1));
                var $load_next2 = $lazy_next2.attr("data-src");
                $lazy_next2.attr("src",$load_next2).removeClass("lazy");

                var $lazy_next = $1102(".openslideshare_body ul.bxslider_<?php echo $slide["key"]; ?> img.image-" +  (newIndex));
                var $load_next = $lazy_next.attr("data-src");
                $lazy_next.attr("src",$load_next).removeClass("lazy");
                $lazy_next.each(function(){});
            },
            onSlideAfter: function($slideElement, oldIndex, newIndex){
                $1102('#slide_progress').slider("value", newIndex + 1);
            }
        }
        myslider = $1102('.openslideshare_body .bxslider_<?php echo $slide["key"]; ?>').bxSlider(slider_config);
        // Add links to move backward or next.
        $1102('.openslideshare_body .bx-wrapper').append($1102('<img src="<?php echo Router::url($this->Html->url("/img/spacer.png"), true); ?>" style="z-index:9999;width:128px;height:128px;position:absolute;top:50%;left:0%;margin-top:-64px;margin-left:0px;cursor:pointer;border:0px !important" onclick="javascript:myslider.goToPrevSlide();" />'));
        $1102('.openslideshare_body .bx-wrapper').append($1102('<img src="<?php echo Router::url($this->Html->url("/img/spacer.png"), true); ?>" style="z-index:9999;width:128px;height:128px;position:absolute;top:50%;left:100%;margin-top:-64px;margin-left:-128px;display:inline;cursor:pointer;border:0px !important" onclick="javascript:myslider.goToNextSlide();" />'));

        // Add keyboard shortcut
        Mousetrap.bind(['j', 'right'], function(e) {
            myslider.goToNextSlide();
        });
        Mousetrap.bind(['k', 'left'], function(e) {
            myslider.goToPrevSlide();
        });
    }

    bxslider_init();
    $1102('#slide_progress').slider({
        change: function(e, ui) { myslider.goToSlide(ui.value -1); }
    });

    var viewport_attr = $1102('.openslideshare_body .bx-viewport').attr('style');
    var timer = setInterval( updateDiv, 10 * 1000);

    // Peiodically update the div when the number of slide is zero.
    function updateDiv() {
        if ($1102('.openslideshare_body .slider ul').attr("data-count") > 0) {
            return;
        }
        $1102.ajax({
            type: 'GET',
            async: false,
            url: "<?php echo Router::url($this->Html->url(array("controller" => "slides", "action" => "update_view", $slide["id"])), true); ?>",
            cache: false,
            success: function(result) {
                if (result + 0 > 0) {
                    location.reload();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });
    }
    $1102("#slide_control_fast_backward").click(function () {
        myslider.goToSlide(0);
    });
    $1102("#slide_control_fast_forward").click(function () {
        myslider.goToSlide(<?php echo count($file_list) -1; ?>);
    });

    $1102("#slide_control_fullscreen").click(function () {
        current_width = $1102('#slide_player').width();
        current_height = $1102('#slide_player').height();
        if (screen.width > screen.height) {
            h = 100;
            w = Math.round(100 * current_height / current_width);
        } else {
            w = 100;
            h = Math.round(100 * current_width / current_height);
        }
        var elm = document.getElementById('slide_player');
        var css = '<style type="text/css">';
            css = css + '.openslideshare_body div#slide_player:-webkit-full-screen { width:' + w + '%; height: ' + h + '%; }';
            css = css + '.openslideshare_body div#slide_player:-moz-full-screen { width:' + w + '% !important; height: ' + h + '% !important; }';
            css = css + '.openslideshare_body div#slide_player:-ms-fullscreen { width:' + w + '%; height: ' + h + '%; }';
            css = css + '.openslideshare_body div#slide_player:fullscreen { width:' + w + '%; height: ' + h + '%; }';
            if (elm.mozRequestFullScreen) {
                css = css + '.bx-wrapper { width: ' + w + '% !important; height:' + h + '% !important; }';
            }
            css = css + '</style>';
        $1102('#fullscreen_css_placeholder').append(css);
        requestFullscreen(elm);
    });

    function requestFullscreen(target) {
        if (target.webkitRequestFullscreen) {
            target.webkitRequestFullscreen(); // Chrome15+, Safari5.1+, Opera15+
        } else if (target.mozRequestFullScreen) {
            target.mozRequestFullScreen();    // FF10+
        } else if (target.msRequestFullscreen) {
            target.msRequestFullscreen();     // IE11+
        } else if (target.requestFullscreen) {
            target.requestFullscreen();       //HTML5 Fullscreen API
        }
    }

    document.addEventListener("webkitfullscreenchange", handleFSevent, false);
    document.addEventListener("mozfullscreenchange", handleFSevent, false);
    document.addEventListener("MSFullscreenChange", handleFSevent, false);
    document.addEventListener("fullscreenchange", handleFSevent, false);
    function handleFSevent() {
        if (!((document.webkitFullscreenElement && document.webkitFullscreenElement !== null)
            || (document.mozFullScreenElement && document.mozFullScreenElement !== null)
            || (document.msFullscreenElement && document.msFullscreenElement !== null)
            || (document.fullScreenElement && document.fullScreenElement !== null))) {
            $1102('#fullscreen_css_placeholder').empty();
        }
        myslider.reloadSlider();
    }
});
</script>
<div id="fullscreen_css_placeholder"></div>
