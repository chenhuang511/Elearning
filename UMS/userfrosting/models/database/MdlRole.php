<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 4/27/2016
 * Time: 10:21 AM
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlRole extends UFModel{
    protected static $_table_id = "mdl_role";

    protected static $connectName = "moodle";

    public function __construct($properties = []){
        parent::__construct($properties);
    }
}