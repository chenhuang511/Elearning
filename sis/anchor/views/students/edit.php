<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Thông tin học viên'); ?></h1>
</hgroup>


<section class="wrap">
    <?php echo $messages; ?>

    <?php if(Auth::admin() || Auth::me($student->id)) : ?>
        <form method="post" action="<?php echo Uri::to('admin/students/edit/' . $student->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

            <input name="token" type="hidden" value="<?php echo $token; ?>">

            <fieldset class="half split">
                <p>
                    <label for="label-fullname"><?php echo 'Full Name' ?>:</label>
                    <?php echo Form::text('fullname', Input::previous('fullname', $student->fullname), array('id' => 'label-fullname')); ?>

                </p>
<!--                <p>-->
<!--                    <label for="label-firstname">--><?php //echo __('students.firstname'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('firstname', Input::previous('firstname', $student->firstname), array('id' => 'label-firstname')); ?>
<!--                    <em>--><?php //echo __('students.firstname_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-lastname">--><?php //echo __('students.lastname'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('lastname', Input::previous('lastname', $student->lastname), array('id' => 'label-lastname')); ?>
<!--                    <em>--><?php //echo __('students.lastname_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-email">--><?php //echo __('students.email'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('email', Input::previous('email', $student->email), array('id' => 'label-email')); ?>
<!--                    <em>--><?php //echo __('students.email_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-address">--><?php //echo __('Address'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('address', Input::previous('address', $student->address), array('id' => 'label-address')); ?>
<!--                    <em>--><?php //echo __('students.address_explain'); ?><!--</em>-->
<!--                </p>-->
                <?php foreach ($studentschool as $stusch) { ?>
                    <p>
                        <label for="label-idschool"><?php echo __('ID School'); ?>:</label>
                        <label for="label-idschool"><?php echo $stusch->id ?></label>
                    </p>
                <?php } ?>

                <?php foreach ($studentcourse as $stucou) { ?>
                            <p>
                                <label for="label-idcourse"><?php echo __('ID Course'); ?>:</label>
                                <label id="label-idcourse"><?php echo $stucou->id ?></label>
                            </p>
                <?php } ?>
            </fieldset>

            <fieldset class="half split">
                <?php foreach($fields as $field): ?>
                    <p>
                        <label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
                        <?php echo Extend::html($field); ?>
                    </p>
                <?php endforeach; ?>
                <p>
                    <label for="label-email"><?php echo __('students.email'); ?>:</label>
                    <?php echo Form::text('email', Input::previous('email', $student->email), array('id' => 'label-email')); ?>
                    <em><?php echo __('students.email_explain'); ?></em>
                </p>
<!--                <p>-->
<!--                    <label for="label-firstaccess">--><?php //echo __('First Access'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('firstaccess', Input::previous('firstaccess', $student->firstaccess), array('id' => 'label-firstaccess')); ?>
<!--                    <em>--><?php //echo __('students.firstaccess_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-lastaccess">--><?php //echo __('Last Access'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('lastaccess', Input::previous('lastaccess', $student->lastaccess), array('id' => 'label-lastaccess')); ?>
<!--                    <em>--><?php //echo __('students.lastaccess_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-lastlogin">--><?php //echo __('Last Login'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('lastlogin', Input::previous('lastlogin', $student->lastlogin), array('id' => 'label-lastlogin')); ?>
<!--                    <em>--><?php //echo __('students.lastlogin_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-timecreate">--><?php //echo __('Time Created'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('timecreate', Input::previous('timecreate', $student->timecreate), array('id' => 'label-timecreate')); ?>
<!--                    <em>--><?php //echo __('students.timecreate_explain'); ?><!--</em>-->
<!--                </p>-->
<!--                <p>-->
<!--                    <label for="label-city">--><?php //echo __('City'); ?><!--:</label>-->
<!--                    --><?php //echo Form::text('city', Input::previous('city', $student->city), array('id' => 'label-city')); ?>
<!--                    <em>--><?php //echo __('students.city_explain'); ?><!--</em>-->
<!--                </p>-->
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
