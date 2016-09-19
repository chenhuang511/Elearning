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
if($_REQUEST['modfunc']=='save')
{
	if(count($_REQUEST['st_arr']))
	{
	$st_list = '\''.implode('\',\'',$_REQUEST['st_arr']).'\'';
	$extra['WHERE'] = ' AND s.STUDENT_ID IN ('.$st_list.')';
	$extra['FROM']=' ,student_contacts sc';
                  $extra['SELECT']=' ,sc.CONTACT_TYPE,sc.RELATION,CONCAT(Relation_Last_Name," " ,Relation_First_Name) AS RELATION_NAME,sc.ADDRESS1,sc.ADDRESS2,sc.CITY,sc.STATE,sc.ZIP,sc.WORK_PHONE,sc.HOME_PHONE,sc.CELL_PHONE,sc.EMAIL_ID';
                  $extra['WHERE'] .=' AND sc.student_id=ssm.student_id';
                  $extra['WHERE'] .=' AND sc.student_id=ssm.student_id';
                  $extra['ORDER'] =' ,sc.sort';

                $RET = GetStuList($extra);
  
	if(count($RET))
	{
                        $column_name=array('STUDENT_ID'=>''._('Student ID').'','ALT_ID'=>''._('Alternate ID').'','FULL_NAME'=>''._('Student').'','CONTACT_TYPE'=>''._('Type').'','RELATION'=>''._('Relation').'','RELATION_NAME'=>''._("Relation")._("'s")._(' ')._('Name').'','ADDRESS1'=>''._('Address')._(' 1').'','ADDRESS2'=>''._('Address')._(' 2').'','CITY'=>''._('City').'','STATE'=>''._('State').'','ZIP'=>''._('Zip').'','WORK_PHONE'=>''._('Work Phone').'','HOME_PHONE'=>''._('Home Phone').'','CELL_PHONE'=>''._('Cell Phone').'','EMAIL_ID'=>''._('Email Address').'');
                        $singular=''._('Student Contact').'';
                        $plural=''._('Student')._(' ')._('Contacts').'';
                        $options=array('search' => false);
						echo '<div style="overflow:auto; width:820px; overflow-x:scroll;">';
                        ListOutput($RET, $column_name,$singular,$plural,$link=false,$group=false,$options);
						echo '</div>';
	}
	else{
		ShowErrPhp(_('No Contacts were found.'));
                                    for_error();
                        }
	}
	else{
		ShowErrPhp(_('You must choose at least one student.'));
                                    for_error();
                        }
	unset($_SESSION['student_id']);
	//echo '<pre>'; var_dump($_REQUEST['modfunc']); echo '</pre>';
	$_REQUEST['modfunc']=true;
}

if(!$_REQUEST['modfunc'])
{
	DrawBC(""._('Students')." >> ".ProgramTitle());

	if($_REQUEST['search_modfunc']=='list')
	{
		echo "<FORM action=Modules.php?modname=$_REQUEST[modname]&modfunc=save&include_inactive=$_REQUEST[include_inactive]&_search_all_schools=$_REQUEST[_search_all_schools] method=POST>";
		//$extra['header_right'] = '<INPUT type=submit value=\'Print Info for Selected Students\'>';

	}

	$extra['link'] = array('FULL_NAME'=>false);
	$extra['SELECT'] = ',s.STUDENT_ID AS CHECKBOX';
	$extra['functions'] = array('CHECKBOX'=>'_makeChooseCheckbox');
	$extra['columns_before'] = array('CHECKBOX'=>'</A><INPUT type=checkbox value=Y name=controller checked onclick="checkAll(this.form,this.form.controller.checked,\'st_arr\');"><A>');
	$extra['options']['search'] = false;
	$extra['new'] = true;

//	Widgets('mailing_labels');
//	Widgets('course');
//	Widgets('request');
//	Widgets('activity');
//	Widgets('absences');
//	Widgets('gpa');
//	Widgets('class_rank');
//	Widgets('letter_grade');
//	Widgets('eligibility');

	Search('student_id',$extra);
	if($_REQUEST['search_modfunc']=='list')
	{
		echo '<BR><CENTER><INPUT type=submit class=btn_xxlarge value=\''._('Print Contact Info for Selected Students').'\'></CENTER>';
		echo "</FORM>";
	}
}

// GetStuList by default translates the grade_id to the grade title which we don't want here.
// One way to avoid this is to provide a translation function for the grade_id so here we
// provide a passthru function just to avoid the translation.

function _makeChooseCheckbox($value,$title)
{
	return '<INPUT type=checkbox name=st_arr[] value='.$value.' checked>';
}
?>
