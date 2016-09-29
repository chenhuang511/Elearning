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

        $vars['schoolstudent'] = Student::where('schoolid', '=', $id)->get();

        //echo '<pre>';
        //var_dump($vars['student-school']);die;

        // extended fields
        $vars['fields'] = Extend::fields('school', $id);

        return View::create('schools/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
        Search school
    */
    Route::get('admin/schools/search', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        //$key = Input::get(array('text-search'));
        $key = $_GET['text-search'];

        $vars['school'] = School::where('name', 'LIKE', '%' . $key . '%')->get();

        //var_dump($vars['school']);die;

        return View::create('schools/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });


    Route::post('admin/schools/edit/(:num)', function($id) {
        $input = Input::get(array('name'));

        // A little higher to avoid messing with the password
        foreach($input as $key => &$value) {
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->add('safe', function($str) use($id) {
            return ($str != 'inactive' and Auth::user()->id == $id);
        });

        $validator->check('name')
            ->is_max(2, __('schools.schoolname_missing', 2));

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/schools/edit/' . $id);
        }


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

        return View::create('schools/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/schools/add', function() {
        $input = Input::get(array('name'));

        foreach($input as $key => &$value) {
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('name')
            ->is_max(3, __('schools.username_missing', 2));

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/schools/add');
        }

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
        StudentSchool::where('schoolid', '=', $id)->delete();

        //Query::table(Base::table('school_meta'))->where('school', '=', $id)->delete();

        Notify::success(__('schools.deleted'));

        return Response::redirect('admin/schools');
    });

});
