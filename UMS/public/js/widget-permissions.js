/**
 * Created by Minh Nguyen on 4/28/2016.
 */
$(document).ready(function() {
    // Link buttons
    bindGroupTableButtons($("body"));
});

function bindGroupTableButtons(table) {
    $(table).find('.js-permission-moveup').click(function () {
        var btn = $(this);
        var roleid = btn.data('id');
        switchRole(roleid,"moveup")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });
    
    $(table).find('.js-permission-movedown').click(function () {
        var btn = $(this);
        var roleid = btn.data('id');
        switchRole(roleid,"movedown")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });
}

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