<div id="module-content">
</div>

<script>
    (function ($) {
        var summary = $('#hidden-summary'),
            content = $('#module-content'),
            summaryLink = $('#course-summary');

        var sections = $('a[id^="csec-"]');

        var changeContent = function (element, cnt) {
            // remove now content
            element.html();
            // add new content
            element.html(cnt);
        }

        $.each(sections, function (index, element) {
            $(sections[index]).on('click', function () {
                var sectionSummary = $(this).attr('data-summary');
                changeContent(content, sectionSummary);
            });
        });

        content.html(summary.val());

        summaryLink.on('click', function () {
            changeContent(content, summary.val());
        });

    })(jQuery)
</script>