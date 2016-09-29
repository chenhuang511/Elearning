<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php  echo __('Kết quả tìm kiếm'); ?></h1>
    <nav style="margin-top: 20px;">
        <form method="get" action="<?php echo Uri::to('admin/students/search'); ?>" novalidate>
            <?php echo Form::text('text-search', Input::previous('text-search'), array('id' => 'text-search')); ?>
            <?php echo Form::button('Tìm kiếm', array(
                'class' => 'btn search blue',
                'type' => 'submit'
            )); ?>
        </form>
    </nav>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <ul class="list">
        <?php foreach($student as $stu): ?>
            <li>
                <a href="<?php echo Uri::to('admin/students/edit/' . $stu->id); ?>">
                    <strong><?php echo $stu->id; ?></strong>
                    <span><?php echo __('students.name'); ?>: <?php echo $stu->fullname; ?></span>
                    <em class="highlight"><?php echo __($stu->email); ?></em>
                </a>
            </li>
        <?php endforeach;  ?>
    </ul>

    <aside class="paging"><?php // echo $school->links(); ?></aside>

</section>

<?php echo $footer; ?>
