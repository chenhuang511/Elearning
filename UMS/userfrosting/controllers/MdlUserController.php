<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/23/2016
 * Time: 5:59 PM
 */
namespace UserFrosting;

class MdlUserController extends \UserFrosting\BaseController
{
    public function __construct($app)
    {
        $this->_app = $app;
    }

    /*
     * show list mdluser
     * Người dùng show ra là những người còn tồn tại trên hệ thống (chưa bị xóa - có trường delete = 0)
     * và tài khoản người dùng không phải là tài khoản guest
     * */
    public function pageMdlUsers()
    {
        //lấy list user trong csdl (là những user đang hoạt động có trường deleted = '0')
        $user_collection = MdlUser::queryBuilder()
            ->where("deleted", "0")
            ->where("username",'<>',"guest")
            ->get();
        $name = "Users Moodle";
        $icon = "fa fa-users";
        //lấy dl siteadmins trong mdl_config - là các user_id của manager.
        $siteadmins = MdlConfig::where("name","siteadmins")->first();
        //sử dụng hàm explode để tách chuỗi, đưa các user_id của admin vào mảng các user_id
        $admins = explode(',', $siteadmins->value);

        $this->_app->render("/users/mdluser_list.twig",[
            "box_title" => $name,
            "icon" => $icon,
            "users" => isset($user_collection) ? $user_collection : [],
            "admins" => $admins
        ]);
    }

    /*
     * Create form Usermood. Hàm này tạo form cho create mdluser
     * */
    public function CreateMdlUser(){
        // Get a list of all groups
        $get = $this->_app->request->get();

        // Get auth, countries, timezone array từ file php đã khai báo ở bên ngoài bằng include
        $auths = array();
        include ("../userfrosting/locale/moodle_local/auth.php");
        $countries = array();
        include ("../userfrosting/locale/moodle_local/contries.php");
        $timezone = array();
        include ("../userfrosting/locale/moodle_local/timezones.php");

        // Khai báo mảng mail_display
        $mail_display = array(
            '0' => 'Hide my email address from everyone',
            '1' => 'Allow everyone to see my email address',
            '2' => 'Allow only other course members to see my email address',
        );

        // Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-create.json");
        $this->_app->jsValidator->setSchema($schema);

        if (isset($get['render']))
            $render = $get['render'];
        else
            $render = "modal";

        // Set default values lay tu xu ly cua moodle
        $data['id'] = "-1";
        // Set default cach tao new user
        $data['auth'] = "manual";
        // Set default thanh vien da duoc xac nhan (confirm)
        $data['confirmed'] = "1";
        // Set default deteted
        $data['deleted'] = "0";
        // Set default timezone
        $data['timezone'] = "99";

        $mdluser = new MdlUser($data);
        $this->_app->render('users/mdluser_form.twig', [
            "box_title" => "Create New User",
            "mdluser" => $mdluser,
            "auths" => $auths,
            "mail_dis" => $mail_display,
            "countries" => $countries,
            "timezone" => $timezone,
            "form_action" => $this->_app->site->uri['public'] . "/mdlusers",
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

    /*
     * xử lý submit form create. lấy dl từ form lưu vào moodledb
     * tạm thời chưa xử lý phần lấy ảnh người dùng lưu vào moodledata
     * muốn lấy dữ liệu ảnh cần chỉnh lại userfrosting.js + các bài toán khác khi chỉnh file này...
     * */
    public function submitCreateMdluser(){
        $post = $this->_app->request->post();

        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-create.json");
        // Get the alert message stream
        $ms = $this->_app->alerts;
        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        // Sanitize data
        $rf->sanitize();
        // Validate, and halt on validation errors.
        $error = !$rf->validate(true);
        // Get the filtered data
        $user = $rf->data();
        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);
        //lấy các dữ liệu còn lại(những dl không cần validate) từ biến post
        foreach ($post as $key => $value) {
            if ($key != 'username') {
                if ($key != 'firstname') {
                    if ($key != 'surname') {
                        if ($key != 'email') {
                            $user[$key] = $value;
                        }
                    }
                }
            }
        }
        // Perform desired data transformations on required fields.
        // chuyển đổi dữ liệu mong muốn vào các trường khác trong db (không lấy dl từ forrm)
        $user['confirmed'] = 1;
        $user['deleted'] = 0;
        $user['descriptionformat'] = 1;
        $user['timecreated'] = time();
        $user['timemodified'] = $user['timecreated'];
        $user['mnethostid'] = 1;
        $user['picture'] = 0;
        //hash password theo moodle
        $fasthash = false;
        $options = ($fasthash) ? array('cost' => 4) : array();
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT, $options);

        $user['username'] = trim($user['username']);
        $user['firstname'] = trim($user['firstname']);
        $user['lastname'] = trim($user['lastname']);

        // Check if user_name or email already exists
        if (MdlUser::where('username', $user['username'])->first()) {
            $ms->addMessageTranslated("danger", "ACCOUNT_USERNAME_IN_USE", $user);
            $error = true;
        }
        if (MdlUser::where('email', $user['email'])->first()) {
            $ms->addMessageTranslated("danger", "ACCOUNT_EMAIL_IN_USE", $user);
            $error = true;
        }
        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }

        // Create the mdlUser
        $mdluser = new MdlUser($user);
        // Store new user to database
        $mdluser->store();
        // Success message
        $ms->addMessageTranslated("success", 'MDLUSER_CREATE_SUCCESS',["name" => $user['username']]);
        //lấy dữ liệu user_id vừa mới thêm vào bảng user để đưa vào instanceid của bảng mdl_context
        $userId = $mdluser->id;
        $context = array();
        $context['contextlevel'] = 30;
        $context['instanceid'] = $userId;
        $context['depth'] = 2;

        // Create the mdlContext
        $mdlcontext = new MdlContext($context);
        //Store new context to database
        $mdlcontext->store();
        //lấy context_id vừa thêm để đưa vào trường path của bảng context
        $contextId = $mdlcontext->id;
        $context['path'] = '/1/' . $contextId;
        //update path cho bảng context
        MdlContext::where('id','=',$contextId)->update(['path' => $context['path']]);

        //Update preferences: mdl_user_preferences: auth_forcepasswordchange
        $pref = array();
        $pref['userid'] = $userId;
        $pref['name']   = 'auth_forcepasswordchange';
        $pref['value']  = $user['preference_auth_forcepasswordchange'];
        $mdlpreference = new MdlUserPreferences($pref);
        $mdlpreference->store();
        //Create mdlcacheflags: auth_forcepasswordchange
        $cacheflag = array();
        $cacheflag['flagtype'] = 'userpreferenceschanged';
        $cacheflag['name'] = $userId;
        $cacheflag['timemodified'] = time();
        $cacheflag['value'] = 1;
        $cacheflag['expiry'] = $cacheflag['timemodified'] + 24*60*60; //24*60*60 chính là sessiontimeout
        $mdlcacheflag = new MdlCacheFlags($cacheflag);
        $mdlcacheflag->store();

        //Update tags
        if(isset($user['taggles'])){
            $usertag = $user['taggles'];
        }
        else{
            $usertag = '';
        }
        if (!empty($usertag)) {
            // 1. update bảng mdl_tag
            $tag = array();
            $tag['userid'] = 2;
            $tag['tagtype'] = 'default';
            $tag['description'] = NULL;
            $tag['descriptionformat'] = 0;
            $tag['flag'] = 0;
            $tag['timemodified'] = time();
            // 2. update bảng mdl_tag_instance
            $tag_instance = array();
            $tag_instance['component'] = 'core';
            $tag_instance['itemtype'] = 'user';
            $tag_instance['itemid'] = $userId;
            $tag_instance['contextid'] = $contextId;
            $tag_instance['tiuserid'] = 0;

            foreach ($usertag as $key => $value) {
                // kiểm tra xem đã tồn tại tag_name này chưa. trành việc lưu cùng sở thích nhiều lần

                $tagcurren = MdlTag::where('name',$value)->first();
                // Nếu đã tồn tại tag_name = value thì lấy tag_id và chỉ thêm bản ghi trong tag_instance
                if($tagcurren) {
                    $tag_instance['tagid'] = $tagcurren->id;
                    $tag_instance['ordering'] = $key;
                    $tag_instance['timecreated'] = time();
                    $tag_instance['timemodified'] = $tag_instance['timecreated'];
                    $mdltag_instance = new MdlTagInstance($tag_instance);
                    $mdltag_instance->store();
                }
                // Nếu chưa tồn tại tại tag_name = value thì thêm bản ghi mới trong mdl_tag và trong mdl_tag_instance
                else{
                    $tag['name'] = $value;
                    $tag['rawname'] = ucfirst($value);
                    $mdltag = new MdlTag($tag);
                    $mdltag->store();
                    // với mỗi bản ghi trong tag vừa thêm ta cũng thêm một bản ghi mới trong tag_instance và với tag_id vừa thêm vào
                    $tag_instance['tagid'] = $mdltag->id;;
                    $tag_instance['ordering'] = $key;
                    $tag_instance['timecreated'] = time();
                    $tag_instance['timemodified'] = $tag_instance['timecreated'];
                    $mdltag_instance = new MdlTagInstance($tag_instance);
                    $mdltag_instance->store();
                }
            }
        }

        //Update mail bounces. mdl_user_preferences: email_bounce_count, email_send_count
        //set_bounce_count($usernew, true);
        $pref['userid'] = $userId;
        $pref['name']   = 'email_bounce_count';
        $pref['value']  = 1;
        $mdlpref_bounce = new MdlUserPreferences($pref);
        $mdlpref_bounce->store();
        //set_send_count($usernew, true);
        $pref['userid'] = $userId;
        $pref['name']   = 'email_send_count';
        $pref['value']  = 1;
        $mdlpref_send = new MdlUserPreferences($pref);
        $mdlpref_send->store();
    }

    /*
     * dùng user_id gửi lên để lấy dữ liệu người dùng từ moodledb và đổ ra form edit mdluser
     * */
    public function formMdluserEdit($user_id){
        // Get the mdluser to edit
        $mdluser = MdlUser::find($user_id)->toArray();
        // Get auth, countries, timezone array từ file php đã khai báo ở bên ngoài bằng include
        $auths = array();
        include ("../userfrosting/locale/moodle_local/auth.php");
        $countries = array();
        include ("../userfrosting/locale/moodle_local/contries.php");
        $timezone = array();
        include ("../userfrosting/locale/moodle_local/timezones.php");

        // Khai báo mảng mail_display
        $mail_display = array(
            '0' => 'Hide my email address from everyone',
            '1' => 'Allow everyone to see my email address',
            '2' => 'Allow only other course members to see my email address',
        );

        // Lấy dữ liệu của trường preference_auth_forcepasswordchance (0 hoặc 1)
        // Update preferences: mdl_user_preferences: auth_forcepasswordchange
        $pref = MdlUserPreferences::where('name','auth_forcepasswordchange')->where('userid',$user_id)->first();

        //lấy dl của sở thích từ việc kết nối 2 bảng mdl_tag và mdl_tag_instance
        $tags = MdlTag::queryBuilder()
            ->join("mdl_tag_instance", "mdl_tag.id", "=", "mdl_tag_instance.tagid")
            ->where("mdl_tag_instance.itemid",$user_id)
            ->select("rawname")
            ->get();

        //Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-update.json");
        $this->_app->jsValidator->setSchema($schema);

        $this->_app->render('users/mdluser_form.twig', [
            "box_title" => "Update user",
            "mdluser" => $mdluser,
            "auths" => $auths,
            "tags" => $tags,
            "mail_dis" => $mail_display,
            "countries" => $countries,
            "timezone" => $timezone,
            "pref" => $pref,
            "form_action" => $this->_app->site->uri['public'] . "/mdlusers/u/$user_id",
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

    /*
     * xử lý submit form edit. lấy dl từ form cập nhật vào moodledb
     * */
    public function submitEditMdluser($user_id){
        $post = $this->_app->request->post();
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-update.json");
        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Get the target user and context_user
        $mdluser = MdlUser::find($user_id);

        //Xử lý show/hide user và confirm user - data gửi lên chỉ là dl tương ứng(user.suspended hoặc user.confirmed) và csrf_token
        if((!isset($post['username'])) || (!isset($post['email']))){
            if(isset($post['suspended'])){
                $mdluser->suspended = $post['suspended'];
                $mdluser->store();
            }
            elseif(isset($post['confirmed'])){
                $mdluser->confirmed = $post['confirmed'];
                $mdluser->store();
            }
            else {
                $this->_app->halt(400);
            }
        }
        else {
            $context = MdlContext::where('instanceid', $user_id)->where('contextlevel', '=', 30)->first();
            // Remove csrf_token
            unset($post['csrf_token']);
            //        // Nếu password không được nhập -> unset password. Tránh việc báo lỗi khi validate dữ liệu pass
            if (($post['password']) == '') {
                unset($post['password']);
            }
            // Set up Fortress to process the request
            $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

            // Check that the username is not in use
            if (isset($post['username']) && $post['username'] != $mdluser->username && MdlUser::where('username', $post['username'])->first()) {
                $ms->addMessageTranslated("danger", 'ACCOUNT_MDLUSERNAME_IN_USE', $post);
                $error = true;
            }
            // Check that the email address is not in use
            if (isset($post['email']) && $post['email'] != $mdluser->email && MdlUser::where('email', $post['email'])->first()) {
                $ms->addMessageTranslated("danger", "ACCOUNT_EMAIL_IN_USE", $post);
                $error = true;
            }
            // Sanitize
            $rf->sanitize();
            $error = !$rf->validate(true);
            // Validate, and halt on validation errors.
            if (!$rf->validate()) {
                $this->_app->halt(400);
            }
            if ($error) {
                $this->_app->halt(400);
            }
            // Get the filtered data
            $data = $rf->data();

            foreach ($post as $key => $value) {
                if ($key != 'username') {
                    if ($key != 'firstname') {
                        if ($key != 'surname') {
                            if ($key != 'email') {
                                $data[$key] = $value;
                            }
                        }
                    }
                }
            }
            $data['timemodified'] = time();
            //hash password theo moodle
            if (isset($data['password'])) {
                $fasthash = false;
                $options = ($fasthash) ? array('cost' => 4) : array();
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, $options);
            }

            //Update preferences: mdl_user_preferences: auth_forcepasswordchange
            $pref = MdlUserPreferences::where('name', 'auth_forcepasswordchange')->where('userid', $user_id)->first();
            if ($data['preference_auth_forcepasswordchange'] != $pref->value) {
                MdlUserPreferences::where('name', 'auth_forcepasswordchange')->where('userid', $user_id)->update(['value' => $data['preference_auth_forcepasswordchange']]);
            }

            //Update tags
            $usertag = $data['taggles'];
            if (!empty($usertag)) {
                // 1. update bảng mdl_tag
                $tag = array();
                $tag['userid'] = 2;
                $tag['tagtype'] = 'default';
                $tag['description'] = NULL;
                $tag['descriptionformat'] = 0;
                $tag['flag'] = 0;
                $tag['timemodified'] = time();
                // 2. update bảng mdl_tag_instance
                $tag_instance = array();
                $tag_instance['component'] = 'core';
                $tag_instance['itemtype'] = 'user';
                $tag_instance['itemid'] = $user_id;
                $tag_instance['contextid'] = $context->id;
                $tag_instance['tiuserid'] = 0;

                foreach ($usertag as $key => $value) {
                    // kiểm tra xem đã tồn tại tag_name này chưa. tránh việc lưu cùng sở thích nhiều lần
                    $tagcurren = MdlTag::where('name', $value)->first();
                    if ($tagcurren) {
                        // Nếu đã tồn tại tag_name = value thì lấy tagid của nó.
                        // Kiểm tra xem với tagid, user_id thì key trong db của mdl_tag_instance có bằng key post từ form không
                        $taginstance_current = MdlTagInstance::where('tagid', $tagcurren->id)->where('itemid', $user_id)->first();
                        //Kiểm tra xem có tồn tại taginstance_current hay không
                        if (!$taginstance_current) {
                            // Nếu không có (sở thích đã có trong tag nhưng người dùng này mới khai báo thêm sở thích này)
                            //Xóa bản ghi cũ trong tag_instance với $user_id và $key
                            MdlTagInstance::where('ordering', $key)->where('itemid', $user_id)->delete();
                            // Thêm bản ghi mới trong tag_instance với tagid và $key
                            $tagid = $tagcurren->id;
                            $tag_instance['tagid'] = $tagid;
                            $tag_instance['ordering'] = $key;
                            $tag_instance['timecreated'] = time();
                            $tag_instance['timemodified'] = $tag_instance['timecreated'];
                            $mdltag_instance = new MdlTagInstance($tag_instance);
                            $mdltag_instance->store();
                        } else {
                            // Nếu có lấy ordering của bản ghi cũ này trong tag_instance để so sánh với $key hiện tại
                            $order = $taginstance_current->ordering;
                            if ($order != $key) {
                                // Nếu khác (có một sở thích cũ phía trước đã bị xóa nên ordering thay đổi)
                                $tag_instance['timemodified'] = time();
                                MdlTagInstance::where('id', '=', $taginstance_current->id)->update(['ordering' => $key], ['timemodified' => $tag_instance['timemodified']]);
                                // Xóa bản ghi cũ với ordering cũ, bản ghi cũ vừa được update phía trên
                                //                            MdlTagInstance::where('ordering',$order)->where('itemid',$user_id)->delete();
                                // xóa bản ghi với tagid cũ đi
                                MdlTagInstance::where('tagid', '<>', $taginstance_current->tagid)->where('ordering', $key)->where('itemid', $user_id)->delete();
                            } else {
                                //Nếu bằng -> update lại timemodified và duyệt sang tag tiếp theo
                                $tag_instance['timemodified'] = time();
                                MdlTagInstance::where('id', '=', $taginstance_current->id)->update(['timemodified' => $tag_instance['timemodified']]);
                            }
                        }
                    } else {
                        // Nếu chưa tồn tại tại tag_name = value thì thêm bản ghi mới trong mdl_tag và trong mdl_tag_instance
                        $tag['name'] = $value;
                        $tag['rawname'] = ucfirst($value); //ucfirst: viết hoa chữ cái đầu của chuỗi để hiển thị
                        $mdltag = new MdlTag($tag);
                        $mdltag->store();
                        // với mỗi bản ghi trong tag vừa thêm ta cũng thêm một bản ghi mới trong tag_instance và với tag_id vừa thêm vào
                        $tag_instance['tagid'] = $mdltag->id;;
                        $tag_instance['ordering'] = $key;
                        $tag_instance['timecreated'] = time();
                        $tag_instance['timemodified'] = $tag_instance['timecreated'];
                        $mdltag_instance = new MdlTagInstance($tag_instance);
                        $mdltag_instance->store();
                    }
                }
                // Xóa những bản ghi trong tag_instance có ordering > $key (key_max)
                MdlTagInstance::where('ordering', '>', $key)->where('itemid', $user_id)->delete();
            }

            // unset các trường trong data dùng để xử lý mà không đưa vào mdl_user db
            unset($data['taggles']);
            unset($data['preference_auth_forcepasswordchange']);
            unset($data['createpassword']);

            foreach ($data as $name => $value) {
                if ($value != $mdluser->$name) {
                    $mdluser->$name = $value;
                }
            }
            $mdluser->store();
            $ms->addMessageTranslated("success", 'MDLUSER_UPDATE_SUCCESS', ["name" => $data['username']]);
        }
    }

    /*
     * Xử lý xóa người dùng
     * lib/moodlelib.php 3900
     * */
    public function deleteMdluser($user_id){
        $post = $this->_app->request->post();
        // Get the target user
        $target_user = MdlUser::find($user_id);

        //lấy dl siteadmins trong mdl_config - là các user_id của manager.
        $siteadmins = MdlConfig::where("name","siteadmins")->first();
        //sử dụng hàm explode để tách chuỗi, đưa các user_id của admin vào mảng các user_id
        $admins = explode(',', $siteadmins->value);

        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Kiểm tra xem tài khoản muốn xóa có là tài khoản guest
        // Guest user account can not be deleted.
        if ($target_user->username === 'guest'){
            $ms->addMessageTranslated("danger", "ACCOUNT_DELETE_GUEST");
            $this->_app->halt(403);
        }

        // Kiểm tra xem tài khoản muốn xóa có là tài khoản quản trị
        $isadmin = in_array($target_user->id, $admins);
        if ($target_user->auth === 'manual' and $isadmin ){
            $ms->addMessageTranslated("danger", "ACCOUNT_DELETE_ADMIN");
            $this->_app->halt(403);
        }

        // Keep user record before updating it, as we have to pass this to user_deleted event.
        // Phương thức clode dùng để sao chép một đối tượng.
        $olduser = clone $target_user;

        // Keep a copy of user context, we need it for event.
        $usercontext = MdlContext::where('instanceid', $user_id)->where('contextlevel', '=', 30)->first();

        // Delete all grades - backup is kept in grade_grades_history table.
        // Xóa dữ liệu của userid trong bảng mdl_grade_grades. Bảng này lưu điểm của học viên đã tham gia một bài kiểm tra nào đó trên hệ thống.
        // Có một số bảng khác có dl của người dùng nhưng không bị xóa như :mdl_grade_grades_history, mdl_grade_items_history, mdl_quiz_grades
        MdlGradeGrades::where('userid', $user_id)->delete();

        /*
         * Move unread messages from this user to read. =>>> message_move_userfrom_unread2read($user_id);
         * Chuyển tin nhắn chưa đọc từ người dùng để đọc
         *
         * Phần xử lý dữ liệu trong message
         * Tin nhắn chưa đọc sẽ được lưu trong các bảng: mdl_message_working và mdl_message
         * Tin nhắn đã được đọc sẽ được lưu trong bảng: mdl_message_read.
         * Khi xóa người dùng thì sẽ chuyển những tin nhắn chưa được đọc từ người dùng này vào bảng mdl_message_read và xóa dl của 2 bảng mdl_message_working và mdl_message.
         * */
        // move all unread messages from message table to message_read
        $messages = MdlMessage::where('useridfrom',$user_id)->get();
        foreach ($messages as $message) {
            $message->timeread = 0;//set timeread = 0 với những tin nhắn không bao giờ được đọc
            $messageid = $message->id;
            unset($message->id);//unset because it will get a new id on insert into message_read

            // Xóa Message trong bảng mdl_message_working
            MdlMessageWorking::where('unreadmessageid', $messageid)->delete();
            // Chuyển dl từ bảng mdl_message sang bảng mdl_message_read
            $messageread = new MdlMessageRead($message);
            // Xóa dữ liệu bảng mdl_message
            MdlMessage::where('id', $messageid)->delete();
        }

        // Remove user tags. xóa dữ liệu người dùng trong tag_instance
        MdlTagInstance::where('itemid', $user_id)->delete();

        // Unconditionally unenrol from all courses. Hủy ghi danh user trong tất cả các khóa học đã ghi danh. (trong bảng mdl_user_enrolments)
        // Brute force unenrol from all courses. ====>>>>>> $DB->delete_records('user_enrolments', array('userid' => $user->id));
        MdlUserEnrolments::where('userid',$user_id)->delete();

        // Unenrol from all roles in all contexts.
        // This might be slow but it is really needed - modules might do some extra cleanup! role_unassign_all(array('userid' => $user_id));
        // @TODO: role_unassign_all(array('userid' => $user_id)); 3950_moodlelib.php
        //Tạm thời chỉ xóa dữ liệu user trong bảng mdl_role_assignments
        MdlRoleAssignments::where('userid',$user_id)->delete();

        // Now do a brute force cleanup.
        // Remove from all cohorts. ====>>>>>> $DB->delete_records('cohort_members', array('userid' => $user->id));
        MdlCohortMembers::where('userid',$user_id)->delete();

        // Remove from all groups. ====>>>>>> $DB->delete_records('groups_members', array('userid' => $user->id));
        MdlGroupMembers::where('userid',$user_id)->delete();

        // Purge user preferences. ====>>>>>> $DB->delete_records('user_preferences', array('userid' => $user->id));
        MdlUserPreferences::where('userid',$user_id)->delete();

        // Purge user extra profile info. ====>>>>>> $DB->delete_records('user_info_data', array('userid' => $user->id));
        MdlUserInfoData::where('userid',$user_id)->delete();

        // Purge log of previous password hashes. ====>>>>>> $DB->delete_records('user_password_history', array('userid' => $user->id));
        MdlUserPasswordHistory::where('userid',$user_id)->delete();

        // Last course access not necessary either. ====>>>>> $DB->delete_records('user_lastaccess', array('userid' => $user->id));
        MdlUserLastaccess::where('userid',$user_id)->delete();

        // Remove all user tokens. ====>>>>> $DB->delete_records('external_tokens', array('userid' => $user->id));
        MdlExternalTokens::where('userid',$user_id)->delete();

        // Unauthorise the user for all services. ====>>>>> $DB->delete_records('external_services_users', array('userid' => $user->id));
        MdlExternalServicesUsers::where('userid',$user_id)->delete();

        // Remove users private keys. ====>>>>> $DB->delete_records('user_private_key', array('userid' => $user->id));
        MdlUserPrivateKey::where('userid',$user_id)->delete();

        // Remove users customised pages. ====>>>>> $DB->delete_records('my_pages', array('userid' => $user->id, 'private' => 1));
        MdlMyPages::where('userid',$user_id)->where('private',1)->delete();

        // @TODO: core\session\manager::kill_user_sessions($user->id); 3990_moodlelib.php
        // $DB->delete_records('sessions', array('sid'=>$sid));

        // Generate username from email address, or a fake email. =>>> Tạo tên người dùng từ địa chỉ email, hoặc từ một email giả mạo.
        $delemail = !empty($target_user->email) ? $target_user->email : $target_user->username . '.' . $target_user->id . '@unknownemail.invalid';
        $delname = str_replace(" s","", $delemail . "." . time()); // Xóa hết kí tự khoảng trống trong chuỗi
        $delname = preg_replace('/[^-\.@_a-z0-9]/', '', $delname);

        // Workaround for bulk deletes of users with the same email address.
        while (MdlUser::where('username',$delname)->first()) { // No need to use mnethostid here.
            $delname++;
        }

        // Mark internal user record as "deleted".
        $updateuser = new stdClass();
        $updateuser->id           = $user->id;
        $updateuser->deleted      = 1;
        $updateuser->username     = $delname;            // Remember it just in case.
        $updateuser->email        = md5($user->username);// Store hash of username, useful importing/restoring users.
        $updateuser->idnumber     = '';                  // Clear this field to free it up.
        $updateuser->picture      = 0;
        $updateuser->timemodified = time();

        // Don't trigger update event, as user is being deleted.
        user_update_user($updateuser, false, false);

        // Now do a final accesslib cleanup - removes all role assignments in user context and context itself.
        context_helper::delete_instance(CONTEXT_USER, $user->id);


        $ms->addMessageTranslated("success", "ACCOUNT_DELETION_SUCCESSFUL", ["user_name" => $target_user->user_name]);
        $target_user->delete();
        unset($target_user);
    }
}