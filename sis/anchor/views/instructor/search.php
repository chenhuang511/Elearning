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
				<th>Số chuyên đề giảng dạy</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instructors->results as $instructor): ?>
				<tr>
					<td><a href="<?php echo Uri::to('admin/instructor/view/' . $instructor->id); ?>">
								<?php echo $instructor->fullname; ?></a></td>
					<td><p><?php echo $instructor->email; ?></p></td>
					<td><p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p></td>
					<td><p><?php echo __('instructor.'.$instructor->type_instructor) ?></p></td>
					<td></td>
				</tr>
			<?php endforeach; ?>
			<?php foreach ($official_instructors->results as $official_instructor): ?>
				<tr>
				 	<td>
                        <?php
                        $url = remote_get_user_link_profile($official_instructor->schoolid, $official_instructor->remoteid);
                        if ($url != 'false' && !empty($url)) { ?>
                            <a target="_blank" href="<?php echo $url; ?>"><?php echo $official_instructor->real_name ?></a>
                        <?php } else { ?>
                            <a href="#"><?php echo $official_instructor->real_name ?></a>
                        <?php } ?>
                    </td>
					<td><p><?php echo $official_instructor->email; ?></p></td>
					<td></td>
					<td><p>Giảng viên chính thức</p></td>
                    <td><a href="<?php echo Uri::to('admin/instructor/curriculum/' . $official_instructor->id); ?>">
               			<?php echo $official_instructor->curriculum_taught; ?>
              		</a></td>
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

