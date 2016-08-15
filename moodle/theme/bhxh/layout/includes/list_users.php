<?php
require_once($CFG->libdir . '/tablelib.php');
global $DB;
$currentgroup = groups_get_course_group($COURSE, true);
$context = context_course::instance($COURSE->id);
if (!$currentgroup) {      // To make some other functions work better later.
    $currentgroup = null;
}

// Define a table showing a list of users in the current role selection.
$tablecolumns = array();
$tableheaders = array();
$tablecolumns[] = 'userpic';
$tablecolumns[] = 'fullname';

$tableheaders[] = get_string('userpic');
$tableheaders[] = get_string('fullnameuser');

$tablecolumns[] = 'city';
$tableheaders[] = get_string('city');
$tablecolumns[] = 'country';
$tableheaders[] = get_string('country');
$tablecolumns[] = 'lastaccess';
$tableheaders[] = get_string('lastcourseaccess');

$table = new flexible_table('user-index-participants-' . $COURSE->id);
$table->define_columns($tablecolumns);
$table->define_headers($tableheaders);
$baseurl = new moodle_url('/course/view.php', array(
    'id' => $COURSE->id));
$table->define_baseurl($baseurl->out());

$table->setup();

list($esql, $params) = get_enrolled_sql($context, null, $currentgroup, true);
$joins = array("FROM {user} u");
$wheres = array();

$userfields = array('username', 'email', 'city', 'country', 'lang', 'timezone', 'maildisplay');
$mainuserfields = user_picture::fields('u', $userfields);

$select = "SELECT $mainuserfields, COALESCE(ul.timeaccess, 0) AS lastaccess";
$joins[] = "JOIN ($esql) e ON e.id = u.id"; // Course enrolled users only.
$joins[] = "LEFT JOIN {user_lastaccess} ul ON (ul.userid = u.id AND ul.courseid = :courseid)"; // Not everybody accessed course yet.
$params['courseid'] = $COURSE->id;

// Performance hacks - we preload user contexts together with accounts.
$ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
$ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = u.id AND ctx.contextlevel = :contextlevel)";
$params['contextlevel'] = CONTEXT_USER;
$select .= $ccselect;
$joins[] = $ccjoin;

$from = implode("\n", $joins);
if ($wheres) {
    $where = "WHERE " . implode(" AND ", $wheres);
} else {
    $where = "";
}

list($twhere, $tparams) = $table->get_sql_where();
if ($twhere) {
    $wheres[] = $twhere;
    $params = array_merge($params, $tparams);
}

$from = implode("\n", $joins);
if ($wheres) {
    $where = "WHERE " . implode(" AND ", $wheres);
} else {
    $where = "";
}

if ($table->get_sql_sort()) {
    $sort = ' ORDER BY ' . $table->get_sql_sort();
} else {
    $sort = '';
}

// List of users at the current visible page - paging makes it relatively short.
$userlist = $DB->get_recordset_sql("$select $from $where $sort", $params);
// If there are multiple Roles in the course, then show a drop down menu for switching.
$datestring = new stdClass();
$datestring->year = get_string('year');
$datestring->years = get_string('years');
$datestring->day = get_string('day');
$datestring->days = get_string('days');
$datestring->hour = get_string('hour');
$datestring->hours = get_string('hours');
$datestring->min = get_string('min');
$datestring->mins = get_string('mins');
$datestring->sec = get_string('sec');
$datestring->secs = get_string('secs');
$strnever = get_string('never');

$countries = get_string_manager()->get_list_of_countries();

$timeformat = get_string('strftimedate');

if ($userlist) {

    $usersprinted = array();
    foreach ($userlist as $user) {
        if (in_array($user->id, $usersprinted)) { // Prevent duplicates by r.hidden - MDL-13935.
            continue;
        }
        $usersprinted[] = $user->id; // Add new user to the array of users printed.

        context_helper::preload_from_record($user);

        if ($user->lastaccess) {
            $lastaccess = format_time(time() - $user->lastaccess, $datestring);
        } else {
            $lastaccess = $strnever;
        }

        if (empty($user->country)) {
            $country = '';

        } else {
            $country = $countries[$user->country];
        }

        $usercontext = context_user::instance($user->id);

        $profilelink = '<strong>' . fullname($user) . '</strong>';

        $data = array();
        $data[] = $OUTPUT->user_picture($user, array('size' => 35));
        $data[] = $profilelink;

        $data[] = $user->city;
        $data[] = $country;
        if (!isset($hiddenfields['lastaccess'])) {
            $data[] = $lastaccess;
        }

        $table->add_data($data);
    }
}

$table->print_html();

