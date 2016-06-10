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
            setInterval(function () {
                $(loading).hide();
            }, 3000);
            // remove now content
            element.html();
            // add new content
            element.html(cnt);
        }

        if (sections) {
            $.each(sections, function (index, element) {
                $(sections[index]).on('click', function () {
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
                $(labels[index]).on('click', function () {
                    content.hide();
                    $(loading).show();
                    var description = $(this).attr('data-description');
                    changeContent(content, description);
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