<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('contract.contract'); ?></h1>
	
	<?php if(Auth::admin()) : ?>
	<nav>
		<div style="float: right; margin: 20px 0 0 20px;">
			<?php echo Html::link('admin/contract/add', __('contract.create_contract'), array('class' => 'btn')); ?>
			<a href="<?php echo Uri::to('admin/instructor/'); ?>"
                               class="btn btn-primary">Quay lại</a>
		</div>	
		<form style="float: right; margin-top: 20px;" method="get" action="<?php echo Uri::to('admin/contract/search'); ?>" novalidate>
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
	<?php if ($contracts->count): ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Tên hợp đồng</th>
				<th>Loại hợp đồng </th>
				<th>Tên giảng viên</th>
				<th>Tên cá nhân/tổ chức</th>
				<th>Ngày bắt đầu</th>
				<th>Ngày kết thúc</th>
				<th>Mức lương</th>
				<th>Trạng thái thanh toán</th>
				<th>Điều khoản</th>
				<th>Quản lý</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contracts->results as $contract): ?>
				<tr>
					<td>
						<p><?php echo $contract->name_contract; ?></p>
					</td>
					<td>
						<p><?php echo __('contract.'. $contract->type); ?></p>
					</td>
					<td>
						<p><?php echo __($contract->fullname); ?></p>
					</td>
					<td>
						<p><?php echo $contract->name_partner; ?></p>
					</td>
					<td>
						<p><?php echo date('d-m-Y', strtotime($contract->start_date)); ?></p>
					</td>
					<td>
						<p><?php echo date('d-m-Y', strtotime($contract->end_date)); ?></p>
					</td>	
					<td>
						<p><?php echo $contract->salary; ?></p>
					</td>
					<td>
						<p><?php echo __('contract.'.$contract->state); ?></p>
					</td>
					<td>
						<p><?php echo $contract->rules; ?></p>
					</td>
					<td>
						<a href="<?php echo Uri::to('admin/contract/edit/' . $contract->id); ?>"
                               class="btn btn-primary">Sửa</a>
						<a href="<?php echo Uri::to('admin/contract/delete/' . $contract->id); ?>"
                               class="btn btn-danger delete red">Xóa</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<aside class="paging"><?php echo $contracts->links(); ?></aside>
	<?php else: ?>
		<aside class="empty pages">
			<span class="icon"></span>
			<?php echo __('instructor.nopages_desc'); ?><br>
		</aside>
	<?php endif; ?>
	<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>
<?php echo $footer; ?>

