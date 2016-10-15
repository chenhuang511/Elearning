<?php

class Room extends Base
{
    public static $table = 'rooms';

    public static function id($id) {
        return static::get('id', $id);
    }

    public static function paginate($page = 1, $perpage = 10) {
        $query = Query::table(static::table());

        $count = $query->count();

        $rooms = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('id', 'asc')->get();
        //$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

        return new Paginator($rooms, $count, $page, $perpage, Uri::to('admin/rooms'));
    }

    public static function getRoomsBy($userid = null, $page = 1, $perpage = 10)
    {
        if ($userid != null) {
            return self::getroomsByUserId($userid, $page, $perpage);
        }
        /**
         * for now, we only support one school
         */
        return self::getAllrooms($page, $perpage);
//        return self::getroomsBySchoolId($page, $perpage);
    }

    private static function getRoomsByStatus($page = 1, $perpage = 10, $status = 1)
    {
        $query = static::where('status', '=', $status);

        $total = static::count();

        // get rooms
        $rooms = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('rooms.*')
            ));

        return array($total, $rooms);
    }

    private static function getAllRooms($page = 1, $perpage = 10)
    {
        $total = static::count();

        // get rooms
        $rooms = static::sort('id', 'DESC')
            ->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('rooms.*')
            ));

        return array($total, $rooms);
    }

    private static function getRoomsBySchoolId($page = 1, $perpage = 10)
    {
        $query = static::join(Base::table('user_room'), Base::table('rooms.id'), '=', Base::table('user_room.roomid'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('user_room.userid'));

        $total = $query->count();

        // get rooms
        $rooms = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get(array(
                Base::table('rooms.*')
            ));

        return array($total, $rooms);
    }

    public static function getById($id)
    {
        $query = Query::table(static::table())
            ->left_join(Base::table('curriculum'), Base::table('curriculum.room'), '=', Base::table('rooms.id'))
            ->where(Base::table('rooms.id'), '=', $id);
        $rooms = $query
            ->get(array(
                Base::table('rooms.name as name'),
                Base::table('rooms.id as id'),
                Base::table('curriculum.room as curroom'),
                Base::table('rooms.description as description'),
                Base::table('rooms.status as status'),
                Base::table('curriculum.topicday as topicday')));
        return $rooms;
    }

    public static function dropdown()
    {
        $items = array();
        $query = Query::table(static::table());

        foreach ($query->sort('id')->get() as $item) {
            $items[$item->id] = $item->name;
        }

        return $items;
    }
}
