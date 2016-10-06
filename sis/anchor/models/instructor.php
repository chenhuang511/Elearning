<?php

class Instructor extends Base {

	public static $table = 'instructors';

	// public static function search($params = array()) {
	// 	$query = static::where('status', '=', 'active');

	// 	foreach($params as $key => $value) {
	// 		$query->where($key, '=', $value);
	// 	}

	// 	return $query->fetch();
	// }

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('fullname', 'asc')->get();
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/instructor'));
	}
	
	public static function get_name_instructor() {
		$query = Query::table(static::table());
		return $query->get();
	}

	public static function search($key, $page = 1, $per_page = 10) {

        $query = static::where('fullname', 'LIKE', '%' . $key . '%');

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get();

        return array($total, $posts);
    }

}
