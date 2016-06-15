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

function getHTMLContentForm(form) {
    if (typeof form === 'string') {
        form = $('#' + form);
    }
    if (!form) {
        return;
    }
    var module = {};

    module['url'] = form.attr('action');
    module['method'] = form.attr('method');
    module['params'] = form.serialize();

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
    if (formParent) {
        getHTMLContentForm(formParent);
    }
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
    var datacontent = $('#module-content');
    var linkDelegate = [
        '.remote-link-action',
        '.remote-lesson-button',
    ];
    var submitDelegate = [
        '.remote-form-action',
        '.remote-submit-btn',
        '.mod_quiz-next-nav',
    ];
    datacontent.on('click', linkDelegate.join(','), linkClickEventHandler);
    datacontent.on('click submit', submitDelegate.join(','), formEventHandler);
}

(function ($) {
    $(document).ready(loadRemoteContent);
}).call(this, jQuery);
