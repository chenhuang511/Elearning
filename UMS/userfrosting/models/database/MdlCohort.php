<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/29/2016
 * Time: 2:04 PM
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlCohort extends UFModel{
    protected static $_table_id = "mdl_cohort";

    protected static $connectName = "moodle";

//    protected $connection = "moodle";

    public function __construct($properties = []) {
        parent::__construct($properties);
    }

    public function users(){
        $link_table = Database::getSchemaTable('mdl_cohort_members')->name;
        return $this->belongsToMany('UserFrosting\MdlUser', $link_table, 'cohortid','userid');
    }
    
}
