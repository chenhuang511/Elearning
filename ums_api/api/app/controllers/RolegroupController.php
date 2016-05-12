<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 12/05/2016
 * Time: 9:56 SA
 */
class RolegroupController extends RESTController
{
    /***
     * role list - show list role trong hệ thống
     * @param
     * @return RoleObject
     */
    public function listroleAction()
    {
        try{
            $roleobject = Rolegroup::find();
            $this->datarespone = array("status"=>1,"mss"=>"Successfully","data"=>$roleobject->toArray());
        }
        catch(Exception $e){
            $this->datarespone = array("status"=>0,"mss"=>$e->getMessage(),"data"=>new stdClass());
        }
        $this->setPayload($this->datarespone);
        $this->render();
    }
}