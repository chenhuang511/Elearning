<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý học viên</li>
</ol>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($students->count): ?>
        <nav>
            <form class="form-inline" method="get" action="<?php echo Uri::to('admin/students/search'); ?>" novalidate>
                <input class="form-control" type="text" name="text-search" placeholder="Tên học viên">
                <?php echo Form::button('Tìm kiếm', array(
                    'class' => 'btn btn-primary',
                    'type' => 'submit'
                )); ?>
            </form>
        </nav>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>Mã học viên</th>
                <th>Tên học viên</th>
                <th>Email</th>
                <th>Kết quả học tập</th>
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
                    <td><a class="btn btn-primary" href= <?php echo Uri::to('admin/students/courses/' . $student->id); ?>>Đăng kí
                            học</a></td>
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
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<?php echo $footer; ?>

