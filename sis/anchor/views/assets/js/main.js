(function ($) {
    $(document).ready(function () {
        var menuSelected = $('#menuSelected').val();

        if (menuSelected == 'course') {
            $('#collapseCourse').addClass('in');
            $('#collapsePost').removeClass('in');
            $('#collapseSystem').removeClass('in');
            $('#collapseRoom').removeClass('in');
        } else if (menuSelected == 'post') {
            $('#collapseCourse').removeClass('in');
            $('#collapsePost').addClass('in');
            $('#collapseSystem').removeClass('in');
            $('#collapseRoom').removeClass('in');
        } else if (menuSelected == 'sys') {
            $('#collapseCourse').removeClass('in');
            $('#collapsePost').removeClass('in');
            $('#collapseSystem').addClass('in');
            $('#collapseRoom').removeClass('in');
        } else if (menuSelected == 'room') {
            $('#collapseCourse').removeClass('in');
            $('#collapsePost').removeClass('in');
            $('#collapseSystem').removeClass('in');
            $('#collapseRoom').addClass('in');
        }
    });
})(jQuery);