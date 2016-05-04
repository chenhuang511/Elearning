<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 4/29/2016
 * Time: 1:51 PM
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlRoleAllowAssign extends UFModel {
    
    protected static $_table_id = "mdl_role_allow_assign";

    protected static $connectName = "moodle";

    public function __construct($properties = [])
    {
        parent::__construct($properties);
    }
}