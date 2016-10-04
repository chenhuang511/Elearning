<?php

class Advance extends Base
{

    public static $table = 'advance';

    public static function get_list($perpage, $page = 1, $params = array())
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('advance.course_id'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('advance.applicant_id'));
        $total = $query->count();
        $advance = $query->sort('anchor_advance.id')->take($perpage)->skip(($page - 1) * $perpage)
            ->get(array(Base::table('advance.*'),
                Base::table('courses.shortname as course_name'),
                Base::table('users.real_name as user')));
        return array($total, $advance);
    }


    public static function get_list_by_status($status, $perpage, $page = 1, $params = array())
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('advance.course_id'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('advance.applicant_id'))
            ->where(Base::table('advance.status'), '=', $status);
        $total = $query->count();
        $advance = $query->sort('anchor_advance.id')->take($perpage)->skip(($page - 1) * $perpage)
            ->get(array(Base::table('advance.*'),
                Base::table('courses.shortname as course_name'),
                Base::table('users.real_name as user')));
        return array($total, $advance);
    }

    public static function get_list_advance_by_key($perpage, $page = 1, $key_name = '', $key_course = '', $moneyMin = '', $moneyMax = '', $key_id = '')
    {
        $query = static::join(Base::table('courses'), Base::table('courses.id'), '=', Base::table('advance.course_id'))
            ->join(Base::table('users'), Base::table('users.id'), '=', Base::table('advance.applicant_id'));
        if ($key_id) {
            $query = $query->where(Base::table('advance.id'), 'like', '%' . $key_id . '%');
        }
        if ($key_name) {
            $query = $query->where(Base::table('users.real_name'), 'like', '%' . $key_name . '%');
        }
        if ($key_course) {
            $query = $query->where(Base::table('courses.shortname'), 'like', '%' . $key_course . '%');
        }
        if ($moneyMin) {
            $query = $query->where(Base::table('advance.money'), '>', $moneyMin);
        }
        if ($moneyMax) {
            $query = $query->where(Base::table('advance.money'), '<', $moneyMax);
        }
        $total = $query->count();
        $advance = $query->sort('anchor_advance.id')->take($perpage)->skip(($page - 1) * $perpage)
            ->get(array(Base::table('advance.*'),
                Base::table('courses.shortname as course_name'),
                Base::table('users.real_name as user')));
        return array($total, $advance);
    }

    public static function getById($id)
    {
        $advance = static::find($id);
        $advance->real_name = User::getRealName($advance->user_check_id);
        return $advance;
    }

}
