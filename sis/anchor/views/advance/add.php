<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>
</hgroup>

<form method="post" action="<?php echo Uri::to('admin/advance/add'); ?>" enctype="multipart/form-data" novalidate>

    <input name="token" type="hidden" value="<?php echo $token; ?>">

    <fieldset class="header">
        <div class="wrap">
            <?php echo $messages; ?>

            <aside class="buttons">
                <?php echo Form::button(__('global.save'), array(
                    'type' => 'submit',
                    'class' => 'btn',
                    'data-loading' => __('global.saving')
                )); ?>

                <?php echo Html::link('admin/advance' , __('global.cancel'), array(
                    'class' => 'btn cancel blue'
                )); ?>
            </aside>
        </div>
    </fieldset>


    <fieldset class="meta split">
        <div class="wrap">
            <table class="form_advance">
                <tr>
                    <td> <label for="label-slug"><?php echo __('advance.courses'); ?>:</label></td>
                    <td><?php echo Form::select('course_id', $course, Input::previous('applicant_id'), array('id' => 'label-course_id')); ?></td>
                </tr>
                <tr>
                    <td> <label for="label-slug"><?php echo __('advance.applicant'); ?>:</label></td>
                    <td><?php echo Form::select('applicant_id', $user, Input::previous('applicant_id'), array('id' => 'label-applicant_id')); ?></td>
                </tr>
                <tr>
                    <td> <label for="label-status"><?php echo __('advance.money'); ?>:</label></td>
                    <td><?php echo Form::text('money', Input::previous('money'), array('id' => 'label-money')); ?></td>
                </tr>
                <tr>
                    <td><label for="label-description"><?php echo __('advance.reason'); ?>:</label></td>
                    <td><?php echo Form::textarea('reason', Input::previous('reason'), array('id' => 'label-reason')); ?></td>
                </tr>

            </table>

    </fieldset>
</form>

<?php echo $footer; ?>
