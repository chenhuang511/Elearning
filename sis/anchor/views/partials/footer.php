</div>
</div>
</div>

<footer class="footer clearfix navbar-fixed-bottom" id="footer">
    <div class="container-fluid">
        <div class="footer-inner">
            <div id="powered-by" class="left-footer">
                <div class="main-footer">
                    <a href="<?php echo base_url(); ?>" class="logo">
                        <img src="<?php echo theme_url('/img/site-logo.png'); ?>" alt="qldt">
                    </a>
                    <h2 class="school-name">
                        Trường Đào Tạo Nghiệp Vụ<br>Bảo Hiểm Xã Hội Việt Nam
                    </h2>
                </div>
            </div>
            <div class="right-footer">
                <p>&copy; 2016. NCCSOFT VIETNAM TECHNOLOGY</p>
                <p>Email: <a href="mailto:contact@nccsoft.vn">contact@nccsoft.vn</a></p>
            </div>
        </div>
    </div>
</footer>
<script src="<?php echo asset_url('js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo asset_url('js/main.js'); ?>"></script>
<?php if (Auth::user()): ?>
    <script>
        // Confirm any deletions
        $('.delete').on('click', function () {
            return confirm('<?php echo __('global.confirm_delete'); ?>');
        });
    </script>
<?php endif; ?>
<script>
    var nav = document.getElementById('header');
    var height = nav.offsetHeight;
    window.onscroll = function (e) {
        var top = (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);
        if (top > height) {
            if (nav.className.indexOf('f-nav') == -1) {
                nav.className += " f-nav";
            }
        } else {
            nav.className = nav.className.replace(/\b f-nav\b/, '');
        }
    }
</script>
<script>
    (function ($) {
        var expandMenu = $('#expand_menu'),
            mainMenu = $('#main_menu'),
            mainContent = $('#main_content'),
            mainBody = $('.main-body'),
            collapseMenu = $('#collapse_menu');

        expandMenu.on('click', function (e) {
            mainMenu.hide();
            mainMenu.removeClass('col-sm-3');
            mainContent.removeClass('col-sm-9');
            mainBody.addClass('container');
            collapseMenu.show();
            e.preventDefault();
        });

        collapseMenu.on('click', function(e) {
            mainMenu.show();
            mainMenu.addClass('col-sm-3');
            mainContent.addClass('col-sm-9');
            mainBody.removeClass('container');
            collapseMenu.hide();
            e.preventDefault();
        });
    })(jQuery);
</script>
</body>
</html>
