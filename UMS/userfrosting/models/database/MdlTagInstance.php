<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 26/04/2016
 * Time: 4:47 CH
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlTagInstance extends UFModel{
    protected static $_table_id = "mdl_tag_instance";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}