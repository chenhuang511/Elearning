<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Edit student '. $student->id); ?></h1>
</hgroup>

<?php
    $idstudent = $student->id;

    $mysqlconn = new mysqli("localhost", "root", "vannhuthe", "anchor");
    $sql = "SELECT * FROM anchor_student_course WHERE studentid = ".$idstudent;
    $result = $mysqlconn->query($sql);

    $sqlschool = "SELECT * FROM anchor_school_student WHERE userid = ".$idstudent;
    $resultschool = $mysqlconn->query($sqlschool);

?>

<?php

?>

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
                <?php
                    while($rowschool = $resultschool->fetch_assoc())
                    {
                        $schoolid = $rowschool["schoolid"];
                        ?>
                            <p>
                                <label for="label-idschool"><?php echo __('ID School'); ?>:</label>
                                <?php // echo Form::text('idschool', Input::previous('idschool', $schoolid), array('id' => 'label-idschool')); ?>
                                <label for="label-idschool"><?php echo $schoolid ?></label>
                            </p>
                        <?php
                    }
                ?>
                <?php
                    while ($row = $result->fetch_assoc())
                    {
                        $courseid = $row["courseid"];

                        $sql1 = "SELECT * FROM anchor_courses WHERE id = " . $courseid;
                        $result1 = $mysqlconn->query($sql1);

                        while ($row1 = $result1->fetch_assoc())
                        {
                            $id = $row1['id'];
                            ?>
                            <p>
                                <label for="label-idcourse"><?php echo __('ID Course'); ?>:</label>
                                <?php // echo Form::text('idcourse', Input::previous('idcourse', $id), array('id' => 'label-idcourse')); ?>
                                <label id="label-idcourse"><?php echo $id ?></label>
                            </p>
                            <?php
                        }
                    }
                ?>
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
                <?php
                    $resultschool2 = $mysqlconn->query($sqlschool);

                    while($rowschool = $resultschool2->fetch_assoc())
                    {
                        $schoolname = $rowschool["schoolname"];
                        ?>
                        <p>
                            <label for="label-schoolname"><?php echo __('School Name'); ?>:</label>
                            <?php // echo Form::text('schoolname', Input::previous('schoolname', $schoolname), array('id' => 'label-schoolname')); ?>
                            <label id="label-schoolname"><?php echo $schoolname ?></label>
                        </p>
                        <?php
                    }
                ?>
                <?php
                    $result3 = $mysqlconn->query($sql);

                    while($row2 = $result3->fetch_assoc())
                    {
                        $courseid = $row2["courseid"];
                        $sql2 = "SELECT * FROM anchor_courses WHERE id = ".$courseid;
                        $result4 = $mysqlconn->query($sql2);

                        while($row3 = $result4->fetch_assoc())
                        {
                            $fullname = $row3['fullname'];
                            ?>
                            <p>
                                <label for="label-fullname"><?php echo __('Course Name'); ?>:</label>
                                <?php // echo Form::text('fullname', Input::previous('fullname', $fullname), array('id' => 'label-fullname')); ?>
                                <label id="label-fullname"><?php echo $fullname ?></label>
                            </p>
                            <?php
                        }
                    }
                ?>
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
