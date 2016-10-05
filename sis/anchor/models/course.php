<?php

class Course extends Base
{
    public static $table = 'courses';

    public static function getCoursesBy($userid = null, $page = 1, $perpage = 10)
    {
        if ($userid != null) {
            return self::getCoursesByUserId($userid, $page, $perpage);
        }

        return self::getCoursesBySchoolId($page, $perpage);
    }

    private static function getCoursesBySchoolId($page = 1, $perpage = 10)
    {
        $query = static::join(Base::table('user_course'), Base::table('courses.id'), '=', Base::table('user_course.courseid'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('user_course.userid'));

        $total = $query->count();

        // get courses
        $courses = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('courses.*')
            ));

        return array($total, $courses);
    }

    private static function getCoursesByUserId($userid, $page = 1, $per_page = 10)
    {
        // get total
        $query = static::join(Base::table('user_course'), Base::table('courses.id'), '=', Base::table('user_course.courseid'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('user_course.userid'))
            ->where(Base::table('users.id'), '=', $userid);

        $total = $query->count();

        // get courses
        $courses = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get(array(Base::table('courses.*'),
                Base::table('courses.id as courseid')));

        return array($total, $courses);
    }

    public static function get_grade_by_course($courseid, $page = 1, $per_page = 10, $key = '', $grademin = '', $grademax = '')
    {
        // get total
        $query = static::join(Base::table('student_course'), Base::table('courses.id'), '=', Base::table('student_course.courseid'))
            ->join(Base::table('students'), Base::table('students.id'), '=', Base::table('student_course.studentid'))
            ->join(Base::table('schools'), Base::table('schools.id'), '=', Base::table('students.schoolid'))
            ->where(Base::table('courses.id'), '=', $courseid);

        if ($key) {
            $query = $query->where(Base::table('students.fullname'), 'like', '%' . $key . '%');
        }
        if ($grademin) {
            $query = $query->where(Base::table('student_course.grade'), '>', $grademin);
        }
        if ($grademax) {
            $query = $query->where(Base::table('student_course.grade'), '<', $grademax);
        }
        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get(array(Base::table('students.*'),
                Base::table('student_course.grade'),
                Base::table('student_course.studentid as studentid'),
                Base::table('courses.id as courseid'),
                Base::table('schools.name as schoolname')));

        return array($total, $posts);
    }


    public function get_list_shortname_courses()
    {
        $items = array();
        $query = Query::table(static::table());
        foreach ($query->sort('shortname')->get() as $item) {
            $items[$item->id] = $item->shortname;
        }

        return $items;
    }

    public static function getById($courseid)
    {
        return static::find($courseid);
    }


    public static function create_course_hub($courseid, $loop = false) {
        $course = static::find($courseid);
        $curiculum = Curriculum::where('course', '=', $courseid);

        if($course->remoteid) {
            $curiculums = $curiculum->get();
            return self::edit_section_hub($curiculums, $course->remoteid);
        }

        $numbersection = $curiculum->count();
        if(!isset($course) || $numbersection < 1){return false;};
        /**
         * call api create course
         */
        $data = array();
        $data['courses[0][fullname]']= $course->fullname;
        $data['courses[0][shortname]']= $course->shortname;
        $data['courses[0][categoryid]']= 2;
        $data['courses[0][summary]']= $course->summary;
        $data['courses[0][startdate]']= strtotime($course->startdate);
        $data['courses[0][visible]']= 1;
        $data['courses[0][numsections]']= $numbersection;
        $data['courses[0][enablecompletion]']= 1;
        $data['courses[0][completionnotify]']= 1;
        $courseupdate = new stdClass();
        $courseremote = remote_add_course($data);
        if(!$courseremote) {return false;};
        $courseupdate->remoteid = $courseremote->id;
        Course::update($course->id, $courseupdate);
        //end api
        // start edit title section
        $curiculums = $curiculum->get();
        return self::edit_section_hub($curiculums, $courseremote->id);
    }
    public static function edit_section_hub($sections, $courseid) {
        $remotesections = remote_get_course_section($courseid); // must be remote course id
        if(count($remotesections) < 2){
            return false;
        }
        $data = array();
        $data['component'] = 'format_weeks';
        $data['itemtype'] = 'sectionname';

        foreach ($sections as $key => $section) {
            $date = date_create($section->time);
            $time = date_format($date,"Y/m/d");
            $name = $time . '-' . $section->topictime . '-' . $section->topicname;
            if(!isset($remotesections[$key+1])){break;};
            $data['itemid'] = $remotesections[$key+1]->id;
            $data['value'] = $name;
            remote_edit_course_section($data);
        }

        return true;
    }
}
