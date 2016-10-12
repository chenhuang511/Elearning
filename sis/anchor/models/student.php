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

        $query2 = static::where('id', '=', $id)->fetch();

        return array($query, $query2);
    }

//    public static function getCoursesSuccessedByStudent($id)
//    {
//        $query = static::join(Base::table('student_course'), Base::table('student_course.studentid'), '=', Base::table('students.id'))
//            ->join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('student_course.courseid'))
//            ->where(Base::table('students.id'), '=', $id)->where(Base::table('student_course.grade'), '>', 0)->get();
//
//        return $query;
//    }

    public static function getTopicByCourse($idcourse)
    {
        $query = Curriculum::where('course', '=', $idcourse);
        $count = $query->count();
        $rs = $query->get();
        //echo '<pre>';var_dump($rs);die;
        return array($rs, $count);
    }

//    public static function getTopicLearningByStudent($id)
//    {
//        $query = static::join(Base::table('student_course'), Base::table('student_course.studentid'), '=', Base::table('students.id'))
//            ->join(Base::table('curriculum'), Base::table('curriculum.course'), '=', Base::table('student_course.courseid'))
//            ->where(Base::table('students.id'), '=', $id)->where(Base::table('student_course.grade'), '=', 0);
//        $count = $query->count();
//        $rs = $query->get();
//        return array($rs, $count);
//    }

    public static function paginate($page = 1, $perpage = 10) {
        $query = Query::table(static::table());

        $count = $query->count();

        $results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('id', 'asc')->get();
        //$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

        return new Paginator($results, $count, $page, $perpage, Uri::to('admin/students'));
    }

    public static function search($key, $page = 1, $per_page = 10) {

        $query = static::where('fullname', 'LIKE', '%' . $key . '%');

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get();

        return array($total, $posts);
    }

    public static function getStudentsByCourse($courseid) {
        $query = static::join(Base::table('student_course'), Base::table('student_course.studentid'), '=', Base::table('students.id'))
            ->where(Base::table('student_course.courseid'), '=', $courseid)
            ->get(array(
                Base::table('students.fullname'),
                Base::table('students.email'),
                Base::table('student_course.studentid')
            ));

        return $query;

    }

}
