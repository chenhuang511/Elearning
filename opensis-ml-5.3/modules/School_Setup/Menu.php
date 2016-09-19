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
$menu['School_Setup']['admin'] = array(
						'School_Setup/PortalNotes.php'=>''._('Portal Notes').'',
						'School_Setup/MarkingPeriods.php'=>''._('Marking Periods').'',
						'School_Setup/Calendar.php'=>''._('Calendars').'',
						'School_Setup/Periods.php'=>''._('Periods').'',
						'School_Setup/GradeLevels.php'=>''._('Grade Levels').'',
                         1=>''._('School').'',
                        'School_Setup/Schools.php'=>''._('School Information').'',
                                                'School_Setup/UploadLogo.php'=>''._('Upload School Logo'),
						'School_Setup/Schools.php?new_school=true'=>''._('Add a School').'',
						'School_Setup/CopySchool.php'=>''._('Copy School').'',
						'School_Setup/system_preference.php'=>''._('System Preference').'',
                         2=>''._('Courses').'',
                        'School_Setup/Courses.php'=>''._('Course Manager').'',
                        'School_Setup/CourseCatalog.php'=>''._('Course Catalog').'',
                        'School_Setup/PrintCatalog.php'=>''._('Print Catalog by Term').'', 
                        'School_Setup/PrintCatalogGradeLevel.php'=>''._('Print Catalog by Grade Level').'', 
                        'School_Setup/PrintAllCourses.php'=>''._('Print all Courses').'',
                        'School_Setup/TeacherReassignment.php'=>''._('Teacher Re- Assignment').''
					);

$menu['School_Setup']['teacher'] = array(
						'School_Setup/Schools.php'=>''._('School Information').'',
						'School_Setup/MarkingPeriods.php'=>''._('Marking Periods').'',
						'School_Setup/Calendar.php'=>''._('Calendar').'',
						1=>''._('Courses').'',
                        'School_Setup/Courses.php'=>''._('Course Manager').'',
                        'School_Setup/CourseCatalog.php'=>''._('Course Catalog').'',
                        'School_Setup/PrintCatalog.php'=>''._('Print Catalog by Term').'', 
                        'School_Setup/PrintAllCourses.php'=>''._('Print all Courses').''
					);

$menu['School_Setup']['parent'] = array(
						'School_Setup/Schools.php'=>''._('School Information').'',
						'School_Setup/Calendar.php'=>''._('Calendar').''
					);

$exceptions['School_Setup'] = array(
						'School_Setup/PortalNotes.php'=>true,
						'School_Setup/Schools.php?new_school=true'=>true,
						'School_Setup/Rollover.php'=>true
					);
?>
