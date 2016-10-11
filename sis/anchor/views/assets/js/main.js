(function ($) {
    var menu_course = ['course', 'curriculum', 'grade'],
        menu_post = ['post', 'cateogory'],
        menu_user = ['user', 'student', 'instructor', 'school'],
        menu_room = ['room', ];

    $(document).ready(function () {
        $("ul.mnu-lst a").click(function(event) {
            //alert($(event.target).attr('data-parent'));
        });
    });
})(jQuery);