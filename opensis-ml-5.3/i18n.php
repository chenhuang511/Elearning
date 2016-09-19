<?php
 session_start();
	$b=($_SESSION['lang_var']);

$t_f=false;
$lang_array = array(
	"en_EN" => array("name" => "English (en_EN)", "short" => "en_EN", "dir" => "ltr", "default" =>$t_f),
	"ar_DZ" => array("name" => "Arabic (ar)", "short" => "ar_DZ", "dir" => "rtl", "default" =>$t_f),
	"de_DE" => array("name" => "German", "short" => "de_DE", "dir" => "ltr", "default" =>$t_f),
        "es_ES" => array("name" => "Spanish (es)", "short" => "es_ES", "dir" => "ltr", "default" =>$t_f),
        "fr_FR" => array("name" => "French (fr)", "short" => "fr_FR", "dir" => "ltr", "default" =>$t_f),
        "pt_PT" => array("name" => "Portuguese (pt_PT)", "short" => "pt_PT", "dir" => "ltr", "default" =>$t_f),
        "bn_BD" => array("name" => "Bengali (bn)", "short" => "bn_BD", "dir" => "ltr", "default" =>$t_f),
        "af_ZA" => array("name" => "Afrikaans", "short" => "af_ZA", "dir" => "ltr", "default" =>$t_f),
        "sq_AL" => array("name" => "Albanian (sq_AL)", "short" => "sq_AL", "dir" => "ltr", "default" =>$t_f),
      "be_BY" => array("name" => "Belarusian (be)", "short" => "be_BY", "dir" => "ltr", "default" =>$t_f),
      "bg_BG" => array("name" => "Bulgarian (bg)", "short" => "bg_BG", "dir" => "ltr", "default" =>$t_f),
     "ca_AD" => array("name" => "Catalan (ca)", "short" => "ca_AD", "dir" => "ltr", "default" =>$t_f),
     "zh_TW" => array("name" => "Chinese (zh_TW)", "short" => "zh_TW", "dir" => "ltr", "default" =>$t_f),
     "zh_CN" => array("name" => "Chinese (zh_CN)", "short" => "zh_CN", "dir" => "ltr", "default" =>$t_f),
     "hr_HR" => array("name" => "Croatian (hr)", "short" => "hr_HR", "dir" => "ltr", "default" =>$t_f),
     "cs_CS" => array("name" => "CCzech (cs)", "short" => "cs_CS", "dir" => "ltr", "default" =>$t_f),
     "da_DK" => array("name" => "Danish (da)", "short" => "da_DK", "dir" => "ltr", "default" =>$t_f),
     "nl_NL" => array("name" => "Dutch (nl)", "short" => "nl_NL", "dir" => "ltr", "default" =>$t_f),
     "fi_FI" => array("name" => "Finnish (fi)", "short" => "fi_FI", "dir" => "ltr", "default" =>$t_f),
     "wa_BE" => array("name" => "Walloon (wa)", "short" => "wa_BE", "dir" => "ltr", "default" =>$t_f),
     "vi_VN" => array("name" => "Vietnamese (vi)", "short" => "vi_VN", "dir" => "ltr", "default" =>$t_f),
    "th_TH" => array("name" => "Thai (th)", "short" => "th_TH", "dir" => "ltr", "default" =>$t_f),
    "tr_TR" => array("name" => "Turkish (tr)", "short" => "tr_TR", "dir" => "ltr", "default" =>$t_f),
    "ru_RU" => array("name" => "Russian (ru)", "short" => "ru_RU", "dir" => "ltr", "default" =>$t_f),
    "ko_KR" => array("name" => "Korean (ko)", "short" => "ko_KR", "dir" => "ltr", "default" =>$t_f),
    "ja_JP" => array("name" => "Japanese (ja)", "short" => "ja_JP", "dir" => "ltr", "default" =>$t_f),
    "lt_LT" => array("name" => "Lithuanian (lt)", "short" => "lt_LT", "dir" => "ltr", "default" =>$t_f),
    "mk_MK" => array("name" => "Macedonian (mk)", "short" => "mk_MK", "dir" => "ltr", "default" =>$t_f),
    "ms_MY" => array("name" => "Malay (ms)", "short" => "ms_MY", "dir" => "ltr", "default" =>$t_f),
    "fa_IR" => array("name" => "Persian (fa_IR)", "short" => "fa_IR", "dir" => "rtl", "default" =>$t_f),
    "ro_RO" => array("name" => "Romanian (ro)", "short" => "ro_RO", "dir" => "ltr", "default" =>$t_f),
    "sr_RS" => array("name" => "Serbian (sr)", "short" => "sr_RS", "dir" => "ltr", "default" =>$t_f),
    "sk_SK" => array("name" => "Slovak (sk)", "short" => "sk_SK", "dir" => "ltr", "default" =>$t_f),
    "lv_LV" => array("name" => "Latvian (lv)", "short" => "lv_LV", "dir" => "ltr", "default" =>$t_f),
    "de_DE" => array("name" => "German (de)", "short" => "de_DE", "dir" => "ltr", "default" =>$t_f),
    "el_GR" => array("name" => "Greek (el)", "short" => "el_GR", "dir" => "ltr", "default" =>$t_f),
    "fr_FR" => array("name" => "French (fr)", "short" => "fr_FR", "dir" => "ltr", "default" =>$t_f),
    "ga_IE" => array("name" => "Irish (ga)", "short" => "ga_IE", "dir" => "ltr", "default" =>$t_f),
    "gl_GL" => array("name" => "Galician (gl)", "short" => "lv_LV", "dir" => "ltr", "default" =>$t_f),
    "he_IL" => array("name" => "Hebrew (he)", "short" => "he_IL", "dir" => "rtl", "default" =>$t_f),
    "hu_HU" => array("name" => "Hungarian (hu)", "short" => "hu_HU", "dir" => "ltr", "default" =>$t_f),
    "id_ID" => array("name" => "Indonesian (id)", "short" => "id_ID", "dir" => "ltr", "default" =>$t_f),
    "is_IS" => array("name" => "Icelandic (is)", "short" => "is_IS", "dir" => "ltr", "default" =>$t_f),
    "hi_IN" => array("name" => "Hindi (hi)", "short" => "hi_HI", "dir" => "ltr", "default" =>$t_f),
    "nn_NO" => array("name" => "Norwegian Nynorsk (nn)", "short" => "nn_NO", "dir" => "ltr", "default" =>$t_f),
    "uk_UA" => array("name" => "Ukrainian (uk)", "short" => "uk_UA", "dir" => "ltr", "default" =>$t_f),
    "et_EE" => array("name" => "Estonian (et)", "short" => "et_EE", "dir" => "ltr", "default" =>$t_f),
    "fi_FI" => array("name" => "Finnish (fi)", "short" => "fi_FI", "dir" => "ltr", "default" =>$t_f),
     "sv_SE" => array("name" => "Swedish (sv)", "short" => "sv_SE", "dir" => "ltr", "default" =>$t_f),
    "sl_SI" => array("name" => "Slovenian (sl)", "short" => "sl_SI", "dir" => "ltr", "default" =>$t_f),
    "cs_CS" => array("name" => "Czech (cs)", "short" => "cs_CS", "dir" => "ltr", "default" =>$t_f),
    "pl_PL" => array("name" => "Polish (pl)", "short" => "pl_PL", "dir" => "ltr", "default" =>$t_f),
    "it_IT" => array("name" => "Italian (it)", "short" => "it_IT", "dir" => "ltr", "default" =>$t_f)
);


if(($b)) {

    foreach($lang_array as $elem) {

           if($b==$elem["name"])
            {
                  $lang=$elem;
                  $lang["default"]=TRUE;
                  if($_SESSION['remember'])
                  {
                  setcookie("lang", $lang["short"], time()+15552000);  /* expires in 6 months*/
                  setcookie("full_name", $lang["name"], time()+15552000);  /* expires in 6 months */
                  }
         } 
        }
        $var = $lang['short'];
        setlocale( LC_MESSAGES,$var);
        putenv("LANG=$var.utf8");
	
}

elseif(($_COOKIE["lang"]))
{
    $var = $_COOKIE["lang"];
    setlocale( LC_MESSAGES,$var);
     putenv("LANG=$var.utf8");
//    putenv("LC_ALL=$var");
}
//$lang["short"]='es_ES';
//
//setlocale( LC_MESSAGES, 'es_ES');
//putenv("LANG=es_ES.utf8");

bindtextdomain('openSIS', './locale');
bind_textdomain_codeset('openSIS', 'UTF-8');
textdomain('openSIS');



?>
