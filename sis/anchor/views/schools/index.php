<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('schools.schools'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav class="split">
		<label style="display: none"></label>
		<?php echo Form::text('text-search', Input::previous('text-search'), array('id' => 'text-search')); ?>
		<?php // echo Form::button(__('global.create'), array('class' => 'btn', 'type' => 'submit')); ?>
		<?php echo Html::link('admin/schools/search/', __('Tìm Kiếm'), array('class' => 'btn search blue')); ?>
		<?php echo Html::link('admin/schools/add', __('schools.create_school'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($schools->results as $school): ?>
		<li>
			<a href="<?php echo Uri::to('admin/schools/edit/' . $school->id); ?>">
				<strong><?php echo $school->id; ?></strong>
				<span><?php echo __('schools.name'); ?>: <?php echo $school->name; ?></span>

				<em class="highlight"><?php echo __($school->id); ?></em>
			</a>
		</li>
		<?php endforeach;  ?>
	</ul>

	<aside class="paging"><?php echo $schools->links(); ?></aside>
</section>

<?php echo $footer; ?>
