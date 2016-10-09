<?php

class Room extends Base
{
    public static $table = 'rooms';

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

    public static function getById($roomid)
    {
        return static::find($roomid);
    }


    public static function create_room_hub($roomid, $loop = false) {
        $room = static::find($roomid);
        $equipment = Equipment::where('room', '=', $roomid);

        if($room->remoteid) {
            $equipments = $equipment->get();
            return self::edit_section_hub($equipments, $room->remoteid);
        }

        $numbersection = $equipment->count();
        if(!isset($room) || $numbersection < 1){return false;};
        /**
         * call api create room
         */
        $data = array();
        $data['rooms[0][fullname]']= $room->fullname;
        $data['rooms[0][shortname]']= $room->shortname;
        $data['rooms[0][categoryid]']= 2;
        $data['rooms[0][summary]']= $room->summary;
        $data['rooms[0][startdate]']= strtotime($room->startdate);
        $data['rooms[0][visible]']= 1;
        $data['rooms[0][numsections]']= $numbersection;
        $data['rooms[0][enablecompletion]']= 1;
        $data['rooms[0][completionnotify]']= 1;
        $roomupdate = new stdClass();
        $roomremote = remote_add_room($data);
        if(!$roomremote) {return false;};
        $roomupdate->remoteid = $roomremote->id;
        room::update($room->id, $roomupdate);
        //end api
        // start edit title section
        $equipments = $equipment->get();
        return self::edit_section_hub($equipments, $roomremote->id);
    }
    public static function edit_section_hub($sections, $roomid) {
        $remotesections = remote_get_room_section($roomid); // must be remote room id
        if(count($remotesections) < 2){
            return false;
        }
        $data = array();
        $data['component'] = 'format_weeks';
        $data['itemtype'] = 'sectionname';

        foreach ($sections as $key => $section) {
            $date = date_create($section->time);
            $time = date_format($date,"Y/m/d");
            $name = $time . '-' . $section->topictime . '-' . $section->topicname;
            if(!isset($remotesections[$key+1])){break;};
            $data['itemid'] = $remotesections[$key+1]->id;
            $data['value'] = $name;
            remote_edit_room_section($data);
        }

        return true;
    }
}
