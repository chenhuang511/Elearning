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
$menu[('Scheduling')]['admin'] = array(
						'Scheduling/Schedule.php'=>''._('Student Schedule').'',
                                                'Scheduling/ViewSchedule.php'=>''._('ViewSchedule').'',
						'Scheduling/Requests.php'=>''._('Student Requests').'',
						'Scheduling/MassSchedule.php'=>''._('Group Schedule').'',
						'Scheduling/MassRequests.php'=>''._('Group Requests').'',
						'Scheduling/MassDrops.php'=>''._('Group Drops').'',
						1=>_('Reports'),
						'Scheduling/PrintSchedules.php'=>''._('Print Schedules').'',
						'Scheduling/PrintClassLists.php'=>''._('Print Class Lists').'',
						'Scheduling/PrintClassPictures.php'=>''._('Print Class Pictures').'',
						'Scheduling/PrintRequests.php'=>''._('Print Requests').'',
						'Scheduling/ScheduleReport.php'=>''._('Schedule Report').'',
						'Scheduling/RequestsReport.php'=>''._('Requests Report').'',
						'Scheduling/UnfilledRequests.php'=>''._('Unfilled Requests').'',
						'Scheduling/IncompleteSchedules.php'=>''._('Incomplete Schedules').'',
						'Scheduling/AddDrop.php'=>''._('Add / Drop Report').'',
						2=>_('Setup'),
						
						'Scheduling/Scheduler.php'=>''._('Run Scheduler').''
					);

$menu['Scheduling']['teacher'] = array(
						'Scheduling/Schedule.php'=>''._('Schedule').'',
                                                'Scheduling/ViewSchedule.php'=>''._('ViewSchedule').'',
						1=>''._('Reports').'',
						'Scheduling/PrintSchedules.php'=>''._('Print Schedules').'',
						'Scheduling/PrintClassLists.php'=>''._('Print Class Lists').'',
						'Scheduling/PrintClassPictures.php'=>''._('Print Class Pictures').''
					);

$menu['Scheduling']['parent'] = array(
						'Scheduling/ViewSchedule.php'=>''._('Schedule').'',
						'Scheduling/PrintClassPictures.php'=>''._('Class Pictures').'',
						'Scheduling/Requests.php'=>''._('Student Requests').'',
                        'Scheduling/StudentScheduleReport.php'=>''._('Schedule Report').''
					);

$exceptions['Scheduling'] = array(
						'Scheduling/Requests.php'=>true,
						'Scheduling/MassRequests.php'=>true,
						'Scheduling/Scheduler.php'=>true,
                        'Scheduling/StudentScheduleReport.php'=>''._('Schedule Report').''
					);
?>
