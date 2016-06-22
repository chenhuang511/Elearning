<div id="module-content">
</div>

<script>
    (function ($) {
        var summary = $('#hidden-summary'),
            coursename = $('#hidden-coursename'),
            content = $('#module-content'),
            summaryLink = $('#course-summary'),
            loading = $('#loading');
        newpost = $('#newpost');

        var tabs = ['#coursewaretab', '#courseinfotab', '#forumtab', '#wikitab', '#processtab'];

        $(document).ready(function () {
            loading.hide();
        });

        $.each(tabs, function (index, element) {
            $(tabs[index]).on('click', function () {
                if (tabs[index] == '#forumtab') {
                    if (newpost.is(':hidden')) {
                        newpost.show();
                    }
                } else {
                    if (newpost.is(':visible')) {
                        newpost.hide();
                    }
                }
            });
        });

        var sections = $('a[id^="csec-"]');
        var labels = $('a[id^="mlabel-"]');


        var changeContent = function (element, cnt) {
            setTimeout(function () {
                $(loading).hide();
            }, 2000);
            // remove now content
            element.empty();
            // add new content
            element.html(cnt);
        };

        var changeIcon = function (el) {
            $.each(sections, function (index, element) {
                if (sections[index] != el) {
                    var ico = $(this).find('i');
                    if (ico.hasClass('fa-caret-down')) {
                        ico.removeClass('fa-caret-down');
                        ico.addClass('fa-caret-right');
                    }
                }
            });
        };

        if (sections) {
            $.each(sections, function (index, element) {
                $(sections[index]).on('click', function () {

                    changeIcon(sections[index]);

                    var ico = $(this).find('i');

                    if (ico.hasClass('fa-caret-right')) {
                        ico.removeClass('fa-caret-right');
                        ico.addClass('fa-caret-down');
                    } else {
                        if (ico.hasClass('fa-caret-down')) {
                            ico.removeClass('fa-caret-down');
                            ico.addClass('fa-caret-right');
                        }
                    }

                    content.hide();
                    $(loading).show();
                    var sectionSummary = $(this).attr('data-summary');
                    changeContent(content, sectionSummary);
                    content.show();
                });
            });
        }

        if (labels) {
            $.each(labels, function (index, element) {
                $(labels[index]).on('click', function (e) {
                    e.preventDefault();
                    content.hide();
//                    $(loading).show();
                    var description = $(this).attr('data-description');
//                    changeContent(content, description);
                    content.html(description);
                    content.show();
                });
            });
        }

        var htmlContent = '<h3>' + coursename.val() + '</h3><br>' + summary.val();

        content.html(htmlContent);

        summaryLink.on('click', function () {
            content.hide();
            $(loading).show();
            var htmlContent = '<h3>' + coursename.val() + '</h3><br>' + summary.val();
            changeContent(content, htmlContent);
            content.show();
        });

    })(jQuery)
</script>