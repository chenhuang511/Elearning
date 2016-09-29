<?php
Route::collection(array('before' => 'auth,csrf'), function () {

    Route::get(array('admin/courses', 'admin/courses/(:any)/(:num)'), function ($tab = '', $page = 1) {

        // get public listings
        $userid = Auth::get_userid();
        list($total, $pages) = Course::getCoursesBy($tab === 'grade' ? $userid : null, $page, $perpage = Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/courses');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url);

        $vars['messages'] = Notify::read();
        $vars['pages'] = $pagination;
        //need process here
        return View::create('course/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });
});