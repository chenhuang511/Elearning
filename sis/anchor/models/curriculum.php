<?php

class Curriculumn extends Base
{
    public static $table = 'curriculum';

    public static function getById($id)
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('curriculum.course'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('curriculum.userid'))
            ->where(Base::table('curriculum.id'), '=', $id);

        $curriculum = null;
        $curriculums = $query->get();
        $curriculum = clone $curriculums;
        return $curriculum;
    }

    public static function getByCourseId($courseid, $page = 1, $perpage = 10)
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('curriculum.course'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('curriculum.userid'))
            ->where(Base::table('courses.id'), '=', $courseid);

        $total = $query->count();

        // get list curriculumn
        $curriculumns = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('curriculum.*'),
                Base::table('courses.fullname as coursename'),
                Base::table('courses.summary as coursesummary'),
                Base::table('courses.startdate'),
                Base::table('courses.enddate')
            ));

        return array($total, $curriculumns);
    }
}