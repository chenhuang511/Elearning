<?php echo $header; ?>

<ol class="breadcrumb">
	<li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
	<li><a href="<?php echo Uri::to('admin/instructor'); ?>">Quản lý giảng viên</a></li>
	<li class="active">Thông tin giảng viên</li>
</ol>

<hgroup class="wrap">
	<h1 style="margin: 0;"><?php echo 'Thông tin giảng viên' ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<table class="table table-hover">
		<thead>
		<tr>
			<th>Tên đầy đủ</th>
			<th>Email</th>
			<th>Ngày sinh</th>
			<th>Chức vụ</th>
			<th>Môn dạy</th>
			<th>Số chuyên đề</th>
			<th>Đánh giá</th>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td><p><?php echo $instructor->fullname; ?></p></td>
				<td><p><?php echo $instructor->email; ?></p></td>
				<td><p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p></td>
				<td><p><?php echo $instructor->type_instructor ?></p></td>
				<td><p><?php echo $instructor->subject; ?></p></td>
				<td><a href="<?php echo Uri::to('admin/instructor/curriculum/' . $instructor->id); ?>">
               		<?php echo $curriculum_taught; ?>
              	</a></td>
				<td><p><?php echo $instructor->comment ?></p></td>
			</tr>
		</tbody>
	</table>

	<h2 style="font-size: 25px; color: #99a3b1;margin: 30px 0 15px;">Thông tin hợp đồng</h2>

	<table class="table table-hover">
		<thead>
		<tr>
			<th>Tên</th>
			<th>Loại</th>
			<th>Tên tổ chức</th>
			<th>Người đứng đầu</th>
			<th>Mã số thuế</th>
			<th>Số điện thoại</th>
			<th>Địa chỉ</th>
			<th>Ngày bắt đầu</th>
			<th>Ngày kết thúc</th>
			<th>Mức lương</th>
			<th>Trạng thái</th>
			<th>Điều khoản</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($contract as $ctr): ?>
			<tr>
				<td><p><?php echo $ctr->name_contract ?></p></td>
				<td><p><?php echo __('contract.'.$ctr->type) ?></p></td>
				<td><p><?php echo $ctr->name_partner ?></p></td>
				<td><p><?php echo $ctr->name_head ?></p></td>
				<td><p><?php echo $ctr->tax_code ?></p></td>
				<td><p><?php echo $ctr->number_phone ?></p></td>
				<td><p><?php echo $ctr->address ?></p></td>
				<td><p><?php echo $ctr->start_date ?></p></td>
				<td><p><?php echo $ctr->end_date ?></p></td>
				<td><p><?php echo $ctr->salary ?></p></td>
				<td><p><?php echo __('contract.'.$ctr->state) ?></p></td>
				<td><p><?php echo $ctr->rules ?></p></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
