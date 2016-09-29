<?php

class Student extends Base {

    public static $table = 'students';

//    public static function search($params = array()) {
//        $query = static::where('status', '=', 'active');
//
//        foreach($params as $key => $value) {
//            $query->where($key, '=', $value);
//        }
//
//        return $query->fetch();
//    }

    public static function getSchoolByStudent($id)
    {
        $query = static::join(Base::table('schools'), Base::table('schools.id'), '=', Base::table('students.schoolid'))
                 ->where(Base::table('students.id'), '=', $id)->get();

        return $query;
    }

    public static function getCoursesByStudent($id)
    {
        $query = static::join(Base::table('student_course'), Base::table('student_course.studentid'), '=', Base::table('students.id'))
            ->join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('student_course.courseid'))
            ->where(Base::table('students.id'), '=', $id)->get();

        return $query;
    }

    public static function paginate($page = 1, $perpage = 10) {
        $query = Query::table(static::table());

        $count = $query->count();

        //$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('real_name', 'desc')->get();
        $results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

        return new Paginator($results, $count, $page, $perpage, Uri::to('admin/students'));
    }

}
