<?php
    Route::get(array('admin/advance','admin/advance/(:num)'), array('before' => 'auth', 'main' => function($page = 1) {
        $total =  count(Staff::_read());
        $vars['token'] = Csrf::token();
        $url = Uri::to('admin/advance');
        $pagination = new Paginator(Staff::page_read($page,5), $total, $page, 5, $url);
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

        $advance = Advance::create($input);
        $validator->check('money')->is_int( __('advance.money_not_int'));
        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/advance/add');
        }
        Extend::process('advance', $advance->id);

        Notify::success(__('advance.created'));

        if(Input::get('autosave') === 'true') return Response::redirect('admin/advance/edit/' . $page->id);
        else return Response::redirect('admin/advance');

    });

    Route::get(array('admin/advance/status/(:any)','admin/advance/status/(:any)/(:num)'), array('before' => 'auth', 'main' => function($status, $page = 1) {
        $total =  count(Staff::page_read_status($status));
        $vars['ds'] =  Staff::read_status(1,$page  ,$status);
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



