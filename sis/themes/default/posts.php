<?php theme_include('header'); ?>
<section class="search-cnt">
    <div class="right-block block-search">
        <form id="" action="/search" method="get" class="form-inline">
            <div class="form-group searchbox">
                <label>Tìm kiếm</label>
                <input type="text" id="search_term" name="search_term" class="form-control"
                       value="<?php echo search_term(); ?>">
                <button type="submit" class="btn btn-primary" value="<?php echo search_term(); ?>">Tìm kiếm</button>
            </div>
        </form>
    </div>
</section>
<section class="content">
    <?php if (has_posts()): ?>
        <ul class="items">
            <?php posts(); ?>
            <div class="summary">
                <li class="col-sm-6">
                    <article class="wrap coursebox">
                        <header>
                            <a href="<?php echo article_url(); ?>"
                               title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
                        </header>
                        <section class="content">
                            <div class="content-post"><?php echo article_html(); ?></div>
                            <span><a href="<?php echo article_url(); ?>">Chi tiết <i class="fa fa-angle-right"
                                                                                     aria-hidden="true"></i></a></span>
                        </section>
                        <footer>
                            Đăng ngày
                            <time
                                datetime="<?php echo article_date(); ?>"><?php echo relative_time(article_time()); ?></time>
                            bởi <?php echo article_author('real_name'); ?>.
                        </footer>
                        <br>
                    </article>
                </li>
                <?php $i = 0;
                while (posts()): ?>
                    <li class="col-sm-6" style="">
                        <article class="wrap coursebox">
                            <header>
                                <a href="<?php echo article_url(); ?>"
                                   title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
                            </header>
                            <section class="content">
                                <div class="content-post"><?php echo article_html(); ?></div>
                                <span><a href="<?php echo article_url(); ?>">Chi tiết <i class="fa fa-angle-right"
                                                                                         aria-hidden="true"></i></a></span>
                            </section>
                            <footer>
                                Đăng
                                <time
                                    datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time>
                                bởi <?php echo article_author('real_name'); ?>.
                            </footer>
                            <br>
                        </article>
                    </li>
                <?php endwhile; ?>

            </div>
        </ul>
        <?php if (has_pagination()): ?>
            <nav class="pagination">
                <div class="wrap">
                    <div class="previous">
                        <?php echo posts_prev(); ?>
                    </div>
                    <div class="next">
                        <?php echo posts_next(); ?>
                    </div>
                </div>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="wrap">
            <h1>No posts yet!</h1>
            <p>Looks like you have some writing to do!</p>
        </div>
    <?php endif; ?>

</section>

<?php theme_include('footer'); ?>
