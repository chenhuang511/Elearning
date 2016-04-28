<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 23/04/2016
 * Time: 10:27 SA
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlCacheFlags extends UFModel{
    protected static $_table_id = "mdl_cache_flags";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}