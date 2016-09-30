<?php

class Advance extends Base {

    public static $table = 'advance';

    public static function get_list($perpage, $page = 1,$params = array()) {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('advance.course_id'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('advance.applicant_id'));
        $total = $query->count();
        $advance =  $query->sort('anchor_advance.id','DESC')->take($perpage)->skip(($page - 1) * $perpage)
            ->get(array(Base::table('advance.*'),
            Base::table('courses.shortname as course_name'),
            Base::table('users.real_name as user')));
        return array($total, $advance);
    }


    public static function get_list_by_status($status,$perpage, $page = 1,$params = array()) {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('advance.course_id'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('advance.applicant_id'))
            ->where(Base::table('advance.status'), '=', $status);
        $total = $query->count();
        $advance =  $query->sort('anchor_advance.id','DESC')->take($perpage)->skip(($page - 1) * $perpage)
            ->get(array(Base::table('advance.*'),
                Base::table('courses.shortname as course_name'),
                Base::table('users.real_name as user')));
        return array($total, $advance);
    }










}
