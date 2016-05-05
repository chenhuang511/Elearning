$(document).ready(function() {
    // Link buttons
    bindGroupTableButtons($("body"));

});
// Hàm xử lý để bao lâu thì gửi request lên 
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

function bindGroupTableButtons(table) {
    // Xử lý khi người dùng bấm vào nút delete cohort
    $(table).find('.js-cohort-delete').click(function() {
        var btn = $(this);
        var cohort_id = btn.data('id');
        var name = btn.data('name');
        deleteCohortDialog('dialog-cohort-delete', cohort_id, name);
    });
    
    // Xử lý khi người dùng ấn vào nút show cohort
    $(table).find('.js-cohort-enable').click(function () {
        var btn = $(this);
        var cohort_id = btn.data('id');
        updateCohortEnabledStatus(cohort_id, "1")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });
    
    // Xử lý khi người dùng ấn vào nút hide cohort
    $(table).find('.js-cohort-disable').click(function () {
        var btn = $(this);
        var cohort_id = btn.data('id');
        updateCohortEnabledStatus(cohort_id, "0")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });
    
    // Xử lý trong form tìm kiếm của người dùng hiện đang trong nhóm 
    $(table).find('#removeselect_searchtext').keyup(function () {
        var btn = $('#btnreset');
        var form_search = $(this);
        var form = $(table).find('#removeselect');
        var search = form_search.val();
        var cohort_id = form_search.data('id');
        
        // Kiểm tra xem trong form có chữ hay không
        if(!search)
            btn.prop('disabled',true);
        else
            btn.prop('disabled',false);
        btn.click(function () {
            form_search.val('');
            search = '';
            btn.prop('disabled',true);
            searchUsers("remove",search,form,cohort_id);
        });
        searchUsers("remove",search,form,cohort_id);
    });
    
    // Xử lý trong form tìm kiếm của người dùng tiềm năng 
    $(table).find('#addselect_searchtext').keyup(function () {
        var btn = $('#btnreset1');
        var form_search = $(this);
        var form = $(table).find('#addselect');
        var search = form_search.val();
        var cohort_id = form_search.data('id');

        // Kiểm tra xem trong form có chữ hay không
        if(!search)
            btn.prop('disabled',true);
        else
            btn.prop('disabled',false);
        btn.click(function () {
            form_search.val('');
            search = '';
            btn.prop('disabled',true);
            searchUsers("add",search,form,cohort_id);
        });
        searchUsers("add",search,form,cohort_id);
    });
}

// Hàm xử lý tìm kiếm người dùng
function searchUsers(mod,search,form,cohort_id){
    var csrf_token = $("meta[name=csrf_token]").attr("content");
    var data = {
        mod: mod,
        search: search,
        csrf_token: csrf_token
    };
    // Hiển thị loading...
    var url = site['uri']['public'] + "/form/search/c/" + cohort_id;
    var loading_text = "<optgroup label=''></optgroup>";
    
    // Thực hiện gửi sau 1 khoảng thời gian ms
    delay(function () {
        $.ajax({
            type: "GET",
            url: url,
            data: data,
            beforeSend: function () {
                form.prop("disabled", true);
                form.html(loading_text);
                form.addClass('loading-img');
            },
            success: function (data) {
                form.removeClass('loading-img');
                form.prop("disabled",false);
                form.html(data);
            }
        });
    }, 500);
}

// Hàm xử lý việc update ẩn hiện cho nhóm
function updateCohortEnabledStatus(cohort_id, visible) {
    visible = typeof visible !== 'undefined' ? visible : 1;
    var csrf_token = $("meta[name=csrf_token]").attr("content");
    var data = {
        visible: visible,
        csrf_token: csrf_token
    };

    var url = site['uri']['public'] + "/forms/cohorts/c/" + cohort_id;

    return $.ajax({
        type: "POST",
        url: url,
        data: data
    });
}

// Hiển thị thông báo xóa nhóm và xóa nhóm khỏi CSDL
function deleteCohortDialog(box_id, cohort_id, name){
    // Delete any existing instance of the form with the same name
    if($('#' + box_id).length ) {
        $('#' + box_id).remove();
    }

    var url = site['uri']['public'] + "/forms/confirm";

    var data = {
        box_id: box_id,
        box_title: "Delete cohort",
        confirm_message: "Are you sure you want to delete the cohort " + '"' + name + '"' + "?",
        confirm_button: "Yes, delete cohort"
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
                var url = site['uri']['public'] + "/forms/cohorts/c/" + cohort_id + "/delete";

                csrf_token = $("meta[name=csrf_token]").attr("content");
                var data = {
                    cohort_id: cohort_id,
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