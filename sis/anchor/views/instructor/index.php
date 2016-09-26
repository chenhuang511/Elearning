<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('instructor.instructor'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<?php echo Html::link('admin/instructor/add', __('instructor.create_instructor'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php
			$mysqlconn = new mysqli("localhost", "root", "", "anchor");
        	$sql = "SELECT * FROM anchor_instructors";
        	$result = $mysqlconn->query($sql);
			while($row = $result->fetch_assoc())
			{
				$id = $row["id"];
			?>
			<li>
				<a href="<?php echo Uri::to('admin/instructor/edit/' . $id); ?>">
					<strong><?php echo $row['lastname']." ".$row['firstname'] ?></strong>
					<span><?php echo __('instructor.subject') ; ?>:<?php echo $row['subject'] ?></span>
					<em class="highlight"><?php echo __($row['email']); ?></em>
				</a>
			</li>
		<?php }	?>
	</ul>

	<aside class="paging"><?php echo $users->links(); ?></aside>
</section>

<?php echo $footer; ?>
