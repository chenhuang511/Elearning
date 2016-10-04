<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Thông tin trường học'); ?></h1>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>

    <?php if(Auth::admin() || Auth::me($school->id)) : ?>
        <form method="post" action="<?php echo Uri::to('admin/schools/info/' . $school->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

            <input name="token" type="hidden" value="<?php echo $token; ?>">

            <fieldset class="half split">
                <p>
                    <label for="label-id"><?php echo __('id'); ?>:</label>
                    <label id="label-id"><?php echo $school->id ?></label>
                </p>

                <?php foreach ($schoolstudent as $schstu): ?>
                    <p>
                        <label for="label-iduser"><?php echo __('ID Student'); ?>:</label>
                        <label id="label-iduser"><?php echo $schstu->id ?></label>
                    </p>
                <?php endforeach; ?>

            </fieldset>

            <fieldset class="half split">
                <?php foreach($fields as $field): ?>
                    <p>
                        <label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
                        <?php // echo Extend::html($field); ?>
                    </p>
                <?php endforeach; ?>
                <p>
                    <label for="label-name"><?php echo __('School name'); ?>:</label>
                    <?php // echo Form::text('name', Input::previous('name', $school->name), array('id' => 'label-name')); ?>
                    <label id="label-name"><?php echo $school->name ?></label>
                </p>

                <?php foreach($schoolstudent as $schstu): ?>
                    <p>
                        <label for="label-username"><?php echo __('User Student'); ?>:</label>
                        <?php // echo Form::text('username', Input::previous('username', $username), array('id' => 'label-username')); ?>
                        <label id="label-username"><?php echo $schstu->fullname ?></label>
                    </p>
                <?php endforeach;  ?>

            </fieldset>
            <aside class="buttons">
                <?php echo Form::button(__('global.update'), array(
                    'class' => 'btn',
                    'type' => 'submit'
                )); ?>

                <?php echo Html::link('admin/schools' , __('global.cancel'), array('class' => 'btn cancel blue')); ?>

                <?php echo Html::link('admin/schools/delete/' . $school->id, __('global.delete'), array('class' => 'btn delete red')); ?>
            </aside>
        </form>
    <?php else : ?>
        <p>You do not have the required privileges to modify this schools information, you must be an Administrator. Please contact the Administrator of the site if you are supposed to have these privileges.</p>
        <br><a class="btn" href="<?php echo Uri::to('admin/schools'); ?>">Go back</a>
    <?php endif; ?>
</section>

<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
