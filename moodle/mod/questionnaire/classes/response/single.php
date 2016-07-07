<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains the parent class for questionnaire question types.
 *
 * @author Mike Churchward
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package questiontypes
 */

namespace mod_questionnaire\response;
defined('MOODLE_INTERNAL') || die();

/**
 * Class for single response types.
 *
 * @author Mike Churchward
 * @package responsetypes
 */

class single extends base {
    public function response_table() {
        return 'questionnaire_resp_single';
    }

    public function insert_response($rid, $val) {
        global $DB;
        if (!empty($val)) {
            foreach ($this->question->choices as $cid => $choice) {
                if (strpos($choice->content, '!other') === 0) {
                    $other = optional_param('q'.$this->question->id.'_'.$cid, null, PARAM_CLEAN);
                    if (!isset($other)) {
                        continue;
                    }
                    if (preg_match("/[^ \t\n]/", $other)) {
                        $data = array();
                        $data['data[0][name]'] = 'response_id';
                        $data['data[0][value]'] = $rid;
                        $data['data[1][name]'] = 'question_id';
                        $data['data[1][value]'] = $this->question->id;
                        $data['data[2][name]'] = 'choice_id';
                        $data['data[2][value]'] = $cid;
                        $data['data[3][name]'] = 'response';
                        $data['data[3][value]'] = $other;
                        $resid = save_remote_response_by_mbl('questionnaire_response_other', $data);
                        $val = $cid;
                        break;
                    }
                }
            }
        }
        if (preg_match("/other_q([0-9]+)/", (isset($val) ? $val : ''), $regs)) {
            $cid = $regs[1];
            if (!isset($other)) {
                $other = optional_param('q'.$this->question->id.'_'.$cid, null, PARAM_CLEAN);
            }
            if (preg_match("/[^ \t\n]/", $other)) {
                $data = array();
                $data['data[0][name]'] = 'response_id';
                $data['data[0][value]'] = $rid;
                $data['data[1][name]'] = 'question_id';
                $data['data[1][value]'] = $this->question->id;
                $data['data[2][name]'] = 'choice_id';
                $data['data[2][value]'] = $cid;
                $data['data[3][name]'] = 'response';
                $data['data[3][value]'] = $other;
                $resid = save_remote_response_by_mbl('questionnaire_response_other', $data);
                $val = $cid;
            }
        }
        $data = array();
        $data['data[0][name]'] = 'response_id';
        $data['data[0][value]'] = $rid;
        $data['data[1][name]'] = 'question_id';
        $data['data[1][value]'] = $this->question->id;
        $data['data[2][name]'] = 'choice_id';
        $data['data[2][value]'] = isset($val) ? $val : 0;
        if ($data['data[2][value]']) {// If "no answer" then choice_id is empty (CONTRIB-846).
            return save_remote_response_by_mbl($this->response_table(), $data);
        } else {
            return false;
        }
    }

    protected function get_results($rids=false) {
        global $DB;

        $rsql = '';
        $params = array($this->question->id);
        if (!empty($rids)) {
            list($rsql, $rparams) = $DB->get_in_or_equal($rids);
            $params = array_merge($params, $rparams);
			if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
				$rsql = ' AND response_id ' . $rsql;
			} else {
            	$rsql = ' AND response_id IN (' . implode(',',$rids) . ')';
			}
        }
        // Added qc.id to preserve original choices ordering.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
        	$sql = 'SELECT rt.id, qc.id as cid, qc.content ' .
               'FROM {questionnaire_quest_choice} qc, ' .
               '{'.$this->response_table().'} rt ' .
               'WHERE qc.question_id= ? AND qc.content NOT LIKE \'!other%\' AND ' .
                     'rt.question_id=qc.question_id AND rt.choice_id=qc.id' . $rsql . ' ' .
               'ORDER BY qc.id';
			$rows = $DB->get_records_sql($sql, $params);
		} else {
	        $sql_select = 'qc.question_id= '.$this->question->id.' AND ' .
	                     'rt.question_id=qc.question_id AND qc.content NOT LIKE \'!other%\' AND rt.choice_id=qc.id' . $rsql;
	        $sql_sort = "qc.id";
	        $rows = get_remote_questionnaire_choice_single($sql_select, $sql_sort);
        }
		// Handle 'other...'.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
			$sql = 'SELECT rt.id, rt.response, qc.content ' .
               'FROM {questionnaire_response_other} rt, ' .
                    '{questionnaire_quest_choice} qc ' .
               'WHERE rt.question_id= ? AND rt.choice_id=qc.id' . $rsql . ' ' .
               'ORDER BY qc.id';
			$recs = $DB->get_records_sql($sql, $params);
		} else {
	        $sql_select = 'rt.question_id= '.$this->question->id.' AND rt.choice_id=qc.id' . $rsql;
	        $sql_sort = 'qc.id';
			$recs = get_remote_questionnaire_choice_other($sql_select, $sql_sort);
		}
        if ($recs) {
            $i = 1;
            foreach ($recs as $rec) {
                $rows['other'.$i] = new \stdClass();
                $rows['other'.$i]->content = $rec->content;
                $rows['other'.$i]->response = $rec->response;
                $i++;
            }
        }

        return $rows;
    }

    public function display_results($rids=false, $sort='') {
        $this->display_response_choice_results($this->get_results($rids), $rids, $sort);
    }

    /**
     * Return all the fields to be used for users in bulk questionnaire sql.
     *
     * @author: Guy Thomas
     * @return string
     */
    protected function user_fields_sql() {
        $userfieldsarr = get_all_user_name_fields();
        $userfieldsarr = array_merge($userfieldsarr, ['username', 'department', 'institution']);
        $userfields = '';
        foreach ($userfieldsarr as $field) {
            $userfields .= $userfields === '' ? '' : ', ';
            $userfields .= 'u.'.$field;
        }
        $userfields .= ', u.id as userid';
        return $userfields;
    }

    /**
     * Return sql and params for getting responses in bulk.
     * @author Guy Thomas
     * @param int $surveyid
     * @param bool|int $responseid
     * @param bool|int $userid
     * @return array
     */
    public function get_bulk_sql($surveyid, $responseid = false, $userid = false) {
        global $DB;

        $usernamesql = $DB->sql_cast_char2int('qr.username');

        $sql = $this->bulk_sql($surveyid, $responseid, $userid);
        $sql .= "
            AND qr.survey_id = ? AND qr.complete = ?
      LEFT JOIN {questionnaire_response_other} qro ON qro.response_id = qr.id AND qro.choice_id = qrs.choice_id
      LEFT JOIN {user} u ON u.id = $usernamesql
        ";
        $params = [$surveyid, 'y'];
        if ($responseid) {
            $sql .= " AND qr.id = ?";
            $params[] = $responseid;
        } else if ($userid) {
            $sql .= " AND qr.username = ?"; // Note: username is the userid.
            $params[] = $userid;
        }

        return [$sql, $params];
    }

    /**
     * Return sql for getting responses in bulk.
     * @author Guy Thomas
     * @return string
     */
    protected function bulk_sql() {
        global $DB;

        $userfields = $this->user_fields_sql();
        $extraselect = '';
        $extraselect .= 'qrs.choice_id, qro.response, 0 AS rank';
        $alias = 'qrs';

        return "
            SELECT " . $DB->sql_concat_join("'_'", ['qr.id', "'".$this->question->helpname()."'", $alias.'.id']) . " AS id,
                   qr.submitted, qr.complete, qr.grade, qr.username, $userfields, qr.id AS rid, $alias.question_id,
                   $extraselect
              FROM {questionnaire_response} qr
              JOIN {".$this->response_table()."} $alias ON $alias.response_id = qr.id
        ";
    }
}
