<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

    /*
        List schools
    */
    Route::get(array('admin/schools', 'admin/schools/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['schools'] = School::paginate($page, Config::get('admin.posts_per_page'));

        return View::create('schools/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
        Edit school
    */
    Route::get('admin/schools/edit/(:num)', function($id) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['school'] = School::find($id);

        // extended fields
        $vars['fields'] = Extend::fields('school', $id);

//        $vars['statuses'] = array(
//            'inactive' => __('global.inactive'),
//            'active' => __('global.active')
//        );
//
//        $vars['roles'] = array(
//            'administrator' => __('global.administrator'),
//            'editor' => __('global.editor'),
//            'user' => __('global.user')
//        );

        return View::create('schools/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/schools/edit/(:num)', function($id) {
        $input = Input::get(array('id', 'name'));
        //$password_reset = false;

        // A little higher to avoid messing with the password
        foreach($input as $key => &$value) {
            $value = eq($value);
        }

//        if($password = Input::get('password')) {
//            $input['password'] = $password;
//            $password_reset = true;
//        }

        $validator = new Validator($input);

        $validator->add('safe', function($str) use($id) {
            return ($str != 'inactive' and Auth::user()->id == $id);
        });

        $validator->check('name')
            ->is_max(2, __('schools.username_missing', 2));

//        $validator->check('email')
//            ->is_email(__('students.email_missing'));

//        if($password_reset) {
//            $validator->check('password')
//                ->is_max(6, __('users.password_too_short', 6));
//        }

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/schools/edit/' . $id);
        }

//        if($password_reset) {
//            $input['password'] = Hash::make($input['password']);
//        }

        School::update($id, $input);

        Extend::process('school', $id);

        Notify::success(__('schools.updated'));

        return Response::redirect('admin/schools/edit/' . $id);
    });

    /*
        Add school
    */
    Route::get('admin/schools/add', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        // extended fields
        $vars['fields'] = Extend::fields('school');

//        $vars['statuses'] = array(
//            'inactive' => __('global.inactive'),
//            'active' => __('global.active')
//        );
//
//        $vars['roles'] = array(
//            'administrator' => __('global.administrator'),
//            'editor' => __('global.editor'),
//            'user' => __('global.user')
//        );

        return View::create('schools/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/schools/add', function() {
        $input = Input::get(array('id', 'name'));

        foreach($input as $key => &$value) {
            //if($key === 'password') continue; // Can't avoid, so skip.
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('name')
            ->is_max(3, __('schools.username_missing', 2));

//        $validator->check('email')
//            ->is_email(__('students.email_missing'));

//        $validator->check('password')
//            ->is_max(6, __('users.password_too_short', 6));

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/schools/add');
        }

        //$input['password'] = Hash::make($input['password']);

        $school = School::create($input);

        Extend::process('school', $school->id);

        Notify::success(__('schools.created'));

        return Response::redirect('admin/schools');
    });

    /*
        Delete school
    */
    Route::get('admin/schools/delete/(:num)', function($id) {
        $self = Auth::user();

        if($self->id == $id) {
            Notify::error(__('schools.delete_error'));

            return Response::redirect('admin/schools/edit/' . $id);
        }

        School::where('id', '=', $id)->delete();

        Query::table(Base::table('school_meta'))->where('school', '=', $id)->delete();

        Notify::success(__('schools.deleted'));

        return Response::redirect('admin/schools');
    });

});
