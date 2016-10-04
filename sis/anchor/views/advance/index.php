<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>


        <nav>
            <?php echo Html::link('admin/advance/add', __('advance.create_advance'), array('class' => 'btn')); ?>
        </nav>

</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <nav class="sidebar ">
        <nav class="sidebar statuses">
            <p>Tình trạng</p>
            <?php foreach($statuses as $data): extract($data); ?>
                <?php echo Html::link('admin/advance' . $url, '<span class="icon"></span> ' . __($lang), array(
                    'class' => (isset($status) && $status == $url ? 'active ' : '') .$class
                )); ?>
            <?php endforeach; ?>
        </nav>

    </nav>
    <?php if($advance->results): ?>
        <ul class="main list list_advance" href="" >
            <form action="<?php echo Uri::to('admin/advance/search'); ?>" method="get" class="pull-right form-inline mb10">
                <div class="form-group" style="margin-bottom: 5px;" >
                    <label for="gradeMin">Tiền</label>
                    <?php echo Form::number('moneyMin', Input::get('gradeMin'), array('class' => 'form-control', 'id' => 'moneyMin')); ?>
                    <label for="gradeMax">Tới</label>
                    <?php echo Form::number('moneyMax', Input::get('gradeMax'), array('class' => 'form-control', 'id' => 'moneyMax')); ?>
                </div>
                <div class="form-group input_key_advance" >
                    <?php echo Form::text('key_course', Input::get('key'), array('class ' => 'form-control key_form', 'placeholder' => 'Khóa học','id' => 'key_course')); ?>
                    <?php echo Form::text('key_name', Input::get('key'), array('class' => 'form-control key_form', 'placeholder' => 'Tên sinh viên','id' => 'key_name')); ?>
                    <?php echo Form::text('key_id', Input::get('key'), array('class' => 'form-control key_form', 'placeholder' => 'Mã tạm ứng','id' => 'key_id')); ?>
                </div>
                <?php echo Form::button( __('Tìm Kiếm'), array('type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'search_')); ?>
            </form>
            <table class="sort-table table table-hover table-responsive" id="mytable">
                <thead>
                <tr>
                    <th>Mã</th>
                    <th>Khóa học</th>
                    <th>Người yêu cầu</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Thời gian yêu cầu</th>
                    <th>Thời gian xét duyệt</th>
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

                                <td><em><?php echo __('advance.'.$page->data['status']); ?></em></td>

                                <td><?php echo $page->data['time_request']; ?></td>
                                <td>
                                    <?php
                                    if (isset($page->data['time_response']) && $page->data['time_response'] !== '0000-00-00')
                                        echo $page->data['time_response'];
                                    else
                                        echo 'Chưa được xét duyệt';
                                    ?></td>

                                <td><a href="<?php echo Uri::to('admin/advance/edit/' .  $page->data['id']); ?>" class="btn">Chỉnh sửa</a></td>
                                <td><a href="<?php echo Uri::to('admin/advance/delete/' .  $page->data['id']); ?>" class="btn delete red ">Xóa</a></td>
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
            <?php echo Html::link('admin/advance/add', __('advance.create_advance'), array('class' => 'btn')); ?>
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
