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



}
