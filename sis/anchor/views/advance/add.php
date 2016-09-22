<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>
</hgroup>

<form method="post" action="<?php echo Uri::to('admin/advance/add'); ?>" enctype="multipart/form-data" novalidate>

    <input name="token" type="hidden" value="<?php echo $token; ?>">

    <fieldset class="header">
        <div class="wrap">


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
                <?php echo Form::select('staff', $staff, Input::previous('staff'), array('id' => 'label-staff')); ?>
                <!--                <em>--><?php //echo __('posts.slug_explain'); ?><!--</em>-->
            </p>
            <p>
                <label for="label-status"><?php echo __('advance.money'); ?>:</label>
                <?php echo Form::text('slug', Input::previous('slug'), array('id' => 'label-slug')); ?>
                <em><?php echo __('posts.status_explain'); ?></em>
            </p>
            <p>
                <label for="label-description"><?php echo __('advance.reason'); ?>:</label>
                <?php echo Form::textarea('description', Input::previous('description'), array('id' => 'label-description')); ?>
                <em><?php echo __('posts.description_explain'); ?></em>
            </p>


    </fieldset>
</form>

<!--    <section class="wrap">-->
<!--        --><?php //echo $messages; ?>
<!---->
<!--        <ul class="list">-->
<!--            <li>-->
<!--                <a href="--><?php //echo Uri::to('admin/extend/pagetypes'); ?><!--">-->
<!--                    <strong>--><?php //echo __('extend.pagetypes'); ?><!--</strong>-->
<!--                    <span>--><?php //echo __('extend.pagetypes_desc'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo Uri::to('admin/extend/fields'); ?><!--">-->
<!--                    <strong>--><?php //echo __('extend.fields'); ?><!--</strong>-->
<!--                    <span>--><?php //echo __('extend.fields_desc'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo Uri::to('admin/extend/variables'); ?><!--">-->
<!--                    <strong>--><?php //echo __('extend.variables'); ?><!--</strong>-->
<!--                    <span>--><?php //echo __('extend.variables_desc'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo Uri::to('admin/extend/metadata'); ?><!--">-->
<!--                    <strong>--><?php //echo __('metadata.metadata'); ?><!--</strong>-->
<!--                    <span>--><?php //echo __('metadata.metadata_desc'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo Uri::to('admin/extend/plugins'); ?><!--">-->
<!--                    <strong>Plugins</strong>-->
<!--                    <span>Coming soon, yo!</span>-->
<!--                </a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </section>-->

<?php echo $footer; ?>
