<?php

/**
 * Created by PhpStorm.
 * User: dao
 * Date: 03/10/2016
 * Time: 10:36
 */
class UserRouter extends Base
{
    public static $table = "router";
    public static function get_router($action) {
        $query = Query::table(static::table())->where(Base::table('router.action'), '=', $action);
        return $query->get();
    }
}