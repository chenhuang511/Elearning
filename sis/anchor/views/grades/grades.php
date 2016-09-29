<?php echo $header; ?>
<section class="wrap">
    <?php echo $messages; ?>
    <?php if ($pages->count): ?>
        <table class="table table-hover">
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
<?php echo $footer; ?>

