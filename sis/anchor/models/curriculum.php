<?php

class Curriculum extends Base
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

    public static function GetDayOfWeek($date) {
        $d = date('l', strtotime($date));

        if($d == 'Monday') {
            return 'Thứ hai';
        } else if ($d == 'Tuesday') {
            return 'Thứ ba';
        } else if ($d == 'Wednesday') {
            return 'Thứ tư';
        } else if ($d == 'Thursday') {
            return 'Thứ năm';
        } else if ($d == 'Friday') {
            return 'Thứ sáu';
        } else if ($d == 'Saturday') {
            return 'Thứ bảy';
        } else {
            return "Chủ nhật";
        }
    }
}