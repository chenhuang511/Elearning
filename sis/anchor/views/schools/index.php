<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý trường học</li>
</ol>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($schools->count): ?>
        <nav>
            <form class="form-inline" method="get" action="<?php echo Uri::to('admin/schools/search'); ?>" novalidate>
                <input class="form-control" type="text" name="text-search" placeholder="Tên trường">
                <?php echo Form::button('Tìm kiếm', array(
                    'class' => 'btn btn-primary',
                    'type' => 'submit'
                )); ?>
            </form>
        </nav>

        <table class="table table-hover">
            <thead>
            <tr>
                <th style="width: 28%;">Mã trường</th>
                <th>Tên trường</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($schools->results as $school) : ?>
                <tr>
                    <td><?php echo $school->id ?></td>
                    <td>
                        <a href="<?php echo Uri::to('admin/schools/info/' . $school->id); ?>">
                                <p style="margin-bottom: 0;"><?php echo $school->name; ?></p>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="paging"><?php echo $schools->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('schools.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<?php echo $footer; ?>
