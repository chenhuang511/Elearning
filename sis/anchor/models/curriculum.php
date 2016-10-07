<?php

class Curriculum extends Base
{
    public static $table = 'curriculum';

    public static function getByCourseId($courseid, $page = 1, $perpage = 10)
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('curriculum.course'))
            ->left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('curriculum.lecturer'))
            ->where(Base::table('curriculum.course'), '=', $courseid);

        $total = $query->count();

        // get list curriculumn
        $curriculums = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('curriculum.*'),
                Base::table('courses.fullname as coursename'),
                Base::table('courses.status as status')
            ));
        foreach ($curriculums as $curriculum) {
            $curriculum->topicday = self::GetDayOfWeek($curriculum->topicday) . ' ' . date('d-m-Y', strtotime($curriculum->topicday));
            $curriculum->teacher_name = User::getRealName($curriculum->lecturer);
        }

        return array($total, $curriculums);
    }

    public static function getById($id)
    {
        return static::find($id);
    }

    public static function GetDayOfWeek($date)
    {
        $d = date('l', strtotime($date));

        if ($d == 'Monday') {
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