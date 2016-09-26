<?php echo $header; ?>

    <section class="wrap">
        <?php echo $messages; ?>
        <?php if($pages->count): ?>
            <ul class="main list">
                <?php foreach($pages->results as $item): $display_pages = array_merge(array($item), $item->children());?>
                    <?php foreach($display_pages as $page) : ?>
                        <li>
                            <a href="<?php echo Uri::to('admin/pages/edit/' . $page->data['id']); ?>">
                                <div class="<?php echo ($page->data['parent'] != 0 ? 'indent' : ''); ?>">
                                    <strong><?php echo $page->data['name']; ?></strong>
						<span>
							<?php echo $page->data['slug']; ?>
                            <em class="status <?php echo $page->data['status']; ?>" title="<?php echo __('global.' . $page->data['status']); ?>">
								<?php echo __('global.' . $page->data['status']); ?>
							</em>
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
    </section>

<?php echo $footer; ?>