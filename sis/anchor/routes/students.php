<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

    /*
        List students
    */
    Route::get(array('admin/students', 'admin/students/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['students'] = Student::paginate($page, Config::get('admin.posts_per_page'));
        $vars['tab'] = 'sys';

        return View::create('students/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
       List courses
   */
    Route::get(array('admin/students/(:num)/courses', 'admin/students/(:num)/courses/(:num)'), function ($stuid, $page = 1) {

        $vars['messages'] = Notify::read();
        $vars['courses'] = Course::paginate($page, Config::get('admin.posts_per_page'), $stuid);
        $vars['tab'] = 'sys';

        return View::create('students/courses', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
        Edit student
    */
    Route::get('admin/students/info/(:num)', function($id) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['student'] = Student::find($id);
        $vars['studentschool'] = Student::getSchoolByStudent($id);
        list($vars['studentcourselearning'], $vars['thisstudent']) = Student::getCoursesLearningByStudent($id);
        $vars['studentcoursesuccessed'] = Student::getCoursesSuccessedByStudent($id);
        list($vars['studenttopicsuccessed'], $vars['counttopicsuccessed']) = Student::getTopicSuccessedByStudent($id);
        list($vars['studenttopiclearning'], $vars['counttopiclearning']) = Student::getTopicLearningByStudent($id);

        // extended fields
        $vars['fields'] = Extend::fields('student', $id);
        $vars['tab'] = 'sys';

        return View::create('students/info', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /*
        Search student
    */
    Route::get(array('admin/students/search', 'admin/students/search/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        //$input = Input::get(array('text-search'));
        //$key = $input['text-search'];
        $key = $_GET['text-search'];

        $whatSearch = '?text-search=' . $key;
        //Session::put($whatSearch, $whatSearch);
        $perpage = Config::get('admin.posts_per_page');
        list($total, $pages) = Student::search($key, $page, $perpage);

        $url = Uri::to('admin/students/search');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url, $whatSearch);

        $vars['students'] = $pagination;
        $vars['tab'] = 'sys';

        return View::create('students/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });


    Route::post('admin/students/info/(:num)', function($id) {
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

            return Response::redirect('admin/students/info/' . $id);
        }

//        if($password_reset) {
//            $input['password'] = Hash::make($input['password']);
//        }

        Student::update($id, $input);

        Extend::process('student', $id);

        Notify::success(__('students.updated'));

        return Response::redirect('admin/students/info/' . $id);
    });

    /*
        Add student
    */
    Route::get('admin/students/add', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        // extended fields
        $vars['fields'] = Extend::fields('student');
        $vars['tab'] = 'sys';

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
            ->is_max(4, __('students.studentname_missing', 2));

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

            return Response::redirect('admin/students/info/' . $id);
        }

        Student::where('id', '=', $id)->delete();
        StudentCourse::where('studentid', '=', $id)->delete();

        //Query::table(Base::table('student_meta'))->where('student', '=', $id)->delete();

        Notify::success(__('students.deleted'));

        return Response::redirect('admin/students');
    });

});
