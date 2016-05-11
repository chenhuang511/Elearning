<?php

/**
 * Created by PhpStorm.
 * User: VietNH
 * Date: 5/10/2016
 * Time: 4:05 PM
 */
class AuthController extends RESTController
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Login API
     * @param username
     * @param password
     */
    public function loginAction()
    {
        $username = $this->request->get("username"); // get username from request
        $password = $this->request->get("password"); // get password from request
        $userobject = User::findFirst(array(
            "columns"=>"id,firstname,lastname,username,address,email",
            "conditions"=>"username = :username: and password=:password:",
            "bind"=>array("username"=>$username,"password"=>Helper::encryptpassword($password)),
            "cache"=>array("key"=>$username.$password)
        )); // select user from cache, and DB
        if($userobject->id<=0){ // if user not available
            $this->session->destroy(); // remove session
            $dtr['status'] = 0; // return data with message
            $dtr['mss'] = "Cannot find user";
            $dtr['data'] = new stdClass();
            $this->setPayload($dtr);
        }
        else{ // if user available
            $dtr['status'] = 1;
            $dtr['mss'] = "Successfully";
            $tokenkey = $this->session->getId(); // get session id and set to tokenkey
            $this->modelsCache->save($tokenkey,$userobject); // save tokenkey to redis cache for re-use lasted request
            $userobject = $userobject->toArray(); // convert user object to array
            $userobject['tokenkey'] = $this->session->getId(); // set return data with tokenkey
            $dtr['data'] = $userobject;
            $this->setPayload($dtr);
        }
        $this->render();
    }
}