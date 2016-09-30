<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

    /*
        List students
    */
    Route::get(array('admin/students', 'admin/students/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['students'] = Student::paginate($page, Config::get('admin.posts_per_page'));

        return View::create('students/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
        Edit student
    */
    Route::get('admin/students/edit/(:num)', function($id) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['student'] = Student::find($id);
        $vars['studentschool'] = Student::getSchoolByStudent($id);
        $vars['studentcourse'] = Student::getCoursesByStudent($id);

        // extended fields
        $vars['fields'] = Extend::fields('student', $id);

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

        return View::create('students/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
        Search student
    */

    Route::get('admin/students/search', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        //$key = Input::get(array('text-search'));
        $key = $_GET['text-search'];

        $vars['student'] = Student::where('fullname', 'LIKE', '%' . $key . '%')->get();

        return View::create('students/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });


    Route::post('admin/students/edit/(:num)', function($id) {
        $input = Input::get(array('fullname', 'email'));
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

        $validator->check('fullname')
            ->is_max(2, __('students.studentname_missing', 2));

        $validator->check('email')
            ->is_email(__('students.email_missing'));

//        if($password_reset) {
//            $validator->check('password')
//                ->is_max(6, __('users.password_too_short', 6));
//        }

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/students/edit/' . $id);
        }

//        if($password_reset) {
//            $input['password'] = Hash::make($input['password']);
//        }

        Student::update($id, $input);

        Extend::process('student', $id);

        Notify::success(__('students.updated'));

        return Response::redirect('admin/students/edit/' . $id);
    });

    /*
        Add student
    */
    Route::get('admin/students/add', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        // extended fields
        $vars['fields'] = Extend::fields('student');

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

        return View::create('students/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/students/add', function() {
        $input = Input::get(array('fullname', 'email'));

        foreach($input as $key => &$value) {
            //if($key === 'password') continue; // Can't avoid, so skip.
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('fullname')
            ->is_max(3, __('students.username_missing', 2));

        $validator->check('email')
            ->is_email(__('students.email_missing'));

//        $validator->check('password')
//            ->is_max(6, __('users.password_too_short', 6));

        if($errors = $validator->errors()) {
            Input::flash();

            Notify::error($errors);

            return Response::redirect('admin/students/add');
        }

        //$input['password'] = Hash::make($input['password']);

        $student = Student::create($input);

        Extend::process('student', $student->id);

        Notify::success(__('students.created'));

        return Response::redirect('admin/students');
    });

    /*
        Delete student
    */
    Route::get('admin/students/delete/(:num)', function($id) {
        $self = Auth::user();

        if($self->id == $id) {
            Notify::error(__('students.delete_error'));

            return Response::redirect('admin/students/edit/' . $id);
        }

        Student::where('id', '=', $id)->delete();
        StudentCourse::where('studentid', '=', $id)->delete();
        //StudentSchool::where('userid', '=', $id)->delete();

        //Query::table(Base::table('student_meta'))->where('student', '=', $id)->delete();

        Notify::success(__('students.deleted'));

        return Response::redirect('admin/students');
    });

});
