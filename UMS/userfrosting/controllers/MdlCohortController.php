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

    // Hàm này được thực hiện để show list các cohort của moodle
    public function listAllCohort()
    {
        // Bảng mdl_cohort kết nối tới 3 bảng mdl_context, mdl_course_categories, mdl_cohort_members
        // Kết nối tới 2 bảng mdl_context, mdl_course_categories để lấy toàn bộ thông tin về nhóm cũng như tên khóa học tương ứng với nhóm
        // Kết nối tới bảng mdl_cohort_members để đếm số lượng thành viên có trong mỗi nhóm
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

    // Hiển thị các nhóm tương ứng với từng khóa học và danh mục trong moodle
    public function listCatCohort($contextid){
        // Lấy tên khóa học và danh mục
        $box_title = MdlCourseCategories::queryBuilder()
            ->join("mdl_context", "mdl_course_categories.id", "=", "mdl_context.instanceid")
            ->where("mdl_context.id", $contextid)
            ->first();

        // Lấy thông tin về nhóm tương ứng với khóa học và danh mục đó, và kết nối tới bảng mdl_cohort_members để lấy số thành viên trong nhóm đó
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

    // Hiển thị form tạo nhóm mới
    public function CreateCohort($context_id)
    {
        // Lấy thông tin về danh mục, khóa học
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

    //  Thực hiện xử lý khi người dùng Submit từ form tạo mới nhóm
    public function submitCreateCohort()
    {
        $post = $this->_app->request->post();
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
        $data['description'] = $post['description'];
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

    // Hiển thị chỉnh sửa về nhóm
    public function EditCohort($cohort_id, $context_id)
    {
        $cohort = MdlCohort::find($cohort_id)->toArray();

        // Bảng mdl_course_categories kết nối với bảng mdl_context để hiển thị danh sách danh mục, khóa học.
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

    // Hàm xử lý khi người dùng submit từ form edit
    public function submitEditCohort($cohort_id)
    {
        $post = $this->_app->request->post();

        $cohort = MdlCohort::find($cohort_id);

        unset($post['csrf_token']);

        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/cohort-update.json");

        $ms = $this->_app->alerts;

        // Kiểm tra xem $_POST truyền lên có rỗng hay không
        foreach ($post as $name => $value) {
            if (!isset($cohort->$name)) {
                $ms->addMessageTranslated("danger", "NO_DATA");
                $this->_app->halt(400);
            }
        }

        // Kiểm tra xem tên nhóm mới sửa có trùng với tên nhóm nào trong CSDL hay không
        if (isset($post['name']) && $post['name'] != $cohort->name && MdlCohort::where('name', $post['name'])->first()){
            $ms->addMessageTranslated("danger", 'COHORT_NAME_IN_USE', $post);
            $this->_app->halt(400);
        }
        
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        $rf->sanitize();


        // Validate, and halt on validation errors.
        if (isset($post['name'])) {
            if (!$rf->validate()) {
                $this->_app->halt(400);
            }
        }

        $data = $rf->data();
        if(isset($post['description'])){
            $data['description'] = $post['description'];
        }

        $data['timemodified'] = time();

        // Update các thuộc tính mới cho bản ghi trong CSDL
        foreach ($data as $name => $value) {
            if ($value != $cohort->$name) {
                $cohort->$name = $value;
            }
        }

        $cohort->store();
        $ms->addMessageTranslated("success", 'COHORT_UPDATE', ["name" => $cohort->name]);
    }

    // Hiển thị form add và remove user cho mỗi cohort
    // Thuộc tính:  $cohort_id: là id của cohort lựa chọn
    //              $context_id: để lấy thông tin về danh mục, khóa học tương ứng với context_id

    public function assignCohortMember($cohort_id,$context_id){
        // Lấy thông tin về nhóm tương ứng với id của nhóm đó
        $cohort = MdlCohort::find($cohort_id);
        // Lấy thông tin tất cả người dùng với  id # 1 (1 là id của Guest),
        //                                      deleted = 0(Người dùng này không bị đình chỉ),
        //                                      confirmed = 1(Người dùng này phải được xác thực).
        $Users = MdlUser::where('mdl_user.id','<>','1' )
            ->where('mdl_user.deleted','0')
            ->where('mdl_user.confirmed','1');
        
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/assign-cohort-members.json");
        $this->_app->jsValidator->setSchema($schema);

        // Lấy danh sách những người dùng hiện ở trong nhóm
        $currentUsers = $cohort->users()->get();
        // Lấy danh sách những người dùng tiềm năng để đăng ký vào nhóm
        $potentialUsers = $Users
            ->get()->diff($currentUsers);

        // Đếm số lượng người dùng ở trong nhóm
        $currentCount = $currentUsers->count();
        // Đếm số lượng người dùng không ở trong nhóm
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

    // Xử lý hai nút add và Remove trong form đăng ký thành viên trong nhóm
    public function assignSubmitCohortMember($cohort_id, $context_id){
        // Kiểm tra xem $_POST có phải là đăng ký người dùng vào trong nhóm hay không.
        if(isset($_POST['addnew'])){
            $post = $this->_app->request->post();

            $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/assign-cohort-members.json");

            $ms = $this->_app->alerts;

            unset($post['csrf_token']);
            if(isset($post['removeselect'])){
                   unset($post['removeselect']);
            }
            unset($post['addnew']);

            // Kiểm tra xem người dùng đã chọn thành viên chưa
            if(!isset($post['addselect'])){
//                $ms->addMessageTranslated("danger", 'Bạn chưa chọn thành viên!');
                $this->_app->halt(400);
            }

            $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);
            $data = $rf->data();

            $cohort = MdlCohort::find($cohort_id);

            // Lưu các thành viên vào trong nhóm
            foreach ($data['addselect'] as $value){
                // Gắn id của người dùng vào tương ứng với id của nhóm vào trong bảng MdlCohortNumber
                $cohort->users()->attach($value);
                // Lấy bản ghi cuối và thêm thời gian update
                $cohortMember = MdlCohortMembers::all()->last();
                $cohortMember->timeadded = time();
                $cohortMember->store();
            }

            $ms->addMessageTranslated("success", 'MEMBERS_COHORT_ADDED', ["name" => $cohort->name]);
        }

        // Kiểm tra xem $_POST có phải là xóa bỏ người dùng khỏi nhóm hay không.
        if(isset($_POST['remove'])){
            $post = $this->_app->request->post();

            $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/assign-cohort-members.json");

            $ms = $this->_app->alerts;

            unset($post['csrf_token']);
            if(isset($post['addselect'])){
                unset($post['addselect']);
            }
            unset($post['addnew']);

            // Kiểm tra xem đã lựa chọn thành viên chưa??
            if(!isset($post['removeselect'])){
//                $ms->addMessageTranslated("danger", 'Bạn chưa chọn thành viên!');
                $this->_app->halt(400);
            }

            $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);
            $data = $rf->data();

            $cohort = MdlCohort::find($cohort_id);

            // Để xóa bỏ xử dụng detach để xóa bỏ thành viên trong nhóm cũng như xóa bỏ bản ghi trong bảng cohort_members
            foreach ($data['removeselect'] as $value) {
                $cohort->users()->detach($value);
            }

            $ms->addMessageTranslated("success", 'MEMBERS_COHORT_REMOVED', ["name" => $cohort->name]);
        }
    }

    // Xử lý form tìm kiếm người dùng trong mỗi danh sách
    public function searchMember($cohort_id){
        $get = $this->_app->request->get();
        // Kiểm tra xem có phải form tìm kiếm của người dùng trong nhóm hay không.
        if(isset($get['mod']) && $get['mod'] == 'remove'){
            // Lấy từ khóa tìm kiếm
            $searchtext = $get['search'];
            unset($get['csrf_token']);

            $cohort = MdlCohort::find($cohort_id);
            // Lấy danh sách người dùng tương ứng với từ khóa tìm kiếm trong 2 trường firstname và lastname của bảng mdl_user
            $currentUsers = $cohort->users()
                ->where(function ($q) use ($searchtext){
                    $q->where('mdl_user.firstname','LIKE','%'.$searchtext.'%')->orWhere('mdl_user.lastname','LIKE','%'.$searchtext.'%');
                })
                ->get();
            // Đếm số lượng người dùng tìm thấy
            $currentCount = $currentUsers->count();
            $this->_app->render("components/common/cohort-search-form.twig", [
                'search' => $searchtext,
                'count' => $currentCount,
                'users' => $currentUsers
            ]);
        }

        // Kiểm tra xem có phải form tìm kiếm của người dùng tiềm năng hay khônng
        if(isset($get['mod']) && $get['mod'] == 'add'){
            $searchtext = $get['search'];
            unset($get['csrf_token']);

            $cohort = MdlCohort::find($cohort_id);
            $Users = MdlUser::where('mdl_user.id','<>','1'  )
                ->where('mdl_user.deleted','0')
                ->where('mdl_user.confirmed','1');
            $currentUsers = $cohort->users()->get();
            // Tìm kiếm người dùng tiềm năng tương ứng với từ khóa tìm kiếm trong 2 trường firstname và lastname của bảng mdl_user
            $potentialUsers = $Users
                ->where(function ($q) use ($searchtext){
                    $q->where('mdl_user.firstname','LIKE','%'.$searchtext.'%')->orWhere('mdl_user.lastname','LIKE','%'.$searchtext.'%');
                })
                ->get()->diff($currentUsers);
            // Đếm số lượng người dùng tiềm năng.
            $potentialCount = $potentialUsers->count();

            $this->_app->render("components/common/cohort-search-form.twig", [
                'search' => $searchtext,
                'count' => $potentialCount,
                'users' => $potentialUsers
            ]);
        }

    }
    // Xử lý để xóa nhóm đang đã chọn.
    public function deleteCohort($cohort_id){
        $post = $this->_app->request->post();
        // Tìm nhóm để xóa
        $target_cohort = MdlCohort::find($cohort_id);

        $ms = $this->_app->alerts;

        // Xóa người dùng tương ứng với nhóm trong bảng cohort_member
        $target_cohort->users()->detach();
        // Xóa nhóm trong bảng cohort
        $target_cohort->delete();

        $ms->addMessageTranslated("success", 'COHORT_DELETION_SUCCESSFUL', ["name" => $target_cohort->name]);

        unset($target_cohort);
    }
}