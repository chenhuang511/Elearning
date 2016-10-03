<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('students.students'); ?></h1>

	<?php if(Auth::admin()) : ?>
		<nav>
			<div style="float: right; margin: 20px 0 0 20px;">
				<?php echo Html::link('admin/students/add', __('students.create_student'), array('class' => 'btn')); ?>
			</div>

			<form style="float: right; margin-top: 20px;" method="get" action="<?php echo Uri::to('admin/students/search'); ?>" novalidate>

				<?php // echo Form::text('text-search', Input::previous('text-search'), array('id' => 'text-search')); ?>
				<input id="text-search" type="text" name="text-search" placeholder="Tên sinh viên">
				<?php echo Form::button('Tìm kiếm', array(
					'class' => 'btn search blue',
					'type' => 'submit'
				)); ?>

			</form>

		</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($students->results as $student): ?>
		<li>
			<a href="<?php echo Uri::to('admin/students/edit/' . $student->id); ?>">
				<strong><?php echo $student->id; ?></strong>
				<span><?php echo __('students.name'); ?>: <?php echo $student->fullname; ?></span>

				<em class="highlight"><?php echo __($student->email); ?></em>
			</a>
		</li>
		<?php endforeach;  ?>
	</ul>

	<aside class="paging"><?php echo $students->links(); ?></aside>
</section>

<?php echo $footer; ?>
