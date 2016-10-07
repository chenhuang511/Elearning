<?php
Route::collection(array('before' => 'auth,csrf,install_exists'), function () {
    Route::get(array('admin/advance/course/(:num)','admin/advance/course/(:num)/(:num)'),function($courseId=1, $page = 1) {
        $vars['page'] = $page;
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['courseId'] =  $courseId;
        $perpage = Config::get('admin.advance_per_page');
        $list =   Advance::get_list_by_courseId($courseId,$perpage ,$page);
        $url = Uri::to('admin/advance/course/'.$courseId);
        $pagination = new Paginator($list[1], $list[0], $page, $perpage, $url);

        $vars['statuses'] = array(
            array('url' => '', 'lang' => 'global.all', 'class' => 'active'),
            array('url' => '/status/published', 'lang' => 'advance.published', 'class' => 'approved'),
            array('url' => '/status/draft', 'lang' => 'advance.draft', 'class' => 'pending'),
            array('url' => '/status/rebuff', 'lang' => 'advance.rebuff', 'class' => 'spam')
        );
        $vars['advance'] =  $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get('admin/advance/course/add/(:num)',function($courseId=1) {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['course_id'] =  $courseId;
        $vars['course'] = Course::getById($courseId)->fullname;
        $user = array();
        $user = $user + array('0' => '--- Chọn người yêu cầu tạm ứng ---');
        $user = $user +   User::get_list_author(1) ;
        $vars['user']   = $user;

        $vars['courses'] =  Course::read();
        return View::create('advance/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/advance/course/add/(:num)', function($courseId) {

        $input = Input::get(array('applicant_id', 'money', 'reason',));
        $vars['course'] = Course::getById($courseId)->fullname;
        $input['time_request'] = date("Y-m-d");
        $input['course_id'] =  $courseId;

        $validator = new Validator($input);
        $validator->check('money')
            ->is_max(1, __('advance.money_null'));
        $validator->check('money')
            ->is_regex('#^[0-9]{1,15}$#',__('advance.money_not_int'));
        $validator->check('reason')
            ->is_max(1, __('advance.reason_null'));
        $validator->check('applicant_id')
            ->is_boolean(__('advance.user_missing'));


        if($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/advance/course/add/'.$courseId);
        }
        $advance = Advance::create($input);
        Extend::process('advance', $advance->id);

        Notify::success(__('advance.created'));

        return Response::redirect('admin/advance/course/'.$courseId);

    });


    Route::get(array('admin/advance/course/status/(:any)/(:num)','admin/advance/course/status/(:any)/(:num)/(:num)'), function($status='',$courseId=1,  $page = 1) {

        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['courseId'] =  $courseId;

        $perpage = Config::get('admin.advance_per_page');
        $list =   Advance::get_list_by_status($courseId,$status,$perpage,$page);
        $url = Uri::to('admin/advance/status/'.$status );
        $pagination = new Paginator($list[1], $list[0], $page, $perpage, $url);
        $vars['status'] = '/status/'.$status;
        $vars['statuses'] = array(
            array('url' => '', 'lang' => 'global.all', 'class' => ''),
            array('url' => '/status/published', 'lang' => 'advance.published', 'class' => 'approved'),
            array('url' => '/status/draft', 'lang' => 'advance.draft', 'class' => 'pending'),
            array('url' => '/status/rebuff', 'lang' => 'advance.rebuff', 'class' => 'spam')
        );

        $vars['advance'] =  $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get('admin/advance/course/edit/(:num)/(:num)', function($courseId,$id) {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $user = array();
        $user = $user + array('0' => '--- Chọn người yêu cầu tạm ứng ---');
        $user = $user +   User::get_list_author(1) ;
        $vars['user']   = $user;
        $vars['article'] = Advance::get_by_courseId($courseId,$id);
        $vars['course'] = Course::getById($courseId)->fullname;
        $vars['courses'] = Course::get_list_shortname_courses();
        $vars['courseId'] =  $courseId;


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
    Route::post('admin/advance/course/edit/(:num)/(:num)', function($courseId,$id) {
        $input = Input::get(array('applicant_id', 'money', 'reason','status'));
        $validator = new Validator($input);

        $validator->check('money')
            ->is_max(1, __('advance.money_null'));
        $validator->check('money')
            ->is_regex('#^[0-9]{1,15}$#',__('advance.money_not_int'));
        $validator->check('reason')
            ->is_max(1, __('advance.reason_null'));
        $validator->check('applicant_id')
            ->is_boolean(__('advance.user_missing'));

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/advance/course/edit/' . $courseId .'/'.$id);
        }
        $user = Auth::user();
        $status = Advance::getById($id)->status;
        if($input['status'] != $status){
            $input['user_check_id'] =  $user->id;
            $input['time_response'] =   date("Y-m-d");
        }
        Advance::update($id, $input);

        Extend::process('post', $id);

        Notify::success(__('posts.updated'));

        return Response::redirect('admin/advance/course/edit/' . $courseId .'/'.$id);
    });

    Route::get('admin/advance/course/delete/(:num)/(:num)', function ($courseId,$id) {
        Advance::find($id)->delete();

        Notify::success(__('posts.deleted'));

        return Response::redirect('admin/advance/course/'.$courseId);
    });

    Route::get(array('admin/advance/course/(:num)/search', 'admin/advance/course/(:num)/search/(:num)'), function ($courseId,$page = 1) {

        $vars['courseId'] =  $courseId;
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        $input = Input::get(array(
            'key_name',
            'key_id',
            'moneyMin',
            'moneyMax',));
        $input['key_name'] = trim($input['key_name']);
        $input['key_id'] = trim($input['key_id']);
        if ($input['moneyMin'] && $input['moneyMax'] && $input['key_name'] && $input['moneyMax'] && $input['key_id']) {
            return Response::redirect('admin/advance/course/'.$courseId);
        }
        foreach ($input as $key => &$value) {
            $value = eq($value);
        }
        $perpage = Config::get('admin.advance_per_page');
        $whatSearch = '?moneyMin=' . $input['moneyMin'] . '&moneyMax=' . $input['moneyMax'] . '&key_name=' . $input['key_name'] . '&key_id=' . $input['key_id'];

        $list = Advance::get_list_advance_by_key($courseId,$perpage, $page, $input['key_name'], $input['moneyMin'], $input['moneyMax'], $input['key_id']);
        $url = Uri::to('admin/advance/search');
        $pagination = new Paginator($list[1], $list[0], $page, $perpage, $url, $whatSearch);
        $vars['statuses'] = array(
            array('url' => '', 'lang' => 'global.all', 'class' => ''),
            array('url' => '/status/published', 'lang' => 'advance.published', 'class' => 'approved'),
            array('url' => '/status/draft', 'lang' => 'advance.draft', 'class' => 'pending'),
            array('url' => '/status/rebuff', 'lang' => 'advance.rebuff', 'class' => 'spam')
        );
        $vars['advance'] = $pagination;
        return View::create('advance/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });


});
