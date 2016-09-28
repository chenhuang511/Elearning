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
                Base::table('courses.*'),
                Base::table('users.username')
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

    public static function get_grade_by_course($courseid, $page = 1, $per_page = 10)
    {
        // get total
        $query = static::join(Base::table('student_course'), Base::table('courses.id'), '=', Base::table('student_course.courseid'))
            ->join(Base::table('students'), Base::table('students.id'), '=', Base::table('student_course.studentid'))
            ->where(Base::table('courses.id'), '=', $courseid);

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get(array(Base::table('students.*'),
                Base::table('student_course.grade'),
                Base::table('courses.id as courseid')));

        return array($total, $posts);
    }
}
