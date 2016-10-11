<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('schools.add_school'); ?></h1>
</hgroup>

<section class="wrap">
    <?php echo $messages; ?>
    <?php if (Auth::admin()) : ?>

        <form method="post" action="<?php echo Uri::to('admin/schools/add'); ?>" novalidate autocomplete="off"
              enctype="multipart/form-data">

            <input name="token" type="hidden" value="<?php echo $token; ?>">

            <fieldset class="half split">
                <p>
                    <label for="label-name"><?php echo __('School Name'); ?>:</label>
                    <?php echo Form::text('name', Input::previous('name'), array('id' => 'label-name')); ?>
                </p>
            </fieldset>

            <fieldset class="half split">
                <?php foreach ($fields as $field): ?>
                    <p>
                        <label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
                        <?php echo Extend::html($field); ?>
                    </p>
                <?php endforeach; ?>
            </fieldset>

            <aside class="buttons">
                <?php echo Form::button(__('global.create'), array('class' => 'btn', 'type' => 'submit')); ?>

                <?php echo Html::link('admin/schools', __('global.cancel'), array('class' => 'btn cancel blue')); ?>
            </aside>
        </form>
    <?php else : ?>
        <p>You do not have the required privileges to add schools, you must be an Administrator. Please contact the
            Administrator of the site if you are supposed to have these privileges.</p>
        <br><a class="btn" href="<?php echo Uri::to('admin/schools'); ?>">Go back</a>
    <?php endif; ?>
</section>
<input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>

<?php echo $footer; ?>
