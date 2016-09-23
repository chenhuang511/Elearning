<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>


        <nav>
            <?php echo Html::link('admin/advance/add', __('advance.create_advance'), array('class' => 'btn')); ?>
        </nav>

</hgroup>

<section class="wrap">

    <nav class="sidebar">
        <nav class="statuses">
            <p>Tình trạng</p>
            <?php echo Html::link('admin/advance', '<span class="icon"></span> ' . __('global.all'), array(
                'class' => isset($status) ? ($status == 'all' ? 'active' : '') : ''
            )); ?>
            <?php foreach(array('published', 'draft') as $type): ?>
                <?php echo Html::link('admin/advance/status/' . $type, '<span class="icon"></span> ' . __('advance.' . $type), array(
                    'class' => ($status == $type) ? 'active' : ''
                )); ?>
            <?php endforeach; ?>
        </nav>

    </nav>
    <?php if($advance->results): ?>
        <ul class="main list">

            <?php foreach($advance->results as $article): ?>
                <li>
                    <a href="">
                        <strong>Đơn tạm ứng số: <?php echo $article->id?></strong>
                        <span>
                            <p>Người yêu cầu: <?php echo $article->full_name?> </p>
                            <p>Chức vụ: <?php echo $article->position ?> </p>
                            <p>Số tiền: <strong><?php echo $article->money ?></strong></p>
                            <p>Lí do: <?php echo $article->reason ?></p>
                            <?php if($article->status == 'draft'){
                                ?>
                                    <p>Đang yêu cầu</p>
                                <?php
                            } else{
                                ?>
                                    <p>Đã được đáp ứng</p>
                                <?php

                            }?>

				        </span>

<!--                        <p>--><?php //echo strip_tags($article->description); ?><!--</p>-->
                    </a>
                </li>
            <?php endforeach; ?>

        </ul>

        <aside class="paging"><?php echo $advance->links(); ?></aside>

    <?php else: ?>

        <p class="empty posts">
            <span class="icon"></span>
            <?php echo __('posts.noposts_desc'); ?><br>
            <?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn')); ?>
        </p>

    <?php endif; ?>
</section>

<?php echo $footer; ?>
