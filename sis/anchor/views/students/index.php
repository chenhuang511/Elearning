<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('students.students'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<?php echo Html::link('admin/students/add', __('students.create_student'), array('class' => 'btn')); ?>
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
				<span><?php echo __('Name'); ?>: <?php echo $student->fullname; ?></span>

				<em class="highlight"><?php echo __($student->email); ?></em>
			</a>
		</li>
		<?php endforeach;  ?>
	</ul>

	<aside class="paging"><?php echo $students->links(); ?></aside>
</section>

<?php echo $footer; ?>
