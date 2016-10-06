<?php echo $header; ?>
    <ol class="breadcrumb">
        <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
        <li><a href="<?php echo Uri::to('admin/courses'); ?>">Quản lý khóa học</a></li>
        <li class="active">Tạo lịch giảng</li>
    </ol>
    <form method="post" class="addtopic"
          action="<?php echo Uri::to('admin/curriculum/add/topic/' . $courseid); ?>"
          enctype="multipart/form-data" novalidate>
        <input name="token" type="hidden" value="<?php echo $token; ?>">
        <div class="form-group notification">
            <?php
            if (count($errors) == 0) {
                echo $messages;
            }
            ?>
        </div>
        <div class="panel-group" role="tablist" id="accordion" aria-multiselectable="true">
            <?php foreach ($dates as $key => $date) { ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading_topic_<?php echo $key ?>">
                        <h4 class="panel-title">
                            <a href="#collapse_topic_<?php echo $key ?>"
                               role="button"
                               data-toggle="collapse"
                               data-parent="#accordion"
                               aria-expanded="<?php if ($key == 1) {
                                   echo 'true';
                               } else {
                                   echo 'false';
                               } ?>"
                               aria-controls="collapse_topic_<?php echo $key ?>">
                                <?php echo $key . '. ' . $date ?> </a>

                            <?php if ((isset($errors['topic_' . $key][0]) && $errors['topic_' . $key][0]) || (isset($errors['teacher_' . $key][0]) && $errors['teacher_' . $key][0])) { ?>
                                <span class="text-danger"> Có lỗi<i class="fa fa-exclamation"
                                                                    aria-hidden="true"></i></span>
                            <?php } ?>
                        </h4>
                    </div>
                    <div class="collapse panel-collapse <?php if ($key == 1) {
                        echo 'in';
                    } ?>" role="tabpanel" id="collapse_topic_<?php echo $key ?>"
                         aria-labelledby="heading_topic_<?php echo $key ?>">
                        <div class="panel-body">
                            <div id="topic_html_<?php echo $key; ?>"></div>
                            <div class="topic-box clearfix">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="time_<?php echo $key; ?>"
                                                   class="control-label"><?php echo __('curriculum.time') ?></label>
                                            <?php echo Form::text('time_' . $key, Input::previous('time'), array(
                                                'placeholder' => __('curriculum.time'),
                                                'autocomplete' => 'off',
                                                'autofocus' => 'true',
                                                'class' => 'form-control',
                                                'id' => 'time_' . $key
                                            )); ?>
                                        </div>
                                        <div class="form-group <?php if (isset($errors['topic_' . $key])) {
                                            echo 'has-error';
                                        } else {
                                            echo '';
                                        } ?>">
                                            <label for="topic_<?php echo $key; ?>"
                                                   class="control-label"><?php echo __('curriculum.topic') ?> <span
                                                    class="text-danger">*</span></label>
                                            <?php echo Form::textarea('topic_' . $key, Input::previous('topic'), array('id' => 'topic_' . $key, 'class' => 'form-control', 'rows' => 3)); ?>
                                            <?php if (isset($errors['topic_' . $key])) { ?>
                                                <p class="help-block"><?php echo $errors['topic_' . $key][0] ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group clearfix <?php if (isset($errors['teacher_' . $key])) {
                                            echo 'has-error';
                                        } else {
                                            echo '';
                                        } ?>">
                                            <label for="teacher_<?php echo $key; ?>"
                                                   class="control-label"><?php echo __('curriculum.teacher') ?> <span
                                                    class="text-danger">*</span></label>
                                            <?php echo Form::select('teacher_' . $key, $teachers, Input::previous('teacher'), array('id' => 'teacher_' . $key, 'class' => 'form-control')); ?>
                                            <input type="hidden" id="hidden_teacher_id_<?php echo $key; ?>" value="">
                                            <?php if (isset($errors['teacher_' . $key])) { ?>
                                                <p class="help-block"><?php echo $errors['teacher_' . $key][0] ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="note_topic_<?php echo $key; ?>"
                                                   class="control-label"><?php echo __('curriculum.note') ?></label>
                                            <?php echo Form::textarea('note_topic_' . $key, Input::previous('note'), array('id' => 'note_topic_' . $key, 'class' => 'form-control', 'rows' => 3)); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <input type="hidden" id="id_topic_<?php echo $key; ?>"
                                           name="id_topic_<?php echo $key; ?>" value="<?php echo $key; ?>">
                                    <input type="hidden" id="content_topic_<?php echo $key; ?>"
                                           name="content_topic_<?php echo $key; ?>" value="">
                                    <p style="font-style: italic;">(Những thông tin có <span
                                            class="text-danger">*</span> là bắt buộc điền thông tin)</p>
                                    <a href="#" id="add_new_topic_<?php echo $key; ?>"
                                       class="btn btn-success"><?php echo __('curriculum.addtopic') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="form-group text-right">
            <aside class="buttons">
                <?php echo Form::button(__('global.save'), array(
                    'type' => 'submit',
                    'class' => 'btn btn-primary btn-save',
                    'data-loading' => __('global.saving')
                )); ?>
                <?php echo Html::link('admin/curriculum/' . $courseid, __('global.cancel'), array(
                    'class' => 'btn btn-danger btn-cancel'
                )); ?>
            </aside>
        </div>
    </form>
    <script src="<?php echo asset('anchor/views/assets/js/topic-module.js'); ?>"></script>
    <script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
    <script type="text/javascript">
        $(function () {
            $(document).ready(function () {
                topicModule.init();
            });
        });
    </script>
<?php echo $footer; ?>