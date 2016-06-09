<div id="module-content">
</div>

<script>
    (function ($) {
        var summary = $('#hidden-summary'),
            content = $('#module-content'),
            summaryLink = $('#course-summary'),
            loading = $('#loading');

        $(document).ready(function(){
            loading.hide();
        });

        var sections = $('a[id^="csec-"]');
        var labels = $('a[id^="mlabel-"]');

        var changeContent = function (element, cnt) {
            setInterval(function() {
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

        if(labels) {
            $.each(labels, function (index, element){
                $(labels[index]).on('click', function() {
                    content.hide();
                    $(loading).show();
                    var description = $(this).attr('data-description');
                    changeContent(content, description);
                    content.show();
                });
            });
        }

        content.html(summary.val());

        summaryLink.on('click', function () {
            content.hide();
            $(loading).show();
            changeContent(content, summary.val());
            content.show();
        });

    })(jQuery)
</script>