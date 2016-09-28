<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function () {

    /*
        index page
        display list of curriculum by courseid
    */
    Route::get(array('admin/curriculum', 'admin/curriculum/(:any)/(:num)'), function ($courseid, $page = 1) {
        $vars['messages'] = Notify::read();
        $vars['curriculums'] = Curriculumn::getByCourseId($courseid, $page, Config::get('admin.posts_per_page'));

        return View::create('users/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });
});
