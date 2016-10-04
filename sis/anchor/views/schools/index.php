<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($schools->count): ?>
        <nav>
            <form style="float: right; margin: 20px 0;" method="get" action="<?php echo Uri::to('admin/schools/search'); ?>" novalidate>
                <input id="text-search" type="text" name="text-search" placeholder="Tên trường">
                <?php echo Form::button('Tìm kiếm', array(
                    'class' => 'btn search blue',
                    'type' => 'submit'
                )); ?>
            </form>
        </nav>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>Mã trường</th>
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
