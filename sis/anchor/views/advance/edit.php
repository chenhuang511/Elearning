<?php echo $header; ?>


<hgroup class="wrap">
    <h1><?php echo __('advance.advance'); ?></h1>
</hgroup>
<form method="post" action="<?php echo Uri::to('admin/advance/course/' .$courseId.'/edit/'. $article->id); ?>" enctype="multipart/form-data" novalidate>

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

                <?php echo Html::link('admin/advance/course/' .$courseId , __('global.cancel'), array(
                    'class' => 'btn cancel blue'
                )); ?>

                <?php echo Html::link('admin/advance/course/' .$courseId.'/delete/' . $article->id, __('global.delete'), array(
                    'class' => 'btn delete red'
                )); ?>
            </aside>
        </div>
    </fieldset>


    <fieldset class="meta split " style="width: 900px !important;">
        <div class="wrap">
            <table class="form_advance">
                <tr >
                    <td><label for="label-slug"><?php echo __('advance.courses'); ?>:</label></td>
                    <td> <label for="label-slug" style="padding-left: 0px !important;"><?php echo $course; ?></label></td>
                </tr>
                <tr >
                    <td><label for="label-slug">Mã đơn:</label></td>
                    <td> <label for="label-slug" style="padding-left: 0px !important;"><?php echo $article->id; ?></label></td>
                </tr>
                <tr >
                    <td><label for="label-slug"><?php echo __('advance.applicant'); ?>:</label></td>
                    <?php if($article->status == "published"){
                        ?>
                        <td> <label for="label-slug" style="padding-left: 0px !important;"><?php echo $article->real_name; ?></label></td>
                        <input type="hidden" name="applicant_id" value="<?php echo $article->applicant_id;?>"/>
                        <?php
                    }else{
                        ?>
                        <td><?php echo Form::select('applicant_id', $user, Input::previous('applicant', $article->applicant_id), array('id' => 'label-applicant_id', '' )); ?></td>
                        <?php
                    }?>

                </tr>
                <tr>
                    <td><label for="label-status"><?php echo __('advance.money'); ?>:</label></td>
                    <?php if($article->status == "published"){
                        ?>
                        <td><?php echo Form::text('money', Input::previous('money',$article->money), array('id' => 'label-applicant_id', 'readonly'=>'true' )); ?></td>
                        <?php
                    }else{
                        ?>
                        <td><?php echo Form::text('money', Input::previous('money',$article->money), array('id' => 'label-money')); ?></td>
                        <?php
                    }?>

                </tr>
                <tr>
                    <td><label for="label-status"><?php echo __('advance.time_request'); ?>:</label></td>
                    <?php if($article->status == "published"){
                        ?>
                        <td><?php echo Form::text('time_request', Input::previous('time_request',$article->time_request), array('id' => 'label-time datetimepicker_startdate','readonly'=>'true')); ?></td>
                        <?php
                    }else{
                        ?>
                        <td>
                            <div class='input-group date' id='datetimepicker_startdate'>
                                <?php echo Form::text('time_request', Input::previous('time_request',$article->time_request), array('id' => 'label-time datetimepicker_startdate')); ?>
                                <span class="input-group-addon">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </span>
                            </div>
                        </td>
                        <?php
                    }?>

                </tr>
                <tr>
                    <td><label for="label-status"><?php echo __('advance.time_response'); ?>:</label></td>
                     <?php
                        if ($article->status == "draft"){
                            ?>
                            <td><label for="label-slug" style="padding-left: 0px !important;"><?php echo 'Chưa được xét duyệt'; ?></label></td>
                            <?php

                        } else{
                                if($article->status == "published"){
                                ?>
                                    <td><?php echo Form::text('time_response', Input::previous('time_response',$article->time_response), array('id' => 'label-time datetimepicker_enddate','readonly'=>'true')); ?></td>
                                <?php
                                }else{
                                ?>
                                    <td>
                                        <div class='input-group date' id='datetimepicker_enddate'>
                                            <?php echo Form::text('time_response', Input::previous('time_response',$article->time_response), array('id' => 'label-time datetimepicker_enddate')); ?>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </td>
                                <?php
                                }
                        }

                        ?>

                </tr>
                <tr>
                    <td><label for="label-description"><?php echo __('advance.reason'); ?>:</label></td>
                    <?php if($article->status == "published"){
                        ?>
                        <td><?php echo Form::textarea('reason', Input::previous('reason',$article->reason), array('id' => 'label-applicant_id', 'readonly'=>'true' )); ?></td>
                        <?php
                    }else{
                        ?>
                        <td><?php echo Form::textarea('reason', Input::previous('reason',$article->reason), array('id' => 'label-reason')); ?></td>
                        <?php
                    }?>

                </tr>
                <tr>
                    <td><label for="label-slug"><?php echo __('advance.status'); ?>:</label></td>
                    <?php if($article->status == "published"){
                        ?>
                        <td><?php echo Form::select('status',  [ 'published' => 'Chấp nhận'], Input::previous('status', $article->status), array('id' => 'label-applicant_id', 'readonly'=>'true' )); ?></td>
                        <?php
                    }else{
                        ?>
                        <td><?php echo Form::select('status',  ['draft' => 'Đang yêu cầu', 'published' => 'Chấp nhận','rebuff' => 'Từ chối yêu cầu'], Input::previous('status', $article->status), array('id' => 'label-applicant_id' )); ?></td>
                        <?php
                    }?>

                </tr>

                    <tr>
                        <td><label for="label-slug">Người xét duyệt:</label></td>
                        <td><label for="label-slug" style="padding-left: 0px !important;"><?php echo $article->real_name; ?></label></td>
                    </tr>


            </table>



    </fieldset>
</form>
<script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/autosave.js'); ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker_startdate').datetimepicker({
            language: 'fr',
            startDate: new Date(),
            startView: 2,
            minView: 2,
            format: 'yyyy-mm-dd',
            pickTime: false,
        });
        $('#datetimepicker_enddate').datetimepicker({
            language: 'fr',
            pickTime: false,
            startView: 2,
            minView: 2,
            format: 'yyyy-mm-dd'
        });

        $("#datetimepicker_startdate").on("changeDate", function (e) {
            $('#datetimepicker_enddate').datetimepicker('setStartDate', e.date);
        });
        $("#datetimepicker_enddate").on("changeDate", function (e) {
            $('#datetimepicker_startdate').datetimepicker('setEndDate', e.date);
        });
    });
</script>

<?php echo $footer; ?>
