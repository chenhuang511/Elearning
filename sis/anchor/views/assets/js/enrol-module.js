var enrolModule = (function ($) {
    var btn;
    var getToken = function () {
        return $('#token').val();
    };
    var getUrl = function (courseid, type) {
        if (type == 'enrol') {
            return '/admin/courses/enrol/user/' + courseid;
        } else {
            return '/admin/courses/unenroll/user/' + courseid;
        }
    };

    var getRoleName = function (role) {
        return role == 3 ? 'Giảng viên' : 'Học viên';
    };

    var callAjax = function(url, token, userid, target, role, type) {
        $.ajax({
            method: "POST",
            url: url,
            data : { token: token, userid: userid, role: role },
            dataType: "text",
            success: function(result) {
                $('#load').remove();
                if(result) {
                    if(target != false) {
                        if(type == 'enrol') {
                            $(target).text(getRoleName(role));
                            btn.find('i').remove();
                            btn.data('type', 'unenroll');
                            btn.append('<i class="fa fa-user-times"></i>');
                        } else {
                            $(target).text(' ');
                            btn.find('i').remove();
                            btn.data('type', 'enrol');
                            btn.append('<i class="fa fa-user-plus"></i>');
                        }
                    } else {
                        if(type == 'enrol') {
                            btn.text('Bỏ ghi danh');
                            btn.data('type', 'unenroll');
                        } else {
                            btn.text('Ghi danh');
                            btn.data('type', 'enrol');
                        }
                    }
                }
            }
        });
    }
    var role_user = function (courseid, userid, role) {
        $('.role-action').click(function () {
            // call ajax
            btn = $(this);
            token = getToken();
            courseid = $(this).data('courseid') ? $(this).data('courseid') : courseid ;
            userid = $(this).data('userid') ? $(this).data('userid') : userid ;
            role = $(this).data('role') ? $(this).data('role') : role ;
            target = $(this).data('target') ? $(this).data('target') : false;
            type = $(this).data('type');
            url = getUrl(courseid, type);

            $('#load').remove();
            $(this).append('<i id="load" class="fa fa-spinner fa-pulse fa fa-fw"></i>');
            
            callAjax(url, token, userid, target, role, type);
        });
    };

    var init = function (courseid) {
        role_user(courseid);
    }

    var init_course = function (userid, role) {
        role_user(false, userid, role);
    }

    return {
        init_course: init_course,
        init: init
    }
}(jQuery));
