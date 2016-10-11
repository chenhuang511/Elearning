<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Danh sách bài viết</li>
</ol>

<section class="wrap">
    <?php echo $messages; ?>
    <p class="text-right">
        <?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn  btn-primary')); ?>
    </p>
    <div class="row">
        <div class="statusbar col-sm-3">
            <div class="sidebarbox">
                <h5>Trạng thái</h5>
                <ul class="sidebar statuses">
                    <li>
                        <?php echo Html::link('admin/posts', '<span class="icon"></span> ' . __('global.all'), array(
                            'class' => isset($status) ? ($status == 'all' ? 'active' : '') : ''
                        )); ?>
                    </li>
                    <?php foreach (array('published', 'draft', 'archived') as $type): ?>
                        <li>
                            <?php echo Html::link('admin/posts/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
                                'class' => ($status == $type) ? 'active' : ''
                            )); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="sidebarbox">
                <h5>Danh mục</h5>
                <ul class="sidebar categories">
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <?php echo Html::link('admin/posts/category/' . $cat->slug, $cat->title, array(
                                'class' => (isset($category) and $category->id == $cat->id) ? 'active' : ''
                            )); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="list_post col-sm-9">
            <?php if ($posts->count): ?>
                <ul class="main list post-box">
                    <?php foreach ($posts->results as $article): ?>
                        <li>
                            <a class="post-title" href="<?php echo Uri::to('admin/posts/edit/' . $article->id); ?>">
                                <?php echo $article->title; ?>
                            </a>
                            <span class="post-time"> Đăng ngày:
					<time><?php echo date('d-m-Y', strtotime($article->created)); ?></time>
					<em class="status <?php echo $article->status; ?>"
                        title="<?php echo __('global.' . $article->status); ?>">
						<?php echo __('global.' . $article->status); ?>
					</em>
				</span>

                            <p class="post-desc"><?php echo strip_tags($article->description); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <aside class="paging"><?php echo $posts->links(); ?></aside>

            <?php else: ?>

                <p class="empty posts">
                    <span class="icon"></span>
                    <?php echo __('posts.noposts_desc'); ?><br>
                    <?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn')); ?>
                </p>

            <?php endif; ?>
        </div>
</section>

<?php echo $footer; ?>
