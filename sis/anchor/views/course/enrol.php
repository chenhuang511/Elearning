<?php echo $header; ?>
<ol class="breadcrumb">
    <li><a href="<?php echo Uri::to('admin'); ?>">Trang chủ</a></li>
    <li><a href="<?php echo Uri::to('admin/courses'); ?>">Quản lý khóa học</a></li>
    <li class="active">Phân quyền người dùng</li>
</ol>
<section class="wrap">
    <div class="clearfix">
        <?php echo $messages; ?>
    </div>
    <div class="course-box clearfix">
        <h3><?php echo $course->fullname ?></h3>
        <table class="table course-info">
            <tbody>
            <tr>
                <td class="first-col">Ngày khai giảng:</td>
                <td class="last-col"><?php echo date('d-m-Y', strtotime($course->startdate)); ?></td>
            </tr>
            <tr>
                <td>Ngày kết thúc:</td>
                <td><?php echo date('d-m-Y', strtotime($course->enddate)); ?></td>
            </tr>
            <tr>
                <td>Mô tả:</td>
                <td><?php echo $course->summary ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="enrollment-block clearfix">
        <ul class="nav nav-tabs enrol-tab" id="myTabs" role="tablist">
            <li role="presentation" class="active"><a href="#teacher" id="home-tab" role="tab" data-toggle="tab"
                                                      aria-controls="teacher" aria-expanded="true">Giảng viên</a></li>
            <li role="presentation" class=""><a href="#student" role="tab" id="profile-tab" data-toggle="tab"
                                                aria-controls="profile" aria-expanded="false">Học viên</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" role="tabpanel" id="teacher" aria-labelledby="teacher-tab">
                <input type="hidden" id="enrol_position" value="">
                <table class="table table-hover enrol-table">
                    <thead>
                    <tr>
                        <th>Họ tên/ Email</th>
                        <th class="th-sort">Ghi danh</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td>
                                    <div class="enrol-user">
                                        <span class="enrol-user-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <p class="enrol-user-info">
                                            <span><?= $user->real_name ?></span><br>
                                            <span><?= $user->email ?></span>
                                        </p>
                                    </div>
                                </td>
                                <td id="rolename-tc-<?= $user->id ?>">
                                    <?php $isenrol = check_user_enrolled($user->id, $course->id, 3); ?>
                                    <?php if($isenrol) : ?>
                                        Giảng viên
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span id="show_enrol_2" class="show-enrol"></span>
                                    <input type="hidden" id="role_id_2" value="">
                                    <?php if($isenrol) : ?>
                                        <button data-role="3" type="button" class="add-enrol role-action unenroll-user"
                                                data-userid="<?= $user->id ?>" data-type="unenroll" data-target="#rolename-tc-<?= $user->id ?>" ><i
                                                class="fa fa-user-times" aria-hidden="true"></i></button>
                                    <?php else: ?>
                                        <button data-role="3" type="button" class="add-enrol role-action enrol-user"
                                                data-userid="<?= $user->id ?>" data-type="enrol" data-target="#rolename-tc-<?= $user->id ?>"><i
                                                class="fa fa-user-plus" aria-hidden="true"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="student" aria-labelledby="student-tab">
                <input type="hidden" id="enrol_position" value="">
                <table class="table table-hover enrol-table">
                    <thead>
                    <tr>
                        <th>Họ tên/ Email</th>
                        <th class="th-sort">Ghi danh</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student) : ?>
                    <tr>
                        <td>
                            <div class="enrol-user">
                                <span class="enrol-user-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <p class="enrol-user-info">
                                    <span><?= $student->fullname ?> </span><br>
                                    <span><?= $student->email ?></span>
                                </p>
                            </div>
                        </td>
                        <td id="rolename-st-<?= $student->id ?>">
                            <?php $isenrol = check_user_enrolled($student->id, $course->id, 5) ?>
                            <?php if($isenrol) : ?>
                            Học viên
                            <?php endif; ?>
                        </td>
                        <td>
                            <span id="show_enrol_3" class="show-enrol"></span>
                            <input type="hidden" id="role_id_3" value="">
                            <?php if($isenrol) : ?>
                                <button data-role="5" type="button" class="add-enrol role-action unenroll-user"
                                        data-userid="<?= $student->id ?>" data-type="unenroll" data-target="#rolename-st-<?= $student->id ?>" ><i
                                        class="fa fa-user-times" aria-hidden="true"></i></button>
                            <?php else: ?>
                                <button data-role="5" type="button" class="add-enrol role-action enrol-user"
                                        data-userid="<?= $student->id ?>" data-type="enrol" data-target="#rolename-st-<?= $student->id ?>" ><i
                                        class="fa fa-user-plus" aria-hidden="true"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input id="menuSelected" type="hidden" value="<?php if (isset($tab)): echo $tab; endif; ?>">
</section>
<input type="hidden" name="token" id="token" value="<?php echo Csrf::token(); ?>">
<script src="<?php echo asset('anchor/views/assets/js/enrol-module.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/sort-elements.js'); ?>"></script>
<script>
    (function ($) {
        $(document).ready(function () {
            enrolModule.init(<?= $course->id ?>);

            $('.enrol-table').each(function(){

                var th = $(this).find('. '),
                    thIndex = th.index(),
                    inverse = true;

                $(this).find('td').filter(function(){

                    return $(this).index() === thIndex;

                }).sortElements(function(a, b){

                    return $.text([a]) > $.text([b]) ?
                        inverse ? -1 : 1
                        : inverse ? 1 : -1;
                }, function(){

                    // parentNode is the element we want to move
                    return this.parentNode;

                });

            });
        });
    })(jQuery);
</script>
<?php echo $footer; ?>
