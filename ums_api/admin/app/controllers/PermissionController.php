<?php

/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 5/11/2016
 * Time: 8:58 AM
 */
class PermissionController extends ControllerBase
{
    public function initialize()
    {
        $this->modulename = "permission";
        $this->view->activesidebar = "/permission/index";
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->checkpermission("permission_view")) return false;
        $cattree = self::getMenu(0);
//        var_dump($cattree); die;
        $this->view->cattree =  $cattree;
    }

    public function formAction()
    {
        $id = $this->request->get('id');
        if(!empty($id)){
            if (!$this->checkpermission("permission_update")) return false;
        }
        else {
            if (!$this->checkpermission("permission_add")) return false;
        }
        $parentid = $this->request->get('parentid');
        if ($this->request->isPost()) {
            try {
                $datapost = Helper::post_to_array('key,note,status');
                if ($id > 0) {//Update
                    $p = Permission::findFirst($id);
                }
                else{// Add new
                    $p = new Permission();
                    $datapost['parentid'] = (int)$parentid;
                    if(!$datapost['status']) $datapost['status'] = 0;
                }
                $p->map_object($datapost);

                $p->save();
                header("Location:/permission/index");
            }catch (Exception $e){
                if((int)$e->getCode()==23000) $this->flash->error($this->culture['general.lbl_duplicatepermission']);
                else $this->flash->error($e->getMessage());
            }
        }
        if (!empty($id)) $p = Permission::findFirst($id);
        $this->view->object = $p;
    }

    public function deleteAction()
    {
        if (!$this->checkpermission("permission_delete")) return false;
        $id = $this->request->get('id');
        $p = Permission::findFirst($id);
        if($p){
            try{
                $p->delete();
                self::getPermission($id);
                $this->flash->success("Delete success !!");
            } catch (Exception $e){
                $this->flash->error($e->getMessage());
            }
        }
        $this->response->redirect($this->request->getHTTPReferer());
    }

    public function getPermission($id){
        $listpermissions = Permission::find(array("conditions" => "parentid=$id"));
        $listpermissions = $listpermissions->toArray();
        if(!$listpermissions) return null;
        foreach ($listpermissions as $row){
            Permission::findFirst($row['id'])->delete();
            self::getPermission($row['id']);
        }
        return 1;
    }

    public function getMenu($parentid)
    {
        $listdata = Permission::find(array("conditions" => "parentid=$parentid"));
        $listdata = $listdata->toArray();
        if (!$listdata) return null;

        $html = "<ol class='dd-list'>";
        foreach ($listdata as $row) {
            $status = $row['status'] == 1 ? '' : '<span class="label label-danger">H</span>';
            $html .= "<li class='dd-item' id='{$row['id']}'> {$row['key']} &nbsp;&nbsp; ";
            $html .= "<a href='form/?id={$row['id']}'>Sửa</a> | ";
            $html .= "<a href='delete/?id={$row['id']}' onclick=\"return confirm('Are you sure?');\" title=\"Delete\">Xóa</a> | ";
            $html .= "<a href='form/?parentid={$row['id']}'>Thêm con</a> &nbsp;&nbsp; $status</li>";
            $html .= self::getMenu($row['id']);
            $html .= "</li>";
        }
        $html .= "</ol>";
        return $html;
    }
}