<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 06/05/2016
 * Time: 6:18 CH
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlMessageWorking extends UFModel{
    protected static $_table_id = "mdl_message_working";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}