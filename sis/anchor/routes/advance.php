<?php
Route::collection(array('before' => 'auth,csrf,install_exists'), function() {
    Route::get(array('admin/advance','admin/advance/(:num)'),function($page = 1) {

        $vars['page'] = $page;
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $list =   Advance::get_list(4,$page);
        $url = Uri::to('admin/advance');
        $pagination = new Paginator($list[1], $list[0], $page, 4, $url);
        $vars['status'] = array(
            'published' => __('advance.published'),
            'draft' => __('advance.draft'),
            'rebuff' => __('advance.rebuff')
        );
        $vars['advance'] =  $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get('admin/advance/add',function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['course'] = Course::get_list_shortname_courses();
        $vars['user'] = User::get_list_author('user') ;
        $vars['courses'] =  Course::read();
        return View::create('advance/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/advance/add', function() {
        $input = Input::get(array('applicant_id', 'money', 'reason','course_id'));
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

    Route::get(array('admin/advance/status/(:any)','admin/advance/status/(:any)/(:num)'), function($status, $page = 1) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $list =   Advance::get_list_by_status($status,4,$page);
        $url = Uri::to('admin/advance/status/'.$status );
        $pagination = new Paginator($list[1], $list[0], $page, 4, $url);
        $vars['status'] = array(
            'published' => __('advance.published'),
            'draft' => __('advance.draft'),
            'rebuff' => __('advance.rebuff')
        );
        $vars['advance'] =  $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });



    Route::get('admin/advance/edit/(:num)', function($id) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['user'] = User::get_list_author();
        $vars['article'] = Advance::find($id);
        $vars['courses'] = Course::get_list_shortname_courses();
        $vars['page'] = Registry::get('posts_page');


        $vars['statuses'] = array(
            'published' => __('global.published'),
            'draft' => __('global.draft'),
            'rebuff' => __('advance.rebuff')
        );
        return View::create('advance/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/advance/edit/(:num)', function($id) {
        $input = Input::get(array('applicant_id', 'money','time', 'reason','status','course_id'));
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
        $user = Auth::user();
        if($input['status'] == 'published' || $input['status'] == 'rebuff'){
            $input['user_check_id'] =  $user->id;
        }
        Advance::update($id, $input);

        Extend::process('post', $id);

        Notify::success(__('posts.updated'));

        return Response::redirect('admin/advance/edit/' . $id);
    });

    Route::get('admin/advance/delete/(:num)', function($id) {
        Advance::find($id)->delete();

        Notify::success(__('posts.deleted'));

        return Response::redirect('admin/advance');
    });




});
