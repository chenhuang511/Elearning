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
            $roleobject = Rolegroup::find(array("columns"=>"id,name,level,permissions,manageid"));
            if(!empty($roleobject)) $roleobject = $roleobject->toArray();
            foreach($roleobject as $key => $value){
                $roleobject[$key]['permissions'] = explode(",",$value['permissions']);
            }
            $this->datarespone = array("status"=>1,"mss"=>"Successfully","data"=>$roleobject);
        }
        catch(Exception $e){
            $this->datarespone = array("status"=>0,"mss"=>$e->getMessage(),"data"=>new stdClass());
        }
        $this->setPayload($this->datarespone);
        $this->render();
    }
}