<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 06/05/2016
 * Time: 11:31 SA
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlGradeGrades extends UFModel{
    protected static $_table_id = "mdl_grade_grades";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}