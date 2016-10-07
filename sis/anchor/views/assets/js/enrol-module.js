var enrolModule = (function () {
    var getAddEnrol = function () {
        return $('button[id^=add_enrol_]');
    };

    var getShowEnrol = function () {
        return $('span[id^=show_enrol_]');
    };

    var getAssignRoles = function () {
        return $('button[id^=assign_role_]');
    };

    var getRoleIds = function () {
        return $('input[id^=role_id_]');
    };

    var init = function () {
        var addEnrols = getAddEnrol(),
            showEnrols = getShowEnrol(),
            assignRoles = getAssignRoles(),
            roleIds = getRoleIds(),
            positionHidden = $('#enrol_position');

        $.each(addEnrols, function (index, element) {
            $(element).on('click', function (e) {
                console.log('vao day');
                var positionid = $(this).attr('data-positon');
                positionHidden.val(positionid);
            });
        });

        $.each(assignRoles, function (index, element) {
            $(element).on('click', function (e) {
                console.log('chon vai tro');
                var positionid = positionHidden.val();
                console.log('vi tri: ', positionid);
                if(positionid.length > 0) {
                    var roleid = $(this).attr('data-role'),
                        rolename = $(this).attr('data-text');
                    console.log('role id: ', roleid);
                    console.log('role name: ', rolename);
                    $('#role_id_' + positionid).val(roleid);
                    $('#show_enrol_' + positionid).html(rolename);
                    $('#enrolModal').modal('hide');
                }
            });
        });
    };
    return {
        init: init
    };
}).call(this);