<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function () {

    /*
        index page
        display list of curriculum by courseid
    */
    Route::get(array('admin/curriculum/(:any)', 'admin/curriculum/(:any)/(:num)'), function ($courseid, $page = 1) {
        $course = Course::getById($courseid);

        if (!$course) {
            Notify::notice(__('courses.course_notfound'));
            return Response::redirect('admin/curriculum/add/course');
        }

        if ($course->startdate === NULL && $course->enddate === NULL) {
            Notify::notice(__('courses.course_notfound'));
            return Response::redirect('admin/curriculum/update/course/' . $courseid);
        }

        $vars['messages'] = Notify::read();

        list($total, $curriculums) = Curriculum::getByCourseId($courseid, $page, $perpage = Config::get('admin.curriculum_per_page'));

        if (count($curriculums) === 0) {
            Notify::notice(__('curriculum.notopic'));
            return Response::redirect('admin/curriculum/add/topic/' . $courseid);
        }

        $url = Uri::to('admin/curriculum/' . $courseid);
        $pagination = new Paginator($curriculums, $total, $page, $perpage, $url);

        $vars['pages'] = $pagination;
        $vars['courseid'] = $courseid;
        $vars['tab'] = 'course';

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
        $vars['tab'] = 'course';

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

        //check fullname and short name exists
        $validator->add('fullname_exists', function ($str) use ($input) {
            return Course::where('fullname', '=', $input['fullname'])->count() == 0;
        });

        $validator->check('fullname')
            ->is_fullname_exists('Tên khóa học đã tồn tại');

        $validator->add('shortname_exists', function ($str) use ($input) {
            return Course::where('shortname', '=', $input['shortname'])->count() == 0;
        });

        $validator->check('shortname')
            ->is_shortname_exists('Tên gợi ý khóa học đã tồn tại');

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
        $vars['tab'] = 'course';

        $dates = array();
        $hidden_dates = array();

        $days = ceil(abs(strtotime($course->enddate) - strtotime($course->startdate)) / 86400);
        $curdate = $course->startdate;
        for ($i = 1; $i <= $days; $i++) {
            if ($i == 1) { // start date
                $dates[$i] = Curriculum::GetDayOfWeek($course->startdate) . ' ' . date('d-m-Y', strtotime($course->startdate));
                $hidden_dates[$i] = $course->startdate;
            } else {
                $curdate = date('Y-m-d', strtotime('+1 day', strtotime($curdate)));
                $dates[$i] = Curriculum::GetDayOfWeek($curdate) . ' ' . date('d-m-Y', strtotime($curdate));
                $hidden_dates[$i] = $curdate;
            }
        }
        $dates[($days + 1)] = Curriculum::GetDayOfWeek($course->enddate) . ' ' . date('d-m-Y', strtotime($course->enddate));
        $hidden_dates[($days + 1)] = $course->enddate;
        $vars['dates'] = $dates;
        $vars['hiddendates'] = $hidden_dates;

        $teachers = array();
        $teachers = $teachers + array('0' => '--- Chọn giảng viên ---');
        $teachers = $teachers + User::dropdown();

        $vars['teachers'] = $teachers;

        $vars['topictime'] = array(
            '0' => '--- Thời gian ---',
            '1' => 'Sáng',
            '2' => 'Chiều'
        );

        $rooms = array();
        $rooms = $rooms + array('0' => '--- Chọn phòng học ---');
        $rooms = $rooms + Room::dropdown();
        $vars['rooms'] = $rooms;

        // extended fields
        $vars['fields'] = Extend::fields('curriculum');
        $vars['tab'] = 'course';

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
        }


        $input = Input::get($arr);

        $user = Auth::user();

        $icount = 1;

        foreach ($input as $item) {
            if (strlen($item) !== 0) {
                $topics = json_decode($item);
                foreach ($topics as $topic) {
                    $arr = array();
                    $arr['course'] = $course->id;
                    $arr['topicday'] = $dates[$icount];
                    if ($topic->timeid != 0) {
                        $arr['topictime'] = $topic->timeid;
                    } else {
                        $arr['topictime'] = NULL;
                    }
                    $arr['topicname'] = $topic->name;
                    $arr['teacher'] = $topic->teacherid;
                    $arr['room'] = $topic->roomid;
                    $arr['userid'] = $user->id;
                    $arr['timecreated'] = time();
                    $arr['timemodified'] = NULL;
                    $arr['usermodified'] = NULL;
                    $arr['note'] = $topic->note;

                    $curriculum = Curriculum::create($arr);
                }
            }
            $icount++;
        }


        Notify::success(__('curriculum.created'));

        return Response::redirect('admin/curriculum/add/course');
    });

    /*
        Update course
     **/
    Route::get('admin/curriculum/update/course/(:any)', function ($courseid) {

        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['course'] = Course::getById($courseid);

        $startdate = strtotime($vars['course']->startdate);
        $vars['course']->startdate = date("Y-m-d", $startdate);

        $enddate =  strtotime($vars['course']->enddate);
        $vars['course']->enddate = date("Y-m-d", $enddate);
        // extended fields
        $vars['fields'] = Extend::fields('courses');
        $vars['tab'] = 'course';

        return View::create('curriculum/update', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/curriculum/update/course/(:any)', function ($courseid) {
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

        $validator->add('course_pendding', function ($str) use ($courseid) {
            return Course::find($courseid)->status == PENDING;
        });

        $validator->check('fullname')
            ->is_course_pendding('Khóa học đã được đồng bộ');

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/curriculum/update/course/' . $courseid);
        }

        $user = Auth::user();

        // set remoteid = null
        $input['remoteid'] = null;

        Course::update($courseid, $input);

        Notify::success(__('courses.updated'));

        return Response::redirect('admin/curriculum/add/topic/' . $courseid);
    });

    /*
        Edit a curriculum
     */
    Route::get('admin/curriculum/edit/topic/(:any)', function ($id) {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        $curriculum = Curriculum::getById($id);

        if (!$curriculum) {
            Notify::error(__('curriculum.notfound'));
            return Response::redirect('admin/courses');
        }

        $vars['curriculum'] = $curriculum;

        $teachers = array();
        $teachers = $teachers + array('0' => '--- Chọn giảng viên ---');
        $teachers = $teachers + User::dropdown();

        $vars['teachers'] = $teachers;
        $vars['topictime'] = array(
            '0' => '--- Thời gian ---',
            '1' => 'Sáng',
            '2' => 'Chiều'
        );

        $rooms = array();
        $rooms = $rooms + array('0' => '--- Chọn phòng học ---');
        $rooms = $rooms + Room::dropdown();
        $vars['rooms'] = $rooms;

        // extended fields
        $vars['fields'] = Extend::fields('curriculum');
        $vars['tab'] = 'course';

        return View::create('curriculum/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/curriculum/edit/topic/(:any)', function ($id) {

        $curriculum = Curriculum::getById($id);
        $input = Input::get(array('topictime', 'topicname', 'teacher', 'room', 'note', 'dayoff'));

        if ($input['dayoff'] === NULL) {

            $validator = new Validator($input);

            $validator->check('topicname')
                ->is_max(1, __('curriculum.topicname_missing'));

            $validator->check('teacher')
                ->is_boolean(__('curriculum.teacher_missing'));

            $validator->check('room')
                ->is_boolean(__('curriculum.teacher_missing'));

            if ($errors = $validator->errors()) {
                Input::flash();
                Notify::error($errors);
                return Response::redirect('admin/curriculum/edit/topic/' . $id);
            }
        }

        $user = Auth::user();

        if (intval($input['dayoff']) === 1) {
            $data = array();
            $data['topictime'] = NULL;
            $data['topicname'] = 'Ngày nghỉ';
            $data['teacher'] = NULL;
            $data['room'] = NULL;
            $data['note'] = NULL;
            $data['usermodified'] = $user->id;
            $data['timemodified'] = time();
            Curriculum::update($id, $data);
        } else {
            unset($input['dayoff']);
            $input['usermodified'] = $user->id;
            $input['timemodified'] = time();
            Curriculum::update($id, $input);
        }

        Notify::success(__('curriculum.updated'));

        return Response::redirect('admin/curriculum/' . $curriculum->course);
    });


    /*
        Delete post
    */
    Route::get('admin/curriculum/topic/delete/(:any)', function ($id) {
        $curriculum = Curriculum::getById($id);
        $courseid = $curriculum->course;

        $curriculum->delete();

        Notify::success(__('curriculum.deleted'));

        return Response::redirect('admin/curriculum/' . $courseid);
    });
    Route::post('admin/curriculum/add/remote/course', function () {
        $input = Input::get(array('courseid', 'loop'));
        $courseid = $input['courseid'];
        $loop = $input['loop'];
        $ispass = Course::create_course_hub($courseid, $loop);

        $output = Json::encode(array('status' => $ispass));
        if($ispass) {
            return Response::create($output, 200, array('content-type' => 'application/json'));
        }
        return Response::create($output, 200, array('content-type' => 'application/json'));
    });

    Route::get('admin/curriculum/topic/checkroom/(:any)/(:any)/(:any)', function ($day, $roomid, $time = null) {
        if ($time == 0) {
            return Curriculum::checkRoom($day, $roomid);
        } else {
            return Curriculum::checkRoom($day, $roomid, $time);
        }
    });
});
