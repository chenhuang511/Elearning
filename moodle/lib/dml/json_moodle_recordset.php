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
 * Experimental pdo recordset
 *
 * @package    core_dml
 * @copyright  2016 nguyentran
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/moodle_recordset.php');

/**
 * Experimental pdo recordset
 *
 * @package    core_dml
 * @copyright  2008 Andrei Bautu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class json_moodle_recordset extends moodle_recordset {

    private $data;
    protected $currentIndex;

    public function __construct($data) {
        $this->data = $data;
        $this->currentIndex = 0;
    }

    public function __destruct() {
        $this->close();
    }

    private function fetch_next() {
        $this->currentIndex++;
        return $this->data[$this->currentIndex];
    }

    public function current() {
        return (object)$this->data[$this->currentIndex];
    }

    public function key() {
        // return first column value as key
        if (!isset($this->data[$this->currentIndex])) {
            return false;
        }
        $key = reset($this->data);
        return $key;
    }

    public function next() {
        $this->fetch_next();
    }

    public function valid() {
        return ($this->currentIndex >= 0 && $this->currentIndex < count($this->data));
    }

    public function close() {
    }
}
