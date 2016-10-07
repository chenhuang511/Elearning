<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý sinh viên</li>
</ol>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($students->count): ?>
        <nav>
            <form style="float: right; margin-top: 20px;" method="get" action="<?php echo Uri::to('admin/students/search'); ?>" novalidate>
                <input id="text-search" type="text" name="text-search" placeholder="Tên sinh viên">
                <?php echo Form::button('Tìm kiếm', array(
                    'class' => 'btn btn-primary',
                    'type' => 'submit'
                )); ?>
            </form>
        </nav>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>Mã sinh viên</th>
                <th>Tên sinh viên</th>
                <th>Email</th>
                <th>Thành tích</th>
                <th>Đăng kí học</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($students->results as $student) : ?>
                <tr>
                    <td><?php echo $student->id ?></td>
                    <td>
                        <a href="<?php echo Uri::to('admin/students/info/' . $student->id); ?>">
                            <p style="margin-bottom: 0;"><?php echo $student->fullname; ?></p>
                        </a>
                    </td>
                    <td><?php echo $student->email ?></td>
                    <td><a class="btn btn-primary" href="#">Chứng chỉ</a></td>
                    <td><a class="btn btn-primary" href="#">Các khóa học</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="paging"><?php echo $students->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('students.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<?php echo $footer; ?>
