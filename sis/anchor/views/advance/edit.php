<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>
</hgroup>

<form method="post" action="<?php echo Uri::to('admin/advance/edit/'. $article->id); ?>" enctype="multipart/form-data" novalidate>

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
            <p>
                <label for="label-slug"><?php echo __('advance.applicant'); ?>:</label>
                <?php echo Form::select('applicant_id', $staff, Input::previous('status', $article->applicant_id), array('id' => 'label-applicant_id' )); ?>
            </p>
            <p>
                <label for="label-status"><?php echo __('advance.money'); ?>:</label>
                <?php echo Form::text('money', Input::previous('money',$article->money), array('id' => 'label-money')); ?>
            </p>
            <p>
                <label for="label-status"><?php echo __('advance.time'); ?>:</label>
                <?php echo Form::text('time', Input::previous('time',$article->time), array('id' => 'label-time')); ?>
            </p>
            <p>
                <label for="label-description"><?php echo __('advance.reason'); ?>:</label>
                <?php echo Form::textarea('reason', Input::previous('reason',$article->reason), array('id' => 'label-reason')); ?>
            </p>
            <p>
                <label for="label-slug"><?php echo __('advance.status'); ?>:</label>
                <?php echo Form::select('status',  ['draft' => 'draff', 'published' => 'published'], Input::previous('status', $article->status), array('id' => 'label-applicant_id' )); ?>
            </p>


    </fieldset>
</form>

<?php echo $footer; ?>
