<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Web service auth plugin, reserves username, prevents normal login.
 * TODO: add IP restrictions and some other features - MDL-17135
 *
 * @package    auth_webservice
 * @copyright  2008 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Web service auth plugin.
 */
class auth_plugin_webservice extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'webservice';
        $this->config = get_config('auth/webservice');
    }

    /**
     * Old syntax of class constructor for backward compatibility.
     */
    public function auth_plugin_webservice() {
        self::__construct();
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     *
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
        global $DB, $CFG;
        // Retrieve the user matching username.
        $user = $DB->get_record('user', array('username' => $username,
            'mnethostid' => $CFG->mnet_localhost_id));
        // Username must exist and have the right authentication method.
        if (!empty($user) && ($user->auth == 'webservice')) {            
            return true;
        }
        return false;
    }

    /**
     * Custom auth hook for web services.
     * @param string $username
     * @param string $password
     * @return bool success
     */
    function user_login_webservice($username, $password) {
        /*global $CFG, $DB;
        // special web service login
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
            return validate_internal_user_password($user, $password);
        }*/
        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object  (with system magic quotes)
     * @param  string  $newpassword Plaintext password (with system magic quotes)
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * Webserice auth doesn't use password fields, it uses only tokens.
     *
     * @return bool
     */
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return false;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        return true;
    }

   /**
     * Confirm the new user as registered. This should normally not be used,
     * but it may be necessary if the user auth_method is changed to manual
     * before the user is confirmed.
     */
    function user_confirm($username, $confirmsecret = null) {
        return AUTH_CONFIRM_ERROR;
    }

    function loginpage_hook() {
    	global $USER, $SESSION, $CFG, $DB;
    	
    	$username = $_POST['username'];
    	$password = $_POST['password'];
    	
    	$userinfo = $this->get_remote_user_info($username, $password);
    	
    	if (!isset($userinfo["status"]) || !$userinfo["status"])
    		return;

    	$useremail = $userinfo["data"]["email"];
    	
    	// Prohibit login if email belongs to the prohibited domain.
    	if ($err = email_is_not_allowed($useremail)) {
    		throw new moodle_exception($err, 'auth_webservice');
    	}

    	// If email not existing in user database then create a new username (userX).
    	if (empty($useremail) || $useremail != clean_param($useremail, PARAM_EMAIL)) {
    		throw new moodle_exception('couldnotgetuseremail', 'auth_webservice');
    		// TODO: display a link for people to retry.
    	}
    	
    	// Get the user.
    	// Don't bother with auth = googleoauth2 because authenticate_user_login() will fail it if it's not 'googleoauth2'.
    	$user = $DB->get_record('user',
    	array('email' => $useremail, 'deleted' => 0, 'mnethostid' => $CFG->mnet_localhost_id));
  		
    	// Create the user if it doesn't exist.
    	if (empty($user)) {
    		// Deny login if setting "Prevent account creation when authenticating" is on.
    		if ($CFG->authpreventaccountcreation) {
    			throw new moodle_exception("noaccountyet", "auth_webservice");
    		}

    		// Get following incremented username.
    		$userprefix = get_config('auth/webservice', 'userprefix');
    		if(empty($userprefix)) {
    			$userprefix = 'webservice_';
    			set_config('userprefix', $userprefix, 'auth/webservice');
    		}
/*    		
    		$lastusernumber = get_config('auth/webservice', 'lastusernumber');
    		$lastusernumber = empty($lastusernumber) ? 1 : $lastusernumber + 1;
    		// Check the user doesn't exist.
    		$nextuser = $DB->record_exists('user', array('username' => $userprefix.$lastusernumber));
    		while ($nextuser) {
    			$lastusernumber++;
    			$nextuser = $DB->record_exists('user', array('username' => $userprefix.$lastusernumber));
    		}
    		set_config('lastusernumber', $lastusernumber, 'auth/webservice');
 */   		
    		$username = $userprefix . $username;
    		create_user_record($username, '', 'webservice');
    	} else {
    		$username = $user->username;
    	}
		echo "bbbbbbbbbbbbbbbbbbbbbbbbbbbbb";	
    	// Authenticate the user.
    	$user = authenticate_user_login($username, null);
    	echo "aaaaaaaaaaaaaaaaaaaaaaaaaaa";	
    	if ($user) {
    		// Set a cookie to remember what auth provider was selected.
    		setcookie('MOODLEAUTHWEBSERVICE_'.$CFG->sessioncookie, "auth_webservice",
    		time() + (DAYSECS * 60), $CFG->sessioncookiepath,
    		$CFG->sessioncookiedomain, $CFG->cookiesecure,
    		$CFG->cookiehttponly);

    		// Prefill more user information if new user.
    		if (!empty($newuser)) {
    			$newuser->id = $user->id;
    			$DB->update_record('user', $newuser);
    			$user = (object) array_merge((array) $user, (array) $newuser);
    		}

    		complete_user_login($user);

    		// Let's save/update the access token for this user.
    		$cansaveaccesstoken = get_config('auth/webservice', 'saveaccesstoken');
    		if (!empty($cansaveaccesstoken)) {
    			$existingaccesstoken = $DB->get_record('auth_webservice_user_idps',
    			array('userid' => $user->id, 'provider' => "auth_webservice"));
    			if (empty($existingaccesstoken)) {
    				$accesstokenrow = new stdClass();
    				$accesstokenrow->userid = $user->id;
    				$accesstokenrow->provideruserid = $userdetails->uid;
    				$accesstokenrow->provider = "auth_webservice";
    				$accesstokenrow->accesstoken = $userinfo["data"]["tokenkey"];
    				$accesstokenrow->refreshtoken = $userinfo["data"]["tokenkey"];
    				$accesstokenrow->expires = $userinfo["data"]["tokenkey"];
					echo "====================";
    				$DB->insert_record('auth_webservice_user_idps', $accesstokenrow);
    			} else {
    				echo "====================+++++++++++++++++";
    				$existingaccesstoken->accesstoken = $userinfo["data"]["tokenkey"];
    				$DB->update_record('auth_webservice_user_idps', $existingaccesstoken);
    			}
    		}

    		// Check if the user picture is the default and retrieve the provider picture.
    		if (empty($user->picture)) {
    			$profilepicurl = '';
    			if (!empty($userdetails->imageUrl)) {
    				$profilepicurl = $userdetails->imageUrl;
    			}
    			if (!empty($profilepicurl)) {
    				$this->set_profile_picture($user, $profilepicurl);
    			}
    		}

    		// Create event for authenticated user.
    		require_once($CFG->dirroot . '/auth/webservice/user_loggedin.php');
    		$event = \auth_webservice\event\user_loggedin::create(
    		array('context' => context_system::instance(),
                            'objectid' => $user->id, 'relateduserid' => $user->id,
                            'other' => array('accesstoken' => $userinfo["data"]["tokenkey"])));
    		echo "adfadfadfadf";
    		$event->trigger();
    		
			echo "DB->insert_record4";
    		// Redirection.
    		if (user_not_fully_set_up($USER)) {
    			$urltogo = $CFG->wwwroot.'/user/edit.php';
    			// We don't delete $SESSION->wantsurl yet, so we get there later.
    		} else if (isset($SESSION->wantsurl) && (strpos($SESSION->wantsurl, $CFG->wwwroot) === 0)) {
    			$urltogo = $SESSION->wantsurl;    // Because it's an address in this site.
    			unset($SESSION->wantsurl);
    		} else {
    			// No wantsurl stored or external - go to homepage.
    			$urltogo = $CFG->wwwroot.'/';
    			unset($SESSION->wantsurl);
    		}
    		$loginrecord = array('userid' => $USER->id, 'time' => time(),
                        'auth' => 'webservice', 'subtype' => 'auth_webservice');
    		try {
    			$DB->insert_record('auth_webservice_logins', $loginrecord);
    		} catch(Exception $e) {
    			echo $e->getMessage();
    		}
			echo "URLTOGO " . $urltogo;
    		redirect($urltogo);
    	} else {
    		echo "bbbbbbbbbbbbbbbbbbbbbbbbbbbbb22222222222222222";
    		// Authenticate_user_login() failure, probably email registered by another auth plugin.
    		// Do a check to confirm this hypothesis.
    		$userexist = $DB->get_record('user', array('email' => $useremail)); 
    		if (!empty($userexist) && $userexist->auth != 'webservice') {
    			$a = new stdClass();
    			$a->loginpage = (string) new moodle_url(empty($CFG->alternateloginurl) ?
                            '/login/index.php' : $CFG->alternateloginurl);
    			$a->forgotpass = (string) new moodle_url('/login/forgot_password.php');
    			
    			//throw new moodle_exception('couldnotauthenticateuserlogin', 'auth_webservice', '', $a);
    		} else {
    			//throw new moodle_exception('couldnotauthenticate', 'auth_webservice');
    		}
    	}
    }
    
    /**
     * Read user information from external database and returns it as array().
     * Function should return all information available. If you are saving
     * this information to moodle user-table you should honour synchronisation flags
     *
     * @param string $username username
     *
     * @return mixed array with no magic quotes or false on error
     */
    function get_userinfo($username) {
    	$userprefix = get_config('auth/webservice', 'userprefix');
    	if(empty($userprefix)) {
    		$userprefix = 'webservice_';
    		set_config('userprefix', $userprefix, 'auth/webservice');
    	}

        $userinfosave = get_config('auth/webservice', 'saveuserinfo_'.core_text::strtolower($userprefix).$username);
        $userinfosave = empty($userinfosave)? get_config('auth/webservice', 'saveuserinfo_'.$username) : $userinfosave;

        $userinfo = unserialize($userinfosave);
        
        return $userinfo;
    }
    
    /**
     * Call api to get remote userinfo
     * Enter description here ...
     */
    function get_remote_user_info($username, $password) {
    	global $CFG;
    	
    	$serverurl = "http://api.ums.dev:4449/auth/login";
    	require_once($CFG->dirroot . '/auth/webservice/curl.php');
		$curl = new curl();
		
		//if rest format == 'xml', then we do not add the param for backward compatibility with Moodle < 2.2
		//$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
		$params = array("username" => $username, "password" => $password);
		
		$resp = $curl->post($serverurl, $params);
		
    	$userinfo = json_decode($resp, true);
    	$data = serialize($userinfo["data"]);
    	if (isset($userinfo["status"]) && $userinfo["status"] == 1) {
    		$userprefix = get_config('auth/webservice', 'userprefix');
    		if(empty($userprefix)) {
    			$userprefix = 'webservice_';
    			set_config('userprefix', $userprefix, 'auth/webservice');
    		}

    		set_config('saveuserinfo_'.core_text::strtolower($userprefix).$username, $data, 'auth/webservice');
    	}
    	return $userinfo;
    }
}
