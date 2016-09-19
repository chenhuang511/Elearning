function loadajax(frmname)
{
  this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  alert("BUG: couldnot get Form object "+frmname);
		return;
	}
	if(this.formobj.onsubmit)
	{
	 this.formobj.old_onsubmit = this.formobj.onsubmit;
	 this.formobj.onsubmit=null;
	}
	else
	{
	 this.formobj.old_onsubmit = null;
	}
	this.formobj.onsubmit=ajax_handler;
	
}

function ajax_handler()
{
	if(ajaxform(this, this.action) =='failed')
	return true;
	
	return false;
}

function formload_ajax(frm){
		var frmloadajax  = new loadajax(frm);
}



var hand = function(str){
	window.document.getElementById('response_span').innerHTML=str;
}
/*function validateUsername(user){
	var strDomain='';
	window.document.getElementById('response_span').innerHTML="Validating username...";
	var valajax = new ValAjax();
	valajax.doGet(strDomain+'validator.php?action=validateUsername&username='+user,hand,'text');
}*/


function ajax_call (url, callback_function, error_function) {
	var xmlHttp = null;
	try {
		// for standard browsers
		xmlHttp = new XMLHttpRequest ();
	} catch (e) {
		// for internet explorer
		try {
			xmlHttp = new ActiveXObject ("Msxml2.XMLHTTP");
	    } catch (e) {
			xmlHttp = new ActiveXObject ("Microsoft.XMLHTTP");
	    }
	}
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState == 4)
			try {
				if (xmlHttp.status == 200) {
					
					callback_function (xmlHttp.responseText);
				}
			} catch (e) {
				
				error_function (e.description);
			}
	 }
	
	 xmlHttp.open ("GET", url);
	 xmlHttp.send (null);
 }
 // --------------------------------------------------- USER ----------------------------------------------------------------------------------- //
 
 function usercheck_init(i) {
	var obj = document.getElementById('ajax_output');
	obj.innerHTML = ''; 
	
	if (i.value.length < 1) return;
	
 	var err = new Array ();
	if (i.value.match (/[^A-Za-z0-9_]/)) err[err.length] = 'Username can only contain letters, numbers and underscores';
 	if (i.value.length < 3) err[err.length] = 'Username too short';
 	if (err != '') {
	 	obj.style.color = '#ff0000';
	 	obj.innerHTML = err.join ('<br />');
	 	return;
 	}
 	
	var pqr = i.value;
	
	
	ajax_call('validator.php?u='+i.value+'user', usercheck_callback, usercheck_error); 
 }
 
  function usercheck_callback (data) {
 	var response = (data == '1');

 	var obj = document.getElementById('ajax_output');
 	obj.style.color = (response) ? '#008800' : '#ff0000';
 	obj.innerHTML = (response == '1') ? 'Username OK' : 'Username already taken';
 }
 
  function usercheck_error (err) {
 	alert ("Error: " + err);
 }

// ------------------------------------------------------ USER ---------------------------------------------------------------------------------- //

// ------------------------------------------------------ Student ------------------------------------------------------------------------------ //

 function usercheck_init_student(i) {
	var obj = document.getElementById('ajax_output_st');
	obj.innerHTML = ''; 
	
	if (i.value.length < 1) return;
	
 	var err = new Array ();
	if (i.value.match (/[^A-Za-z0-9_]/)) err[err.length] = 'Username can only contain letters, numbers and underscores';
 	if (i.value.length < 3) err[err.length] = 'Username too short';
 	if (err != '') {
	 	obj.style.color = '#ff0000';
	 	obj.innerHTML = err.join ('<br />');
	 	return;
 	}
	ajax_call('validator.php?u='+i.value+'stud', usercheck_callback_student, usercheck_error_student); 
 }

 function usercheck_callback_student (data) {
 	var response = (data == '1');

 	var obj = document.getElementById('ajax_output_st');
 	obj.style.color = (response) ? '#008800' : '#ff0000';
 	obj.innerHTML = (response == '1') ? 'Username OK' : 'Username already taken';
 }

 function usercheck_error_student (err) {
 	alert ("Error: " + err);
 }

// ------------------------------------------------------ Student ------------------------------------------------------------------------------ //

// ------------------------------------------------------ Student ID------------------------------------------------------------------------------ //

 function usercheck_student_id(i) {
	var obj = document.getElementById('ajax_output_stid');
	obj.innerHTML = ''; 
	
	if (i.value.length < 1) return;
	
 	var err = new Array ();
	if (i.value.match (/[^0-9_]/)) err[err.length] = 'Student ID can only contain numbers';
 	
 	if (err != '') {
	 	obj.style.color = '#ff0000';
	 	obj.innerHTML = err.join ('<br />');
	 	return;
 	}
 	ajax_call ('validator_int.php?u='+i.value+'stid', usercheck_callback_student_id, usercheck_error_student_id); 
 }

 function usercheck_callback_student_id (data) {
 	var response = (data == '1');

 	var obj = document.getElementById('ajax_output_stid');
 	obj.style.color = (response) ? '#008800' : '#ff0000';
 	obj.innerHTML = (response == '1') ? 'Student ID OK' : 'Student ID already taken';
 }

 function usercheck_error_student_id (err) {
 	alert ("Error: " + err);
 }

// ------------------------------------------------------ Student ID------------------------------------------------------------------------------ //


//-----------------Take attn depends on period------------------------------------------------------

function formcheck_periods_attendance_F2(attendance)
{
           if(document.getElementById('cp_period'))
           {
                period_id = document.getElementById('cp_period').value;
           }
           else
           {
                period_id = 0;
           }
    var err = new Array ();
    if(attendance.checked)
        {
           var obj = document.getElementById('ajax_output');
           var period_id;
           
           var cp_id=document.getElementById('cp_id').value;
           obj.innerHTML = '';

           if (attendance.value.length < 1) return '';

                if (period_id.length ==0)
                    {
                    err[err.length] = _('Select Period');
                    document.getElementById('get_status').value = 'false';
                    }
                    else
                        err[err.length] ='';
                if (err != '') {
                        obj.style.color = '#ff0000';
                        obj.innerHTML = err.join ('<br />');
                        return;
                }
                var pqr = attendance.value;
                ajax_call('validator_attendance.php?u='+attendance.value+'&p_id='+period_id+'&cp_id='+cp_id, attendance_callback, attendance_error);
        }
        else
            {
                if (period_id.length ==0)
                    {
                        err[err.length] = _('Select Period');
                        document.getElementById('get_status').value = 'false';
                    }
                    else
                        err[err.length] ='';
                if (err != '') {
                        obj.style.color = '#ff0000';
                        obj.innerHTML = err.join ('<br />');
                        return;
                }
                if(err =='')
                {
           document.getElementById('ajax_output').innerHTML = '';
           document.getElementById('get_status').value ='';
            }
}
}

  function attendance_callback (data)
  {
       var response = (data == '1');
 	var obj = document.getElementById('ajax_output');
 	obj.style.color = (response) ? '#008800' : '#ff0000';
        obj.innerHTML = (response == '1' ? '' : 'Turn on attendance for the<br>period in School Setup &gt;&gt; Periods');
       if(response==false)
           {
         document.getElementById('get_status').value = response;
         document.getElementById('cp_does_attendance').checked=false;
           }
       else
        document.getElementById('get_status').value ='';
           }

  function attendance_error (err) {
// 	alert ("Error: " + err);
 }

function formcheck_periods_F2()
{
    if(!document.getElementById('cp_does_attendance') || (!document.getElementById('cp_does_attendance').checked))
    {
       var obj = document.getElementById('ajax_output');
       var period_id=document.getElementById('cp_period').value;
       var cp_id=document.getElementById('cp_id').value;
       var err = new Array ();
       if (period_id.length ==0)
       {
            err[err.length] = _('Select Period');
            document.getElementById('get_status').value = 'false';
       }
       else
           err[err.length]='';
       if(err =='')
           {
           document.getElementById('ajax_output').innerHTML = '';
           document.getElementById('get_status').value ='';
           }
       if (err != '')
       {
            obj.style.color = '#ff0000';
            obj.innerHTML = err.join ('<br />');
            return;
       }
       if(!document.getElementById('cp_does_attendance'))
       ajax_call('validator_attendance.php?u=N&p_id='+period_id+'&cp_id='+cp_id, attendance_callback, attendance_error);
    }
    else
    {
      if(document.getElementById('cp_does_attendance').checked)
      {
        formcheck_periods_attendance_F2(document.getElementById('cp_does_attendance'));
      }
      else
        document.getElementById('get_status').value ='';
}
}

//----------------------------------------------------------------------


function ajax_call_modified(url,callback_function,error_function)
{
    var xmlHttp = null;
    try {
        xmlHttp = new XMLHttpRequest ();
    } catch (e) {
    try {
        xmlHttp = new ActiveXObject ("Msxml2.XMLHTTP");
    } catch (e) {
        xmlHttp = new ActiveXObject ("Microsoft.XMLHTTP");
    }
    }
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 1){
            try {
                document.getElementById('calculating').style.display="block";
            } catch (e) {
                error_function (e.description);
            }
        }
        if (xmlHttp.readyState == 4){
            try {
                if (xmlHttp.status == 200) {
                    callback_function(xmlHttp.responseText);
                }
            } catch (e) {
                error_function (e.description);
            }
        }
    }
    xmlHttp.open ("GET", url);
    xmlHttp.send (null);
}

//=========================================Missing Attendance===========================
function mi_callback(mi_data)
{
                    document.getElementById("resp").innerHTML=mi_data;
                    document.getElementById("calculating").style.display="none";
                    if(mi_data.search('NEW_MI_YES')!=-1)
                    {
                        document.getElementById("attn_alert").style.display="block"
                    }
}
function calculate_missing_atten()
{
     var url = "calculate_missing_attendance.php";
     ajax_call_modified(url,mi_callback,missing_attn_error);
}

function missing_attn_error(err)
{
    alert ("Error: " + err);
}
//-------------------------------------Missing Attendance end ------------------------------------------------

function recalculate_gpa(stu_all,mp)
{
     var url = 'recalculateprocess.php?students='+stu_all+'&mp='+mp;
     ajax_call_modified(url,re_gpa_callback,recal_gpa_error);
}
function re_gpa_callback(re_gpa_data)
{
                    document.getElementById("resp").innerHTML=re_gpa_data;
                    document.getElementById("calculating").style.display="none";
                    
}
function recal_gpa_error(err)
{
    alert ("Error: " + err);
}


function calculate_gpa(mp)
{
     var url = 'calculate_gpa_process.php?&mp='+mp;
     ajax_call_modified(url,gpa_callback,gpa_error);
}
function gpa_callback(re_gpa_data)
{
                    document.getElementById("resp").innerHTML=re_gpa_data;
                    document.getElementById("calculating").style.display="none";
                    
}
function gpa_error(err)
{
    alert ("Error: " + err);
}



function rollover_callback(roll_data)
{
    //alert(roll_data);
    var total_data;
    total_data=roll_data.split('|');
	var value=total_data[2];
	if(value==0)
	{
		var rollover_class='rollover_no';
	}
	else
	{
		var rollover_class='rollover_yes';		
	}
	
	if(total_data[0]=='Users'){
            document.getElementById("staff").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("staff").setAttribute("class", rollover_class);
            document.getElementById("staff").setAttribute("className", rollover_class);
            if(document.getElementById("chk_school_periods").value=='Y')
            {
            ajax_rollover('school_periods');
        }
            else
            {
                ajax_rollover('school_years');
            }
        }
        else if(total_data[0]=='School Periods')
        {
            document.getElementById("school_periods").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("school_periods").setAttribute("class", rollover_class);
            document.getElementById("school_periods").setAttribute("className", rollover_class);
            ajax_rollover('school_years');
        }

       else if(total_data[0]=='Marking Periods')
       {
            document.getElementById("school_years").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("school_years").setAttribute("class", rollover_class);
            document.getElementById("school_years").setAttribute("className", rollover_class);
            if(document.getElementById("chk_attendance_calendars").value=='Y')
            {
            ajax_rollover('attendance_calendars');
       }
            else if(document.getElementById("chk_report_card_grade_scales").value=='Y')
            {
                ajax_rollover('report_card_grade_scales');
            }
            else if(document.getElementById("chk_course_subjects").value=='Y')
            {
                ajax_rollover('course_subjects');
            }
            else if(document.getElementById("chk_courses").value=='Y')
            {
                ajax_rollover('courses');
            }
           else if(document.getElementById("chk_course_periods").value=='Y')
            {
                ajax_rollover('course_periods');
            }
            else
            {
                ajax_rollover('student_enrollment_codes');
            }
            
       }
       else if(total_data[0]=='Calendars')
       {
            document.getElementById("attendance_calendars").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("attendance_calendars").setAttribute("class", rollover_class);
            document.getElementById("attendance_calendars").setAttribute("className", rollover_class);
            ajax_rollover('report_card_grade_scales');
       }
       else if(total_data[0]=='Report Card Grade Codes')
       {
            document.getElementById("report_card_grade_scales").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("report_card_grade_scales").setAttribute("class", rollover_class);
            document.getElementById("report_card_grade_scales").setAttribute("className", rollover_class);
            if(document.getElementById('chk_course_subjects').value=='Y')
                ajax_rollover('course_subjects');
            else if(document.getElementById('chk_courses').value=='Y')
            ajax_rollover('courses');
            else if(document.getElementById('chk_course_periods').value=='Y')
                ajax_rollover('course_periods');
            else
                ajax_rollover('student_enrollment_codes');
       }
       else if(total_data[0]=='Subjects')
       {
            document.getElementById("course_subjects").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("course_subjects").setAttribute("class", rollover_class);
            document.getElementById("course_subjects").setAttribute("className", rollover_class);
            if(document.getElementById('chk_courses').value=='Y')
                ajax_rollover('courses');
            else if(document.getElementById('chk_course_periods').value=='Y')
                ajax_rollover('course_periods');
            else
                ajax_rollover('student_enrollment_codes');
       }
       else if(total_data[0]=='Courses')
       {
            document.getElementById("courses").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("courses").setAttribute("class", rollover_class);
            document.getElementById("courses").setAttribute("className", rollover_class);
            if(document.getElementById('chk_course_periods').value=='Y')
                ajax_rollover('course_periods');
            else
            ajax_rollover('student_enrollment_codes');
       }
        else if(total_data[0]=='Course Periods')
       {
            document.getElementById("course_periods").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("course_periods").setAttribute("class", rollover_class);
            document.getElementById("course_periods").setAttribute("className", rollover_class);
            ajax_rollover('student_enrollment_codes');
       }
        else if(total_data[0]=='Student Enrollment Codes')
       {
            document.getElementById("student_enrollment_codes").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("student_enrollment_codes").setAttribute("class", rollover_class);
            document.getElementById("student_enrollment_codes").setAttribute("className", rollover_class);
            ajax_rollover('student_enrollment');
       }
       else if(total_data[0]=='Students')
       {
            document.getElementById("student_enrollment").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("student_enrollment").setAttribute("class", rollover_class);
            document.getElementById("student_enrollment").setAttribute("className", rollover_class);
            if(document.getElementById("chk_honor_roll").value=='Y')
            {
            ajax_rollover('honor_roll');
       }
            else if(document.getElementById("chk_attendance_codes").value=='Y')
            {
                ajax_rollover('attendance_codes');
            }
            else if(document.getElementById("chk_report_card_comments").value=='Y')
            {
                ajax_rollover('report_card_comments');
            }
            else
            {
                ajax_rollover('NONE');
            }
       }
       else if(total_data[0]=='Honor Roll Setup')
       {
            document.getElementById("honor_roll").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("honor_roll").setAttribute("class", rollover_class);
            document.getElementById("honor_roll").setAttribute("className", rollover_class);
            if(document.getElementById("chk_attendance_codes").value=='Y')
            {
            ajax_rollover('attendance_codes');
       }
            else if(document.getElementById("chk_report_card_comments").value=='Y')
            {
                ajax_rollover('report_card_comments');
            }
            else
            {
                ajax_rollover('NONE');
            }
            
       }
       else if(total_data[0]=='Attendance Codes')
       {
            document.getElementById("attendance_codes").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("attendance_codes").setAttribute("class", rollover_class);
            document.getElementById("attendance_codes").setAttribute("className", rollover_class);
            
            if(document.getElementById("chk_report_card_comments").value=='Y')
            {
            ajax_rollover('report_card_comments');
       }
            else
            {
                ajax_rollover('NONE');
            }
       }

       else if(total_data[0]=='Report Card Comment Codes')
       {
            document.getElementById("report_card_comments").innerHTML=total_data[0]+" "+total_data[1]+" "+total_data[2]+" "+total_data[3];
            document.getElementById("report_card_comments").setAttribute("class", rollover_class);
            document.getElementById("report_card_comments").setAttribute("className", rollover_class);
            ajax_rollover('NONE');
       }
        else 
        {
            document.getElementById("response").innerHTML=roll_data;
            document.getElementById("calculating").style.display="none";
        }
}

function rollover_error(err)
{
    alert ("Error: " + err);
}

function ajax_rollover(roll_table)
{   
     var url = 'Rollover_shadow.php?table_name='+roll_table;
     ajax_call_modified(url,rollover_callback,rollover_error);
}
function formcheck_rollover()
{
    var start_month_len=document.getElementById("monthSelect1").value;
    var start_day_len=document.getElementById("daySelect1").value;
    var start_year_len=document.getElementById("yearSelect1").value;
    if(start_month_len=="" || start_day_len=="" || start_year_len=="")
    {     
        document.getElementById("start_date").innerHTML="Please Enter Start Date ";
        return false;
    }
//    var custom_dt=document.getElementById("custom_date").checked;
//    if(custom_dt==true)
    var custom_dt=document.getElementById("custom_date").value;
    if(custom_dt=="Y")
    {
    var prev_end_date_s=document.getElementById("prev_start_date").value;
    var prev_end_date=Date.parse(prev_end_date_s);
    var s_month_len=document.getElementById("monthSelect2").value;
    var s_day_len=document.getElementById("daySelect2").value;
    var s_year_len=document.getElementById("yearSelect2").value;

    var e_month_len=document.getElementById("monthSelect3").value;
    var e_day_len=document.getElementById("daySelect3").value;
    var e_year_len=document.getElementById("yearSelect3").value;

    if(s_month_len=="" || s_day_len=="" || s_year_len=="")
    {
    document.getElementById("start_date").innerHTML="Please Enter a Valid New Year's Begin Date";
    return false;   
    }
    if(e_month_len=="" || e_day_len=="" || e_year_len=="")
    {
    document.getElementById("start_date").innerHTML="Please Enter Valid New Year's End Date";
    return false;   
    }
    if(s_month_len!="" && s_day_len!="" && s_year_len!="" && e_month_len!="" && e_day_len!="" && e_year_len!="")
    {
        var s_start_s=s_year_len+'-'+s_month_len+'-'+s_day_len;
        var e_start_s=e_year_len+'-'+e_month_len+'-'+e_day_len;
        var s_start_dt=Date.parse(s_start_s);
        var s_end_dt=Date.parse(e_start_s);
        if(s_start_dt<=prev_end_date)
        {
        document.getElementById("start_date").innerHTML="New Year's Begin Date Has To Be After Previous Year's End Date";
        return false;   
        }
        else if(s_start_dt>=s_end_dt)
        {
        document.getElementById("start_date").innerHTML="New Year's End Date Has To Be After New Year's Start Date";
        return false;   
        }
        else
        {
            var tot_round=document.getElementById("tot_round");
            
            if(tot_round!=null)
            {
                tot_round=tot_round.value;
                tot_round=parseInt(tot_round);
                var prev_l_st=0;
                for(var i=1;i<=tot_round;i++)
                {
                    var l_st=document.getElementById("round_"+i).value;
                    l_st=parseInt(l_st);
                    var l_st_m=l_st-1;
                    var l_en=document.getElementById("roll_"+i).value;
                    l_en=parseInt(l_en);
                    ///////Checking semesters////////////////////////
                    for(var j=l_st_m;j<=l_st;j++)
                    {
                    var s_month=document.getElementById("monthSelect"+j).value;
                    var s_day=document.getElementById("daySelect"+j).value;
                    var s_year=document.getElementById("yearSelect"+j).value;
                    var sem_dt=s_year+'-'+s_month+'-'+s_day;
                    var sem_name=document.getElementById("name_"+j).value;
                        if(s_month=="" || s_day=="" || s_year=="")
                        {
                         document.getElementById("start_date").innerHTML="Please Enter Valid "+sem_name;
                         return false;   
                        }
                        else
                        {
                         sem_dt=Date.parse(sem_dt); 
                         if(sem_dt<s_start_dt)
                         {
                          document.getElementById("start_date").innerHTML=sem_name+" Cannot Be Before School's Begin Date";
                          return false;      
                         }
                         if(sem_dt>s_end_dt)
                         {
                          document.getElementById("start_date").innerHTML=sem_name+" Cannot Be Be After School's End Date";
                          return false;   
                         }
                         else
                         {
                            
                            if(j!=l_st_m)
                            {
                            var j_p=j-1;
                            var s_p_month=document.getElementById("monthSelect"+j_p).value;
                            var s_p_day=document.getElementById("daySelect"+j_p).value;
                            var s_p_year=document.getElementById("yearSelect"+j_p).value;
                            var sem_p_dt=s_p_year+'-'+s_p_month+'-'+s_p_day;
                             sem_p_dt=Date.parse(sem_p_dt); 
                            var sem_p_name=document.getElementById("name_"+j_p).value;
                                if(sem_dt<sem_p_dt)
                                {
                                document.getElementById("start_date").innerHTML=sem_name+" Cannot Be Before "+sem_p_name;
                                return false;   
                                }
                            }
                            else
                            {
                            if(prev_l_st!=0)
                            {
                                var p_e_month=document.getElementById("monthSelect"+prev_l_st).value;
                                var p_e_day=document.getElementById("daySelect"+prev_l_st).value;
                                var p_e_year=document.getElementById("yearSelect"+prev_l_st).value;
                                var e_p_dt=p_e_year+'-'+p_e_month+'-'+p_e_day;
                                e_p_dt=Date.parse(e_p_dt);
                                var e_p_name=document.getElementById("name_"+prev_l_st).value;
                                if(sem_dt<e_p_dt)
                                {
                                document.getElementById("start_date").innerHTML=sem_name+" Cannot Be Before "+e_p_name;
                                return false;       
                                }
                            }
                            }
                         }
                        }
                    }
                    
                    var check_q=document.getElementById("quarter_"+i);
                    if(check_q!=null)
                    {
                        check_q=check_q.value;
                        if(check_q!='')
                        {
                        var q_da=check_q.split("-");
                        for(var d_q=0;d_q<q_da.length;d_q++)
                        {
                            var q_t=q_da[d_q];
                            var t_q=q_t.split("`");
                            var qs_month=document.getElementById("monthSelect"+t_q[0]).value;
                            var qs_day=document.getElementById("daySelect"+t_q[0]).value;
                            var qs_year=document.getElementById("yearSelect"+t_q[0]).value;
                            var qs_dt=qs_year+'-'+qs_month+'-'+qs_day;

                            qs_dt=Date.parse(qs_dt);
                            var qs_name=document.getElementById("name_"+t_q[0]).value;

                            var qe_month=document.getElementById("monthSelect"+t_q[1]).value;
                            var qe_day=document.getElementById("daySelect"+t_q[1]).value;
                            var qe_year=document.getElementById("yearSelect"+t_q[1]).value;
                            var qe_dt=qe_year+'-'+qe_month+'-'+qe_day;
                            qe_dt=Date.parse(qe_dt);
                            var qe_name=document.getElementById("name_"+t_q[1]).value;
                            
                            var ss_month=document.getElementById("monthSelect"+l_st_m).value;
                            var ss_day=document.getElementById("daySelect"+l_st_m).value;
                            var ss_year=document.getElementById("yearSelect"+l_st_m).value;
                            var ss_dt=ss_year+'-'+ss_month+'-'+ss_day;
                            ss_dt=Date.parse(ss_dt);
                            var ss_name=document.getElementById("name_"+l_st_m).value;

                            var se_month=document.getElementById("monthSelect"+l_st).value;
                            var se_day=document.getElementById("daySelect"+l_st).value;
                            var se_year=document.getElementById("yearSelect"+l_st).value;
                            var se_dt=se_year+'-'+se_month+'-'+se_day;
                            se_dt=Date.parse(se_dt);
                            var se_name=document.getElementById("name_"+l_st).value;
                            
                            if(qs_month=="" || qs_day=="" || qs_year=="")
                            {
                            document.getElementById("start_date").innerHTML="Please Enter Valid "+qs_name;
                            return false;      
                            }
                            if(qe_month=="" || qe_day=="" || qe_year=="")
                            {
                            document.getElementById("start_date").innerHTML="Please Enter Valid "+qe_name;
                            return false;      
                            }
                           
                            if(qs_month!="" && qs_day!="" && qs_year!="")
                            {
                                if(qs_dt<ss_dt)
                                {
                                document.getElementById("start_date").innerHTML=qs_name+" Cannot Be Before "+ss_name;
                                return false;  
                                }
                                if(qs_dt>se_dt)
                                {
                                document.getElementById("start_date").innerHTML=qs_name+" Cannot Be After "+se_name;
                                return false;  
                                }
                            }
                            if(qe_month!="" && qe_day!="" && qe_year!="")
                            {
                                if(qe_dt<qs_dt)
                                {
                                document.getElementById("start_date").innerHTML=qe_name+" Cannot Be Before "+qs_name;
                                return false;  
                                }
                                if(qe_dt>se_dt)
                                {
                                document.getElementById("start_date").innerHTML=qe_name+" Cannot Be After "+se_name;
                                return false;  
                                }
                            }
                           
                            if(d_q!=0)
                            {
                            var pd_q=d_q-1;
                            var old_elem=q_da[pd_q];
                            var s_old_elem=old_elem.split('`');
                            var qp_month=document.getElementById("monthSelect"+s_old_elem[1]).value;
                            var qp_day=document.getElementById("daySelect"+s_old_elem[1]).value;
                            var qp_year=document.getElementById("yearSelect"+s_old_elem[1]).value;
                            var qp_dt=qp_year+'-'+qp_month+'-'+qp_day;
//                            for(var ai=0;ai<arr_s.length;ai++)
//                            {
//                                alert(arr_s[ai]);
//                            }
                            qp_dt=Date.parse(qp_dt);
                            var qp_name=document.getElementById("name_"+s_old_elem[1]).value;
                            if(qp_dt>qs_dt)
                            {
                            document.getElementById("start_date").innerHTML=qs_name+" Cannot Be Before "+qp_name;
                            return false;    
                            }
                            }
                            var check_p=document.getElementById("progress_"+i);
                            if(check_p!=null)
                            {
                                check_p=check_p.value;
                                if(check_p!='')
                                {
                                var p_da=check_p.split("-");
  
                                var check_c_p=p_da[d_q].split('^');
                                for(var ip=0;ip<check_c_p.length;ip++)
                                {
                                    var m_p=check_c_p[ip].split('`');
                                    var ps_month=document.getElementById("monthSelect"+m_p[0]).value;
                                    var ps_day=document.getElementById("daySelect"+m_p[0]).value;
                                    var ps_year=document.getElementById("yearSelect"+m_p[0]).value;
                                    var ps_dt=ps_year+'-'+ps_month+'-'+ps_day;

                                    ps_dt=Date.parse(ps_dt);
                                    var ps_name=document.getElementById("name_"+m_p[0]).value;
                                    
                                    var pe_month=document.getElementById("monthSelect"+m_p[1]).value;
                                    var pe_day=document.getElementById("daySelect"+m_p[1]).value;
                                    var pe_year=document.getElementById("yearSelect"+m_p[1]).value;
                                    var pe_dt=pe_year+'-'+pe_month+'-'+pe_day;
                                    pe_dt=Date.parse(pe_dt);
                                    var pe_name=document.getElementById("name_"+m_p[1]).value;
                                    
                                    if(ps_month=='' || ps_day=='' || ps_year=='')
                                    {
                                    document.getElementById("start_date").innerHTML="Please Enter Valid "+ps_name;
                                    return false;    
                                    }
                                    if(pe_month=='' || pe_day=='' || pe_year=='')
                                    {
                                    document.getElementById("start_date").innerHTML="Please Enter Valid "+pe_name;
                                    return false;    
                                    }
                                    if(ps_month!="" && ps_day!="" && ps_year!="")
                                    {
                                        if(ps_dt<qs_dt)
                                        {
                                        document.getElementById("start_date").innerHTML=ps_name+" Cannot Be Before "+qs_name;
                                        return false;  
                                        }
                                        if(ps_dt>qe_dt)
                                        {
                                        document.getElementById("start_date").innerHTML=ps_name+" Cannot Be After "+qe_name;
                                        return false;  
                                        }
                                    }
                                    if(pe_month!="" && pe_day!="" && pe_year!="")
                                    {
                                        if(pe_dt<ps_dt)
                                        {
                                        document.getElementById("start_date").innerHTML=pe_name+" Cannot Be Before "+ps_name;
                                        return false;  
                                        }
                                        if(pe_dt>qe_dt)
                                        {
                                        document.getElementById("start_date").innerHTML=pe_name+" Cannot Be After "+qe_name;
                                        return false;  
                                        }
                                    }
                                    if(ip!=0)
                                    {
                                    var pd_p=ip-1;
                                    var old_elem_p=check_c_p[pd_p];
                                    var p_old_elem=old_elem_p.split('`');
                                    var pp_month=document.getElementById("monthSelect"+p_old_elem[1]).value;
                                    var pp_day=document.getElementById("daySelect"+p_old_elem[1]).value;
                                    var pp_year=document.getElementById("yearSelect"+p_old_elem[1]).value;
                                    var pp_dt=pp_year+'-'+pp_month+'-'+pp_day;
        //                            for(var ai=0;ai<arr_s.length;ai++)
        //                            {
        //                                alert(arr_s[ai]);
        //                            }
                                    pp_dt=Date.parse(pp_dt);
                                    var pp_name=document.getElementById("name_"+p_old_elem[1]).value;
                                    if(pp_dt>ps_dt)
                                    {
                                    document.getElementById("start_date").innerHTML=ps_name+" Cannot Be Before "+pp_name;
                                    return false;    
                                    }
                                    }
                                    
                                }
                                } 
                            }
                        }
                        }
                    }
                    prev_l_st=l_st;
                }
                
            }
        }

    }
        
      
    }
}
//function show_date_picker()
//{
//
//    var i=document.getElementById("check_click").value;
//    
//    var i_mod=parseInt(i)%2;
//     if(i_mod==0)
//     {
//     document.getElementById("show_date_range").style.display="block";
//     var i_inc=parseInt(i)+1;
//     }
//     else
//     {
//     document.getElementById("show_date_range").style.display="none";        
//     var i_inc=parseInt(i)-1;
//     }
//     document.getElementById("check_click").value=i_inc;
//}
function validate_rollover(thisFrm,thisElement)
{
    if(thisElement.name=='courses')
    {
        if(thisElement.checked==true)
        {
            thisFrm.course_subjects.checked=true;
        }
    }
    
    if(thisElement.name=='course_periods')
    {
        if(thisElement.checked==true)
        {
            thisFrm.school_periods.checked=true;
            thisFrm.attendance_calendars.checked=true;
            thisFrm.course_subjects.checked=true;
            thisFrm.courses.checked=true;
            
        }
        if(thisFrm.report_card_comments.checked==true && thisElement.checked==false)
        {
            thisElement.checked=true;
        }
    }
    if(thisElement.name=='report_card_comments' && thisElement.checked==true)
    {
        thisFrm.school_periods.checked=true;
        thisFrm.attendance_calendars.checked=true;
        thisFrm.course_subjects.checked=true;
        thisFrm.courses.checked=true;
        thisFrm.course_periods.checked=true;
    }
    if(thisFrm.course_periods.checked==true && thisElement.checked==false && (thisElement.name=='school_periods' || thisElement.name=='attendance_calendars' || thisElement.name=='course_subjects'|| thisElement.name=='courses'))
    {
        thisElement.checked=true;
    }
    if(thisFrm.courses.checked==true && thisElement.checked==false && thisElement.name=='course_subjects')
    {
        thisElement.checked=true;
    }
}

function validate_password(password,stid)
{   
 
     var url = "validator.php?validate=pass&password=" + password +"&stfid="+ stid;
     ajax_call(url,pass_val_callback,pass_val_error);
}

function pass_val_callback(data)
{
 	var obj = document.getElementById('passwordStrength');
        
            if(data!='1')
            {
 	obj.style.color ='#ff0000';
                  obj.style.backgroundColor =  "#cccccc" ;
 	obj.innerHTML = 'Invalid password';
            }
         
}
function pass_val_error(err)
{
    alert ("Error: " + err);
}



//-------------------------------------------------- historical grade school name pickup --------------------------------------//
function pick_schoolname (data) {
 	
        document.getElementById('SCHOOL_NAME').value = data;
 }

// ------------------------------------------------------ Student ------------------------------------------------------------------------------ //

// ------------------------------------------------------ Student ID------------------------------------------------------------------------------ //

 function GetSchool(i) {
	var obj = document.getElementById('SCHOOL_NAME');
	obj.innerHTML = ''; 
	
 	ajax_call ('GetSchool.php?u='+i, pick_schoolname); 
 }


function fill_hidden_field(id,value)
{
    var final_value=new Array();
    var temp_text;

    for(var i=0;i<value.length;i++)
    {
        temp_text=value.substr(i,1);
        if(temp_text!=' ')
            final_value[i]=temp_text;
            else
            final_value[i]='+';
    }
    document.getElementById(id).value=final_value.join('');

}