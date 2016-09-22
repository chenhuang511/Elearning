<?php
    Route::get('admin/advance', array('before' => 'auth', 'main' => function($page = 1) {
        $vars['advance'] = Advance::read();
        $vars['token'] = Csrf::token();

        $vars['status'] = array(
            'published' => __('advance.published'),
            'draft' => __('advance.draft'),
        );

        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    }));

    Route::get('admin/advance/add', array('before' => 'auth', 'main' => function($page = 1) {
        $vars['staff'] = Staff::read();
        $vars['token'] = Csrf::token();

        return View::create('advance/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    }));


