<div id="module-content">
</div>

<script>
    (function($){
        var summary = $('#hidden-summary'),
            content = $('#module-content'),
            summaryLink = $('#course-summary');

        content.html(summary.val());

        summaryLink.on('click', function(){
            content.html();
            content.html(summary.val());
        });

    })(jQuery)
</script>