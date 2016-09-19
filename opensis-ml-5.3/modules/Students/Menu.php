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
$menu['Students']['admin'] = array(
						'Students/Student.php'=>''._('Student Info').'',
						'Students/Student.php&include=General_Info&student_id=new'=>''._('Add a Student').'',
						'Students/AssignOtherInfo.php'=>''._('Group Assign Student Info').'',
						'Students/AddUsers.php'=>''._('Associate Parents with Students').'',
                                                'Students/StudentReenroll.php'=>''._('Student').' '._('Re Enroll').'',
						1=>''._('Reports').'',
						'Students/AdvancedReport.php'=>''._('Advanced Report').'',
						'Students/AddDrop.php'=>''._('Add')._(' / ')._('Drop Report').'',
						'Students/Letters.php'=>''._('Print Letters').'',
						'Students/MailingLabels.php'=>''._('Print Mailing Labels').'',
						'Students/StudentLabels.php'=>''._('Print Student Labels').'',
						'Students/PrintStudentInfo.php'=>''._('Print Student Info').'',
                        'Students/PrintStudentContactInfo.php'=>''._('Print Student Contact Info').'',
                        'Students/GoalReport.php'=>''._('Print Goals & Progresses').'',
                        'Students/EnrollmentReport.php'=>''._('Student Enrollment Report').'',
						2=>''._('Setup').'',
						'Students/StudentFields.php'=>''._('Student Fields').'',
						#'Students/AddressFields.php'=>'Address Fields',
						#'Students/PeopleFields.php'=>'Contact Fields',
						'Students/EnrollmentCodes.php'=>''._('Enrollment Codes').'',
						'Students/Upload.php'=>''._('Upload Student Photo').'',
						'Students/Upload.php?modfunc=edit'=>''._('Update Student Photo').''
					);

$menu['Students']['teacher'] = array(
						'Students/Student.php'=>''._('Student Info').'',
						'Students/AddUsers.php'=>''._('Associated Parents').'',
						1=>''._('Reports').'',
						'Students/AdvancedReport.php'=>''._('Advanced Report').'',
						'Students/StudentLabels.php'=>''._('Print Student Labels').''
					);

$menu['Students']['parent'] = array(
						'Students/Student.php'=>''._('Student Info').'',
						'Students/ChangePassword.php'=>''._('Change Password').''
					);

$exceptions['Students'] = array(
						'Students/Student.php?include=General_Info?student_id=new'=>true,
						'Students/AssignOtherInfo.php'=>true
					);
?>
