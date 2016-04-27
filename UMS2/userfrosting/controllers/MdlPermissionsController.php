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

    public function manageRoles(){
        $roles = MdlRole::queryBuilder()->orderBy('sortorder','asc')->get();
        $firstrole = reset($roles);
        $lastrole = end($roles);

        $undeletablerole = array();

        foreach ($roles as &$role){
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
            "undeletablerole" => $undeletablerole
        ]);
    }

}