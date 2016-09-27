<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('students.students'); ?></h1>

	<?php if(Auth::admin()) : ?>
	<nav>
		<?php echo Html::link('admin/students/add', __('students.create_student'), array('class' => 'btn')); ?>
	</nav>
	<?php endif; ?>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<ul class="list">
		<?php foreach($students->results as $student): ?>
		<li>
			<a href="<?php echo Uri::to('admin/students/edit/' . $student->id); ?>">
				<strong><?php echo $student->id; ?></strong>
				<span><?php echo __('Name'); ?>: <?php echo $student->fullname; ?></span>

				<em class="highlight"><?php echo __($student->email); ?></em>
			</a>
		</li>
		<?php endforeach;  ?>
        
<!--        --><?php
//        $mysqlconn = new mysqli("localhost", "root", "vannhuthe", "anchor");
//        $sql = "SELECT * FROM anchor_students";
//        $result = $mysqlconn->query($sql);
//
//        while($row = $result->fetch_assoc())
//        {
//            $id = $row["id"];
//        ?>
<!---->
<!--            <li>-->
<!--                <a href="--><?php //echo Uri::to('admin/students/edit/' . $id); ?><!--">-->
<!--                    <strong>--><?php //echo $row['firstname'] ?><!--</strong>-->
<!--                    <span>--><?php //echo __('students.username'); ?><!--: --><?php //echo $row['username'] ?><!--</span>-->
<!--                    <em class="highlight">--><?php //echo __($row['email']); ?><!--</em>-->
<!--                </a>-->
<!--            </li>-->
<!---->
<!--		--><?php //} ?>
	</ul>

	<aside class="paging"><?php echo $students->links(); ?></aside>
</section>

<?php echo $footer; ?>
