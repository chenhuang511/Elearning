<?php

class Curriculum extends Base
{
    public static $table = 'curriculum';

    public static function getByCourseId($courseid, $page = 1, $perpage = 10)
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('curriculum.course'))
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
            if ($curriculum->teacher != NULL) {
                $curriculum->teacher_name = User::getRealName($curriculum->teacher);
            }
            if($curriculum->room != NULL) {
                $curriculum->room_name = Room::getRoomName($curriculum->room);
            }
        }

        return array($total, $curriculums);
    }

    public static function getTopicByCourse($id)
    {
        $query = static::join(Base::table('student_course'), Base::table('student_course.studentid'), '=', Base::table('students.id'))
            ->join(Base::table('curriculum'), Base::table('curriculum.course'), '=', Base::table('student_course.courseid'))
            ->where(Base::table('students.id'), '=', $id);
        $count = $query->count();
        $rs = $query->get();
        return array($rs, $count);
    }

    public static function getByTeacherId($teacherid, $page = 1, $perpage = 10) {
        $query = static::join(Base::table('users'), Base::table('users.id'), '=', Base::table('curriculum.teacher'))
            ->join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('curriculum.course'))
            ->where(Base::table('curriculum.teacher'), '=', $teacherid);

        $total = $query->count();

        // get list curriculumn
        $curriculums = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('curriculum.*'),
                Base::table('courses.fullname as coursename')
            ));

      foreach ($curriculums as $curriculum) {
            $curriculum->topicday = self::GetDayOfWeek($curriculum->topicday) . ' ' . date('d-m-Y', strtotime($curriculum->topicday));
            if($curriculum->room != NULL) {
                $curriculum->room_name = Room::getRoomName($curriculum->room);
            } 
        }

        return array($total, $curriculums);
    }

    public static function checkRoom($day, $roomid, $time = NULL)
    {
        $query = static::join(Base::table('rooms'), Base::table('rooms.id'), '=', Base::table('curriculum.room'))
            ->where(Base::table('curriculum.topicday'), '=', $day)
            ->where(Base::table('curriculum.room'), '=', $roomid);
        if ($time == NULL)
            $query->where(Base::table('curriculum.topictime'), 'IS', NULL);
        else
            $query->where(Base::table('curriculum.topictime'), '=', $time);

        return $query->count() == 0 ? 'n' : 'y';
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