$(function () {

    $("table").wrap(function () {
        var ctab_obj = $(this);
        if (ctab_obj.parent('div').hasClass('no-overflow')) {

        } else {
            return "<div class='no-overflow'></div>";
        }

    });
});

(function ($) {
    $(document).ready(function() {
        var courseRemote = $('.get-remote-content');
        if (courseRemote && courseRemote.length > 0) {
            courseRemote.each(function (index, item) {
                var course = $(item);
                course.bind('click', function (e) {
                    e.preventDefault();
                    var el = $(this);
                    var module = el.attr('data-module') || '';
                    var id = el.attr('data-remote-id') || 0;
                    getHTMLContent(module, id);
                })
            });
        }
        function getHTMLContent(module, id) {
            var url = '/mod/' + module + '/remote/api-view.php?id=' + id;
            var target = $('#module-content');
            switch (module) {
                default :
                    $.ajax({
                        url: url,
                        beforeSend: function () {
                            target.empty();
                        },
                        success: function (data) {
                            target.html(data);
                        },
                        error: function (err) {
                            target.html(err);
                        }
                    });
                    break;
            }
        }
    });
}).call(this, jQuery);
