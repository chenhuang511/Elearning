<?php theme_include('header'); ?>
	<div class="right-block col-md-3 block-search search-post">
		<div class="search-box">
			<h3 class="el-sidebar-heading"> Search <?php echo menu_name(); ?></h3>
		</div>
		<form id="" action="" method="get">
			<div class="search-input">
				<input type="text" id="search_term" name="search_term" value="">
				<button type="submit" class="search-btn" value="">Search</button>
			</div>
		</form>
	</div>
	<div class="summary col-md-9">
		<h1 class="wrap">You searched for &ldquo;<?php echo $_GET['search_term']; ?>&rdquo;.</h1>

		<?php $posts = Post::search($_GET['search_term']); if($posts[0] != 0 ): ?>
			<ul class="items">
				<?php foreach($posts[1] as $post): ?>
					<li>
						<article class="wrap coursebox">
							<h3><b>
									<a href="/posts/<?php echo $post->slug; ?>"
									   title="<?php echo $post->title; ?>"><?php echo $post->title; ?></a>
								</b></h3>
							<div class="content" >
								<div class="content-post"><?php echo $post->description; ?></div>
								<span><a href="/posts/<?php echo $post->slug; ?>">Read more</a></span>
							</div>
							<footer>
								Posted
								<time
									datetime=""><?php echo $post->created; ?></time>
								by <?php echo $post->author_name; ?>.
							</footer><br>
						</article>
					</li>
				<?php endforeach; ?>
			</ul>


		<?php else: ?>
			<p class="wrap">Unfortunately, there's no results for &ldquo;<?php echo $_GET['search_term']; ?>&rdquo;. Did you spell everything correctly?</p>
		<?php endif; ?>
	</div>




<?php theme_include('footer'); ?>