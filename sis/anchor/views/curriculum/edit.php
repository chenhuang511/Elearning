<?php echo $header; ?>

    <form method="post" class="edtitopic"
          action="<?php echo Uri::to('admin/curriculum/edit/topic/' . $curriculum->id); ?>"
          enctype="multipart/form-data" novalidate>
        <input name="token" type="hidden" value="<?php echo $token; ?>">
        <div class="form-group notification">
            <?php
            if (count($errors) == 0) {
                echo $messages;
            }
            ?>
        </div>
        <div class="topic-box clearfix">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group clearfix <?php if (isset($errors['topicname'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="topicname"
                               class="control-label"><?php echo __('curriculum.topic') ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::text('topicname', Input::previous('topicname', $curriculum->topicname), array('id' => 'topicname', 'class' => 'form-control')); ?>
                        <?php if (isset($errors['topicname'])) { ?>
                            <p class="help-block"><?php echo $errors['topicname'][0] ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="note"
                               class="control-label"><?php echo __('curriculum.note') ?></label>
                        <?php echo Form::textarea('note', Input::previous('note', $curriculum->note), array('id' => 'note', 'class' => 'form-control', 'rows' => 3)); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="topictime"
                                       class="control-label"><?php echo __('curriculum.time') ?></label>
                                <?php echo Form::select('topictime', $topictime, Input::previous('topictime', $curriculum->topictime), array('id' => 'topictime', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div
                                class="form-group clearfix <?php if (isset($errors['teacher'])) {
                                    echo 'has-error';
                                } else {
                                    echo '';
                                } ?>">
                                <label for="teacher"
                                       class="control-label"><?php echo __('curriculum.teacher') ?>
                                    <span
                                        class="text-danger">*</span></label>
                                <?php echo Form::select('teacher', $teachers, Input::previous('teacher', $curriculum->teacher), array('id' => 'teacher', 'class' => 'form-control')); ?>
                                <?php if (isset($errors['teacher'])) { ?>
                                    <p class="help-block"><?php echo $errors['teacher'][0] ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group <?php if (isset($errors['room'])) {
                        echo 'has-error';
                    } else {
                        echo '';
                    } ?>">
                        <label for="room"
                               class="control-label"><?php echo __('curriculum.room'); ?> <span
                                class="text-danger">*</span></label>
                        <?php echo Form::select('room', $rooms, Input::previous('room', $curriculum->room), array('id' => 'room', 'class' => 'form-control')); ?>
                        <?php if (isset($errors['room'])) { ?>
                            <p class="help-block"><?php echo $errors['room'][0] ?></p>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="dayoff" name="dayoff" type="checkbox" value="1">
                                Ngày nghỉ
                            </label>
                        </div>
                        <p style="font-style: italic;">(Những thông tin có <span
                                class="text-danger">*</span> là bắt buộc điền thông tin)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group text-right">
            <input type="hidden" id="topic_day"
                   name="topic_day"
                   value="<?php echo substr($curriculum->topicday, 0, 10); ?>">
            <aside class="buttons">
                <?php echo Form::button(__('global.update'), array(
                    'id' => 'btn_save',
                    'type' => 'submit',
                    'class' => 'btn btn-primary btn-save',
                    'data-loading' => __('global.updating')
                )); ?>
                <?php echo Html::link('admin/curriculum/' . $curriculum->course, __('global.cancel'), array(
                    'class' => 'btn btn-danger btn-cancel'
                )); ?>
            </aside>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="confirmRoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-danger" id="myModalLabel"><i class="fa fa-exclamation-triangle"
                                                                             aria-hidden="true"></i> Cảnh báo</h4>
                </div>
                <div class="modal-body">
                    Phòng học này đã được đăng ký sử dụng. Bạn có chắc chắn muốn đăng ký?
                </div>
                <div class="modal-footer">
                    <button id="confirmRoomButton" type="button" class="btn btn-primary" data-dismiss="modal">Đăng ký
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>
    <input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
    <script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
    <script>
        (function ($) {
            var addWarningClass = function (node, message) {
                if (!$(node).hasClass('has-warning')) {
                    $(node).addClass('has-warning');
                }

                var helpBlock = $(node).find('p.help-block');
                if (helpBlock.length) {
                    helpBlock.html(message);
                } else {
                    $(node).append('<p class="help-block">' + message + '</p>');
                }
            }

            var removeWarningClass = function (node) {
                if ($(node).hasClass('has-warning')) {
                    $(node).removeClass('has-warning');
                }

                var helpBlock = $(node).find('p.help-block');
                if (helpBlock.length) {
                    helpBlock.remove();
                }
            };

            var checkRoom = function (day, roomid, time, node, button) {
                var url = '/admin/curriculum/topic/checkroom/' + day + '/' + roomid + '/' + time;

                $.ajax({
                    method: "GET",
                    url: url,
                    dataType: "text",
                    success: function (rs) {
                        if (rs == 'y') {
                            $(confirmRoom).modal('show');
                            addWarningClass($(node).parent(), '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Phòng học này đã được sử dụng');
                            // disable add new topic
                            $(button).attr('disabled', 'disabled');
                        }
                        if (rs == 'n') {
                            removeWarningClass($(node).parent());
                            $(button).removeAttr('disabled');
                        }
                    },
                    fail: function (data) {
                        console.log('co loi');
                    }
                });
            };

            $(document).ready(function () {
                var dayoff = $('#dayoff');
                var inputs = ['#topicname', '#topictime', '#teacher', '#room', '#note'];

                dayoff.on('click', function () {
                    var checked = $(this).is(':checked');

                    if (checked == true) {
                        $.each(inputs, function (index, element) {
                            $(element).attr('disabled', 'disabled');
                        });
                    } else {
                        $.each(inputs, function (index, element) {
                            $(element).removeAttr('disabled');
                        });
                    }
                });

                $('#room').on('change', function () {
                    var day = $('#topic_day').val();
                    var time = $(this).find(':selected').val();
                    var roomid = $(this).find(':selected').val();
                    if (roomid != 0) {
                        checkRoom(day, roomid, time, this, '#btn_save');
                    }
                });

                $('#topictime').on('change', function () {
                    var day = $('#topic_day').val();
                    var time = $(this).find(':selected').val();
                    var roomid = $('#room').find(':selected').val();
                    if (roomid != 0) {
                        checkRoom(day, roomid, time, '#room', '#btn_save');
                    }
                });


                $('#confirmRoomButton').on('click', function () {
                    var disabled = $('#btn_save').attr('disabled');
                    if (disabled) {
                        $('#btn_save').removeAttr('disabled');
                    }
                });
            });
        })(jQuery);
    </script>
<?php echo $footer; ?>