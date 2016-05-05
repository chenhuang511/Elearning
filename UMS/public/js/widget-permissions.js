/**
 * Created by Minh Nguyen on 4/28/2016.
 */
$(document).ready(function() {
    // Link buttons
    bindGroupTableButtons($("body"));
    
    // Lưu tab đang đang lựa chọn để tải lại trang không bị thay đổi
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
    }

});

function bindGroupTableButtons(table) {
    // Xử lý nút move up
    $(table).find('.js-permission-moveup').click(function () {
        var btn = $(this);
        var roleid = btn.data('id');
        switchRole(roleid,"moveup")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });

    // Xử lý nút move down
    $(table).find('.js-permission-movedown').click(function () {
        var btn = $(this);
        var roleid = btn.data('id');
        switchRole(roleid,"movedown")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });
    
    // Xử lý reload lại trang khi người dùng submit từ form 'allow_assign'
    $(table).find('#allow_assign').submit(function () {
        setTimeout(function () {
            window.location.reload(true);
        }, 10);
    });

    // Xử lý reload lại trang khi người dùng submit từ form 'allow_override'
    $(table).find('#allow_override').submit(function () {
        setTimeout(function () {
            window.location.reload(true);
        }, 10);
    });

    // Xử lý reload lại trang khi người dùng submit từ form 'allow_switch'
    $(table).find('#allow_switch').submit(function () {
        setTimeout(function () {
            window.location.reload(true);
        }, 10);
    });
}


// Hàm xử lý gọi đến hàm swap 2 role
function switchRole(roleid, mode) {
    var csrf_token = $("meta[name=csrf_token]").attr("content");
    var data = {
        csrf_token: csrf_token
    };
    var url = site['uri']['public'] + "/roles/manage/" + mode + "/" +roleid;
    console.log(mode);

    return $.ajax({
        type: "POST",
        url: url,
        data: data
    });
}