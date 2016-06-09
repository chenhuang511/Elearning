
<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 30/05/2016
 * Time: 3:50 CH
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_quiz_by_id($id) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_quiz_by_id',
            'params' => array('id'=>$id)
        )
    );
}

function get_remote_user_attemps($quizid, $userid, $status, $includepreviews) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_get_user_attempts',
            'params' => array('quizid' => $quizid, 'userid' => $userid, 'status' => $status, 'includepreviews' => $includepreviews)
        )
    );
}

function get_remote_coursemodule_from_instance($module, $instance) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'core_course_get_course_module_by_instance',
            'params' => array('module'=>$module, 'instance'=>$instance)
        )
    );
}

function get_remote_quiz_access_information($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_get_quiz_access_information',
            'params' => array('quizid'=>$quizid)
        )
    );
}

function get_remote_user_best_grade($quizid,  $userid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_get_user_best_grade',
            'params' => array('quizid' => $quizid, 'userid' => $userid)
        )
    );
}

function get_remote_question($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_questions_by_quizid',
            'params' => array('id' => $quizid)
        )
    );
}


/**
 * Validate permissions for creating a new attempt and start a new preview attempt if required.
 *
 * @param  quiz $quizobj quiz object
 * @param  quiz_access_manager $accessmanager quiz access manager
 * @param  bool $forcenew whether was required to start a new preview attempt
 * @param  int $page page to jump to in the attempt
 * @param  bool $redirect whether to redirect or throw exceptions (for web or ws usage)
 * @return array an array containing the attempt information, access error messages and the page to jump to in the attempt
 * @throws moodle_quiz_exception
 * @since Moodle 3.1
 */
function quiz_remote_validate_new_attempt(quiz $quizobj, quiz_access_manager $accessmanager, $forcenew, $page, $redirect) {
    $timenow = time();

    if ($quizobj->is_preview_user() && $forcenew) {
        $accessmanager->current_attempt_finished();
    }

    // Check capabilities.
    if (!$quizobj->is_preview_user()) {
        $quizobj->require_capability('mod/quiz:attempt');
    }

    // Check to see if a new preview was requested.
    if ($quizobj->is_preview_user() && $forcenew) {
        // To force the creation of a new preview, we mark the current attempt (if any)
        // as finished. It will then automatically be deleted below.
        //@ TODO ????
//        $DB->set_field('quiz_attempts', 'state', quiz_attempt::FINISHED,
//            array('quiz' => $quizobj->get_quizid(), 'userid' => $USER->id));
    }

    // Look for an existing attempt.
    //get user mapping
    $user = get_remote_user_mapping_userid();
    $attempts = get_remote_user_attemps($quizobj->get_quizid(), $user[0]->id, 'all', true)->attempts;
    $lastattempt = end($attempts);

    $attemptnumber = null;
    // If an in-progress attempt exists, check password then redirect to it.
    if ($lastattempt && ($lastattempt->state == quiz_attempt::IN_PROGRESS ||
            $lastattempt->state == quiz_attempt::OVERDUE)) {
        $currentattemptid = $lastattempt->id;
        $messages = $accessmanager->prevent_access();

        // If the attempt is now overdue, deal with that.
        // @TODO $quizobj->create_attempt_object($lastattempt)->handle_if_time_expired($timenow, true);

        // And, if the attempt is now no longer in progress, redirect to the appropriate place.
        if ($lastattempt->state == quiz_attempt::ABANDONED || $lastattempt->state == quiz_attempt::FINISHED) {
            // ???
            if ($redirect) {
                redirect($quizobj->review_url($lastattempt->id));
            } else {
                throw new moodle_quiz_exception($quizobj, 'attemptalreadyclosed');
            }
        }

        // If the page number was not explicitly in the URL, go to the current page.
        if ($page == -1) {
            $page = $lastattempt->currentpage;
        }

    } else {
        while ($lastattempt && $lastattempt->preview) {
            $lastattempt = array_pop($attempts);
        }

        // Get number for the next or unfinished attempt.
        if ($lastattempt) {
            $attemptnumber = $lastattempt->attempt + 1;
        } else {
            $lastattempt = false;
            $attemptnumber = 1;
        }
        $currentattemptid = null;

        $messages = $accessmanager->prevent_access() +
            $accessmanager->prevent_new_attempt(count($attempts), $lastattempt);

        if ($page == -1) {
            $page = 0;
        }
    }
    return array($currentattemptid, $attemptnumber, $lastattempt, $messages, $page);
}

// Sử dụng API có sẵn mod_quiz_start_attempt để thay thế cho hàm xử lý quiz_prepare_and_start_new_attempt trong startattempt.php
function get_remote_quiz_start_attempt($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_start_attempt',
            'params' => array('quizid' => $quizid)
        )
    );
}

function get_remote_attempt_by_attemptid($attemptid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_attempt_by_attemptid',
            'params' => array('attemptid' => $attemptid)
        )
    );
}

function get_remote_load_questions_usage_by_activity($unique) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_load_questions_usage_by_activity',
            'params' => array('unique' => $unique)
        )
    );
}

function get_remote_get_slots_by_quizid($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_slots_by_quizid',
            'params' => array('quizid' => $quizid)
        )
    );
}

function get_remote_get_sections_by_quizid($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_sections_by_quizid',
            'params' => array('quizid' => $quizid)
        )
    );
}