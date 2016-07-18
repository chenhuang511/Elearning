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
 * Strings for component 'scorm', language 'vi', branch 'MOODLE_31_STABLE'
 *
 * @package   scorm
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['autocontinue_help'] = '<p><b>Tự động tiếp tục</b></p>

<p>Nếu Tự động tiếp tục được đặt là Có, khi một SCO
gọi phương thức "kết thúc trao đổi thông tin", SCO tiếp theo sẽ được phân phối
tự động đến cho học viên.</p>

<p>Nếu nó được đặt là Không, người dùng phải nhấn
vào nút "Tiếp tục" để học tiếp.</p>';
$string['firstaccess'] = 'Truy cập lần đầu tiên';
$string['grademethod_help'] = '<p><b>Phương pháp tính điểm</b></p>

<p>Kết quả của hoạt động SCORM/AICC hiển thị trong trang Điểm số có thể được đánh
giá theo một trong các cách sau:
    <ul>
	<li><b>Trạng thái SCO</b><br />Chế độ này hiển thị số SCO đã hoàn thành/đã đỗ của hoạt động. Giá trị lớn nhất là số lượng SCO.</li>
	<li><b>Điểm cao nhất</b><br />Trang điểm số sẽ hiển thị điểm cao nhất mà học viên đạt được trong tất cả các SCO đã đỗ.</li>
	<li><b>Điểm trung bình</b><br />Nếu bạn chọn chế độ này, Moodle sẽ tính trung bình tất cả các điểm.</li>
	<li><b>Điểm tổng</b><br />Với chế độ này tất cả các điểm sẽ được cộng lại.</li>
</p>';
$string['modulename'] = 'Gói SCORM';
$string['modulenameplural'] = 'Các gói SCORM';
$string['navigation'] = 'Điều hướng';
$string['package_help'] = '<p><b>Tập tin gói</b></p>

<p>Gói là một tập tin đặc biệt có phần mở rộng là <b>zip</b> (hoặc pif) bao gồm các
   tập tin định nghĩa khóa học AICC hoặc SCORM hợp lệ.</p>

<p>Một gói <b>SCORM</b> phải chứa ở gốc của tập tin zip một tập tin tên là <b>imsmanifest.xml</b>,
   là tập tin định nghĩa cấu trúc khóa học SCORM, vị trí tài nguyên và nhiều thứ khác nữa.</p>

<p>Một gói <b>AICC</b> được định nghĩa bởi vài tập tin (từ 4 đến 7) với phần mở rộng được định nghĩa trước.
   Bạn sẽ thấy ý nghĩa của các phần mở rộng ở đây:</p>
   <ul>
	<li>CRS - Tập tin Mô tả khóa học (bắt buộc)</li>
	<li>AU  - Tập tin Đơn vị khả chuyển (bắt buộc)</li>
	<li>DES - Tập tin Ký hiệu (bắt buộc)</li>
	<li>CST - Tập tin Cấu trúc khóa học(bắt buộc)</li>
	<li>ORE - Tập tin Quan hệ mục tiêu (không bắt buộc)</li>
	<li>PRE - Tập tin Điều kiện ban đầu (không bắt buộc)</li>
	<li>CMP - Tập tin Yêu cầu về tính hoàn thành (không bắt buộc)</li>
   </ul>';
$string['pluginname'] = 'Gói SCORM';
