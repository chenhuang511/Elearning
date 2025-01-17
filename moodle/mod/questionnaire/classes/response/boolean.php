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

use mod_questionnaire\db\bulk_sql_config;

/**
 * Class for boolean response types.
 *
 * @author Mike Churchward
 * @package response
 */

class boolean extends base {

    public function response_table() {
        return 'questionnaire_response_bool';
    }

    public function insert_response($rid, $val) {
        global $DB;
        if (!empty($val)) { // If "no answer" then choice is empty (CONTRIB-846).
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $record = new \stdClass();
                $record->response_id = $rid;
                $record->question_id = $this->question->id;
                $record->choice_id = $val;
                return $DB->insert_record($this->response_table(), $record);
            } else {
                $data = array();
                $data['data[0][name]'] = 'response_id';
                $data['data[0][value]'] = $rid;
                $data['data[1][name]'] = 'question_id';
                $data['data[1][value]'] = $this->question->id;
                $data['data[2][name]'] = 'choice_id';
                $data['data[2][value]'] = $val;
                return save_remote_response_by_tbl($this->response_table(), $data);
            }
        } else {
            return false;
        }
    }

    protected function get_results($rids=false) {
        global $DB;

        $rsql = '';
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $params = array($this->question->id);
            if (!empty($rids)) {
                list($rsql, $rparams) = $DB->get_in_or_equal($rids);
                $params = array_merge($params, $rparams);
                $rsql = ' AND response_id ' . $rsql;
            }
            $params[] = '';

            $sql = 'SELECT choice_id, COUNT(response_id) AS num ' .
                'FROM {'.$this->response_table().'} ' .
                'WHERE question_id= ? ' . $rsql . ' AND choice_id != ? ' .
                'GROUP BY choice_id';
            return $DB->get_records_sql($sql, $params);
        } else {
            if (!empty($rids)) {
                $rsql = implode(',', $rids);
                $rsql = ' AND response_id IN (' . $rsql . ')';
            }
            $sql_select = 'question_id= ' . $this->question->id . $rsql;
            return get_remote_questionnaire_bool_count_choice($sql_select);
        }
    }

    public function display_results($rids=false, $sort='') {
        if (empty($this->stryes)) {
            $this->stryes = get_string('yes');
            $this->strno = get_string('no');
        }

        if (is_array($rids)) {
            $prtotal = 1;
        } else if (is_int($rids)) {
            $prtotal = 0;
        }

         $this->counts = array($this->stryes => 0, $this->strno => 0);
        if ($rows = $this->get_results($rids)) {
            foreach ($rows as $row) {
                $this->choice = $row->choice_id;
                $count = $row->num;
                if ($this->choice == 'y') {
                    $this->choice = $this->stryes;
                } else {
                    $this->choice = $this->strno;
                }
                $this->counts[$this->choice] = intval($count);
            }
            \mod_questionnaire\response\display_support::mkrespercent($this->counts, count($rids),
                $this->question->precise, $prtotal, $sort = '');
        } else {
            echo '<p class="generaltable">&nbsp;'.get_string('noresponsedata', 'questionnaire').'</p>';
        }
    }

    /**
     * Configure bulk sql
     * @return bulk_sql_config
     */
    protected function bulk_sql_config() {
        return new bulk_sql_config($this->response_table(), 'qrb', true, false, false);
    }
}

