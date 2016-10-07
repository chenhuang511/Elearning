<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('instructor.instructor'); ?></h1>
	
	<?php if(Auth::admin()) : ?>
	<nav>
		<form style="float: right; margin-top: 20px;" method="get" action="<?php echo Uri::to('admin/instructor/search'); ?>" novalidate>
				<input id="text-search" type="text" name="text-search" placeholder="Tên Hợp Đồng">
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
	<?php if ($instructors->count): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Tên đầy đủ</th>
				<th>Email</th>
				<th>Ngày sinh</th>
				<th>Hình thức</th>
				<th>Môn học</th>
                <th>Quản lý</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instructors->results as $instructor): ?>
				<tr>
					<td>
						<p><?php echo $instructor->fullname; ?></p>
					</td>
					<td>
						<p><?php echo $instructor->email; ?></p>
					</td>
					<td>
						<p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p>
					</td>
					<td>
						<p><?php echo __('instructor.'.$instructor->type_instructor); ?></p>
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
	<aside class="paging"><?php echo $instructors->links(); ?></aside>
	<?php else: ?>
		<aside class="empty pages">
			<span class="icon"></span>
			<?php echo __('instructor.nopages_desc'); ?><br>
		</aside>
	<?php endif; ?>	
</section>
<?php echo $footer; ?>

