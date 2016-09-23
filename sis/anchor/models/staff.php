<?php

class Staff extends Base {

    public static $table = 'staff';

    public static function read($params = array()) {
        $items = array();
        $query = Query::table(static::table());
        foreach($query->sort('full_name')->get() as $item) {
            $items[$item->id] = $item->full_name;
        }

        return $items;
    }

    public static function _read($params = array()) {

        $query = static::join(Base::table('advance'), Base::table('staff.id'), '=', Base::table('advance.applicant_id')) ;

        return $query->get();
    }
    public static function page_read($params = array(), $perpage , $page = 1) {

        $query = static::join(Base::table('advance'), Base::table('staff.id'), '=', Base::table('advance.applicant_id')) ;

        return $query->sort('anchor_advance.id','DESC')->take($perpage)->skip(($page - 1) * $perpage)->get();
    }

    public static function read_status( $perpage, $page = 1, $status) {
        $query = static::join(Base::table('advance'), Base::table('staff.id'), '=', Base::table('advance.applicant_id'))->where(Base::table('advance.status'), '=', $status) ;

        return $query->sort('anchor_advance.id','DESC')->take($perpage)->skip(($page - 1) * $perpage)->get();
    }

    public static function page_read_status($status) {

        $query = static::join(Base::table('advance'), Base::table('staff.id'), '=', Base::table('advance.applicant_id'))->where(Base::table('advance.status'), '=', $status) ;

        return $query->get();
    }




}
