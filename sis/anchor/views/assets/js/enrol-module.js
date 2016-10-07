var enrolModule = (function ($) {
    var getToken = function () {
        return $('#token').val();
    };
    var getUrl = function (courseid) {
        return '/admin/courses/enrol/user/' + courseid;
    };

    var getRoleName = function (role) {
        return role == 3 ? 'Giảng viên' : 'Học viên';
    };

    var callAjax = function(url, token, userid, target, role) {
        $.ajax({
            method: "POST",
            url: url,
            data : { token: token, userid: userid, role: role },
            dataType: "text",
            success: function(result){
                $('#load').remove();
                if(result) {
                    $(target).text(getRoleName(role));
                }
            }
        });
    }
    var init = function (courseid) {
        
        $('.enrol-user').click(function () {
            // call ajax
            
            token = getToken();
            userid = $(this).data('id');
            role = $(this).data('role');
            url = getUrl(courseid);
            target = $(this).data('target');
            
            $(this).append('<i id="load" class="fa fa-spinner fa-pulse fa fa-fw"></i>');
            callAjax(url, token, userid, target, role);
        });
    }
    return {
        init: init
    }
}(jQuery));
