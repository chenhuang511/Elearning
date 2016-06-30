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

function getHTMLContentForm(form, addSubmitField) {
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

    if (addSubmitField) {
        var submits = form.find('[type=submit]');
        var s = '';
        submits.each(function (index, item) {
            var input = $(item);
            var name = input.attr('name');
            var value = input.attr('value');
            if (name) {
                s += '&' + name + '=' + value;
            }
        });
        module['params'] += s;
    }

    return getHTMLContent(module, false);
}

function getHTMLContentJson(module) {
    var moduleObj = jQuery.parseJSON(module);
    return getHTMLContent(moduleObj, true);
}

function getHTMLContent(module, encode) {
    var getLink = (arguments.length === 3) ? arguments[2] : true;
    var url = module.url;
    if (getLink) {
       url = module.url + '?';
    }
    url += (encode)?arrayToUrlParmas(module.params):module.params;
    var target = $('#module-content');
    var loading = $('#loading');
    if (window.sectionTimeout) {
        clearTimeout(window.sectionTimeout);
    }
    $.ajax({
        url: url,
        method: module.method,
        data: module.params,
        beforeSend: function () {
            target.hide();
            loading.show();
            target.empty();
        },
        error: function (err) {
            loading.hide();
            target.html(err);
        }
    }).done(function (data) {
        target.html(data).promise().done(function () {
            loading.hide();
            target.show();
        });
    });

    return false;
}

function hubFormEventHandler(e) {
    e.preventDefault();
    var el = $(this);
    var formParent = $(el.closest('form'));
    if (formParent) {
        getHTMLContentForm(formParent, false);
    }
}

function formEventHandler(e) {
    e.preventDefault();
    var el = $(this);
    var formParent = $(el.closest('form'));
    if (formParent) {
        getHTMLContentForm(formParent, true);
    }
}

function linkClickEventHandler(e) {
    e.preventDefault();
    var el = $(this);
    var module = el.attr('data-module') || '';
    getHTMLContentJson(module);
}

function linkDataEventHandler(e) {
    e.preventDefault();
    var link = $(this);
    var module = {
        url: link.attr('href'),
        method: 'get',
        params: []
    };
    getHTMLContent(module, true, false);
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
    ];
    var submitDelegate = [
        '.remote-form-action',
        '.remote-submit-btn',
        '.mod_quiz-next-nav',
        '#id_submit',
        '#id_submitbutton'
    ];
    var hubSubmitDelegate = [
        '.hub-submit-btn'
    ];

    var hrefDelegate = [
        'a[href*="remote"]'
    ];

    var inputChange = [
        '#id_perpage',
        '#id_filter',
        '#id_markerfilter',
        '#id_workflowfilter',
        '#id_quickgrading',
        '#id_showonlyactiveenrol'
    ];

    datacontent.on('click', linkDelegate.join(','), linkClickEventHandler);
    datacontent.on('click submit', submitDelegate.join(','), formEventHandler);
    datacontent.on('click submit', hubSubmitDelegate.join(','), hubFormEventHandler);
    datacontent.on('click', hrefDelegate.join(','), linkDataEventHandler);
    datacontent.on('change', inputChange.join(','), formEventHandler);
}

(function ($) {
    $(document).ready(loadRemoteContent);
}).call(this, jQuery);
