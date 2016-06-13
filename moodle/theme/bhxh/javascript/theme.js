$(function () {

    $("table").wrap(function () {
        var ctab_obj = $(this);
        if (ctab_obj.parent('div').hasClass('no-overflow')) {

        } else {
            return "<div class='no-overflow'></div>";
        }

    });
});

function arrayToUrlParmas(params) {
    var out = [];
    for (var key in params) {
        out.push(key + '=' + encodeURIComponent(params[key]));
    }
    return out.join('&');
}

function getHTMLContentForm(formid) {
    var module = {};

    module['url'] = $('#' + formid).attr('action');
    module['method'] = $('#' + formid).attr('method');
    module['params'] = $('#' + formid).serialize();

    return getHTMLContent(module, false);
}

function getHTMLContentJson(module) {
    var moduleObj = jQuery.parseJSON(module);
    return getHTMLContent(moduleObj, true);
}

function getHTMLContent(module, encode) {
    var url = module.url + '?';
    url += (encode)?arrayToUrlParmas(module.params):module.params;
    var target = $('#module-content');
    var loading = $('#loading');

    $.ajax({
        url: url,
        method: module.method,
        data: module.params,
        beforeSend: function () {
            target.hide();
            loading.show();
            target.empty();
        },
        success: function (data) {
            target.html(data);
            loading.hide();
            target.show();
        },
        error: function (err) {
            target.html(err);
        }
    });

    return false;
}

function formEventHandler(e) {
    e.preventDefault();
    var el = $(this);
    var formParent = $(el.closest('form'));
    console.log(formParent);
    var formId = formParent.attr('id') || null;
    getHTMLContentForm(formId);
}

function linkClickEventHandler(e) {
    e.preventDefault();
    var el = $(this);
    var module = el.attr('data-module') || '';
    getHTMLContentJson(module);
}

function loadRemoteContent() {
    var courseRemote = $('.get-remote-content');
    if (courseRemote && courseRemote.length > 0) {
        courseRemote.each(function (index, item) {
            var course = $(item);
            course.bind('click', linkClickEventHandler);
        });
    }
    $('#module-content').on('click', '.remote-link-action', linkClickEventHandler);
    $('#module-content').on('click submit', '.remote-form-action', formEventHandler);
}

(function ($) {
    var datacontent = $('#module-content');
    $(document).ready(loadRemoteContent);
}).call(this, jQuery);
