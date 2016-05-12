<?php

class RolegroupController extends ControllerBase
{
    public function initialize()
    {
        $this->modulename = "rolegroup";
        $this->view->activesidebar = "/rolegroup/index";
        parent::initialize();
    }

    /***
     * Danh sách nhóm quyền trên hệ thống
     * @return view
     */
    public function indexAction()
    {
        // Check quyền thao tác
        if (!$this->checkpermission("rolegroup_view")) return false;
        $userinfo = $this->userinfo; // Lấy userinfo login từ session
        $limit = 20; // Phân trang
        $p = $this->request->get("p");
        if ($p <= 1) $p = 1;
        $cp = ($p - 1) * $limit;
        $listrole = $userinfo['listrole']; // Set danh sách quyền mà user login được quyền quản trị
        $query = "id > 0";
        if (!in_array("all", $listrole)) { // Nếu ko có từ khóa all, thì check các role nằm trong đó
            if (count($listrole) > 0) $listrole = implode(",", $listrole); // Nếu mảng role ko có phần tử, set về giá trị 0 để tránh select lỗi
            if(strlen($listrole)<=0) $listrole = "0";
            $query .= " and id in($listrole)";
        }
        $q = $this->request->getQuery("q", "string");
        if ($q) $query .= " AND name LIKE '%" . $q . "%'";
        $listdata = Rolegroup::find(
            array(
                "conditions" => $query,
                "order" => "level asc",
                "limit" => $limit,
                "offset" => $cp
            )
        );

        $this->view->q = $q;
        $this->view->listdata = $listdata;
        $this->view->painginfo = Helper::paginginfo(Rolegroup::count($query), $limit, $p);
    }

    /***
     * Form xử lý thông tin nhóm quyền
     * @return view
     */
    public function formAction()
    {
        $id = $this->request->get("id");
        // Check permission to process
        if (!empty($id)) {
            if (!$this->checkpermission("rolegroup_update")) return false;
        } else {
            if (!$this->checkpermission("rolegroup_add")) return false;
        }
        $uinfo = (array)$this->session->get("uinfo"); // Select userinfo login from session

        if ($this->request->isPost()) { // If form save
            try {
                $datapost = Helper::post_to_array("name,level,permissions,rolemanageid");
                $datapost['permissions'] = implode(",", $datapost['permissions']); // Select Permission from form and implode array to string
                $datapost['manageid'] = implode(",", $datapost['rolemanageid']); // Select RoleID from form and implode array to string
                unset($datapost['rolemanageid']);
                // <editor-fold desc="Validate">
                if ($id > 0) { // Update
                    $o = Rolegroup::findFirst($id);
                } else { //insert
                    $o = new Rolegroup();
                    $datapost['datecreate'] = time();
                    $datapost['usercreate'] = $uinfo['id'];
                }
                $o->map_object($datapost);
                // </editor-fold>
                $o->save();
                $this->flash->success("Information saved !");
            } catch (Exception $e) {
                $this->flash->error($e->getMessage());
            }

        }
        // Select and bind to view old value
        if (!empty($id)) $o = Rolegroup::findFirst($id); // Select Role by ID if id exist
        $activepermission = $o->permissions; // set permission to list
        $o->manageid = explode(",", $o->manageid); // explode manageid to array
        $this->view->object = $o; // set RoleObject to object in view

        $activepermission = explode(",", $activepermission); // explode permission from string to array
        $module = new Module(); // Get list permission from system
        $listpermission = $module->Permission();
        //set active for permission
        foreach ($listpermission as $key => $item) {
            if (in_array($key, $activepermission)) $listpermission[$key]['checked'] = 'checked';
            else $listpermission[$key]['checked'] = '';
            foreach ($item['child'] as $ckey => $val) {
                if (in_array($key . "_" . $val['key'], $activepermission)) $listpermission[$key]['child'][$ckey]['checked'] = "checked";
                else $listpermission[$key]['child'][$ckey]['checked'] = "";
            }
        }

        //Select list role for manage
        $listdata = Rolegroup::find(
            array(
                "conditions" => "1=1",
                "order" => "level asc"
            )
        )->toArray();
        foreach ($listdata as $key => $item) {
            if (in_array($item['id'], $o->manageid)) $listdata[$key]['checked'] = 'checked';
            else $listdata[$key]['checked'] = '';
        }
        //set active for roleid
        $this->view->listdata = $listdata;
        $this->view->module = $listpermission;
        $this->view->backurl = strlen($this->request->getHTTPReferer()) <= 0 ? $this->view->activesidebar : $this->request->getHTTPReferer();
    }

    public function deleteAction()
    {
        if (!$this->checkpermission("rolegroup_delete")) return false;
        $id = $this->request->get("id");
        $o = Rolegroup::findFirst($id);
        if ($o) {
            try {
                $o->delete();
                $this->flash->success($this->view->labelkey['general.lbl_process_success']);
            } catch (Exception $e) {
                $this->flash->error($e->getMessage());
            }
        }
        $this->response->redirect($this->request->getHTTPReferer());
    }
}

