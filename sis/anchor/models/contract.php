<?php

class Contract extends Base {

	public static $table = 'instructor_contract';

	// public static function search($params = array()) {
	// 	$query = static::where('status', '=', 'active');

	// 	foreach($params as $key => $value) {
	// 		$query->where($key, '=', $value);
	// 	}

	// 	return $query->fetch();
	// }
	public static function search_by_instructor_id($id) {
		$query = Query::table(static::table())->where(Base::table('instructor_contract.instructor_id'), '=', $id);
		return $query->get();
	}

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table())->join(Base::table('instructors'), Base::table('instructor_contract.instructor_id'),'=',Base::table('instructors.id'));
		$count = $query->count();

		//$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('id', 'desc')->get();
		$results = $query->take($perpage)->skip(($page - 1) * $perpage)
					->get(array(Base::table('instructor_contract.*'),
					 			Base::table('instructors.firstname as firstname'),
								Base::table('instructors.lastname as lastname')));
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/contract'));
	}

	public static function search($key, $page = 1, $per_page = 10) {

        $query = static::where('name_contract', 'LIKE', '%' . $key . '%')->join(Base::table('instructors'), Base::table('instructor_contract.instructor_id'),'=',Base::table('instructors.id'));

        $total = $query->count();

        // get posts
        $posts = $query->take($per_page)
            ->skip(--$page * $per_page)
            ->get(array(Base::table('instructor_contract.*'),
					 			Base::table('instructors.firstname as firstname'),
								Base::table('instructors.lastname as lastname')));

        return array($total, $posts);
    }

}
