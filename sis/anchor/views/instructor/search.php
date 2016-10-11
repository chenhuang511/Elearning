<?php echo $header; ?>

<ol class="breadcrumb">
	<li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
	<li><a href="<?php echo Uri::to('admin/instructor'); ?>">Quản lý giảng viên</a></li>
	<li class="active">Tìm kiếm</li>
</ol>

<section class="wrap">
	<?php echo $messages; ?>

	<nav>
		<form class="form-inline" method="get" action="<?php echo Uri::to('admin/instructor/search'); ?>" novalidate>
			<input class="form-control" type="text" name="text-search" placeholder="Tên giảng viên">
			<?php echo Form::button('Tìm kiếm', array(
				'class' => 'btn btn-primary',
				'type' => 'submit'
			)); ?>
		</form>
	</nav>

	<?php if ($instructors->count): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Tên đầy đủ</th>
				<th>Email</th>
				<th>Ngày sinh</th>
				<th>Chức vụ</th>
				<th>Môn học</th>
                <th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instructors->results as $instructor): ?>
				<tr>
					<?php if ($instructor->type_instructor != 'official') { ?>
						<td><a href="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>">
								<?php echo $instructor->fullname; ?></a></td>
					<?php } else { ?>
						<td><a href="<?php echo Uri::to('admin/instructor/view/' . $instructor->id); ?>">
								<?php echo $instructor->fullname; ?></a></td>
					<?php } ?>
					<td><p><?php echo $instructor->email; ?></p></td>
					<td><p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p></td>
					<td><p><?php echo $instructor->type_instructor ?></p></td>
					<td><p><?php echo $instructor->subject; ?></p></td>
					<td>
						<?php if ($instructor->type_instructor != 'official') : ?>
							<a href="<?php echo Uri::to('admin/instructor/delete/' . $instructor->id); ?>" class="btn btn-primary">Xóa</a>
						<?php endif; ?>
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
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<?php echo $footer; ?>

