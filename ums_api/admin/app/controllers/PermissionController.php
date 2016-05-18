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

    /***
     * Danh sách permission trên hệ thống
     * @return view
     */
    public function indexAction()
    {
        // Kiểm tra quyền thao tác
        if (!$this->checkpermission("permission_view")) return false;
        $cattree = self::getMenu(0);    // Lấy danh sách cây phân quyền
        $this->view->cattree =  $cattree;
    }

    /***
     * Form xử lý thông tin nhóm quyền
     * @return view
     */
    public function formAction()
    {
        $id = $this->request->get('id');
        // Lấy thông tin về parentid
        $parentid = $this->request->get('parentid');
        
        // Kiểm tra quyền để thao tác
        if(!empty($id)){
            if (!$this->checkpermission("permission_update")) return false;
        }
        else {
            if (!$this->checkpermission("permission_add")) return false;
        }
        if ($this->request->isPost()) {
            try {
                $datapost = Helper::post_to_array('key,note,status');
                if ($id > 0) {// Cập nhật
                    $p = Permission::findFirst($id);
                }
                else{// Add new
                    $p = new Permission();
                    $datapost['parentid'] = (int)$parentid; // Gán parentid cho permission mới để biết cha nó là ai
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
        if (!empty($id)) $p = Permission::findFirst($id); // Kiểm tra id xem có hệ thống chưa, nếu có thì lấy ra rồi truyền vào form edit
        $this->view->object = $p;
    }

    /***
     * Action xử lý việc xóa một permission
     * @return boolean
     */
    public function deleteAction()
    {
        // Kiểm tra quyền để thao tác
        if (!$this->checkpermission("permission_delete")) return false;
        $id = $this->request->get('id');
        $p = Permission::findFirst($id);
        if($p){
            try{
                $p->delete();
                self::deletePermissionChild($id); // Xóa các con của nó
                $this->flash->success("Delete success !!");
            } catch (Exception $e){
                $this->flash->error($e->getMessage());
            }
        }
        $this->response->redirect($this->request->getHTTPReferer());
    }

    /***
     * Action xử lý việc xóa một permission
     * @param $id tên id của permission
     * @return boolean
     */
    public function deletePermissionChild($id){
        $listpermissions = Permission::find(array("conditions" => "parentid=$id"));
        $listpermissions = $listpermissions->toArray(); // Lấy danh sách permission dưới dạng mảng
        if(!$listpermissions) return null; // Kiểm tra xem danh sách có tồn tại hay không
        foreach ($listpermissions as $row){
            Permission::findFirst($row['id'])->delete();
            self::deletePermissionChild($row['id']);
        }
        return 1;
    }

    /***
     * Phương thức xử lý việc hiển thị cây đệ quy
     * @param $parentid tên parentid của permission
     * @return định dạng html
     */
    public function getMenu($parentid)
    {
        $listdata = Permission::find(array("conditions" => "parentid=$parentid"));
        $listdata = $listdata->toArray();
        if (!$listdata) return null;
        // Thực hiện đệ quy để hiện thị ra cây permission dưới dạng html
        $html = "<ol class='tree'>";
        foreach ($listdata as $row) {
            $html .= "<li id='{$row['id']}'>";
            $row['status'] == 0 ? $html .= "<span style='text-decoration:line-through'> {$row['key']} </span> &nbsp;&nbsp; " : $html .= " {$row['key']} &nbsp;&nbsp; ";
            $html .= "<a href='form/?id={$row['id']}'>Edit</a> | ";
            $html .= "<a href='delete/?id={$row['id']}' onclick=\"return confirm('Are you sure?');\" title=\"Delete\">Delete</a> | ";
            $html .= "<a href='form/?parentid={$row['id']}'>Add child</a> &nbsp;&nbsp;";
            $html .= self::getMenu($row['id']);
            $html .= "</li>";
        }
        $html .= "</ol>";
        return $html;
    }
}