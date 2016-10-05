
<?php

class VirtualClassEquipment extends Base {

    public static $table = 'virtual_class_equipment';

	public static function getList($page = 1, $perpage = 10) {
		$query = Query::table(static::table());

        $total = $query->count();

        // get list equiments
        $equipments = $query->take($perpage)
            ->skip(--$page * $perpage)
            ->get();

        return array($total, $equipments);
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
