/**
 * Created by vanhaIT on 28/04/2016.
 */
$(document).ready(function() {
    // Link buttons
    bindGroupTableButtons($("body"));

});

function bindGroupTableButtons(table) {
    // Xử lý show (user.suppenden =0) người dùng
    $(table).find('.js-mdluser-enable').click(function () {
        var btn = $(this);
        var mdluser_id = btn.data('id');
        updateMdluserEnabled(mdluser_id, "0")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });

    // Xứ lý hide người dùng
    $(table).find('.js-mdluser-disable').click(function () {
        var btn = $(this);
        var mdluser_id = btn.data('id');
        updateMdluserEnabled(mdluser_id, "1")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });

    //xử lý confirm mdluser (xác nhận mdluser)
    // Gọi đến updateMdlserConfirm để lấy data, url để gửi đến MdlUserController để xử lý
    $(table).find('.js-mdluser-confirm').click(function() {
        var btn = $(this);
        var mdluser_id = btn.data('id');
        updateMdlserConfirm(mdluser_id)
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });

    // Xử lý dialog xóa người dùng. gọi xuống hàm deleteMdluserDialog bên dưới lấy data, url và gửi đến controller để xử lý
    $(table).find('.js-mdluser-delete').click(function() {
        var btn = $(this);
        var mdluser_id = btn.data('id');
        var mdluser_name = btn.data('mdluser_name');
        deleteMdluserDialog('dialog-mdluser-delete', mdluser_id, mdluser_name);
    });
}

// Hide/ Show the mdluser
function updateMdluserEnabled(mdluser_id, suspended) {
    suspended = typeof suspended !== 'undefined' ? suspended : 0;
    var csrf_token = $("meta[name=csrf_token]").attr("content");
    var data = {
        suspended: suspended,
        csrf_token: csrf_token
    };

    var url = site['uri']['public'] + "/mdlusers/u/" + mdluser_id;
    return $.ajax({
        type: "POST",
        url: url,
        data: data
    });
}

// Activate (confirm) mdluser account
function updateMdlserConfirm(mdluser_id) {
    csrf_token = $("meta[name=csrf_token]").attr("content");
    var data = {
        confirmed: "1",
        csrf_token: csrf_token
    };

    var url = site['uri']['public'] + "/mdlusers/u/" + mdluser_id;

    return $.ajax({
        type: "POST",
        url: url,
        data: data
    });
}

function deleteMdluserDialog(box_id, mdluser_id, name){
    // Delete any existing instance of the form with the same name
    if($('#' + box_id).length ) {
        $('#' + box_id).remove();
    }

    var url = site['uri']['public'] + "/forms/confirm";

    var data = {
        box_id: box_id,
        box_title: "Delete moodle user",
        confirm_message: "Are you sure you want to delete the user " + '"' + name + '"' + "?",
        confirm_button: "Yes, delete user"
    };

    // Generate the form
    $.ajax({
            type: "GET",
            url: url,
            data: data
        })
        .fail(function(result) {
            // Display errors on failure
            $('#userfrosting-alerts').flashAlerts().done(function() {
            });
        })
        .done(function(result) {
            // Append the form as a modal dialog to the body
            $( "body" ).append(result);
            $('#' + box_id).modal('show');
            $('#' + box_id + ' .js-confirm').click(function(){
                var url = site['uri']['public'] + "/mdlusers/u/" + user_id + "/delete";

                csrf_token = $("meta[name=csrf_token]").attr("content");
                var data = {
                    mdluser_id: mdluser_id,
                    csrf_token: csrf_token
                };

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data
                }).done(function(result) {
                    // Reload the page
                    window.location.reload();
                }).fail(function(jqXHR) {
                    if (site['debug'] == true) {
                        document.body.innerHTML = jqXHR.responseText;
                    } else {
                        console.log("Error (" + jqXHR.status + "): " + jqXHR.responseText );
                    }
                    $('#userfrosting-alerts').flashAlerts().done(function() {
                        // Close the dialog
                        $('#' + box_id).modal('hide');
                    });
                });
            });
        });
}



