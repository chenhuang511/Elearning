<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('users.users'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<?php echo Html::link('admin/users/add', __('users.create_user'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php /*foreach($users->results as $user): ?>
		<li>
			<a href="<?php echo Uri::to('admin/users/edit/' . $user->id); ?>">
				<strong><?php echo $user->real_name; ?></strong>
				<span><?php echo __('users.username'); ?>: <?php echo $user->username; ?></span>

				<em class="highlight"><?php echo __('users.' . $user->role); ?></em>
			</a>
		</li>
		<?php endforeach; */ ?>

        <?php
        $mysqlconn = new mysqli("localhost", "root", "vannhuthe", "anchor");
        $sql = "SELECT * FROM anchor_students";
        $result = $mysqlconn->query($sql);

        while($row = $result->fetch_assoc())
        {
            $id = $row["id"];
        ?>

            <li>
                <a href="<?php echo Uri::to('admin/users/edit/' . $id); ?>">
                    <strong><?php echo $row['firstname'] ?></strong>
                    <span><?php echo __('users.username'); ?>: <?php echo $row['username'] ?></span>
                    <em class="highlight"><?php echo __('users.' . $row['email']); ?></em>
                </a>
            </li>

		<?php } ?>
	</ul>

	<aside class="paging"><?php echo $users->links(); ?></aside>
</section>

<?php echo $footer; ?>
