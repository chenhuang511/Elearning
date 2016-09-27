<?php
    Route::get(array('admin/advance','admin/advance/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
        $total =  count(Staff::_read());
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $url = Uri::to('admin/advance');
        $pagination = new Paginator(Staff::page_read(5,$page), $total, $page, 5, $url);
        $vars['status'] = array(
            'published' => __('advance.published'),
            'draft' => __('advance.draft'),
        );
        $vars['advance'] =  $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    }));

    Route::get('admin/advance/add', array('before' => 'auth', 'main' => function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['staff'] = Staff::read();
        $vars['token'] = Csrf::token();

        return View::create('advance/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    }));

    Route::post('admin/advance/add', function() {
        $input = Input::get(array('applicant_id', 'money', 'reason'));
        $input['time'] = date("Y-m-d");

        $validator = new Validator($input);
        $validator->check('money')
            ->is_regex('#^[0-9]{1,9}$#',__('advance.money_not_int'));

        if($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/advance/add');
        }
        $advance = Advance::create($input);
        Extend::process('advance', $advance->id);

        Notify::success(__('advance.created'));

        return Response::redirect('admin/advance');

    });

    Route::get(array('admin/advance/status/(:any)','admin/advance/status/(:any)/(:num)'), array('before' => 'auth', 'main' => function($status, $page = 1) {
        $total =  count(Staff::page_read_status($status));
        $vars['ds'] =  Staff::read_status(1,$page  ,$status);
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $url = Uri::to('admin/advance/status/'.$status );
        $pagination = new Paginator(Staff::read_status(5, $page ,$status), $total, $page, 5, $url);
        $vars['status'] = array(
            'published' => __('advance.published'),
            'draft' => __('advance.draft'),
        );
        $vars['advance'] =  $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    }));



    Route::get('admin/advance/edit/(:num)', function($id) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['staff'] = Staff::read();
        $vars['article'] = Staff::find($id);
        $vars['page'] = Registry::get('posts_page');


        $vars['statuses'] = array(
            'published' => __('global.published'),
            'draft' => __('global.draft'),
        );
        return View::create('advance/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/advance/edit/(:num)', function($id) {
        $input = Input::get(array('applicant_id', 'money','time', 'reason','status'));
             var_dump($input);
        $validator = new Validator($input);


        $validator->check('money')
            ->is_regex('#^[0-9]{1,9}$#',__('advance.money_not_int'));

        $validator->check('time')
            ->is_regex('#^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$#', __('posts.time_invalid'));

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/advance/edit/' . $id);
        }


        Advance::update($id, $input);

        Extend::process('post', $id);

        Notify::success(__('posts.updated'));

        return Response::redirect('admin/advance/edit/' . $id);
    });


