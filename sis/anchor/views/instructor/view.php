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
			<th>Đánh giá</th>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td><p><?php echo $instructor->fullname; ?></p></td>
				<td><p><?php echo $instructor->email; ?></p></td>
				<td><p><?php echo date('d-m-Y', strtotime($instructor->birthday)); ?></p></td>
				<td><p><?php echo __('instructor.'.$instructor->type_instructor) ?></p></td>
				<td><p><?php echo $instructor->comment ?></p></td>
			</tr>
		</tbody>
	</table>

	<h2 style="font-size: 25px; color: #99a3b1;margin: 30px 0 15px;">Thông tin hợp đồng</h2></br>
	<div class="col-sm-6 col-sm-offset-3">
	<?php foreach($contract as $ctr): ?>
	<table class="table table-striped " style="border-style:double;border-color:black" >
		<tbody>
			<tr>
				<th>Tên</th>
				<td><p><?php echo $ctr->name_contract ?></p></td>
			</tr>
			<tr>
				<th>Loại</th>
				<td><p><?php echo __('contract.'. $ctr->type)?></p></td>
			</tr>
			<tr>
				<th>Tên tổ chức</th>
				<td><p><?php echo $ctr->name_partner ?></p></td>
			</tr>
			<tr>
				<th>Người đứng đầu</th>
				<td><p><?php echo $ctr->name_head ?></p></td>
			</tr>
			<tr>
				<th>Mã số thuế</th>
				<td><p><?php echo $ctr->tax_code ?></p></td>
			</tr>
			<tr>
				<th>Số điện thoại</th>
				<td><p><?php echo $ctr->number_phone ?></p></td>
			</tr>
			<tr>
				<th>Địa chỉ</th>
				<td><p><?php echo $ctr->address ?></p></td>
			</tr>
			<tr>
				<th>Ngày bắt đầu</th>
				<td><p><?php echo $ctr->start_date ?></p></td>
			</tr>
			<tr>
				<th>Ngày kết thúc</th>
				<td><p><?php echo $ctr->end_date ?></p></td>
			</tr>
			<tr>
				<th>Mức lương</th>
				<td><p><?php echo $ctr->salary ?></p></td>
			</tr>
			<tr>
				<th>Trạng thái</th>
				<td><p><?php echo __('contract.'.$ctr->state) ?></p></td>
			</tr>
			<tr>
				<th>Điều khoản</th>
				<td><p><?php echo $ctr->rules ?></p></td>
			</tr>
		
		</tbody>
	</table></br></br>
	<?php endforeach; ?>
	</div>
	  	
	<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>
<p>
	<?php if ($instructor->type_instructor != 'official') : ?>
		<a href="<?php echo Uri::to('admin/instructor/edit/' . $instructor->id); ?>"class="btn btn-primary">Sửa</a>&nbsp
		<a href="<?php echo Uri::to('admin/instructor'); ?>"class="btn btn-primary">Hủy bỏ</a>
    <?php endif; ?>
</p>
<script type="text/javascript">
	$(document).ready(function(){
	
	});
</script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
