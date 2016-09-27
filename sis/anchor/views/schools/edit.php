<?php echo $header; ?>

<hgroup class="wrap">
    <h1><?php echo __('Edit school', $school->name); ?></h1>
</hgroup>

<?php
$idschool = $school->remoteid;

$mysqlconn = new mysqli("localhost", "root", "vannhuthe", "anchor");
$sql = "SELECT * FROM anchor_school WHERE schoolid = ".$idschool;
$result = $mysqlconn->query($sql);

?>

<section class="wrap">
    <?php echo $messages; ?>

    <?php if(Auth::admin() || Auth::me($school->id)) : ?>
        <form method="post" action="<?php echo Uri::to('admin/schools/edit/' . $school->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">

            <input name="token" type="hidden" value="<?php echo $token; ?>">

            <fieldset class="half split">
                <p>
                    <label for="label-id"><?php echo __('id'); ?>:</label>
                    <?php echo Form::text('id', Input::previous('id', $school->id), array('id' => 'label-id')); ?>
                    <em><?php echo __('schools.username_explain'); ?></em>
                </p>
                <?php
                while($row = $result->fetch_assoc())
                {
                    $userid = $row["userid"];
                    //var_dump($courseid);

                        ?>
                        <p>
                            <label for="label-iduser"><?php echo __('ID Student'); ?>:</label>
                            <?php echo Form::text('iduser', Input::previous('iduser', $userid), array('id' => 'label-iduser')); ?>
                        </p>
                        <?php
                }
                //die;
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
                    <label for="label-name"><?php echo __('School name'); ?>:</label>
                    <?php echo Form::text('schoolname', Input::previous('schoolname', $school->name), array('id' => 'label-schoolname')); ?>

                </p>
                <?php
                $result1 = $mysqlconn->query($sql);
                while($row = $result1->fetch_assoc())
                {
                    $username = $row["username"];
                    //var_dump($courseid);

                    ?>
                    <p>
                        <label for="label-username"><?php echo __('User Student'); ?>:</label>
                        <?php echo Form::text('username', Input::previous('username', $username), array('id' => 'label-username')); ?>
                    </p>
                    <?php
                }
                //die;
                ?>
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
