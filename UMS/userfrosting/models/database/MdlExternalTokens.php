<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 07/05/2016
 * Time: 2:35 CH
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlExternalTokens extends UFModel{
    protected static $_table_id = "mdl_external_tokens";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}