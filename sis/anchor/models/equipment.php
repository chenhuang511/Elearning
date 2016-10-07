<?php

class Equipment extends Base
{
    public static $table = 'equipment';

    public static function getByRoomId($roomid, $page = 1, $perpage = 10)
    {
        $query = static::join(Base::table('rooms'), Base::table('rooms.id'), '=', Base::table('equipment.room'))
            // ->left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('equipment.lecturer'))
            ->where(Base::table('equipment.room'), '=', $roomid);

        $total = $query->count();

        // get list curriculumn
        $equipments = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('equipment.*'),
                Base::table('rooms.name as roomname')
            ));

        // foreach ($curriculums as $curriculum) {
        //     $curriculum->topicday = self::GetDayOfWeek($curriculum->topicday) . ' ' . date('d-m-Y', strtotime($curriculum->topicday));
        //     $curriculum->teacher_name = User::getRealName($curriculum->lecturer);
        // }

        return array($total, $equipments);
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