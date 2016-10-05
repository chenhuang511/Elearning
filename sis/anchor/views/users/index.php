<?php echo $header; ?>
<section class="wrap">
	<?php echo $messages; ?>
	<?php if ($users->count): ?>
		<nav>
			<form style="float: right; margin: 20px 0;" method="get" action="<?php echo Uri::to('admin/users/search'); ?>" novalidate>
				<input id="text-search" type="text" name="text-search" placeholder="Tên thành viên">
				<?php echo Form::button('Tìm kiếm', array(
					'class' => 'btn btn-primary',
					'type' => 'submit'
				)); ?>
			</form>
		</nav>

		<table class="table table-hover">
			<thead>
			<tr>
				<th>Mã</th>
				<th>Tên</th>
				<th>Email</th>
				<th>Tài khoản</th>
				<th>Quyền truy cập</th>
				<th>Trạng thái</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($users->results as $user): ?>
				<tr>
					<td><?php echo $user->id ?></td>
					<td>
<!--						<a href="--><?php //echo Uri::to('http://192.168.1.253/user/profile.php?id=' . $user->id); ?><!--">-->
							<p><?php echo $user->real_name; ?></p>
<!--						</a>-->
					</td>
					<td>
						<p><?php echo $user->email; ?></p>
					<td>
						<p><?php echo $user->username; ?></p>
					</td>
					<td>
						<p><?php echo $user->role; ?></p>
					</td>
					<td>
						<p><?php echo $user->status; ?></p>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<aside class="paging"><?php echo $users->links(); ?></aside>

	<?php else: ?>
		<aside class="empty pages">
			<span class="icon"></span>
			<?php echo __('pages.nopages_desc'); ?><br>
		</aside>
	<?php endif; ?>
</section>
<?php echo $footer; ?>

