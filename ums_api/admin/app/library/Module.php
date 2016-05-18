<?php

class Module
{
    public static function Permission()
    {
        $permission["loginsystem"] = array("name" => "Đăng nhập hệ thống");
        $permission["rolegroup"] = array("name" => "Nhóm quyền", "child" => array(
            array("name" => "Xem danh sách", "key" => "view"),
            array("name" => "Thêm mới", "key" => "add"),
            array("name" => "Sửa", "key" => "update"),
            array("name" => "Xóa", "key" => "delete")
        ));

        $permission["user"] = array("name" => "User", "child" => array(
            array("name" => "Xem danh sách", "key" => "view"),
            array("name" => "Thêm mới", "key" => "add"),
            array("name" => "Sửa", "key" => "update"),
            array("name" => "Xóa", "key" => "delete"),
            array("name" => "Thay đổi nhóm quyền", "key" => "role")
        ));
        $permission["permission"] = array("name" => "Permission", "child" => array(
            array("name" => "Xem danh sách", "key" => "view"),
            array("name" => "Thêm mới", "key" => "add"),
            array("name" => "Sửa", "key" => "update"),
            array("name" => "Xóa", "key" => "delete")
        ));
        return $permission;
    }

    public static function Sidebar($langarr)
    {
        $sidebar[] = array("name" => $langarr['sidebar.home'], "icon" => "zmdi zmdi-home", "key" => "loginsystem", "controller" => "/index/index");
        $sidebar[] = array("name" => $langarr['sidebar.account'], "icon" => "zmdi zmdi-account", "key" => "user,rolegroup", "controller" => "javascript:void(0)", "child" => array(
            array("name" => $langarr['sidebar.user'], "key" => "loginsystem", "controller" => "/user/index"),
            array("name" => $langarr['sidebar.rolegroup'], "key" => "loginsystem", "controller" => "/rolegroup/index"),
            array("name" => $langarr['sidebar.permission'], "key" => "loginsystem", "controller" => "/permission/index")
        ));

        return $sidebar;
    }

    public static function is_accept_permission($key)
    {
        $uinfo = $_SESSION['uinfo'];
        $permissionlist = $uinfo['listpermission'];
        if (count($permissionlist) <= 0) $permissionlist = array();
        $tmp = explode(",", $key);
        if (count($tmp) > 0) {
            $rs = array_intersect($tmp, $permissionlist);
            if (count($rs) > 0) return 1;
            else return 0;
        } else {
            if (in_array($key, $permissionlist)) return 1;
            else return 0;
        }
    }
}

?>