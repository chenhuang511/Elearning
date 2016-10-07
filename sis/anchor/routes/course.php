<?php
Route::collection(array('before' => 'auth,csrf'), function () {

    Route::get(array('admin/courses', 'admin/courses/(:num)'), function ($page = 1) {

        // get public listings
        $userid = Auth::get_userid();
        list($total, $courses) = Course::getCoursesBy(null, $page, $perpage = Config::get('admin.posts_per_page'));

        foreach ($courses as $course) {
            if ($course->startdate !== NULL && $course->enddate !== NULL) {
                $course->startdate = date('d-m-Y', strtotime($course->startdate));
                $course->enddate = date('d-m-Y', strtotime($course->enddate));
            }
        }

        $url = Uri::to('admin/courses');

        $pagination = new Paginator($courses, $total, $page, $perpage, $url);

        $vars['messages'] = Notify::read();
        $vars['pages'] = $pagination;
        //need process here
        return View::create('course/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get(array('admin/courses/enrol/(:num)', 'admin/courses/enrol/(:num)/(:num)'), function ($courseid, $page = 1) {

        // get public listings
        $course = Course::find($courseid);
        if(!isset($course) && $course->status == 1) {
            return  Response::redirect('/');
        };
        $studentsenrol = Student::getStudentsByCourse($courseid);
        $usersenrol = User::getUsersByCourse($courseid);
        $vars['messages'] = Notify::read();

        $vars['course'] = $course;
        $vars['usersenrol'] = $usersenrol;
        $vars['studentsenrol'] = $studentsenrol;
        //need process here
        return View::create('course/enrol', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get(array('admin/courses/enrol/teacher/(:num)', 'admin/courses/enrol/teacher/(:num)/(:num)'), function ($courseid, $page = 1) {

        // get public listings
        $course = Course::find($courseid);
        if(!isset($course) && $course->status == PENDING) {
            return  Response::redirect('/');
        };

        $url = 'admin/courses/' . $courseid . '/enrol/teacher';
        // need merge user with student - pedding
        $users = User::paginate($page, Config::get('admin.posts_per_page'), $url);
        $vars['messages'] = Notify::read();

        $vars['course'] = $course;
        $vars['pages'] = $users;
        //need process here
        return View::create('users/enrol', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/courses/(:num)/enrol/teacher', function($courseid) {
        $input = Input::get(array('userid'));
        $userid = $input['userid'];
        $user = User::find($userid);
        $course = Course::find($courseid);
        $ispass = true;
        if(!$user || !$course) {
            $ispass = false;
        }
        $school = School::find($user->schoolid);
        //enrol for school
        $rolehost = 5;

        $domain = $school->wwwroot;
        $token = $school->token;
        $roleid = 3;
        $userremoteid = $user->remoteid;
        $courseremoteid = $course->remoteid;

        $hostid = $school->remoteid;
        $enrolhost = remote_enrol_host($rolehost, $hostid, $courseremoteid);
        var_dump($enrolhost);
        $fetch = remote_fetch_course($domain, $token);
        var_dump($fetch);
        $abc = remote_enrol_user($domain, $token, $roleid, $userremoteid, $courseremoteid);
        var_dump($abc);
//        if(!$ispass){
//            return Response::create(0, 500, array('content-type' => 'application/json'));
//        }
//        return Response::create(1, 200, array('content-type' => 'application/json'));
    });

    Route::get(array('admin/courses/(:num)/enrol/teacher/search', 'admin/courses/(:num)/enrol/teacher/search/(:num)'), function($courseid, $page = 1) {
        $course = Course::find($courseid);
        if(!isset($course) && $course->status == PENDING) {
            return  Response::redirect('/');
        };
        $input = Input::get(array(
            'key'
        ));
        foreach($input as $key => &$value) {
            $value = eq($value);
        }

        $key = $input['key'];
        $whatSearch = '?key=' . $key;
        $perpage = Config::get('admin.posts_per_page');
        list($total, $pages) = User::searchuser($key, $page, $perpage);

        $url = Uri::to('admin/courses/' . $courseid . '/enrol/teacher/search');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url, $whatSearch);

        $vars['messages'] = Notify::read();
        $vars['course'] = $course;
        $vars['pages'] = $pagination;
        //need process here
        return View::create('users/enrol', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });
});