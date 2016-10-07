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
                        <th>Ghi danh</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="enrol-user">
                                <span class="enrol-user-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <p class="enrol-user-info">
                                    <span>teacher name</span><br>
                                    <span>teacher@email.com</span>
                                </p>
                            </div>
                        </td>
                        <td>
                            <span id="show_enrol_1" class="show-enrol"></span>
                            <input type="hidden" id="role_id_1" value="">
                            <button id="add_enrol_1" data-positon="1" type="button" class="add-enrol"
                                    data-toggle="modal" data-target="#enrolModal"><i
                                    class="fa fa-user-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="enrol-user">
                                <span class="enrol-user-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <p class="enrol-user-info">
                                    <span>teacher name</span><br>
                                    <span>teacher@email.com</span>
                                </p>
                            </div>
                        </td>
                        <td>
                            <span id="show_enrol_2" class="show-enrol"></span>
                            <input type="hidden" id="role_id_2" value="">
                            <button id="add_enrol_2" data-positon="2" type="button" class="add-enrol"
                                    data-toggle="modal" data-target="#enrolModal"><i
                                    class="fa fa-user-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="student" aria-labelledby="student-tab">
                <input type="hidden" id="enrol_position" value="">
                <table class="table table-hover enrol-table">
                    <thead>
                    <tr>
                        <th>Họ tên/ Email</th>
                        <th>Ghi danh</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <div class="enrol-user">
                                <span class="enrol-user-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <p class="enrol-user-info">
                                    <span>student name</span><br>
                                    <span>student@email.com</span>
                                </p>
                            </div>
                        </td>
                        <td>
                            <span id="show_enrol_3" class="show-enrol"></span>
                            <input type="hidden" id="role_id_3" value="">
                            <button id="add_enrol_3" data-positon="3" type="button" class="add-enrol"
                                    data-toggle="modal" data-target="#enrolModal"><i
                                    class="fa fa-user-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="enrol-user">
                                <span class="enrol-user-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                <p class="enrol-user-info">
                                    <span>student name</span><br>
                                    <span>student@email.com</span>
                                </p>
                            </div>
                        </td>
                        <td>
                            <span id="show_enrol_4" class="show-enrol"></span>
                            <input type="hidden" id="role_id_4" value="">
                            <button id="add_enrol_4" data-positon="4" type="button" class="add-enrol"
                                    data-toggle="modal" data-target="#enrolModal"><i
                                    class="fa fa-user-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="enrolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Vai trò người dùng</h4>
            </div>
            <div class="modal-body">
                <div class="form-group enrol-options">
                    <button id="assign_role_2" class="btn btn-primary" data-role="3" data-text="Giảng viên">Giảng viên
                    </button>
                    <button id="assign_role_4" class="btn btn-primary" data-role="5" data-text="Học viên">Học viên
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo asset('anchor/views/assets/js/enrol-module.js'); ?>"></script>
<script>
    (function ($) {
        $(document).ready(function () {
            enrolModule.init();
        });
    })(jQuery);
</script>
<?php echo $footer; ?>
