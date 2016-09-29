<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('instructor.instructor'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<?php echo Html::link('admin/instructor/add', __('instructor.create_instructor'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($instructor->results as $instructor): ?>
			<li>
				<a href="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>">
					<strong><?php echo $instructor->lastname." ".$instructor->firstname ?></strong>
					<span><?php echo __('instructor.subject') ; ?>:<?php echo $instructor->subject ?></span>
					<em class="highlight"><?php echo __($instructor->email); ?></em>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php echo $footer; ?>
