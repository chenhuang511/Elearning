<?php

class School extends Base {

    public static $table = 'schools';

//    public static function search($params = array()) {
//        $query = static::where('status', '=', 'active');
//
//        foreach($params as $key => $value) {
//            $query->where($key, '=', $value);
//        }
//
//        return $query->fetch();
//    }

    public static function paginate($page = 1, $perpage = 10) {
        $query = Query::table(static::table());

        $count = $query->count();

        //$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('real_name', 'desc')->get();
        $results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();

        return new Paginator($results, $count, $page, $perpage, Uri::to('admin/schools'));
    }

    public static function search($key, $page = 1, $per_page = 10) {

        $query = static::where('name', 'LIKE', '%' . $key . '%');

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get();

        return array($total, $posts);
    }
}
