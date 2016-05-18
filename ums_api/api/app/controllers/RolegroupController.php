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
     * @param limit, page
     * @return RoleObject
     */
    public function listroleAction()
    {
        try{
            $datapost = Helper::post_to_array("limit,page");// Get data from form
            // Tính toán tham số phân trang
            $total = Rolegroup::count(); // Tổng số bản ghi
            $limit = trim($datapost["limit"]); // số record trên một trang
            if(($limit == 0) || $limit =='all' || $limit ==''){
                $limit = $total;
            }
            $p = trim($datapost["page"]); // trang muốn lấy
            if ($p <= 1 || $p =='' ) $p = 1;

            $cp = ($p - 1) * $limit; // bắt đầu lấy từ bản ghi cp

            $roleobject = Rolegroup::find(array(
                "columns"=>"id,name,level,permissions,manageid",
                "order" => "id DESC",
                "limit" => $limit,
                "offset" => $cp // lấy từ bản ghi $cp là chỉ lấy $limit bản ghi
            ));
            $paging = array(
                "limit" => "$limit",
                "page" => "$p",
                "total" => "$total"
            );
            if(!empty($roleobject)) $roleobject = $roleobject->toArray();
            foreach($roleobject as $key => $value){
                $roleobject[$key]['permissions'] = explode(",",$value['permissions']);
            }
            $this->datarespone = array("status"=>1,"mss"=>"Successfully","paging" => $paging,"data"=>$roleobject);
        }
        catch(Exception $e){
            $this->datarespone = array("status"=>0,"mss"=>$e->getMessage(),"paging" => new stdClass(),"data"=>new stdClass());
        }
        $this->setPayload($this->datarespone);
        $this->render();
    }
}