<?php
#**************************************************************************
#  openSIS is a free student information system for public and non-public 
#  schools from Open Solutions for Education, Inc. web: www.os4ed.com
#
#  openSIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding this system or the license, please send 
#  an email to info@os4ed.com.
#
#  This program is released under the terms of the GNU General Public License as  
#  published by the Free Software Foundation, version 2 of the License. 
#  See license.txt.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#***************************************************************************************
include('../../Redirect_modules.php');
$menu['Attendance']['admin'] = array(
						'Attendance/Administration.php'=>''._('Administration').'',
						'Attendance/AddAbsences.php'=>''._('Add Absences').'',
						1=>''._('Reports').'',
						'Attendance/AttendanceData.php?list_by_day=true'=>''._('Attendance Report').'',
						'Attendance/Percent.php'=>''._('Average Daily Attendance').'',
						'Attendance/Percent.php?list_by_day=true'=>''._('Average Attendance by Day').'',
						'Attendance/DailySummary.php'=>''._('Attendance Chart').'',
						'Attendance/StudentSummary.php'=>''._('Absence Summary').'',
						'Attendance/TeacherCompletion.php'=>''._('Teacher Completion').'',
						2=>''._('Utilities').'',
						'Attendance/FixDailyAttendance.php'=>''._('Recalculate Daily Attendance').'',
						'Attendance/DuplicateAttendance.php'=>''._('Delete Duplicate Attendance').'',
						3=>''._('Setup').'',
						'Attendance/AttendanceCodes.php'=>''._('Attendance Codes').''
					);

$menu['Attendance']['teacher'] = array(
						'Attendance/TakeAttendance.php'=>''._('Take Attendance').'',
						'Attendance/DailySummary.php'=>''._('Attendance Chart').'',
						'Attendance/StudentSummary.php'=>''._('Absence Summary').''
					);

$menu['Attendance']['parent'] = array(
						'Attendance/StudentSummary.php'=>''._('Absences').'',
						'Attendance/DailySummary.php'=>''._('Daily Summary').''
					);

$menu['Users']['admin'] += array(
						'Users/TeacherPrograms.php?include=Attendance/TakeAttendance.php'=>''._('Take Attendance').'',
						'Users/TeacherPrograms.php?include=Attendance/Missing_Attendance.php'=>''._('Missing Attendance').''
					);

$exceptions['Attendance'] = array(
						'Attendance/AddAbsences.php'=>true
					);
?>
