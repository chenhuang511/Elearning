<?php echo $header; ?>

<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý giảng viên</li>
</ol>

<section class="wrap">
	<?php echo $messages; ?>
	<?php if ($instructors->count): ?>
        <nav>
            <form class="form-inline" method="get" action="<?php echo Uri::to('admin/instructor/search'); ?>" novalidate>
                <input class="form-control" type="text" name="text-search" placeholder="Tên giảng viên">
                <?php echo Form::button('Tìm kiếm', array(
                    'class' => 'btn btn-primary',
                    'type' => 'submit'
                )); ?>
                <?php echo Html::link('admin/instructor/add', __('contract.create_contract'), array('class' => 'btn btn-primary')); ?>
            </form>
        </nav>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Tên đầy đủ</th>
				<th>Email</th>
				<th>Ngày sinh</th>
				<th>Chức vụ</th>
				<th>Môn dạy</th>
                <th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($instructors->results as $instructor): ?>
				<tr>
                    <td><a href="<?php echo Uri::to('admin/instructor/view/' . $instructor->id); ?>">
                            <?php echo $instructor->fullname; ?></a></td>
					<td><p><?php echo $instructor->email; ?></p></td>
					<td><p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p></td>
					<td><p><?php echo __('instructor.'.$instructor->type_instructor); ?></p></td>
					<td><p><?php echo $instructor->subject; ?></p></td>
                    <td>
                        <?php if ($instructor->type_instructor != 'official') : ?>
							<a href="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>"class="btn btn-primary">Sửa</a>
                        <?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php foreach ($official_instructors->results as $instructor): ?>
				<tr>
				 	<td>
                        <?php
                        $url = remote_get_user_link_profile($instructor->schoolid, $instructor->remoteid);
                        if ($url != 'false' && !empty($url)) { ?>
                            <a target="_blank" href="<?php echo $url; ?>"><?php echo $instructor->real_name ?></a>
                        <?php } else { ?>
                            <a href="#"><?php echo $instructor->real_name ?></a>
                        <?php } ?>
                    </td>
					<td><p><?php echo $instructor->email; ?></p></td>
					<td></td>
					<td><p>Giảng viên chính thức</p></td>
					<td></td>
                    <td></td>
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
	<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>

<?php echo $footer; ?>
