<?php echo $header; ?>
    <ol class="breadcrumb">
        <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
        <li class="active">Danh mục</li>
    </ol>

    <hgroup class="wrap">


        <nav>
            <?php echo Html::link('admin/categories/add', __('categories.create_category'), array('class' => 'btn')); ?>
        </nav>
    </hgroup>

    <section class="wrap">
        <?php echo $messages; ?>

        <ul class="list">
            <?php foreach ($categories->results as $category): ?>
                <li>
                    <a href="<?php echo Uri::to('admin/categories/edit/' . $category->id); ?>">
                        <strong><?php echo $category->title; ?></strong>

                        <span><?php echo $category->slug; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <aside class="paging"><?php echo $categories->links(); ?></aside>
        <input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
    </section>

<?php echo $footer; ?>
