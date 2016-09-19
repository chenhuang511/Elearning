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
error_reporting(0);
include('Redirect_root.php');
include("data.php");
$connection=mysql_connect($DatabaseServer,$DatabaseUsername,$DatabasePassword);
mysql_select_db($DatabaseName,$connection);
$log_msg=mysql_fetch_assoc(mysql_query("SELECT MESSAGE FROM login_message WHERE DISPLAY='Y'"));
//mysql_query("CALL SEAT_COUNT()");
Warehouse('header');
	echo '<link rel="stylesheet" type="text/css" href="styles/login.css?i='.rand(000, 99999).'>';
	echo '<script type="text/javascript" src="js/tabmenu.js"></script>';
	echo '<script type="text/javascript" src="assets/jquery-1.4.3.min.js"></script>';
	echo "
	<script type='text/javascript'>
	function delete_cookie (cookie_name)
		{
  			var cookie_date = new Date ( );
  			cookie_date.setTime ( cookie_date.getTime() - 1 );
			  document.cookie = cookie_name += \"=; expires=\" + cookie_date.toGMTString();
		}

</script>";
?>
<script type="text/ecmascript">
function showLangPanel(){

$('#LangPanel').fadeIn('slow', function() {
  });
$('#blackOverlay').fadeIn('slow', function() {
  });
       
}

function CloseLangPanel(lang,short_code){
	$('#LangPanel').fadeOut('slow', function() {
  	});
	$('#blackOverlay').fadeOut('slow', function() {
  	});
	//alert(lang);
	document.getElementById('textboxLanguage').value = lang;
	document.getElementById('LanguageCode').value = short_code;
	
}


function CloseLangPanel2(){
	$('#LangPanel').fadeOut('slow', function() {
  	});
	$('#blackOverlay').fadeOut('slow', function() {
  	});
	//alert(lang);
                
}
function set_cookie_check()
{
    var option=document.getElementById('remember_me_checkbox');
    if(option.checked==false)
    document.getElementById('remember_me').value='N';
    if(option.checked==true)
    document.getElementById('remember_me').value='Y';

}
</script>
<?php
	echo "<BODY onLoad='document.loginform.USERNAME.focus();  delete_cookie(\"dhtmlgoodies_tab_menu_tabIndex\");'>";
	echo '<div class="language_bg">';
	echo '<div id="blackOverlay" onclick="CloseLangPanel2()"></div>';
	
	echo "
	<form name=loginform method='post' action='index.php'>
	<table width='100%' height='100%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td valign='middle' height='100%'><table class='wrapper' border='0' width='689' cellspacing='0' cellpadding='0' align='center'>
        
        <tr>
          <td class='header'>
		  	  <table width='100%' border='0' cellspacing='0' cellpadding='0' class='logo_padding'>
              <tr>
                <td><img src='assets/osis_logo.png' border='0' /></td>
                <td align='right' style=\"padding-right:15px;\"><a href='http://www.os4ed.com' target=_blank ><img src='assets/os4ed_logo.png' height='62' width='66' border='0'/></a></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td class='content'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                      <td class='header_padding'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td class='header_txt'>Student Information System</td>
                          </tr>
                        </table></td>
                    </tr>";
					if($_REQUEST['maintain']=='Y'){
				echo "<tr><td align='center' style='color:red'><b>openSIS is under maintenance and login privileges have been turned off. Please log in when it is available again.</b></td></tr>";
					}
                    echo "<tr>
                      <td class='padding'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr>
                            <td>
                
				<table border='0' width='100%' cellspacing='2' cellpadding='2' align=center>
                 
                  <tr>
                    <td width='30%' align='right'>Username :</td>
                    <td><input name='USERNAME' value='".$_COOKIE['remember_me_username']."' type='text' class='input_bg'></td>
                  </tr>
                  <tr>
                    <td align='right' class='login_txt'>Password :</td>
                    <td><input name='PASSWORD'  value='".$_COOKIE['remember_me_password']."' type='password'  class='input_bg'></td>
                  </tr>
                   <tr>
                    <td align='right' class='login_txt'>Language Preference:</td>";
                    
                    if($_COOKIE['remember_me_langname']!="")
                    {
                        $val=$_COOKIE['remember_me_langname'];
                    }
                    elseif ($_COOKIE['lang']!="") {
                          $val=$_COOKIE['full_name'];
                    }
                    else
                    {
                         $val="English (en_EN)";
                    }
                    echo "

                    <td><div class='fake_ddl' onclick='showLangPanel()'><input class='input_bg_transparent' id=\"textboxLanguage\" type='text' name='langname' onfocus=\"showLangPanel()\" value=\"$val\"/>
						<input id=\"LanguageCode\" name=\"LanguageCode\" type='hidden' /></div>
						<div id='LangPanel'>
							<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"tableHead\"><tr><th>Select a Language</th><td clign=\"right\"><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel2('')\" class=\"btnClose\"></a></td></tr></table>
							<div class=\"languages\">
								<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
								<tr>
									<td width=\"25%\">
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Afrikaans','af_ZA')\">Afrikaans (af_ZA)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Albanian (sq_AL)','sq_AL')\">Albanian (sq_AL)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Arabic (ar)','ar')\">Arabic (ar)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Belarusian (be)','be')\">Belarusian (be)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Bengali (bn)','bn')\">Bengali (bn)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Bulgarian (bg)','bg')\">Bulgarian (bg)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Catalan (ca)','ca')\">Catalan (ca)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Chinese (zh_CN)','zh_CN')\">Chinese (zh_CN)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Chinese (zh_TW)','zh_TW')\">Chinese (zh_TW)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Croatian (hr)','hr')\">Croatian (hr)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Czech (cs)','cs')\">Czech (cs)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Danish (da)','da')\">Danish (da)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Dutch (nl)','nl')\">Dutch (nl)</a></p>
									</td>
									<td width=\"25%\">
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('English (en_EN)','en_EN')\">English (en_EN)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Estonian (et)','et')\">Estonian (et)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Finnish (fi)','fi')\">Finnish (fi)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('French (fr)','fr')\">French (fr)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Galician (gl)','gl')\">Galician (gl)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('German (de)','de')\">German (de)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Greek (el)','el')\">Greek (el)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Hebrew (he)','he')\">Hebrew (he)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Hindi (hi)','hi')\">Hindi (hi)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Hungarian (hu)','hu')\">Hungarian (hu)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Icelandic (is)','is')\">Icelandic (is)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Indonesian (id)','id')\">Indonesian (id)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Irish (ga)','ga')\">Irish (ga)</a></p>
									</td>
									<td width=\"25%\">
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Italian (it)','it')\">Italian (it)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Japanese (ja)','ja')\">Japanese (ja)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Korean (ko)','ko')\">Korean (ko)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Latvian (lv)','lv')\">Latvian (lv)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Lithuanian (lt)','lt')\">Lithuanian (lt)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Macedonian (mk)','mk')\">Macedonian (mk)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Malay (ms)','ms')\">Malay (ms)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Norwegian Nynorsk (nn)','nn')\">Norwegian Nynorsk (nn)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Persian (fa_IR)','fa_IR')\">Persian (fa_IR)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Polish (pl)','pl')\">Polish (pl)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Portuguese (pt_PT)','pt_PT')\">Portuguese (pt_PT)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Romanian (ro)','ro')\">Romanian (ro)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Russian (ru)','ru')\">Russian (ru)</a></p>
									</td>
									<td>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Serbian (sr)','sr')\">Serbian (sr)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Slovak (sk)','sk')\">Slovak (sk)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Slovenian (sl)','sl')\">Slovenian (sl)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Spanish (es)','es')\">Spanish (es)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Swedish (sv)','sv')\">Swedish (sv)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Thai (th)','th')\">Thai (th)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Turkish (tr)','tr')\">Turkish (tr)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Ukrainian (uk)','uk')\">Ukrainian (uk)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Vietnamese (vi)','vi')\">Vietnamese (vi)</a></p>
										<p><a href=\"javascript:void(0)\" onclick=\"CloseLangPanel('Walloon (wa)','wa')\">Walloon (wa)</a></p>
										<p></p>
										<p></p>
									</td>
								</tr>
								</table>
							</div>
						</div>
					</td>
                  </tr>
				  <tr><td colspan=2 align=right>
				  " ;
                    
				  if($_REQUEST['reason'])
				$note[] = 'You must have javascript enabled to use openSIS.';
				echo ErrorMessage($error,'Error');
                                if($_COOKIE['remember_me_username']!='')
                                 $checked='checked=checked'; 
                                else
                                 $checked='';   
				  echo "
				  </td></tr>
				  <tr>
                    <td></td>
                    <td>
					<table width='99%' height='100%' border='0' cellspacing='0' cellpadding='0'>
					<tr>
					<td><input type='checkbox' $checked id='remember_me_checkbox' name='remember_me_checkbox' onClick='set_cookie_check();' /> Remember Me</td>
                                        <td><input type='hidden' id='remember_me' name=remember_me /></td>
                    <td><input name='' type='submit' class='login' value='' onMouseDown=Set_Cookie('dhtmlgoodies_tab_menu_tabIndex','',-1) /></td>
                    </tr></table>
					</td>
                  </tr>
				  </table>
				  </td>
                          </tr>
                          <tr>
                            <td align='center' class=\"message\"><p style='padding:6px;'>".$log_msg['MESSAGE']."</p></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
              </tr>
            </table>
        <tr>
          <td class='footer' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='margin'></td>
              </tr>
              <tr>
                <td align='center' class='copyright'>
                openSIS is a product of Open Solutions for Education, Inc.(<a href='http://www.os4ed.com' target='_blank'>OS4Ed</a>).
                and is licensed under the <a href='http://www.gnu.org/licenses/gpl.html' target='_blank'>GPL License</a>.
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</td>
</tr>
</table></form>
</div>
";

	Warehouse("footer");
?>
