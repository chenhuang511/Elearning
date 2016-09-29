<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function () {

    /*
        index page
        display list of curriculum by courseid
    */
    Route::get(array('admin/curriculum', 'admin/curriculum/(:any)', 'admin/curriculum/(:any)/(:num)'), function ($courseid, $page = 1) {
        $vars['messages'] = Notify::read();
        $vars['curriculums'] = Curriculum::getByCourseId($courseid, $page, Config::get('admin.posts_per_page'));

        return View::create('curriculum/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
		Add new curriculum
	*/
    Route::get('admin/curriculum/add', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        // extended fields
        $vars['fields'] = Extend::fields('courses');
        $vars['fields'] = Extend::fields('curriculum');

        $vars['statuses'] = array(
            'published' => __('global.published'),
            'draft' => __('global.draft'),
            'archived' => __('global.archived')
        );

        return View::create('curriculum/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer')
            ->partial('editor', 'partials/editor');
    });
});
