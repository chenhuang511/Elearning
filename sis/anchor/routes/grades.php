<?php
Route::collection(array('before' => 'auth,csrf'), function() {

    Route::get(array('admin/grade', 'admin/courses/grade/(:num)'), function($page = 1) {

        // get public listings
        $userid = Auth::get_userid();
        list($total, $pages) = Course::get_courses_by_user($userid, $page, $perpage = Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/courses');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url);

        $vars['messages'] = Notify::read();
        $vars['pages'] = $pagination;
        //need process here
        return View::create('grades/courses', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get(array('admin/grade/info/(:num)'), function($id = 1) {

        // get public listings
        $pages = Course::where('id', '=', $id)->get()[0];

        $url = Uri::to('admin/courses');
        var_dump($pages);
        $vars['messages'] = Notify::read();
        $vars['pages'] = $pages;
        //need process here
        return View::create('grades/courseinfo', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get(array('admin/grade/grades/(:num)', 'admin/courses/grades/(:num)/(:num)'), function($courseid, $page = 1) {

        // get public listings
        list($total, $pages) = Course::get_grade_by_course($courseid, $page, $perpage = Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/courses/grades/' . $courseid);

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