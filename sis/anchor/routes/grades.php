<?php
Route::collection(array('before' => 'auth,csrf'), function() {

    Route::get(array('admin/grade', 'admin/grade/(:num)'), function($page = 1) {

        // get public listings
        $userid = Auth::get_userid();
        list($total, $pages) = Course::getCoursesBy($userid, $page, $perpage = Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/grade');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url);

        $vars['messages'] = Notify::read();
        $vars['pages'] = $pagination;
        //need process here
        return View::create('course/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get(array('admin/grade/course/(:num)', 'admin/grade/course/(:num)/(:num)'), function($courseid, $page = 1) {

        // get public listings
        list($total, $pages) = Course::get_grade_by_course($courseid, $page, $perpage = Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/grade/course/' . $courseid);

        $pagination = new Paginator($pages, $total, $page, $perpage, $url);

        $vars['messages'] = Notify::read();
        $vars['pages'] = $pagination;
        $vars['courseid'] = $courseid;
        //need process here
        return View::create('grades/grades', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });
});