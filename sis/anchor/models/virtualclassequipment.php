
<?php

class VirtualClassEquipment extends Base {

    public static $table = 'virtual_class_equipment';

	public static function paginate($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

		$count = $query->count();

		//$results = $query->take($perpage)->skip(($page - 1) * $perpage)->sort('real_name', 'desc')->get();
		$results = $query->take($perpage)->skip(($page - 1) * $perpage)->get();
		return new Paginator($results, $count, $page, $perpage, Uri::to('admin/virtual_class_equipments'));
	}

	public static function search($params = array()) {
		$query = Query::table(static::table());

		foreach($params as $key => $value) {
			$query->where($key, '=', $value);
		}

		return $query->fetch();
	}
	
	public static function getVirtualClassEquipment() {
		$query = Query::table(static::table());
		return $query->get();
	}

}
