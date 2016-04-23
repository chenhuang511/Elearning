$(document).ready(function() {
    // Link buttons
    bindGroupTableButtons($("body"));

});

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

function bindGroupTableButtons(table) {
    $(table).find('.js-cohort-delete').click(function() {
        var btn = $(this);
        var cohort_id = btn.data('id');
        var name = btn.data('name');
        deleteCohortDialog('dialog-cohort-delete', cohort_id, name);
    });

    $(table).find('.js-cohort-enable').click(function () {
        var btn = $(this);
        var cohort_id = btn.data('id');
        updateCohortEnabledStatus(cohort_id, "1")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });

    $(table).find('.js-cohort-disable').click(function () {
        var btn = $(this);
        var cohort_id = btn.data('id');
        updateCohortEnabledStatus(cohort_id, "0")
            .always(function(response) {
                // Reload page after updating user details
                window.location.reload();
            });
    });

    $(table).find('#removeselect_searchtext').keyup(function () {
        var btn = $('#btnreset');
        var form_search = $(this);
        var form = $(table).find('#removeselect');
        var search = form_search.val();
        var cohort_id = form_search.data('id');

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

    $(table).find('#addselect_searchtext').keyup(function () {
        var btn = $('#btnreset1');
        var form_search = $(this);
        var form = $(table).find('#addselect');
        var search = form_search.val();
        var cohort_id = form_search.data('id');

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

function searchUsers(mod,search,form,cohort_id){
    var csrf_token = $("meta[name=csrf_token]").attr("content");
    var data = {
        mod: mod,
        search: search,
        csrf_token: csrf_token
    };
    var url = site['uri']['public'] + "/form/search/c/" + cohort_id;
    var loading_text = "<optgroup label=''></optgroup>";

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