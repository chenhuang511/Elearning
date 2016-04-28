<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/29/2016
 * Time: 2:17 PM
 */
namespace UserFrosting;

use PhpParser\Node\Stmt\Goto_;

class MdlCohortController extends \UserFrosting\BaseController
{

    public function __construct($app)
    {
        $this->_app = $app;
    }

    public function listAllCohort()
    {
        $cohorts = MdlCohort::queryBuilder()
            ->leftJoin("mdl_context", "mdl_cohort.contextid", "=", "mdl_context.id")
            ->leftJoin("mdl_course_categories", "mdl_context.instanceid", "=", "mdl_course_categories.id")
            ->leftJoin("mdl_cohort_members", "mdl_cohort.id", "=", "mdl_cohort_members.cohortid")
            ->selectRaw('mdl_cohort.*, mdl_course_categories.name as cname, count(mdl_cohort_members.cohortid) as count_members')
            ->groupBy('mdl_cohort.id')
            ->get();
        
        $this->_app->render("cohorts/list-all-cohort.twig", [
            "cohorts" => $cohorts
        ]);
    }

    public function listCatCohort($contextid){

        $box_title = MdlCourseCategories::queryBuilder()
            ->join("mdl_context", "mdl_course_categories.id", "=", "mdl_context.instanceid")
            ->where("mdl_context.id", $contextid)
            ->first();
        $cohorts = MdlCohort::queryBuilder()
            ->where("mdl_cohort.contextid", $contextid)
            ->leftJoin("mdl_cohort_members", "mdl_cohort.id", "=", "mdl_cohort_members.cohortid")
            ->selectRaw('mdl_cohort.*, count(mdl_cohort_members.cohortid) as count_members')
            ->groupBy('mdl_cohort.id')
            ->get();

        $this->_app->render("cohorts/list-cat-cohort.twig",[
            "box_title" => ($contextid != 1) ? $box_title['name'] : "System",
            "cohorts" => $cohorts,
            "contextid" => $contextid
        ]);

    }

    public function CreateCohort($context_id)
    {
        $coursecats = MdlCourseCategories::queryBuilder()
            ->join("mdl_context", "mdl_course_categories.id", "=", "mdl_context.instanceid")
            ->where("mdl_context.contextlevel", "40")
            ->get();

        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/cohort-create.json");
        $this->_app->jsValidator->setSchema($schema);

        $this->_app->render("cohorts/form-edit-cohort.twig", [
            "box_title" => "Create Cohort",
            "contextid" => $context_id,
            "form_action" => $this->_app->site->uri['public'] . "/forms/cohorts",
            "cohort" => array(
                "contextid" => $context_id
            ),
            "coursecats" => $coursecats,
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

    public function submitCreateCohort()
    {
        $post = $this->_app->request->post();
//        var_dump($post);die();
        // DEBUG: view posted data
        // error_log(print_r($post, true));
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/cohort-create.json");
        // Get the alert message stream
        $ms = $this->_app->alerts;

        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        // Sanitize data
        $rf->sanitize();

        // Validate, and halt on validation errors.
        if (!$rf->validate()) {
            $this->_app->halt(400);
        }

        // Get the filtered data
        $data = $rf->data();

        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);

        // Perform desired data transformations on required fields.

        if (!$data['idnumber']) {
            $data['idnumber'] = '';
        }

        $data['description'] = $_POST['description'];

        $data['descriptionformat'] = 1;
        $data['timecreated'] = time();
        $data['timemodified'] = $data['timecreated'];

        // Check if group name already exists
        if (MdlCohort::where('name', $data['name'])->first()) {
            $ms->addMessageTranslated("danger", "COHORT_NAME_IN_USE", $data);
            $this->_app->halt(400);
        }

        // Create the group
        $group = new MdlCohort($data);

        // Store new group to database
        $group->store();

        // Success message
        $ms->addMessageTranslated("success", 'COHORT_CREATION_SUCCESSFUL', $data);
    }

    public function EditCohort($cohort_id, $context_id)
    {
        $cohort = MdlCohort::find($cohort_id)->toArray();

        $coursecats = MdlCourseCategories::queryBuilder()
            ->join("mdl_context", "mdl_course_categories.id", "=", "mdl_context.instanceid")
            ->where("mdl_context.contextlevel", "40")
            ->get();

        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/cohort-update.json");
        $this->_app->jsValidator->setSchema($schema);

        $this->_app->render("cohorts/form-edit-cohort.twig", [
            "box_title" => "Update Cohort",
            "contextid" => $context_id,
            "form_action" => $this->_app->site->uri['public'] . "/forms/cohorts/c/$cohort_id",
            "cohort" => $cohort,
            "coursecats" => $coursecats,
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

    public function submitEditCohort($cohort_id)
    {
        $post = $this->_app->request->post();

        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/cohort-update.json");

        $ms = $this->_app->alerts;

        $cohort = MdlCohort::find($cohort_id);

        unset($post['csrf_token']);

        foreach ($post as $name => $value) {
            if (!isset($cohort->$name)) {
                $ms->addMessageTranslated("danger", "NO_DATA");
                $this->_app->halt(400);
            }
        }

        if (isset($post['name']) && $post['name'] != $cohort->name && MdlCohort::where('name', $post['name'])->first()){
            $ms->addMessageTranslated("danger", 'COHORT_NAME_IN_USE', $post);
            $this->_app->halt(400);
        }

        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        $rf->sanitize();

        if (!$rf->validate()) {
            $this->_app->halt(400);
        }

        $data = $rf->data();
        if(isset($_POST['description'])){
            $data['description'] = $_POST['description'];
        }

        $data['timemodified'] = time();

        foreach ($data as $name => $value) {
            if ($value != $cohort->$name) {
                $cohort->$name = $value;
            }
        }

        $ms->addMessageTranslated("success", 'COHORT_UPDATE', ["name" => $cohort->name]);
        $cohort->store();
    }

    public function assignCohortMember($cohort_id,$context_id){
        $cohort = MdlCohort::find($cohort_id);
        $Users = MdlUser::where('mdl_user.id','<>','1' )
            ->where('mdl_user.deleted','0')
            ->where('mdl_user.confirmed','1');
        
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/assign-cohort-members.json");
        $this->_app->jsValidator->setSchema($schema);

        $currentUsers = $cohort->users()->get();
        $potentialUsers = $Users
            ->get()->diff($currentUsers);

        $currentCount = $currentUsers->count();
        $potentialCount = $potentialUsers->count();

        $this->_app->render('cohorts/assign-cohort.twig',[
            "box_title" => $cohort['name'],
            "cohortid" => $cohort_id,
            "contextid" => $context_id,
            "form_action" => $this->_app->site->uri['public'] . "/assign/cohorts/c/$cohort_id/$context_id",
            "validators" => $this->_app->jsValidator->rules(),
            "currentCount" => $currentCount,
            "currentUsers" => $currentUsers,
            "potentialCount" => $potentialCount,
            "potentialUsers" => $potentialUsers
        ]);
    }

    public function assignSubmitCohortMember($cohort_id, $context_id){

        if(isset($_POST['addnew'])){
            $post = $this->_app->request->post();

            $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/assign-cohort-members.json");

            $ms = $this->_app->alerts;

            unset($post['csrf_token']);
            if(isset($post['removeselect'])){
                   unset($post['removeselect']);
            }
            unset($post['addnew']);
            if(!isset($post['addselect'])){
//                $ms->addMessageTranslated("danger", 'Bạn chưa chọn thành viên!');
                $this->_app->halt(400);
            }

            $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);
            $data = $rf->data();

            $cohort = MdlCohort::find($cohort_id);

            foreach ($data['addselect'] as $value){
                $cohort->users()->attach($value);
                $cohortMember = MdlCohortMembers::all()->last();
                $cohortMember->timeadded = time();
                $cohortMember->store();
            }

            $ms->addMessageTranslated("success", 'MEMBERS_COHORT_ADDED', ["name" => $cohort->name]);
        }

        if(isset($_POST['remove'])){
            $post = $this->_app->request->post();

            $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/assign-cohort-members.json");

            $ms = $this->_app->alerts;

            unset($post['csrf_token']);
            if(isset($post['addselect'])){
                unset($post['addselect']);
            }
            unset($post['addnew']);
            if(!isset($post['removeselect'])){
//                $ms->addMessageTranslated("danger", 'Bạn chưa chọn thành viên!');
                $this->_app->halt(400);
            }

            $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);
            $data = $rf->data();

            $cohort = MdlCohort::find($cohort_id);

            foreach ($data['removeselect'] as $value) {
                $cohort->users()->detach($value);
            }

            $ms->addMessageTranslated("success", 'MEMBERS_COHORT_REMOVED', ["name" => $cohort->name]);
        }
    }

    public function searchMember($cohort_id){
        $get = $this->_app->request->get();
        if($get['mod'] == 'remove'){
            $searchtext = $get['search'];
            unset($get['csrf_token']);

            $cohort = MdlCohort::find($cohort_id);
            $currentUsers = $cohort->users()
                ->where(function ($q) use ($searchtext){
                    $q->where('mdl_user.firstname','LIKE','%'.$searchtext.'%')->orWhere('mdl_user.lastname','LIKE','%'.$searchtext.'%');
                })
                ->get();

            $currentCount = $currentUsers->count();
            $this->_app->render("components/common/cohort-search-form.twig", [
                'search' => $searchtext,
                'count' => $currentCount,
                'users' => $currentUsers
            ]);
        }

        if($get['mod'] == 'add'){
            $searchtext = $get['search'];
            unset($get['csrf_token']);

            $cohort = MdlCohort::find($cohort_id);
            $Users = MdlUser::where('mdl_user.id','<>','1'  )
                ->where('mdl_user.deleted','0')
                ->where('mdl_user.confirmed','1');
            $currentUsers = $cohort->users()->get();

            $potentialUsers = $Users
                ->where(function ($q) use ($searchtext){
                    $q->where('mdl_user.firstname','LIKE','%'.$searchtext.'%')->orWhere('mdl_user.lastname','LIKE','%'.$searchtext.'%');
                })
                ->get()->diff($currentUsers);

            $potentialCount = $potentialUsers->count();

            $this->_app->render("components/common/cohort-search-form.twig", [
                'search' => $searchtext,
                'count' => $potentialCount,
                'users' => $potentialUsers
            ]);
        }

    }

    public function deleteCohort($cohort_id){
        $post = $this->_app->request->post();

        $target_cohort = MdlCohort::find($cohort_id);

        $ms = $this->_app->alerts;

        $ms->addMessageTranslated("success", 'COHORT_DELETION_SUCCESSFUL', ["name" => $target_cohort->name]);

        $target_cohort->users()->detach();
        $target_cohort->delete();

        unset($target_cohort);
    }
}