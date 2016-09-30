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

    </nav>
    <?php if($advance->results): ?>
        <ul class="main list list_advance" href="">
            <form action="<?php echo Uri::to('admin/advance/search'); ?>" method="get" class="pull-right form-inline mb10">
                <div class="form-group">
                    <label for="gradeMin">Tiền</label>
                    <?php echo Form::number('moneyMin', Input::get('gradeMin'), array('class' => 'form-control', 'id' => 'moneyMin')); ?>
                    <label for="gradeMax">Tới</label>
                    <?php echo Form::number('moneyMax', Input::get('gradeMax'), array('class' => 'form-control', 'id' => 'moneyMax')); ?>
                </div>
                <div class="form-group">
                    <?php echo Form::text('key_course', Input::get('key'), array('class' => 'form-control', 'placeholder' => 'Khóa học')); ?>
                    <?php echo Form::text('key_name', Input::get('key'), array('class' => 'form-control', 'placeholder' => 'Tên sinh viên')); ?>
                </div>
                <?php echo Form::button( __('Tìm Kiếm'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
            </form>
            <table class="sort-table table table-hover" id="mytable">
                <thead>
                <tr>
                    <th>Mã</th>
                    <th>Khóa học</th>
                    <th>Người yêu cầu</th>
                    <th>Số tiền</th>
                    <td>Trạng thái</td>
                    <th>Thời gian</th>
                    <td></td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($advance->results as $item): $display_pages = array($item); ?>
                    <?php foreach ($display_pages as $page) : ?>
                            <tr>

                                <td><?php echo $page->data['id']; ?></td>

                                <td><?php echo $page->data['course_name']; ?></td>

                                <td><?php echo $page->data['user']; ?></td>

                                <td><?php echo $page->data['money']; ?></td>

                                <td><?php echo __('advance.'.$page->data['status']); ?></td>

                                <td><?php echo $page->data['time']; ?></td>

                                <td><a href="<?php echo Uri::to('admin/advance/edit/' .  $page->data['id']); ?>">Chỉnh sửa</a></td>
                                <td><a href="<?php echo Uri::to('admin/advance/delete/' .  $page->data['id']); ?>" class="delete">Xóa</a></td>
                            </tr>

                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
            <aside class="paging"><?php echo $advance->links(); ?></aside>

        </ul>



    <?php else: ?>

        <p class="empty posts">
            <span class="icon"></span>
            <?php echo __('posts.noposts_desc'); ?><br>
            <?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn')); ?>
        </p>

    <?php endif; ?>
</section>
<script src="<?php echo asset_url('js/jquery.tablesorter.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#mytable').tablesorter();
    } );
</script>
<?php echo $footer; ?>
