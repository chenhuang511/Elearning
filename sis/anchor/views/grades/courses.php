    <?php echo $header; ?>

    <section class="wrap">
        <?php echo $messages; ?>
        <?php if($pages->count): ?>
            <ul class="main list">
                <?php foreach($pages->results as $item): $display_pages = array($item);?>
                    <?php foreach($display_pages as $page) : ?>
                        <li>
                            <a href="<?php echo Uri::to('admin/grade/course/' . $page->data['courseid']); ?>">
                                <div class="bhxh-course">
                                    <strong><?php echo $page->data['fullname']; ?></strong>
                                    <span>
                                        <em class="status">status</em>
                                    </span>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>

            <aside class="paging"><?php echo $pages->links(); ?></aside>

        <?php else: ?>
            <aside class="empty pages">
                <span class="icon"></span>
                <?php echo __('pages.nopages_desc'); ?><br>
            </aside>
        <?php endif; ?>
        <input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
    </section>

<?php echo $footer; ?>