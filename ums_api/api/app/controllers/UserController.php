<?php

/**
 * Created by PhpStorm.
 * User: VietNH
 * Date: 5/11/2016
 * Time: 10:29 AM
 */
class UserController extends RESTController
{
    /***
     * Create User - Tạo mới user
     * @param username ,password,email,firstname,lastname,dob,address,phone,gender
     * @return UserObject
     */
    public function createAction()
    {
        try {
            // Xử lý form
            $datapost = Helper::post_to_array("username,password,email,firstname,lastname,dob,address,phone,gender");// Get data from form
            $datapost['username'] = strtolower($datapost['username']);
            $datapost['password'] = Helper::encryptpassword($datapost['password']);
            $datapost['dob'] = strtotime($datapost['dob']);
            $datapost['flags'] = "user";
            $flag_insert = true; // Đánh dấu tất cả các validator đều hợp lệ. Nếu sai, không cho insert vào DB
            $status = $mss = "";// Thông báo trạng thái khi thực hiện thao tác
            // Validation
            if(strlen($datapost['username'])<=0){
                $flag_insert = false;
                $status = 0;
                $mss = "Fill your username";
            }
            if (strlen($datapost['email']) > 0) { // Kiểm tra xem email đã tồn tại trên hệ thống hay chưa
                $c = User::count(array(
                    "conditions" => "email = :email:",
                    "bind" => array("email" => $datapost['email'])
                ));
                if($c>0) {
                    $flag_insert = false;
                    $status = 0;
                    $mss = "Email is available in system";
                }
            }
            if($flag_insert==true){ // nếu tất cả thông tin là hợp lệ thông qua biến flag_insert
                $o = new User(); // Tạo mới object user
                $datapost['datecreate'] = time(); // khởi tạo các giá trị ban đầu
                $o->map_object($datapost); // Đồng bộ hóa form với các thông tin cột trong userobject
                $o->save(); // Lưu vào Database
                $userobject = User::findFirst(array(
                    "conditions"=>"username = :username: and password = :password:",
                    "bind"=>array("username"=>$datapost['username'],"password"=>$datapost['password'])
                ))->toArray(); // Select ngược lại thông tin để lấy chính xác thông tin user trả về cho client
                $tokenkey = $this->session->getId(); // get session id and set to tokenkey
                $userobject['tokenkey'] = $tokenkey; // set return data with tokenkey
                $this->session->set("uinfo", $userobject); // Set lại đăng nhập cho user
                $this->datarespone = array("status"=>1,"mss"=>"Successfully","data"=>$userobject); // Set giá trị vào biến datarespone trong RESTController để trả về client
            }
            else{
                
            }
        } catch (Exception $e) { // Xử lý thông báo lỗi
            $this->datarespone = array("status"=>0,"mss"=>$e->getMessage(),"data"=>new stdClass());
            $this->session->destroy();// Hủy session thông tin user
            session_destroy();
        }
        $this->setPayload($this->datarespone); // Trả dữ liệu cho client
        $this->render();
    }

    /***
     * Detail User - Xem thông tin user
     * @param userid
     * @return UserObject(userid)
     */
    public function detailAction(){
        try{
            // Xử lý form
            $userid = $this->request->get("userid","int");
            $mss = "";
            $userobject = User::findFirst(array(
                "conditions"=>"id = :userid:",
                "bind"=>array("userid"=>$userid)
            ))->toArray();
            $this->datarespone = array("status"=>1,"mss"=>"Successfully","data"=>$userobject);
            $this->setPayload($this->datarespone);
        }
        catch (Exception $e){

        }
        $this->render();
    }

}