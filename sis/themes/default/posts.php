<?php theme_include('header'); ?>

<section class="content">

	<?php if (has_posts()): ?>
		<ul class="items">
			<?php posts(); ?>
			<div class="summary col-md-9">
				<li>
					<article class="wrap coursebox">
						<h3><b>
								<a href="<?php echo article_url(); ?>"
								   title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
							</b></h3>
						<div class="content" >
							<div class="content-post"><?php echo article_html(); ?></div>
							<span><a href="<?php echo article_url(); ?>">Read more</a></span>
						</div>
						<footer>
							Posted
							<time
								datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time>
							by <?php echo article_author('real_name'); ?>.
						</footer><br>
					</article>
				</li>
				<?php $i = 0;
				while (posts()): ?>
					<li style="">
						<article class="wrap coursebox">
							<h3>
								<b><a href="<?php echo article_url(); ?>"
									  title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
								</b>
							</h3>
							<div class="content">
								<div class="content-post"><?php echo article_html(); ?></div>
								<span><a href="<?php echo article_url(); ?>">Read more</a></span>
							</div>
							<footer>
								Posted
								<time
									datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time>
								by <?php echo article_author('real_name'); ?>.
							</footer><br>
						</article>
					</li>
				<?php endwhile; ?>

			</div>
			<div class="right-block col-md-3 block-search">
				<div class="search-box">
					<h3 class="el-sidebar-heading"> Search <?php echo menu_name(); ?></h3>
				</div>
				<form id="" action="/search" method="get">
					<div class="search-input">
						<input type="text" id="search_term" name="search_term" value="<?php echo search_term(); ?>">
						<button type="submit" class="search-btn" value="<?php echo search_term(); ?>">Search</button>
					</div>
				</form>
			</div>
			<div class="col-md-3 block-posts">
				<li>
					<h3 class="el-sidebar-heading"> New <?php echo menu_name(); ?></h3>
				</li>
				<?php $i = 0;
				while (posts()): ?>
				<?php $bg = sprintf('background: hsl(215, 28%%, %d%%);', round(((++$i / posts_per_page()) * 20) + 20)); ?>
				<li>
					<article class="wrap">
						<ul class="main list">
							<li>
								<a href="<?php echo article_url(); ?>">
									<strong><?php echo article_title(); ?></strong>
									<span>
										<ul class="list-post">
											<li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo date("Y-m-d", article_time()); ?></li>
											<li><em class="status <?php echo article_status(); ?>" title="<?php echo __('global.' . article_status()) ?>"><?php echo __('global.' . article_status()) ?></em></li>
											</ul>
									</span>
								</a>
							</li>
						</ul>
					</article>
				</li>
				<?php endwhile; ?>
			</div><br>
		</ul>
		<?php if (has_pagination()): ?>
			<nav class="pagination">
				<div class="wrap">
					<div class="previous">
						<?php echo posts_prev(); ?>
					</div>
					<div class="next">
						<?php echo posts_next(); ?>
					</div>
				</div>
			</nav>
		<?php endif; ?>

	<?php else: ?>
		<div class="wrap">
			<h1>No posts yet!</h1>
			<p>Looks like you have some writing to do!</p>
		</div>
	<?php endif; ?>

</section>

<?php theme_include('footer'); ?>
