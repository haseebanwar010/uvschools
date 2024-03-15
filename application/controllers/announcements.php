<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Announcements extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login/index"));
        }
         check_user_permissions();
    }

    public function index(){
        $this->load->view("announcement/index");
    }

    public function all(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year = $this->session->userdata("userdata")["academic_year"];
        
        if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $where = " school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ";
            $sql = "SELECT * FROM sh_announcements WHERE ".$where;
            $data =  $this->admin_model->dbQuery($sql);
            foreach($data as $d){
                $d->employee_names = array();
                $d->employee_names_string = "";
                $d->employee_avatars = array();
                $d->class_names = array();
                $d->class_names_string = "";
                $d->section_names = array();
                $d->section_names_string = "";
                $d->category_names = array();
                $d->category_names_string = "";
                $d->department_names = array();
                $d->department_names_string = "";
                if(!empty($d->employees) || $d->employees!= null){
                    $arr = $this->admin_model->dbSelect("GROUP_CONCAT(name) as employee_names, GROUP_CONCAT(avatar) as employee_avatars","users"," id IN ($d->employees) ");
                    if(count($arr) > 0){
                        $d->employee_names = explode(",",$arr[0]->employee_names);
                        $d->employee_avatars = explode(",",$arr[0]->employee_avatars);
                        $d->employee_names_string = $arr[0]->employee_names;
                    }
                }
                if(!empty($d->classes) || $d->classes!= null){
                    $arr1 = $this->admin_model->dbSelect("GROUP_CONCAT(name) as class_names","classes"," id IN ($d->classes) ");
                    if(count($arr1) > 0){
                        $d->class_names = explode(",",$arr1[0]->class_names);
                        $d->class_names_string = $arr1[0]->class_names;
                    }
                }
                if(!empty($d->sections) || $d->sections!= null){
                    $arr2 = $this->admin_model->dbSelect("GROUP_CONCAT(name) as section_names","batches"," id IN ($d->sections) ");
                    if(count($arr2) > 0){
                        $d->section_names = explode(",",$arr2[0]->section_names);
                        $d->section_names_string = $arr2[0]->section_names;
                    }
                }
                if(!empty($d->categories) || $d->categories!= null){
                    $arr3 = $this->admin_model->dbSelect("GROUP_CONCAT(category) as category_names","role_categories"," id IN ($d->categories) ");
                    if(count($arr3) > 0){
                        $d->category_names = explode(",",$arr3[0]->category_names);
                        $d->category_names_string = $arr3[0]->category_names;
                    }
                }
                if(!empty($d->departments) || $d->departments!= null){
                    $arr4 = $this->admin_model->dbSelect("GROUP_CONCAT(name) as department_names","departments"," id IN ($d->departments) ");
                    if(count($arr4) > 0){
                        $d->department_names = explode(",",$arr4[0]->department_names);
                        $d->department_names_string = $arr4[0]->department_names;
                    }
                }
            }
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID ) {
            
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'students') AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID ) {
            $data =  $this->admin_model->dbSelect( "*", "announcements", "school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID ) {
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'parents') AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id){
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'employees') AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        }
        
        if(count($data) > 0){
            foreach($data as $key=>$d){
                $d->index = ++$key;
                $d->from_date = to_html_date($d->from_date);
                $d->to_date = to_html_date($d->to_date);
            }
        }
        echo json_encode($data);
    }

    public function get() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year = $this->session->userdata("userdata")["academic_year"];
        $date = date("Y-m-d");
        if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $where = " status = 'Active' AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ";
            $sql = "SELECT * FROM sh_announcements WHERE ".$where;
            $data =  $this->admin_model->dbQuery($sql);
            foreach($data as $d){
                $d->employee_names = array();
                $d->employee_names_string = "";
                $d->employee_avatars = array();
                $d->class_names = array();
                $d->class_names_string = "";
                $d->section_names = array();
                $d->section_names_string = "";
                $d->category_names = array();
                $d->category_names_string = "";
                $d->department_names = array();
                $d->department_names_string = "";
                if(!empty($d->employees) || $d->employees!= null){
                    $arr = $this->admin_model->dbSelect("GROUP_CONCAT(name) as employee_names, GROUP_CONCAT(avatar) as employee_avatars","users"," id IN ($d->employees) ");
                    if(count($arr) > 0){
                        $d->employee_names = explode(",",$arr[0]->employee_names);
                        $d->employee_avatars = explode(",",$arr[0]->employee_avatars);
                        $d->employee_names_string = $arr[0]->employee_names;
                    }
                }
                if(!empty($d->classes) || $d->classes!= null){
                    $arr1 = $this->admin_model->dbSelect("GROUP_CONCAT(name) as class_names","classes"," id IN ($d->classes) ");
                    if(count($arr1) > 0){
                        $d->class_names = explode(",",$arr1[0]->class_names);
                        $d->class_names_string = $arr1[0]->class_names;
                    }
                }
                if(!empty($d->sections) || $d->sections!= null){
                    $arr2 = $this->admin_model->dbSelect("GROUP_CONCAT(name) as section_names","batches"," id IN ($d->sections) ");
                    if(count($arr2) > 0){
                        $d->section_names = explode(",",$arr2[0]->section_names);
                        $d->section_names_string = $arr2[0]->section_names;
                    }
                }
                if(!empty($d->categories) || $d->categories!= null){
                    $arr3 = $this->admin_model->dbSelect("GROUP_CONCAT(category) as category_names","role_categories"," id IN ($d->categories) ");
                    if(count($arr3) > 0){
                        $d->category_names = explode(",",$arr3[0]->category_names);
                        $d->category_names_string = $arr3[0]->category_names;
                    }
                }
                if(!empty($d->departments) || $d->departments!= null){
                    $arr4 = $this->admin_model->dbSelect("GROUP_CONCAT(name) as department_names","departments"," id IN ($d->departments) ");
                    if(count($arr4) > 0){
                        $d->department_names = explode(",",$arr4[0]->department_names);
                        $d->department_names_string = $arr4[0]->department_names;
                    }
                }
            }
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID ) {
             $user_id = $this->session->userdata("userdata")["user_id"];
             
           $resp = $this->db->select('class_id, batch_id')->from('sh_student_class_relation')->where('student_id',$user_id)->get()->row();
          
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' AND (level = 'all'  OR (level = 'students' AND FIND_IN_SET('$resp->class_id', classes) AND FIND_IN_SET('$resp->batch_id', sections))) AND deleted_at IS NULL ");
            
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID ) {
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'employees') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID ) {
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'parents') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'teachers') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' AND deleted_at IS NULL ");
        }
        if(count($data) > 0){
            foreach($data as $key=>$d){
                $d->index = ++$key;
                $d->from_date = to_html_date($d->from_date);
                $d->to_date = to_html_date($d->to_date);
            }
        }
        echo json_encode($data);
    }

    public function details($id){
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_announcements');
        $xcrud->columns('title, details, from_date, to_date, level, status, img_or_document');
        $xcrud->fields('title, details, from_date, to_date, level, status, img_or_document');
        $xcrud->label('img_or_document', lang('lbl_attachments'));
        $xcrud->label('title', lang('lbl_tbl_title'));
        $xcrud->label('from_date', lang('start_date'));
        $xcrud->label('to_date', lang('end_date'));
        $xcrud->label('status', lang('lbl_status'));
        $xcrud->load_view("view", "announcement.php");
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data["announcement"] = $xcrud->render('view', $id);
        $this->load->view("announcement/detail", $data);
    }

    public function softDelete(){
        $id = $this->input->post("id");
        $this->common_model->update_where("sh_announcements", array("id" => $id), array("deleted_at"=>date("Y-m-d h:i:s")));
        $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('announcement_deleted')));
        echo "success";
    }

    public function upload(){
        if ( !empty( $_FILES ) ) {
            $tempPath = $_FILES['file']['tmp_name'];
            $arr = explode(".", $_FILES["file"]["name"]);
            $extension = $arr[count($arr) - 1];

            if($extension == 'pdf' || $extension == 'jpg' || $extension == 'JPG' || $extension == 'doc' || $extension == 'docx' || $extension == 'txt' || $extension == 'png' || $extension == 'PNG' || $extension == 'JPEG' || $extension == 'jpeg' || $extension == 'ppt' || $extension == 'pptx' || $extension == 'xlsx'){

                    // $newfilename = $_FILES['file']['name'].'.'.$extension;
                    $newfilename = $_FILES['file']['name'];
                    // print_r($newfilename);die();
                    $uploadPath = 'uploads/announcements/'.$newfilename;
                    move_uploaded_file( $tempPath, $uploadPath );
                    $answer = array( 'status' => 'success', 'uploaded_file_name' => $newfilename );
                    $json = json_encode( $answer );
                    echo $json;

                }
                else{
                    $answer = array( 'status' => 'error', 'message' => "Sorry Not allowed!" );
                    $json = json_encode( $answer );
                    echo $json; die();
                }
        } else {
            $answer = array( 'status' => 'error', 'uploaded_file_name' => "" );
            $json = json_encode( $answer );
            echo $json;
        }
    }

    public function getBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        //-------------------------
        $where_part = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            $where_part = " AND id IN (" . implode(',', login_user()->t_data->batches) . ") ";
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }
        //-------------------------

        if (count($request->class_ids) > 0) {
            $query = "  school_id=" . login_user()->user->sh_id . " AND class_id IN (".implode(',',$request->class_ids).") AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        }

        $where_part .= " ORDER BY name ASC  ";
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        echo json_encode($data);
    }

    function getCategoriesByDepartmentID(){
        $school_id = $this->session->userdata('userdata')["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $data = $this->admin_model->dbSelect("*","role_categories"," department_id IN (".implode(',',$request->departments).") AND school_id=".$school_id." AND deleted_at=0 ");
        echo json_encode($data);
    }

    function getEmployees(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["employees"] = $this->db->select('id,name')->from('sh_users')->where_in('role_category_id', $request->categories)->where_in('department_id',$request->departments)->where('school_id', $school_id)->where('deleted_at',0)->where_in('role_id',array(1,4))->get()->result();
        echo json_encode($data);
    }

    public function save() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $attachments = "";
        $attachments_type="";
        if(count($request->attachments) > 0){
            $attachments = $request->attachments[0];
            $attachments_type = explode(".",$request->attachments[0])[1];
        }
        $details = $request->details;
        $random_id = "__yasir".generateRandomString(10);
        if(strpos($details, 'target="_blank"') !== false){
            $details .= '<script>
                $(document).ready(function(){
                    $(".'.$random_id.'").magnificPopup({
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 0,
                        preloader: false,
                        fixedContentPos: false
                    });
                });
            </script>';
            $details = str_replace('target="_blank"', 'class="'.$random_id.'"', $details);
            $details = str_replace('rel="nofollow"', '', $details);
        }

        $data = array(
            'school_id' => $school_id, 
            'academic_year_id' => $academic_year_id, 
            'img_or_document' => $attachments, 
            'img_or_document_type' => $attachments_type, 
            'title' => $request->title, 
            'details' => $details, 
            'from_date' => to_mysql_date($request->from_date), 
            'to_date' => to_mysql_date($request->to_date), 
            'level' => $request->level, 
            'departments' => isset($request->departments) ? implode(",", $request->departments) : null, 
            'categories' => isset($request->categories) ? implode(",", $request->categories) : null, 
            'employees' => isset($request->employees) ? implode(",", $request->employees) : null, 
            'classes' => isset($request->classes) ? implode(",", $request->classes): null, 
            'sections' => isset($request->section) ? implode(",", $request->section) : null, 
            'status' => $request->status
        );
        $res = $this->admin_model->dbInsert("sh_announcements", $data);
        if($res){
            $recipants = $this->getReceipants($school_id, $request->level, $data["departments"], $data["categories"], $data["employees"], $data["classes"], $data["sections"]);
            $response = array("status"=>"success", "message"=>"Announcement saved successfully!", "recipants"=>$recipants, "new_announcement_id"=>$res, "sender"=>$this->session->userdata("userdata")["name"]);
            echo json_encode($response);
        }
    }

    public function update() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        
        
        $attachments = "";
        $attachments_type="";
        if(count($request->attachments) > 0){
            $attachments = $request->attachments[0];
            $attachments_type = explode(".",$request->attachments[0])[1];
        } else {
            $attachments = $request->img_or_document;
            $attachments_type = $request->img_or_document_type;
        }

        $details = $request->details;
        $random_id = "__yasir".generateRandomString(10);
        if(strpos($details, 'target="_blank"') !== false){
            $details .= '<script>
                $(document).ready(function(){
                    $(".'.$random_id.'").magnificPopup({
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 0,
                        preloader: false,
                        fixedContentPos: false
                    });
                });
            </script>';
            $details = str_replace('target="_blank"', 'class="'.$random_id.'"', $details);
            $details = str_replace('rel="nofollow"', '', $details);
        } 
        $data = array(
            'img_or_document' => $attachments, 
            'img_or_document_type' => $attachments_type, 
            'title' => $request->title, 
            'details' => $details, 
            'from_date' => to_mysql_date($request->from_date), 
            'to_date' => to_mysql_date($request->to_date), 
            'level' => $request->level, 
            'status' => $request->status
        );

        $data["departments"] = null; 
        $data["categories"] = null;
        $data["employees"] = null;
        $data["classes"] = null; 
        $data["sections"] = null;

        if($request->level == "employees"){
            $data['departments'] = implode(",", $request->departments); 
            $data['categories'] = implode(",", $request->categories);
            $data['employees'] = implode(",", $request->employees);
            $data['classes'] = null; 
            $data['sections'] = null;
        } else if($request->level == "students"){
            $data['departments'] = null; 
            $data['categories'] = null;
            $data['employees'] = null;
            $data['classes'] = implode(",", $request->classes); 
            $data['sections'] = implode(",", $request->section);
        }

        
        
        $res = $this->common_model->update_where("sh_announcements", array("id"=>$request->id) ,$data);
        if($res){
            $recipants = $this->getReceipants($school_id, $request->level, $data["departments"], $data["categories"], $data["employees"], $data["classes"], $data["sections"]);
            $response = array("status"=>"success", "message"=>"Announcement updated successfully!", "recipants"=>$recipants, "new_announcement_id"=>$request->id, "sender"=>$this->session->userdata("userdata")["name"]);
            echo json_encode($response);
        }
    }

    public function getReceipants($school_id, $level, $departments, $categories, $employees, $classes, $section){
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $sql = "";
        switch($level){
            case "all":
                $sql .= "SELECT id FROM sh_users WHERE school_id='$school_id' AND deleted_at=0 ";
                break;
            case "employees":
                $departments = empty($departments)?0:$departments;
                $categories = empty($categories)?0:$categories;
                $employees = empty($employees)?0:$employees;
                $sql .= "SELECT id FROM sh_users WHERE school_id='$school_id' AND deleted_at=0 AND role_id=".EMPLOYEE_ROLE_ID;
                if($departments != 0) {
                    $sql .= " AND department_id IN (".$departments.")";
                }
                if($categories != 0) {
                    $sql .= " AND role_category_id IN (".$categories.")";
                }
                if($employees != 0) {
                    $sql = "SELECT id FROM sh_users WHERE school_id='$school_id' AND deleted_at=0 AND id IN (".$employees.") ";
                }
                break;
            case "parents":
                $sql .= "SELECT id FROM sh_users WHERE school_id='$school_id' AND deleted_at=0 AND role_id=".PARENT_ROLE_ID;
                break;
            case "students":
                $classes = empty($classes)?0:$classes;
                $section = empty($section)?0:$section;
                $sql .= "SELECT id FROM sh_students_".$school_id." WHERE school_id='$school_id' AND deleted_at=0 AND academic_year_id='$academic_year_id' ";
                if($classes != 0) {
                    $sql .= " AND class_id IN (".$classes.")";
                }
                if($section != 0) {
                    $sql .= " AND batch_id IN (".$section.")";
                }
                break;
            case "admin":
                $sql .= "SELECT id FROM sh_users WHERE school_id='$school_id' AND deleted_at=0 AND role_id=".ADMIN_ROLE_ID;
                break;
        }
        $result = $this->admin_model->dbQuery($sql);
        $new_ids = array();
        if(count($result) > 0){
            foreach($result as $res){
                array_push($new_ids, $res->id);
            }
        }
        return $new_ids;
    }

    public function getDepCatEmployees(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        if (count($request->categories) > 0 AND count($request->departments)) {

            $data["employees"] = $this->db->select('id,name')->from('sh_users')->where('school_id', $school_id)->where('deleted_at',0)->where_in('role_id',array(1,4))->where_in("role_category_id",$request->categories)->where_in("department_id",$request->departments)->get()->result();

        } else {

            $data["employees"] = "";

        }

        echo json_encode($data);

    }


}
