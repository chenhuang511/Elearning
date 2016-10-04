<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Thông tin học viên'); ?></h1>
</hgroup>


<section class="wrap">
    <?php echo $messages; ?>

    <?php if(Auth::admin() || Auth::me($student->id)) : ?>
        <form method="post" action="<?php echo Uri::to('admin/students/info/' . $student->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

            <input name="token" type="hidden" value="<?php echo $token; ?>">

            <fieldset class="half split">
                <p>
                    <label for="label-fullname"><?php echo 'Full Name' ?>:</label>
                    <?php // echo Form::text('fullname', Input::previous('fullname', $student->fullname), array('id' => 'label-fullname')); ?>
                    <label id="label-fullname"><?php echo $student->fullname ?></label>
                </p>
                <?php foreach ($studentschool as $stusch) { ?>
                    <p>
                        <label for="label-schoolname"><?php echo __('School Name'); ?>:</label>
                        <label id="label-schoolname"><?php echo $stusch->name ?></label>
                    </p>
                <?php } ?>

                <?php foreach ($studentcourse as $stucou) { ?>
                    <p>
                        <label for="label-fullname"><?php echo __('Course Name'); ?>:</label>
                        <label id="label-fullname"><?php echo $stucou->fullname ?></label>
                    </p>
                <?php } ?>
            </fieldset>

            <fieldset class="half split">
                <p>
                    <label for="label-email"><?php echo __('students.email'); ?>:</label>
                    <?php // echo Form::text('email', Input::previous('email', $student->email), array('id' => 'label-email')); ?>
                    <label id="label-email"><?php echo $student->email ?></label>
                </p>
                <p style="height: 39px;"></p>
                <?php foreach ($studentcourse as $stucou) { ?>
                    <p>
                        <label id="label-point"><?php echo $stucou->grade ?></label>
                        <?php
                        $certificate = remote_get_link_certificate($stucou->schoolid, $stucou->studentid, $stucou->id);
                        if ( $certificate != 'false' && !empty($certificate)) { ?>
                            <a target="_blank" class="btn" href="<?php echo $certificate; ?>" style="margin-left: 30px">Tổng kết</a>
                        <?php } else { ?>
                            <a class="btn" href="#" style="margin-left: 30px">Tổng kết</a>
                        <?php } ?>
                    </p>
                <?php } ?>

            </fieldset>
            <aside class="buttons">
                <?php echo Form::button(__('global.update'), array(
                    'class' => 'btn',
                    'type' => 'submit'
                )); ?>

                <?php echo Html::link('admin/students' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>

                <?php echo Html::link('admin/students/delete/' . $student->id, __('global.delete'), array('class' => 'btn delete red')); ?>
            </aside>
        </form>
    <?php else : ?>
        <p>You do not have the required privileges to modify this students information, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
        <br><a class="btn" href="<?php echo Uri::to('admin/students'); ?>">Go back</a>
    <?php endif; ?>
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
