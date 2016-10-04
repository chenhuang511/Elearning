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
    public static function get_router($router) {
        $query = Query::table(static::table())->where(Base::table('router.router'), '=', $router);
        return $query->get();
    }
}