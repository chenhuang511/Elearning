<?php

Route::collection(array('before' => 'auth,csrf'), function() {

    Route::get(array('admin/courses', 'admin/courses/(:num)'), function($page = 1) {
        $perpage = Config::get('admin.posts_per_page');
        $total = Page::where(Base::table('pages.parent'), '=', '0')->count();
        $pages = Page::sort('title')->where(Base::table('pages.parent'), '=', '0')->take($perpage)->skip(($page - 1) * $perpage)->get();
        $url = Uri::to('admin/pages');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url);

        $vars['messages'] = Notify::read();
        $vars['pages'] = $pagination;
        $vars['status'] = 'all';
        //need process here
        return View::create('grades/courses', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

});