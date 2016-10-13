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

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('id', 'asc')->get();

		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/instructor'));
	}

	public static function get_official_instructor($page = 1, $perpage = 10) {
		$query = Query::table(Base::table('users'))->where('role_id', '=', 5);

		$count = $query->count();

		$results = $query
			->take($perpage)
			->skip(($page - 1) * $perpage)
			->sort('id', 'asc')
			->get(array(Base::table('users.id as id'),
						Base::table('users.real_name as real_name'),
						Base::table('users.email as email'),
						Base::table('users.schoolid as schoolid'),
						Base::table('users.remoteid as remoteid')));
		
		foreach($results as $instructor){
			$instructor->curriculum_taught = Query::table(base::table('curriculum'))->where(('teacher'), '=', $instructor->id)->count();
		}

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

	public static function search_official_instructor($key, $page = 1, $per_page = 10) {

        $query = Query::table(Base::table('users'))->where('role_id', '=', 5)->where('real_name', 'LIKE', '%' . $key . '%');

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get();
		foreach($posts as $instructor){
			$instructor->curriculum_taught = Query::table(base::table('curriculum'))->where(('teacher'), '=', $instructor->id)->count();
		}

        return array($total, $posts);
    }

}
