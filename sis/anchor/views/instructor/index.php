<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('instructor.instructor'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<div style="float: right; margin: 20px 0 0 20px;">
			<?php echo Html::link('admin/instructor/add', __('instructor.create_instructor'), array('class' => 'btn btn-primary')); ?>
		</div>
		<form style="float: right; margin-top: 20px;" method="get" action="<?php echo Uri::to('admin/instructor/search'); ?>" novalidate>
			<input id="text-search" type="text" name="text-search" placeholder="Tên Giảng Viên">
				<?php echo Form::button('Tìm kiếm', array(
					'class' => 'btn search blue',
					'type' => 'submit'
				)); ?>
		</form>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Họ</th>
				<th>Tên</th>
				<th>Email</th>
				<th>Ngày sinh</th>
				<th>Môn học</th>
                <th>Quản lý</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instructor->results as $instructor): ?>
				<tr>
					<td>
						<p><?php echo $instructor->lastname; ?></p>
					</td>
					<td>
						<p><?php echo $instructor->firstname; ?></p>
					</td>
					<td>
						<p><?php echo $instructor->email; ?></p>
					</td>
					<td>
						<p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p>
					</td>
					<td>
						<p><?php echo $instructor->subject; ?></p>
                    <td>
						<a href="<?php echo Uri::to('admin/instructor/view/' . $instructor->id); ?>"
                               class="btn blue">Xem</a>
						<a href="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>"
                               class="btn btn-primary">Sửa</a>
						<a href="<?php echo Uri::to('admin/instructor/delete/' . $instructor->id); ?>"
                               class="btn btn-danger delete red">Xóa</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</section>

<?php echo $footer; ?>
