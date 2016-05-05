<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 4/26/2016
 * Time: 3:08 PM
 */
namespace UserFrosting;

use PhpParser\Node\Stmt\Goto_;

class MdlPermissionsController extends \UserFrosting\BaseController{

    public function __construct($app)
    {
        $this->_app = $app;
    }

    // Hiển thị danh sách các roles trong hệ thống
    public function manageRoles(){
        // Lấy danh sách các roles và sắp xếp theo thứ tự tăng dần theo trường sortorder
        $roles = MdlRole::queryBuilder()->orderBy('sortorder','asc')->get();
        // Lấy role đầu tiên trong danh sách role
        $firstrole = reset($roles);
        // Lấy role cuối cùng trong danh sách role
        $lastrole = end($roles);
        $undeletablerole = array();

        // Lấy tất cả các bản ghi trong 3 bảng mdl_role_allow_assign, mdl_role_allow_override, mdl_role_allow_switch
        $roleallowassign = MdlRoleAllowAssign::all();
        $roleallowoverride = MdlRoleAllowOverride::all();
        $roleallowswitch = MdlRoleAllowSwitch::all();

        foreach ($roles as &$role){
            // Thực hiện gán tên khi phần tên của role trong CSDL là rỗng
            if(empty($role['name']))
                switch ($role['shortname']){
                    case 'manager':         $role['name'] = 'Manager'; break;
                    case 'coursecreator':   $role['name'] = "Course creator";break;
                    case 'editingteacher':  $role['name'] = "Teacher"; break;
                    case 'teacher':         $role['name'] = "Non-editing teacher"; break;
                    case 'student':         $role['name'] = "Student"; break;
                    case 'guest':           $role['name'] = "Guest";break;
                    case 'user':            $role['name'] = "Authenticated user"; break;
                    case 'frontpage':       $role['name'] = "Authenticated user on frontpage"; break;
                    // We should not get here, the role UI should require the name for custom roles!
                    default:                break;
                }

            // Thực hiện gán mô tả cho role khi trong CSDL là rỗng
            if(empty($role['description']))
                switch ($role['shortname']){
                    case 'manager':         $role['description'] = "Managers can access course and modify them, they usually do not participate in courses."; break;
                    case 'coursecreator':   $role['description'] = "Course creators can create new courses."; break;
                    case 'editingteacher':  $role['description'] = "Teachers can do anything within a course, including changing the activities and grading students."; break;
                    case 'teacher':         $role['description'] = "Non-editing teachers can teach in courses and grade students, but may not alter activities."; break;
                    case 'student':         $role['description'] = "Students generally have fewer privileges within a course."; break;
                    case 'guest':           $role['description'] = "Guests have minimal privileges and usually can not enter text anywhere."; break;
                    case 'user':            $role['description'] = "All logged in users."; break;
                    case 'frontpage':       $role['description'] = "All logged in users in the frontpage course."; break;
                    // We should not get here, the role UI should require the name for custom roles!
                    default:                break;
                }
            // Gán những role không thể xóa
            switch ($role['shortname']){
                case 'user':           $undeletablerole[$role['id']] = 1;
                case 'guest':           $undeletablerole[$role['id']] = 1;
            }
        }

        $this->_app->render("permissions/manage-roles.twig",[
            "title" => "Define Roles",
            "roles" => $roles,
            "firstrole" => $firstrole,
            "lastrole" => $lastrole,
            "undeletablerole" => $undeletablerole,
            "roleallowassign" => $roleallowassign,
            "roleaalowoverride" => $roleallowoverride,
            "roleallowswitch" => $roleallowswitch,
            "formactionassignments" => $this->_app->site->uri['public'] . "/roles/manage/assignments",
            "formactionoverrides" => $this->_app->site->uri['public'] . "/roles/manage/overrides",
            "formactionswitchs" => $this->_app->site->uri['public'] . "/roles/manage/switchs",
        ]);
    }

    // Hàm xử lý khi tăng độ ưu tiên của role
    public function moveupRole($roleid){
        $post = $this->_app->request()->post();
        unset($post['csrf_token']);

        $ms = $this->_app->alerts;

        $roles = MdlRole::orderBy('sortorder','asc')->get();
        $tmp = new MdlRole();
        // Role lựa chọn để tăng độ ưu tiên
        $selectedrole = MdlRole::find($roleid);
        $prevrole = null;

        // Lấy role trước role lựa chọn để thực hiện swap
        foreach($roles as $role){
            if($role->id == $selectedrole->id)
            {
                break;
            }
            else
                $prevrole = $role;
        }
        // Kiểm tra xem có phải role đầu tiên hay không
        if(empty($prevrole)){
            $ms->addMessageTranslated("danger", 'CAN_NOT_SWITCH');
            $this->_app->halt(400);
        }
        // Xử lý swap 2 role cho nhau qua một role $tmp
        $tmp->sortorder = $selectedrole->sortorder;
        $selectedrole->sortorder = $prevrole->sortorder;
        $prevrole->sortorder = null;
        $prevrole->store();
        $selectedrole->store();

        $prevrole->sortorder = $tmp->sortorder;
        $prevrole->store();
        $tmp->delete();
        unset($tmp);

        $ms->addMessageTranslated("success", 'SWITCH_SUCCESSFULLY');
    }

    // Hàm xử lý khi giảm độ ưu tiên của role
    public function movedownRole($roleid)
    {
        $post = $this->_app->request()->post();
        unset($post['csrf_token']);

        $ms = $this->_app->alerts;

        $roles = MdlRole::orderBy('sortorder','asc')->get();
        $tmp = new MdlRole();
        // Role lựa chọn để tăng độ ưu tiên
        $selectedrole = MdlRole::find($roleid);
        $thisrole = null;
        // Role kế tiếp sau Role lựa chọn
        $nextrole = null;

        foreach ($roles as $role){
            if($role->id == $selectedrole->id){
                $thisrole = $selectedrole;
            }
            else if (!empty($thisrole)){
                $nextrole = $role;
                break;
            }
        }
        // Kiểm tra xem có phải Role cuối cùng trong danh sách hay không
        if(empty($nextrole)){
            $ms->addMessageTranslated("danger", 'CAN_NOT_SWITCH');
            $this->_app->halt(400);
        }

        // Xử lý swap 2 role cho nhau qua một role $tmp
        $tmp->sortorder = $thisrole->sortorder;
        $thisrole->sortorder = $nextrole->sortorder;
        $nextrole->sortorder = null;
        $nextrole->store();
        $thisrole->store();

        $nextrole->sortorder = $tmp->sortorder;
        $nextrole->store();
        $tmp->delete();
        unset($tmp);

        $ms->addMessageTranslated("success", 'SWITCH_SUCCESSFULLY');

    }

    // Xử lý những thay đổi trong bảng Allow Role Assigments
    public function allowRoleAssignments(){
        $post = $this->_app->request()->post();
        $ms = $this->_app->alerts;

        // Xóa tất cả các bản ghi, sử dụng queryBuilder->truncate
        MdlRoleAllowAssign::queryBuilder()->truncate();

        if (isset($post['allow_role'])) {
            $allowroles = $post['allow_role'];

            // Lưu vào trong database allow_role_assignments
            foreach ($allowroles as $fromroleid => $targetrole)
                foreach ($targetrole as $targetroleid => $value) {
                    $data['roleid'] = $fromroleid;
                    $data['allowassign'] = $targetroleid;
                    $roleallowassignments = new MdlRoleAllowAssign($data);
                    $roleallowassignments->store();
                }
            $ms->addMessageTranslated("success", 'UPDATE_ALLOW_ROLE_ASSIGNMENTS_SUCCESSFULLY');
        }
    }

    // Xử lý những thay đổi trong bảng Allow Role Overrides
    public function allowRoleOverrides(){
        $post = $this->_app->request()->post();
        $ms = $this->_app->alerts;

        // Xóa tất cả các bản ghi, sử dụng queryBuilder->truncate
        MdlRoleAllowOverride::queryBuilder()->truncate();

        if (isset($post['allow_role'])) {
            $allowroles = $post['allow_role'];

            // Lưu vào trong database allow_role_overrides
            foreach ($allowroles as $fromroleid => $targetrole)
                foreach ($targetrole as $targetroleid => $value) {
                    $data['roleid'] = $fromroleid;
                    $data['allowoverride'] = $targetroleid;
                    $roleallowoverrides = new MdlRoleAllowOverride($data);
                    $roleallowoverrides->store();
                }
            $ms->addMessageTranslated("success", 'UPDATE_ALLOW_ROLE_OVERRIDES_SUCCESSFULLY');
        }
    }

    // Xử lý những thay đổi trong bảng Allow Role Switchs
    public function allowRoleSwitchs(){
        $post = $this->_app->request()->post();
        $ms = $this->_app->alerts;

        // Xóa tất cả các bản ghi, sử dụng queryBuilder->truncate
        MdlRoleAllowSwitch::queryBuilder()->truncate();

        if (isset($post['allow_role'])) {
            $allowroles = $post['allow_role'];

            // Lưu vào trong database allow_role_switchs
            foreach ($allowroles as $fromroleid => $targetrole)
                foreach ($targetrole as $targetroleid => $value) {
                    $data['roleid'] = $fromroleid;
                    $data['allowswitch'] = $targetroleid;
                    $roleallowswitchs = new MdlRoleAllowSwitch($data);
                    $roleallowswitchs->store();
                }
            $ms->addMessageTranslated("success", 'UPDATE_ALLOW_ROLE_SWITCHS_SUCCESSFULLY');
        }
    }
}