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
$menu['Grades']['admin'] = array(
						'Grades/ReportCards.php'=>_('Report Cards'),
						'Grades/CalcGPA.php'=>_('Calculate GPA'),
						'Grades/Transcripts.php'=>_('Transcripts'),
						1=>_('Reports'),
						'Grades/TeacherCompletion.php'=>_('Teacher Completion'),
						'Grades/GradeBreakdown.php'=>_('Grade Breakdown'),
						'Grades/FinalGrades.php'=>_('Student Final Grades'),
						'Grades/GPARankList.php'=>_('GPA / Class Rank List'),
                                                'Grades/AdminProgressReports.php'=>_('Progress Reports'),
                        'Grades/HonorRoll.php'=>_('Honor Roll'),
						2=>_('Setup'),
						'Grades/ReportCardGrades.php'=>_('Report Card Grades'),
						'Grades/ReportCardComments.php'=>_('Report Card Comments'),
                                                'Grades/HonorRollSetup.php'=>_('Honor Roll Setup'),
                        'Grades/HonorRollSetup.php'=>_('Honor Roll Setup'),
						3=>_('Utilities'),
						'Grades/FixGPA.php'=>_('Recalculate GPA Numbers'),
                                                'Grades/EditReportCardGrades.php'=>_('Edit Report Card Grades'),
                                                'Grades/EditHistoryMarkingPeriods.php'=>''._('Add / Edit Historical Marking Periods').'',
                                                'Grades/HistoricalReportCardGrades.php'=>''._('Add / Edit Historical Report Card Grades').''
					);

$menu['Grades']['teacher'] = array(
						'Grades/InputFinalGrades.php'=>''._('Input Final Grades').'',
						'Grades/ReportCards.php'=>''._('Report Cards').'',
						1=>''._('Gradebook').'',
						'Grades/Grades.php'=>''._('Grades').'',
						'Grades/Assignments.php'=>''._('Assignments').'',
						'Grades/AnomalousGrades.php'=>''._('Anomalous Grades').'',
						'Grades/ProgressReports.php'=>''._('Progress Reports').'',
						2=>''._('Reports').'',
						'Grades/StudentGrades.php'=>''._('Student Grades').'',
						'Grades/FinalGrades.php'=>''._('Final Grades').'',
						3=>''._('Setup').'',
						'Grades/Configuration.php'=>''._('Configuration').'',
						'Grades/ReportCardGrades.php'=>''._('Report Card Grades').'',
						'Grades/ReportCardComments.php'=>''._('Report Card Comments').''
					);

$menu['Grades']['parent'] = array(
						'Grades/StudentGrades.php'=>''._('Gradebook Grades').'',
						'Grades/FinalGrades.php'=>''._('Final Grades').'',
						'Grades/ReportCards.php'=>''._('Report Cards').'',
                                                'Grades/ParentProgressReports.php'=>''._('Progress Reports').'',
						'Grades/Transcripts.php'=>''._('Transcripts').'',
						'Grades/GPARankList.php'=>''._('GPA / Class Rank').''
					);

$menu['Users']['admin'] += array(
						'Users/TeacherPrograms.php?include=Grades/InputFinalGrades.php'=>''._('Input Final Grades').'',
						'Users/TeacherPrograms.php?include=Grades/Grades.php'=>''._('Gradebook Grades').'',
                                                'Users/TeacherPrograms.php?include=Grades/ProgressReports.php'=>''._('Progress Reports').''
					);

$exceptions['Grades'] = array(
						'Grades/CalcGPA.php'=>true
					);
?>