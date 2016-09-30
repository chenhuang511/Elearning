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

        if($key) {
            $query = $query->where(Base::table('students.fullname'), 'like', '%' . $key . '%');
        }
        if($grademin) {
            $query = $query->where(Base::table('student_course.grade'), '>', $grademin);
        }
        if($grademax) {
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


    public function get_list_shortname_courses(){
        $items = array();
        $query = Query::table(static::table());
        foreach($query->sort('shortname')->get() as $item) {
            $items[$item->id] = $item->shortname;
        }

        return $items;
    }

}
