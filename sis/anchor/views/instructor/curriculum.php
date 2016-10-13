<?php echo $header; ?>

<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li class="active">Quản lý giảng viên</li>
</ol>

<section class="wrap">
	<?php echo $messages; ?>
	<?php if ($curriculums->count): ?>
	<table class="table table-hover">
		<thead>
			<tr>
                <th>Tên chuyên đề</th>
				<th>Tên khóa học</th>
				<th>Ngày</th>
				<th>Giờ</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($curriculums->results as $curriculum): ?>
				<tr>
					<td>
						<p><?php echo $curriculum->topicname; ?></p>
					</td>
					<td>
						<p><?php echo $curriculum->coursename; ?></p>
					</td>
					<td>
						<p><?php echo $curriculum->topicday; ?></p>
					</td>
                    <td>
						<p><?php echo $curriculum->topictime; ?></p>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<aside class="paging"><?php echo $curriculums->links(); ?></aside>
	<?php else: ?>
		<aside class="empty pages">
			<span class="icon"></span>
			<?php echo __('instructor.nopages_desc'); ?><br>
		</aside>
	<?php endif; ?>
	<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>
<?php echo $footer; ?>

