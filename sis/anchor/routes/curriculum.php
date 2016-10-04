<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function () {

    /*
        index page
        display list of curriculum by courseid
    */
    Route::get(array('admin/curriculum', 'admin/curriculum/(:any)', 'admin/curriculum/(:any)/(:num)'), function ($courseid, $page = 1) {
        $vars['messages'] = Notify::read();
        $curriculums = Curriculum::getByCourseId($courseid, $page, Config::get('admin.posts_per_page'));
        if (count($curriculums) === 0) {
            Response::redirect('admin/curriculum/add/topic/' . $courseid);
        }
        $vars['curriculums'] = $curriculums;

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

        $user = Auth::user();

        // set remoteid = null
        $input['remoteid'] = null;

        $course = Course::create($input);

        $ucourse = array(
            'userid' => $user->id,
            'courseid' => $course->id,
            'remoterole' => null
        );

        $usercourse = UserCourse::create($ucourse);

        Notify::success(__('courses.created'));

        return Response::redirect('admin/curriculum/add/topic/' . $course->id);
    });

    /*
      Add new curriculum
     */
    Route::get('admin/curriculum/add/topic/(:any)', function ($courseid) {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        $course = Course::getById($courseid);

        if (!$course) {
            Notify::warning(__('courses.notfound'));
            return Response::redirect('admin/courses');
        }

        $vars['courseid'] = $course->id;

        $dates = array();

        $days = ceil(abs(strtotime($course->enddate) - strtotime($course->startdate)) / 86400);
        $curdate = $course->startdate;
        for ($i = 1; $i <= $days; $i++) {
            if ($i == 1) { // start date
                $dates[$i] = Curriculum::GetDayOfWeek($course->startdate) . ' ' . date('d-m-Y', strtotime($course->startdate));
            } else {
                $curdate = date('Y-m-d', strtotime('+1 day', strtotime($curdate)));
                $dates[$i] = Curriculum::GetDayOfWeek($curdate) . ' ' . date('d-m-Y', strtotime($curdate));
            }
        }
        $dates[($days + 1)] = Curriculum::GetDayOfWeek($course->enddate) . ' ' . date('d-m-Y', strtotime($course->enddate));
        $vars['dates'] = $dates;

        $teachers = User::dropdown();
        $teachers = array_merge(array(0 => '--- Chọn giảng viên ---'), $teachers);

        $vars['teachers'] = $teachers;

        // extended fields
        $vars['fields'] = Extend::fields('curriculum');

        return View::create('curriculum/addtopic', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/curriculum/add/topic/(:any)', function ($courseid) {

        $course = Course::getById($courseid);

        if (!$course) {
            Notify::warning(__('courses.notfound'));
            return Response::redirect('admin/courses');
        }

        $dates = array();

        $days = ceil(abs(strtotime($course->enddate) - strtotime($course->startdate)) / 86400);
        $curdate = $course->startdate;
        for ($i = 1; $i <= $days; $i++) {
            if ($i == 1) { // start date
                $dates[$i] = $course->startdate;
            } else {
                $curdate = date('Y-m-d', strtotime('+1 day', strtotime($curdate)));
                $dates[$i] = $curdate;
            }
        }
        $dates[($days + 1)] = $course->enddate;

        $arr = array();
        for ($j = 1; $j <= $days + 1; $j++) {
            array_push($arr, "content_topic_" . $j);
            array_push($arr, "topic_" . $j);
            array_push($arr, "teacher_" . $j);
        }


        $input = Input::get($arr);

        $validator = new Validator($input);
        $count = 1;
        foreach ($input as $key => $value) {
            if (isset($input['content_topic_' . $count]) && ($key === 'content_topic_' . $count && $value === '')) {
                    $validator->check('topic_' . $count)
                        ->is_max(1, __('curriculum.topicname_missing'));

                    $validator->check('teacher_' . $count)
                        ->is_boolean(__('curriculum.teacher_missing'));
                $count++;
            }

        }

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/curriculum/add/topic/' . $course->id);
        }

        $user = Auth::user();

        $icount = 1;
        foreach ($input as $key => $val) {
            // choose multi topic
            if (strpos($key, 'content_topic_') !== FALSE && strlen($val) !== 0) {
                $topics = json_decode($val);
                foreach ($topics as $topic) {
                    $arr = array();
                    $arr['course'] = $course->id;
                    $arr['time'] = $dates[$icount];
                    if ($topic->timetopic !== '') {
                        $arr['topic'] = parse('<strong>' . $topic->timetopic . '</strong> ' . $topic->name);
                    } else {
                        $arr['topic'] = $topic->name;
                    }
                    $arr['lecturer'] = $topic->teacherid;
                    $arr['userid'] = $user->id;
                    $arr['timecreated'] = time();
                    $arr['timemodified'] = time();
                    $arr['usermodified'] = $user->id;
                    $arr['note'] = $topic->note;

                    $curriculum = Curriculum::create($arr);
                }
            }
        }

        Notify::success(__('courses.created'));

        return Response::redirect('admin/curriculum/add/course');
    });
});
