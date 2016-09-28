<?php

class Course extends Base
{

    public static $table = 'courses';

    public static function getCoursesBy($userid = null, $page = 1, $perpage = 10)
    {
        if ($userid != null) {
            return self::getCoursesByUserId($userid, $page, $perpage);
        }
    }

    private static function getCoursesBySchool($page = 1, $perpage = 10) {

    }

    private static function getCoursesByUserId($id, $page = 1, $per_page = 10)
    {
        // get total
        $query = static::join(Base::table('user_course'), Base::table('courses.id'), '=', Base::table('user_course.courseid'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('user_course.userid'))
            ->where(Base::table('users.id'), '=', $id);

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get(array(Base::table('courses.*'),
                Base::table('courses.id as courseid')));

        return array($total, $posts);
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
