(function ($) {
    var changeExpanded = function (node, val) {
        $(node).find('a.collapsed').attr('aria-expanded', val);
    };

    $(document).ready(function () {
        var menuSelected = $('#menuSelected').val();

        if (menuSelected == 'course') {
            $('#collapseCourse').addClass('in');
            $('#collapseCourse').attr('aria-expanded', 'true');
            changeExpanded('#headingCourse', true);
            $('#collapsePost').removeClass('in');
            changeExpanded('#headingPost', false);
            $('#collapseSystem').removeClass('in');
            changeExpanded('#headingSystem', false);
            $('#collapseRoom').removeClass('in');
            changeExpanded('#headingRoom', false);
        } else if (menuSelected == 'post') {
            $('#collapseCourse').removeClass('in');
            changeExpanded('#headingCourse', false);
            $('#collapsePost').addClass('in');
            $('#collapsePost').attr('aria-expanded', 'true');
            changeExpanded('#headingPost', true);
            $('#collapseSystem').removeClass('in');
            changeExpanded('#headingSystem', false);
            $('#collapseRoom').removeClass('in');
            changeExpanded('#headingRoom', false);
        } else if (menuSelected == 'sys') {
            $('#collapseCourse').removeClass('in');
            $('#collapsePost').removeClass('in');
            $('#collapseSystem').addClass('in');
            $('#collapseSystem').attr('aria-expanded', 'true');
            $('#collapseRoom').removeClass('in');
        } else if (menuSelected == 'room') {
            $('#collapseCourse').removeClass('in');
            $('#collapsePost').removeClass('in');
            $('#collapseSystem').removeClass('in');
            $('#collapseRoom').addClass('in');
            $('#collapseRoom').attr('aria-expanded', 'true');
        }
    });
})(jQuery);