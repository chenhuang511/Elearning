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
		Add new course
	*/
    Route::get('admin/curriculum/add/course', function () {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        // extended fields
        $vars['fields'] = Extend::fields('courses');

        return View::create('curriculum/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/curriculum/add/course', function () {
        $input = Input::get(array('fullname', 'shortname', 'startdate', 'enddate', 'summary'));

        // an array of items that we shouldn't encode - they're no XSS threat
        $dont_encode = array('summary', 'css', 'js');

        foreach ($input as $key => &$value) {
            if (in_array($key, $dont_encode)) continue;
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('fullname')
            ->is_max(1, __('courses.fullname_missing'));

        $validator->check('shortname')
            ->is_max(1, __('courses.shortname_missing'));

        $validator->check('startdate')
            ->is_max(1, __('courses.startdate_missing'));

        $validator->check('enddate')
            ->is_max(1, __('courses.enddate_missing'));

        $validator->check('summary')
            ->is_max(1, __('courses.summary_missing'));

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/curriculum/add/course');
        }
        Session::put('errors', '');


        echo 'vao day';
        die();

        $user = Auth::user();

        if (!empty($input['startdate'])) {
            $input['startdate'] = strtotime($input['startdate']);
        }

        if (!empty($input['enddate'])) {
            $input['enddate'] = strtotime($input['enddate']);
        }

        $input['remoteid'] = null;

        var_dump($input);
        die();

        $course = Course::create($input);


        Notify::success(__('courses.created'));
        Session::put('errors', null);

        if (Input::get('autosave') === 'true') return Response::redirect('admin/curriculum/add/topic' . $course->id);
        else return Response::redirect('admin/posts');
    });
});
