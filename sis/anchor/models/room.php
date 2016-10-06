
<?php

class Room extends Base {

    public static $table = 'room';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/room'));
	}

	public static function search($params = array()) {
		$query = Query::table(static::table());

		foreach($params as $key => $value) {
			$query->where($key, '=', $value);
		}

		return $query->fetch();
	}
	
	public static function getRoom() {
		$query = Query::table(static::table());
		return $query->get();
	}

}
