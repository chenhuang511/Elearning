<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>


        <nav>
            <?php echo Html::link('admin/advance/add', __('advance.create_advance'), array('class' => 'btn')); ?>
        </nav>

</hgroup>

<section class="wrap">
    <?php echo $messages; ?>
    <nav class="sidebar advance_sidebar">
        <nav class="statuses" style="margin-bottom: 40px">
            <p>Tình trạng</p>
            <?php echo Html::link('admin/advance', '<span class="icon"></span> ' . __('global.all'), array(
                'class' => isset($status) ? ($status == 'all' ? 'active' : '') : ''
            )); ?>
            <?php foreach(array('published', 'draft','rebuff') as $type): ?>
                <?php echo Html::link('admin/advance/status/' . $type, '<span class="icon"></span> ' . __('advance.' . $type), array(
                    'class' => ($status == $type) ? 'active' : ''
                )); ?>
            <?php endforeach; ?>
        </nav>
        <nav class="search">
            <form method="post" action="<?php echo Uri::to('admin/advance/search'); ?>" enctype="multipart/form-data" novalidate>
                <p>Tìm kiếm</p>
                <?php echo Form::text('name_user', Input::previous('name_user'), array('id' => 'label-name_user','placeholder'=>'Người xin tạm ứng','style'=>'height: 35px;')); ?>
                <?php echo Form::button(__('global.search'), array(
                    'type' => 'submit',
                    'class' => 'btn',
                    'data-loading' => __('global.searching')
                )); ?>
            </form>
        </nav>

    </nav>
    <?php if($advance->results): ?>
        <ul class="main list" href="">

            <?php foreach($advance->results as $article): ?>
                <li>
                    <a class="advance-link" style="text-decoration: none;" href="<?php echo Uri::to('admin/advance/edit/' . $article->id); ?>">
                        <strong style=" font-size: 30px; padding-bottom: 73px;">Đơn tạm ứng số: <?php echo $article->id?></strong>
                        <table class="table-advance">
                            <tr>
                                <td>Khóa học:</td>
                                <td style="font-size: 20px;"><?php echo $article->course_name?></td>
                            </tr>
                            <tr>
                                <td>Người yêu cầu:</td>
                                <td style="font-size: 20px;"><?php echo $article->user?></td>
                            </tr>
                            <tr>
                                <td>Số tiền:</td>
                                <td style="font-size: 25px;"><?php echo $article->money?></td>
                            </tr>
                            <tr>
                                <td>Lí do:</td>
                                <td style="font-size: 20px;"><?php echo $article->reason?></td>
                            </tr>
                            <tr>
                                <td>Tình trạng:</td>
                                <td style="font-size: 20px;"><?php echo __('advance.' . $article->status); ?></td>
                            </tr>
                            <?php
                            if($article->user_check){
                            ?><tr>
                                <td>Người xác nhận:</td>
                                <td> <?php echo $article->user_check ;?></td>
                            </tr>
                                <?php }?>
                        </table>

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
