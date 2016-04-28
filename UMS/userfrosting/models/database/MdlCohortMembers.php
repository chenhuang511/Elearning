<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/29/2016
 * Time: 3:42 PM
 */

namespace UserFrosting;

use Illuminate\Database\Eloquent\Model;

class MdlCohortMembers extends UFModel{
    protected static $_table_id = "mdl_cohort_members";

    protected static $connectName = "moodle";

    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}