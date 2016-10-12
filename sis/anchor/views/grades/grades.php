<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <form action="<?php echo Uri::to('admin/grade/course/search/' . $courseid) ?>" method="get" class="pull-right form-inline mb10">
            <div class="form-group">
                <label for="gradeMin">Điểm</label>
                <?php echo Form::number('gradeMin', Input::get('gradeMin'), array('class' => 'form-control', 'id' => 'gradeMin')); ?>
                <label for="gradeMax">Tới</label>
                <?php echo Form::number('gradeMax', Input::get('gradeMax'), array('class' => 'form-control', 'id' => 'gradeMax')); ?>
            </div>
            <div class="form-group">
                <?php echo Form::text('key', Input::get('key'), array('class' => 'form-control', 'placeholder' => 'Tên sinh viên')); ?>
            </div>
            <?php echo Form::button( __('Tìm Kiếm'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
        </form>
        <table class="sort-table table table-hover" id="mytable">
            <thead>
            <tr>
                <th>Mã</th>
                <th>Tên sinh viên</th>
                <th>Trường</th>
                <th>Điểm</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pages->results as $item): $display_pages = array($item); ?>
                <?php foreach ($display_pages as $page) : ?>
                    <tr>
                        <td><?php echo $page->data['id']; ?></td>

                        <td><?php echo $page->data['fullname']; ?></td>

                        <td><?php echo $page->data['schoolname']; ?></td>

                        <td><?php echo $page->data['grade']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="paging"><?php echo $pages->links(); ?></aside>

    <?php else: ?>
        <aside class="empty pages">
            <span class="icon"></span>
            <?php echo __('pages.nopages_desc'); ?><br>
        </aside>
    <?php endif; ?>
</section>
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<script src="<?php echo asset_url('js/jquery.tablesorter.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#mytable').tablesorter();
    } );
</script>
<?php echo $footer; ?>

