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
     * The index action
     */
    public function loginAction()
    {
        $username = $this->request->get("username");
        $password = $this->request->get("password");
        $userobject = User::findFirst(array(
            "columns"=>"id,firstname,lastname,username,address,email",
            "conditions"=>"username = :username: and password=:password:",
            "bind"=>array("username"=>$username,"password"=>Helper::encryptpassword($password)),
            "cache"=>array("key"=>$username.$password)
        ));
        if($userobject->id<=0){
            $this->session->destroy();
            $dtr['status'] = 0;
            $dtr['mss'] = "Cannot find user";
            $this->setPayload($dtr);
        }
        else{
            $dtr['status'] = 1;
            $dtr['mss'] = "Successfully";
            $tokenkey = $this->session->getId();
            $this->modelsCache->save($tokenkey,$userobject);
            $userobject = $userobject->toArray();
            $userobject['tokenkey'] = $this->session->getId();
            $dtr['data'] = $userobject;
            $this->setPayload($dtr);
        }
        echo $this->render();
    }
}