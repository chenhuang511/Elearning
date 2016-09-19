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
$menu['Users']['admin'] = array(
						'Users/User.php'=>_('User Info'),
						'Users/User.php&staff_id=new'=>_('Add a User'),
						'Users/AddStudents.php'=>_('Associate Students with Parents'),
						'Users/Preferences.php'=>''._('Preferences').'',
                                                1=>_('Report'),
                                                'Users/UserAdvancedReport.php'=>_('Advanced Report'),
						2=>_('Setup'),
						'Users/Profiles.php'=>_('Profiles'),
						'Users/Exceptions.php'=>_('User Permissions'),
						'Users/UserFields.php'=>_('User Fields'),
                                                'Users/UploadUserPhoto.php'=>_("Upload Staff's Photo"),
						'Users/UploadUserPhoto.php?modfunc=edit'=>_("Update Staff's Photo"),
						3=>_('Teacher Programs'),
                                                
					);

$menu['Users']['teacher'] = array(
						'Users/User.php'=>_('General Info'),
						'Users/Preferences.php'=>_('Preferences')
					);

$menu['Users']['parent'] = array(
						'Users/User.php'=>_('General Info'),
						'Users/Preferences.php'=>_('Preferences')
					);

$exceptions['Users'] = array(
						'Users/User.php?staff_id=new'=>true
					);
?>