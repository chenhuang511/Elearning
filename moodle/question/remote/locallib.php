<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mnet/lib.php');
require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function sync_question_categories($length)
{
    global $DB;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_question_categories',
            'params' => array('sort' => 'contextid asc'),
        ), false
    );

    if ($result->categories) {
        $categories = $result->categories;

        if ($length == 1) {
            unset($categories[0]);
        }

        foreach ($categories as $category) {

            $local_category = new stdClass();
            $local_category->name = $category->name;
            $local_category->contextid = $category->contextid;
            $local_category->info = $category->info;
            $local_category->infoformat = $category->infoformat;
            $local_category->stamp = $category->stamp;
            $local_category->parent = $category->parent;
            $local_category->sortorder = $category->sortorder;
            $local_category->remoteid = $category->id;

            if ($category->contextlevel == CONTEXT_COURSECAT) {
                $localcoursecatid = $DB->get_field('course_categories', 'id', array('remoteid' => $category->instanceid));
                if ($localcoursecatid) {
                    $localcontextid = $DB->get_field('context', 'id', array('instanceid' => $localcoursecatid, 'contextlevel' => $category->contextlevel));
                    if ($localcontextid) {
                        $local_category->contextid = $localcontextid;
                    }
                }
            }
            if ($category->contextlevel == CONTEXT_COURSE) {
                $localcourseid = $DB->get_field('course', 'id', array('remoteid' => $category->instanceid));
                if ($localcourseid) {
                    $localcontextid = $DB->get_field('context', 'id', array('instanceid' => $localcourseid, 'contextlevel' => $category->contextlevel));
                    if ($localcontextid) {
                        $local_category->contextid = $localcontextid;
                    }
                }
            }
            if ($category->contextlevel == CONTEXT_MODULE) {
                $localcmid = $DB->get_field('course_modules', 'id', array('remoteid' => $category->instanceid));
                if ($localcmid) {
                    $localcontextid = $DB->get_field('context', 'id', array('instanceid' => $localcmid, 'contextlevel' => $category->contextlevel));
                    if ($localcontextid) {
                        $local_category->contextid = $localcontextid;
                    }
                }
            }

            $DB->insert_record('question_categories', $local_category);
        }
    }
}