<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/permission'); ?>">Quản lý permission</a></li>
</ol>
<form method="post" action="<?php echo Uri::to('admin/permission/' . $role); ?>" enctype="multipart/form-data"
      novalidate class="form-horizontal ">
    <section class="wrap">
        <p class="text-right">
        </p>
        <?php echo $messages; ?>
        <div class="row">
            <div class="statusbar col-sm-3">
                <div class="sidebarbox">
                    <h5>Người dùng </h5>
                    <ul class="sidebar statuses">
                        <?php foreach (array('user', 'student', 'school', 'instructor', 'contract') as $type): ?>
                            <li>
                                <?php echo Html::link('admin/permission/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
                                    'class' => ($role == $type) ? 'active' : ''
                                )); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="list_advance col-sm-9">
                <div class="container">
                    <div class="control-group">
                        <?php foreach ($permission->results as $key => $value): ?>
                            <?php if (in_array($value, $dbrouter)): ?>
                                <label class="control control--checkbox"> <?php echo $value; ?>
                                    <?php echo Form::checkbox('box[]', $value, true, array()) ?>
                                    <div class="control__indicator"></div>
                                </label>
                            <?php else: ?>
                                <label class="control control--checkbox"> <?php echo $value; ?>
                                    <?php echo Form::checkbox('box[]', $value, false, array()) ?>
                                    <div class="control__indicator"></div>
                                </label>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>

            </div>
            <div class="form-group text-right" style="padding-right: 15px;">
                <aside class="buttons">
                    <?php echo Form::button(__('global.continue'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-primary btn-continue',
                        'data-loading' => __('global.saving'),
                        'id' => 'submit'
                    )); ?>
                    <?php echo Html::link('admin/permission', __('global.cancel'), array(
                        'class' => 'btn cancel blue'
                    )); ?>
                </aside>
            </div>
        </div>

    </section>
</form>

<?php echo $footer; ?>
