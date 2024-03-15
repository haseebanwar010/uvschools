<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function all() {
        $UserData = $this->session->userdata('userdata');
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sql_std = "select DISTINCT u.*, cl.name as class, b.name as batch, u.gender, u.contact, u.nationality from sh_students_".$school_id." u"
                . " inner join sh_classes cl on cl.id = u.class_id"
                . " inner join sh_batches b on b.id = u.batch_id "
                . " WHERE u.deleted_at = 0 AND u.role_id = 3  AND u.school_id = $school_id";
        $sql_emp = "Select DISTINCT u.*, d.name as department_name, cat.category as category_name FROM sh_users u LEFT JOIN sh_departments d ON u.department_id=d.id "
                . "LEFT JOIN sh_role_categories cat ON u.role_category_id=cat.id WHERE u.school_id =" . $school_id . " AND u.role_id=4 AND u.deleted_at = '0'"
                . "order by u.department_id";

        $data["count_emp"] = count($this->admin_model->dbQuery($sql_emp));
        $data["count_std"] = count($this->admin_model->dbQuery($sql_std));
        $this->load->view("reports/index", $data);
    }

    public function students() {
        $this->load->view("reports/students/index");
    }

    public function employees() {
        if (isset($_GET) && $_GET) {
            $request = $_GET;
        } else {
            $request = NULL;
        }

        $UserData = $this->session->userdata('userdata');
        $data['categories'] = $this->employee_model->getCategories($UserData['sh_id']);
        $data['departments'] = $this->employee_model->getDepartments($UserData['sh_id']);
        $data['employees'] = $this->employee_model->getAll($UserData['sh_id'], $request);

        $this->load->view("reports/employees/index", $data);
    }

    public function admissions() {
        $this->load->view("reports/admissions/index");
    }

    public function examination() {
        $this->load->view("reports/examination/index");
    }

    public function getClass() {

        $q = $_GET['q'];

        $response['rec'] = $this->admin_model->dbSelect("*", "classes", " name like '%$q%' ");


        echo json_encode($response);
    }

    public function getClasses() {
        //-------------------------
        $data = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            if (count(login_user()->t_data->classes) > 0) {
                $data = $this->admin_model->dbSelect(" DISTINCT *", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ") AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ORDER BY name asc ");
            }
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $data = $this->admin_model->dbSelect(" DISTINCT *", "classes", " school_id = ".login_user()->user->sh_id." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." AND deleted_at is null ORDER BY name asc ");
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data = $this->admin_model->dbSelect(" DISTINCT *", "classes", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ORDER BY name asc ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }
        //-------------------------

        $data = $this->admin_model->dbSelect(" DISTINCT *", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        echo json_encode($data);
    }

    public function getClassBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
       
        $where_part = "";
        
        $ids = "";
        foreach ($request->classes as $key => $value) {
            $ids .= $value . ",";
        }
        $ids = rtrim($ids, ',');
        // print_r($ids);die();
        // $ids = implode(',', $request->classes);
        

        if (!empty($ids) && $ids != "") {
            $where_part .= "b.school_id=" . login_user()->user->sh_id . " AND b.class_id IN ($ids) AND b.deleted_at IS NULL";
            $sqly = "Select DISTINCT b.*, c.name as class_name FROM sh_batches b INNER JOIN  sh_classes c ON b.class_id = c.id "
                    . "WHERE  " . $where_part
                    . " Order BY c.id";

            $data["batches"] = $this->admin_model->dbQuery($sqly);
        } else {
            $data = "";
        }
        echo json_encode($data);
    }

    public function getClassBatches_feesummary() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $where_part = "";
        //-------------------------
        $ids = implode(",", $request->classes);
        
        if (!empty($ids) && $ids != "") {
            $where_part .= "b.school_id=" . login_user()->user->sh_id . " AND b.class_id IN ($ids) AND b.deleted_at IS NULL";
            $sqly = "Select DISTINCT b.*, c.name as class_name FROM sh_batches b INNER JOIN  sh_classes c ON b.class_id = c.id "
                    . "WHERE  " . $where_part
                    . " Order BY c.id";

            $data["batches"] = $this->admin_model->dbQuery($sqly);
        } else {
            $data = "";
        }
        echo json_encode($data);
    }

    public function getAcademicYears_feeSummary(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $data = $this->admin_model->dbSelect("*","academic_years"," school_id='$school_id' AND deleted_at IS NULL ");
        $academic_year_id = "-1";
        if(isset($this->session->userdata("userdata")["academic_year"]) && !empty($this->session->userdata("userdata")["academic_year"])){
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }
        $response = array('current_academic_year_id'=>$academic_year_id,"data"=>$data);
        echo json_encode($response);
    }

    public function fetchStudents() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $inputs = $this->input->post("formData");

        if (isset($inputs['cls_id'])) {
            $cls = $inputs['cls_id'];
        }
        if (isset($inputs['btch_id'])) {
            $btch = $inputs['btch_id'];
        }
        if (isset($inputs['name'])) {
            $name = $inputs['name'];
        }
        if (isset($inputs['gender'])) {
            $gender = $inputs['gender'];
        }
        if (isset($inputs['from'])) {
            $from = $inputs['from'];
        }
        if (isset($inputs['to'])) {
            $to = $inputs['to'];
        }

        //check request and make search query
        if (!isset($cls) && !isset($btch) && !isset($name) && !isset($gender) && !isset($from) && !isset($to)) {

             $sql = "select DISTINCT u.*, cl.name as class, b.name as batch, u.gender, u.contact as mobile_phone, ctr.country_name as nationality from sh_students_".$school_id." u"
                    . " inner join sh_classes cl on cl.id = u.class_id"
                    . " inner join sh_batches b on b.id = u.batch_id "
                    . " LEFT JOIN sh_countries ctr on ctr.id = u.nationality"
                    . " WHERE u.deleted_at = 0 AND u.role_id = 3  AND u.school_id = $school_id";

            $data = $this->admin_model->dbQuery($sql);

            foreach ($data as $key => $row) {
                $data[$key]->id = encrypt($row->id);
            }

            echo json_encode($data);
        } else {
            $where = "";
            $where .= "u.deleted_at = 0 AND u.role_id = 3  AND u.school_id = $school_id";

            if (isset($name)) {
                $name = strtolower($name);
                $where .= " AND u.name like '%$name%' ";
            } else {
                $where .= " AND u.name like '%%' ";
            }

            if (isset($cls) && !empty($cls)) {
                /* split class and batch id's form object request */
                $cls_id = "";
                foreach ($cls as $key => $value) {
                    $cls_id .= $value . ",";
                }
                $classes_id = rtrim($cls_id, ",");
                $where .= "AND u.class_id IN ($classes_id)";
            }else{
                unset($cls_id);
            }

            if (isset($btch) && !empty($btch)) {
                $bch_id = "";
                foreach ($btch as $key => $value) {
                    $bch_id .= $value . ",";
                }
                $batches_id = rtrim($bch_id, ",");
                $where .= "AND u.batch_id IN ($batches_id) ";
            }else{
                unset($bch_id);
            }

            if (isset($gender) && $gender != NULL) {
                $where .= "AND u.gender = '$gender' ";
            } else if (isset($gender) && $gender == NULL) {
                $where .= "AND u.gender IN ('male','female') ";
            } else {
                $where .= "AND u.gender IN ('male','female') ";
            }
            if(isset($from) && $from != NULL) {
                $where .= " AND u.created_at between '" . to_mysql_date($from) . "%' ";
            }
            if(isset($to) && $to != NULL) {
                $where .= " AND '" . to_mysql_date($to) . "%' ";
            }

                   
            $where .= " AND  u.deleted_at = 0 AND u.role_id = 3  AND u.school_id = $school_id";

            $sqly = "select DISTINCT u.*, cl.name as class, b.name as batch, u.gender, u.contact as mobile_phone, ctr.country_name as nationality from sh_students_".$school_id." u"
                    . " inner join sh_classes cl on cl.id = u.class_id"
                    . " inner join sh_batches b on b.id = u.batch_id "
                    . " LEFT JOIN sh_countries ctr on ctr.id = u.nationality"
                    . " where " . $where
                    . " Order BY u.class_id";

            $data = $this->admin_model->dbQuery($sqly);

            foreach ($data as $key => $row) {
                $data[$key]->id = encrypt($row->id);
            }

            echo json_encode($data);
        }
    }

    public function fetchEmployees() {

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $inputs = $this->input->post("formData");

        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            $data["classes"] = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ")");
            $where = "u.school_id=" . login_user()->user->sh_id . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " AND u.batch_id IN (" . implode(',', login_user()->t_data->batches) . ") ";
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL ");
            $where = "u.school_id=" . login_user()->user->sh_id . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " ";
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }
        if (isset($inputs['dept'])) {
            $department = $inputs['dept'];
        }
        if (isset($inputs['cat'])) {
            $category = $inputs['cat'];
        }
        if (isset($inputs['name'])) {
            $name = $inputs['name'];
        }
        if (isset($inputs['gender'])) {
            $gender = $inputs['gender'];
        }
        if (isset($inputs['from'])) {
            $from = $inputs['from'];
        }
        if (isset($inputs['to'])) {
            $to = $inputs['to'];
        }

        $where = "";

        if (empty($department) && empty($category) && empty($name) && empty($gender) && empty($from) && empty($to)) {
            $sql = "Select DISTINCT u.*, d.name as department_name, cat.category as category_name "
                    . "FROM sh_users u"
                    . " LEFT JOIN sh_departments d ON u.department_id=d.id "
                    . "LEFT JOIN sh_role_categories cat ON u.role_category_id=cat.id "
                    . "WHERE u.school_id =" . $school_id . " "
                    . " AND u.deleted_at = 0 and u.role_id=" . EMPLOYEE_ROLE_ID . " "
                    . "order by u.name";

            $data = $this->admin_model->dbQuery($sql);
            foreach ($data as $key => $row) {
                $data[$key]->id = encrypt($row->id);
            }

            echo json_encode($data);
        } else {

            if (isset($name)) {
                $name = strtolower($name);
                $where .= "u.name like '%$name%' ";
            } else {
                $where .= "u.name like '%%' ";
            }

            if (isset($department) && !empty($department)) {
                /* split class and batch id's form object request */
                $dept_ids = "0,";
                foreach ($department as $key => $value) {
                    $dept_ids .= $value . ",";
                }
                $department_ids = rtrim($dept_ids, ",");

                $where .= "AND u.department_id IN ($department_ids)";
            }else{
                unset($department_ids);
            }
            if (isset($category) && !empty($category)) {
                /* split class and batch id's form object request */
                $cat_ids = "0,";
                foreach ($category as $key => $value) {
                    $cat_ids .= $value . ",";
                }
                $category_ids = rtrim($cat_ids, ",");
                $where .= "AND u.role_category_id IN ($category_ids) ";
            }else{
                unset($category_ids);
            }
            if (isset($gender) && $gender != NULL) {

                $where .= "AND u.gender = '$gender' ";
            } else if (isset($gender) && $gender == NULL) {
                $where .= "AND u.gender IN ('male','female') ";
            } else {
                $where .= "AND u.gender IN ('male','female') ";
            }

            if (isset($from) && $from != NULL) {
                $where .= "AND u.created_at between '" . to_mysql_date($from) . "%' ";
            }
            if (isset($to) && $to != NULL) {
                $where .= "AND '" . to_mysql_date($to) . "%' ";
            }

            $where .= "AND u.deleted_at = '0' AND u.school_id =" . $school_id . " AND u.role_id=" . EMPLOYEE_ROLE_ID . " ";
            $sqly = "Select DISTINCT u.*, d.name as department_name, cat.category as category_name "
                    . "FROM sh_users u "
                    . "LEFT JOIN sh_departments d ON u.department_id=d.id "
                    . "LEFT JOIN sh_role_categories cat ON u.role_category_id=cat.id "
                    . "WHERE  " . $where
                    . " Order BY u.name";

            $data = $this->admin_model->dbQuery($sqly);
            foreach ($data as $key => $row) {
                $data[$key]->id = encrypt($row->id);
            }
            echo json_encode($data);
        }
    }

    public function getDepartments() {
        $UserData = $this->session->userdata('userdata');
        $data['departments'] = $this->employee_model->getDepartments($UserData['sh_id']);
        echo json_encode($data);
    }

    public function getCategories() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $UserData = $this->session->userdata('userdata');
        //-------------------------
        $where_part = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            $where_part = " AND id IN (" . implode(',', login_user()->t_data->batches) . ") ";
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }

        $id = "";
        foreach ($request->departments as $key => $value) {
            $id .= $value->id . ",";
        }
        $ids = rtrim($id, ",");

        if (!empty($ids) && $ids != "") {
            $where_part .= "c.school_id=" . login_user()->user->sh_id . " AND c.department_id IN ($ids) AND c.deleted_at = '0'";
            $sqly = "Select DISTINCT c.*, d.name as dept_name FROM sh_role_categories c INNER JOIN  sh_departments d ON d.id = c.department_id "
                    . "WHERE  " . $where_part
                    . " Order BY c.id";

            $data["categories"] = $this->admin_model->dbQuery($sqly);
        } else {
            $data = "";
        }
        echo json_encode($data);
    }

    // fee report
    public function fee() {
        $this->load->view("reports/fee/index");
    }
    public function fetchFees(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // $currency_symbol = $this->session->userdata("userdata")["currency_symbol"];
       
        $inputs = $this->input->post("formData");
        $academic_year = $this->session->userdata("userdata")["academic_year"];
       
        if(!empty($inputs)){
           
        //------set request values
        if (isset($inputs['name'])) {
            $name = $inputs['name'];
        }
        if (isset($inputs['class_id'])) {
            $class_id = $inputs['class_id'];
        }
        if (isset($inputs['types_id'])) {
            $feeType = $inputs['types_id'];
        }
        if (isset($inputs['btch_id'])) {
            $btch = $inputs['btch_id'];
        }
        if(isset($inputs['discounts_id'])){
            $discType = $inputs['discounts_id'];
        }
        if(isset($inputs['collects_id'])){
            $collector = $inputs['collects_id'];
        }
        if(isset($inputs['mode'])){
            $mode = $inputs['mode'];
        }
        if(isset($inputs['from'])){
            $from = $inputs['from'];
        }
        if(isset($inputs['to']) && $inputs['to'] != ""){
            $to = $inputs['to'];
        }else if(isset($inputs['from'])){
                $to = date('d/m/Y');
        }
        if (isset($inputs['partial'])) {
            $partial = $inputs['partial'];
        }
           
        $where = "";
        if (isset($inputs['isDue']) && $inputs['isDue'] == 'true') {

            $sql1 = "SELECT DISTINCT
                fc.*, 
                u.name as std_name, 
                country.country_name,
                c.name as class_name,
                sc.country_name as variant_country,
                fv.title,
                b.name as batch,
                u.rollno as studentID,
                u_col.name as collector, 
                ft.name as type, 
                ft.due_date as due_date,
                ft.amount as feetype_amount, 
                fd.name as discount,
                fd.amount as percentage ,
                fv.percentage as varient_fee,
                round(v.percentage,0) as v_percentage ,
                    COALESCE((round((ft.amount * v.percentage  / 100))),'')  as discount_amount,
                    COALESCE((round((fv.percentage * v.percentage  / 100))),'') as discount_amount_varient
                    from sh_students_".$school_id." u 
                LEFT JOIN sh_fee_types ft ON ft.class_id = u.class_id AND ft.deleted_at is null AND ft.academic_year_id = ".$academic_year."
                LEFT join sh_fee_discount fd ON u.discount_id = fd.id AND fd.deleted_at is null 
                LEFT JOIN sh_batches b ON b.id = u.batch_id 
                LEFT JOIN sh_discount_varients v ON v.discount_id = fd.id AND v.fee_type_id = ft.id AND v.deleted_at is null
                LEFT join sh_fee_collection fc ON fc.student_id = u.id AND ft.id = fc.feetype_id AND fc.deleted_at is null AND ft.deleted_at is NULL
                LEFT JOIN sh_users u_col ON u_col.id = fc.collector_id AND u_col.deleted_at = 0
                LEFT join sh_classes c ON c.id = u.class_id
                LEFT join sh_countries country ON country.id = u.nationality
                LEFT JOIN sh_fee_varients fv ON u.nationality = fv.nationality AND u.joining_date BETWEEN fv.admission_from AND fv.admission_to AND fv.feetype_id = ft.id AND fv.deleted_at is NULL AND ft.deleted_at is NULL
                LEFT JOIN sh_countries sc on fv.nationality = sc.id
                WHERE u.school_id = $school_id AND u.role_id = 3 AND u.deleted_at = 0
                     
                AND ft.due_date < DATE(NOW())
                AND  NOT EXISTS(SELECT * FROM sh_fee_collection fcc WHERE fcc.student_id = u.id AND ft.class_id = u.class_id AND ft.id = fc.feetype_id AND fcc.deleted_at is NULL AND ft.deleted_at is NULL)";
            if (isset($name)) {
                $name = strtolower($name);
                $sql1 .= " AND u.name like '%$name%' ";
            } else {
                $sql1 .= " AND u.name like '%%' ";
            }     
            if (isset($class_id) && $class_id != NULL) {
                $cls_id = "";
                foreach ($class_id as $key => $value) {
                    $cls_id .= $value . ",";
                }
                $cls_ids = rtrim($cls_id, ",");
                $sql1 .= "AND u.class_id IN ($cls_ids)";
            }
            if (isset($feeType) && !empty($feeType)) {
                /* split class and batch id's form object request with , */
                $type_id = "";
                foreach ($feeType as $key => $value) {
                    $type_id .= $value . ",";
                }
                $feeType_id = rtrim($type_id, ",");
                $sql1 .= " AND ft.id IN ($feeType_id)";
            }
            if (isset($btch) && !empty($btch)) {
                $bch_id = "";
                foreach ($btch as $key => $value) {
                    $bch_id .= $value . ",";
                }
                $batches_id = rtrim($bch_id, ",");
                $sql1 .= " AND u.batch_id IN ($batches_id) ";
            }
            if (isset($discType) && !empty($discType)) {
                $disc_id = "";
                foreach ($discType as $key => $value) {
                    $disc_id .= $value . ",";
                }
                $discType_id = rtrim($disc_id, ",");
                $sql1 .= " AND fd.id IN ($discType_id) ";
            }
            if (isset($collector) && !empty($collector)) {
                $cltr = "";
                foreach ($collector as $key => $value) {
                    $cltr .= $value . ",";
                }
                $cltr_ids = rtrim($cltr, ",");
                $sql1 .= " AND fc.collector_id IN ($cltr_ids) ";
            }
            if (isset($from) && $from != NULL) {
                $sql1 .= " AND u.created_at between '" . to_mysql_date($from) . "%' ";
            }
            if (isset($to) && $to != NULL) {
                $sql1 .= " AND '" . to_mysql_date($to) . "%' ";
            }
            if (isset($partial) && $partial = true) {
                $sql1 .= "OR fc.status = '2' ";
            }

            $sql1 .= "ORDER BY c.id, b.id, fc.feetype_id";
            // print_r($sql1);die();
            $data = $this->admin_model->dbQuery($sql1);
        } else {

            $sql1 = "SELECT DISTINCT
                fc.*,
                u.name as std_name,
                country.country_name,
                sc.country_name as variant_country,
                fv.title,
                u.rollno as studentID,
                b.name as batch, 
                c.name as class_name,
                u_col.name as collector, 
                ft.name as type, 
                ft.due_date as due_date,
                ft.amount as feetype_amount, 
                fd.name as discount,
                round(v.percentage,0) as v_percentage ,

                fv.percentage as varient_fee,

                    COALESCE((round((ft.amount * v.percentage  / 100))), 0)  as discount_amount,
                    COALESCE((round((fv.percentage * v.percentage  / 100))), 0) as discount_amount_varient,
                    uu.contact as guardian_contact,
                    uu.name as guardian_name
                    from sh_students_".$school_id." u
                    LEFT JOIN sh_student_guardians sg ON u.id=sg.student_id 
                    LEFT JOIN sh_users uu ON sg.guardian_id=uu.id 
                    LEFT  JOIN sh_fee_types ft on ft.class_id = u.class_id and ft.deleted_at is null AND ft.academic_year_id = ".$academic_year."
                    LEFT join sh_fee_discount fd on u.discount_id = fd.id and fd.deleted_at is null
                    LEFT JOIN sh_batches b on b.id = u.batch_id AND b.deleted_at is NULL
                    LEFT JOIN sh_discount_varients v on v.discount_id = fd.id AND v.fee_type_id = ft.id AND v.deleted_at is null
                    LEFT join sh_fee_collection fc on fc.student_id = u.id and ft.id = fc.feetype_id and fc.deleted_at is null AND ft.deleted_at is NULL
                    LEFT JOIN sh_users u_col ON u_col.id = fc.collector_id AND u_col.deleted_at = 0
                    LEFT join sh_classes c on c.id = u.class_id AND c.deleted_at is NULL
                    LEFT join sh_countries country on country.id = u.nationality
                    LEFT JOIN sh_fee_varients fv on u.nationality = fv.nationality AND (u.joining_date between fv.admission_from AND fv.admission_to) AND fv.feetype_id = ft.id AND fv.deleted_at is NULL AND ft.deleted_at is NULL
                    LEFT JOIN sh_countries sc on fv.nationality = sc.id
                    where u.school_id = $school_id and u.role_id = 3 AND u.deleted_at = 0 ";

            // set filters of query
            if (isset($name)) {
                $name = strtolower($name);
                $sql1 .= " AND u.name like '%$name%' ";
            } else {
                $sql1 .= " AND u.name like '%%' ";
            }    
            if (isset($mode) && $mode != NULL) {
                $sql1 .= " and fc.mode = '$mode' ";
            }
            if (isset($class_id) && $class_id != NULL) {
                $cls_id = "";
                foreach ($class_id as $key => $value) {
                    $cls_id .= $value . ",";
                }
                $cls_ids = rtrim($cls_id, ",");
                $sql1 .= " AND u.class_id IN ($cls_ids) ";
            }
            if (isset($feeType) && !empty($feeType)) {
                /* split class and batch id's form object request with , */
                $type_id = "";
                foreach ($feeType as $key => $value) {
                    $type_id .= $value . ",";
                }
                $feeType_id = rtrim($type_id, ",");
                $sql1 .= " AND ft.id IN ($feeType_id)";
            }
            if (isset($btch) && !empty($btch)) {
                $bch_id = "";
                foreach ($btch as $key => $value) {
                    $bch_id .= $value . ",";
                }
                $batches_id = rtrim($bch_id, ",");
                $sql1 .= " AND u.batch_id IN ($batches_id) ";
            }
            if (isset($discType) && !empty($discType)) {
                $disc_id = "";
                foreach ($discType as $key => $value) {
                    $disc_id .= $value . ",";
                }
                $discType_id = rtrim($disc_id, ",");
                $sql1 .= " AND fd.id IN ($discType_id) ";
            }
            if (isset($collector) && !empty($collector)) {
                $cltr = "";
                foreach ($collector as $key => $value) {
                    $cltr .= $value . ",";
                }
                $cltr_ids = rtrim($cltr, ",");
                $sql1 .= " AND fc.collector_id IN ($cltr_ids) ";
            }
            if (isset($from) && $from != NULL) {
                $sql1 .= " AND fc.created_at between '" . to_mysql_date($from) . "%' ";
            }
            if (isset($to) && $to != NULL) {
                $sql1 .= " AND '" . to_mysql_date($to) . "%' ";
            }
            if (isset($partial) && $partial = true) {
                $sql1 .= "AND fc.status = '2' ";
            }
           
            $sql1 .= "ORDER BY c.id, b.id, fc.feetype_id";
             //print_r($sql1);die();
            $data = $this->admin_model->dbQuery($sql1);
           
        }

        $yasir_array = array();
        $prev_feetype_id = '';
        $prev_paid_amount = '';
        $balance = '';
        // foreach($data as $key => $rrr) {
            
        //     if( $prev_feetype_id == null){
        //         // $yasir_array = array();
        //         array_push($yasir_array, $rrr);
        //     }else{
        //         if($prev_feetype_id == $rrr->feetype_id){
        //             $rrr->paid_amount = $rrr->paid_amount + $prev_paid_amount;
        //             array_push($yasir_array, $rrr);
        //         }else{
        //             //  $yasir_array= array();
        //             array_push($yasir_array, $rrr);
        //         }
               

        //     }
        
        //     $prev_feetype_id = $rrr->feetype_id;
        //     $prev_paid_amount = $rrr->paid_amount;
        // }
        $balance = 0;
          foreach($data as $key => $rrr) {

          
            
            if( $prev_feetype_id == null){
                // $yasir_array = array();
                array_push($yasir_array, $rrr);
            }else{
                if($prev_feetype_id == $rrr->feetype_id){
                    $balance = $balance + $rrr->paid_amount;
                    $rrr = (array)$rrr;
                    $rrr['balance'] =   $balance;
                    $rrr = (object)$rrr;
                   
                    array_push($yasir_array, $rrr);
                }else{
                //  $yasir_array= array();
                    array_push($yasir_array, $rrr);
                }
               

            }
           
              // print_r($rrr);
            $prev_feetype_id = $rrr->feetype_id;
            $prev_paid_amount = $rrr->paid_amount;
        }
      
        echo json_encode($yasir_array);
    }
}

    public function getDiscountVariant($discount_id, $fees_type_id, $fee_amount) {
        $varients = 0;
        $res = $this->admin_model->dbSelect("*", "discount_varients", " discount_id='$discount_id' AND fee_type_id='$fees_type_id' AND deleted_at IS NULL ");
        if (count($res) > 0) {
            $res = $res[0];
            if($res->type == "number"){
                $varients = $res->percentage;
            } else if($res->type == "percentage"){
                $varients = ($res->percentage/100)*$fee_amount;
            }  
        }
        return $varients;
    }

    public function getDiscountVariantType($discount_id, $fees_type_id) {
        $discount_type = 'number';
        $res = $this->admin_model->dbSelect("*", "discount_varients", " discount_id='$discount_id' AND fee_type_id='$fees_type_id' AND deleted_at IS NULL ");
        if (count($res) > 0) {
            $res = $res[0];
             $discount_type = $res->name;
        }
        return $discount_type;
    }

    public function feSummary_method(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $inputs = $this->input->post("formData");
        $academic_year = $inputs['academic_year_id'];
        $class_ids = implode(",", $inputs['class_id']);
        $batch_ids = implode(",", $inputs['batch_id']);
        $academic_year_id = $inputs['academic_year_id'];
        $status = $inputs['status'];
        $sql = "SELECT u.id, cr.class_id, cr.batch_id,cr.discount_id, u.avatar, u.rollno,u.name,c.name as class_name, b.name as batch_name,u.contact, uu.contact as guardian_contact,uu.name as guardian_name FROM sh_student_class_relation  cr LEFT JOIN sh_users u ON cr.student_id=u.id INNER JOIN sh_classes c ON cr.class_id=c.id INNER JOIN sh_batches b ON cr.batch_id=b.id LEFT JOIN sh_student_guardians sg ON cr.student_id=sg.student_id LEFT JOIN sh_users uu ON sg.guardian_id=uu.id WHERE cr.class_id IN ($class_ids) AND cr.batch_id IN ($batch_ids) AND cr.academic_year_id='$academic_year_id' AND cr.deleted_at is NULL AND u.school_id='$school_id'";
        $student_ids = $this->admin_model->dbQuery($sql);
        
        //$student_ids = $this->admin_model->dbSelect("id, class_id, batch_id, academic_year_id, discount_id, avatar, rollno, name, class_id, batch_id","students_".$school_id," class_id IN ($class_ids) AND batch_id IN ($batch_ids) AND deleted_at=0 ");
        
        $data = array();
        foreach($student_ids as $std){
            $obj = new stdClass;
            $obj->std_id = $std->id;
            $obj->class_id = $std->class_id;
            $obj->discount_id = $std->discount_id;
            $obj->discount_amount = null;
            $obj->academic_year_id = $academic_year_id;
            $obj->contact = $std->contact;
            $obj->guardian_contact = $std->guardian_contact;
            $obj->guardian_name = $std->guardian_name;
            $sdata = $this->getStudentFeeRecrods($obj);
            $obj->student_id = $std->id;
            $obj->avatar = $std->avatar;
            $obj->rollno = $std->rollno;
            $obj->student_name = $std->name;
            $obj->class_id = $std->class_id;
            $obj->batch_id = $std->batch_id;
            $obj->paid_amount = $sdata["paid_total"];
            $obj->amount = $sdata["total_fees_amount"];
            $obj->class_name = $std->class_name;
            $obj->batch_name = $std->batch_name;
            $sstatus = "Unpaid";
            if($obj->amount == $obj->paid_amount){
                $sstatus = "Paid";
            }
            $obj->status = $sstatus;
            array_push($data, $obj);
        }        
        
        $new_data = array();
        if($status == "all"){
            $new_data = $data;
        } else if($status == "paid"){
            $new_data = array();
            foreach($data as $d){
                if($d->status == "Paid"){
                    array_push($new_data, $d);
                }
            }
        } else if($status == "unpaid"){
            $new_data = array();
            foreach($data as $dd){
                if($dd->status == "Unpaid"){
                    array_push($new_data, $dd);
                }
            }
        }

        echo json_encode($new_data);
    }

    public function getStudentFeeRecrods($request) {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $feetypes = $this->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND class_id='$request->class_id' AND academic_year_id='$request->academic_year_id' AND deleted_at IS NULL ");
        //feestypes join with discount and discount variants
        $sql = "SELECT "
            . "COALESCE(cls.id,'NULL') as class_id, "
            . "COALESCE(bth.id,'NULL') as batch_id, "
            . "COALESCE(cls.name,'NULL') as class_name, "
            . "COALESCE(bth.name,'NULL') as batch_name, "

            . "replace(format(CASE WHEN v.type = 'number' THEN COALESCE(v.percentage,0)
                WHEN v.type = 'percentage' THEN COALESCE(c.feetype_amount,0) * COALESCE(v.percentage,0) / 100
                ELSE COALESCE(v.percentage,0)
                END , 2),',','') as discount, "

            //. "IFNULL(v.percentage,0) as discount,"
            . "COALESCE(c.id,'NULL') as fee_collection_id, "
            . "COALESCE(d.id,'NULL') as discount_id, "
            . "c.receipt_no, c.comment, "
            . "ft.id as feetype_id, "
            . "u.name as collector_name, "
            . "COALESCE(c.feetype_amount,0) as feetype_amount, "
            . "ft.name as feetype, "
            . "ft.due_date as due_date, "
            . "COALESCE(DATE_FORMAT(c.created_at,'%d/%m/%Y'), 'NULL') as paid_date,"
            . "COALESCE(c.paid_amount,0) as paid_amount,"
            . "COALESCE(c.status, '-') as status, "
            . "COALESCE(c.mode, NULL) as mode, "
            . "ft.amount as amount, "
            . "COALESCE(c.discount_amount,0) as discounted_amount "
            . "FROM sh_fee_types ft "
            . "LEFT JOIN sh_fee_collection c ON c.feetype_id=ft.id "
            . "LEFT JOIN sh_fee_discount d ON c.discount_id=d.id "
            . "LEFT JOIN sh_discount_varients v ON ft.id=v.fee_type_id and d.id=v.discount_id "
            . "INNER JOIN sh_users u ON c.collector_id=u.id "
            . "LEFT JOIN sh_classes cls ON c.class_id=cls.id "
            . "LEFT JOIN sh_batches bth ON c.batch_id=bth.id "
            . "WHERE ft.school_id='$school_id' AND "
            . "ft.academic_year_id='$request->academic_year_id' AND "
            . "c.deleted_at IS NULL AND "
            . "ft.deleted_at IS NULL AND c.student_id='$request->std_id'";
        $collected_fees = $this->admin_model->dbQuery($sql);
        
        $paid_feetype_names = array();
        foreach ($collected_fees as $c) {
            //collected fee discount varient type (percentage or number) handled
            $res = $this->admin_model->dbSelect("type","discount_varients"," fee_type_id='$c->feetype_id' AND deleted_at IS NULL");
            if(count($res) > 0){
                $c->discount_type = $res[0]->type;
            }
            array_push($paid_feetype_names, $c->feetype);
        }
        
        foreach ($feetypes as $feetype) {
            if (!in_array($feetype->name, $paid_feetype_names)) {
                $array = array(
                    "fee_collection_id" => 'NULL',
                    "class_id" => 'NULL',
                    "class_name" => 'NULL',
                    "batch_id" => 'NULL',
                    "batch_name" => 'NULL',
                    "collector_name" => '',
                    "feetype_id" => $feetype->id,
                    "feetype_amount" => 0,
                    "feetype" => $feetype->name,
                    "due_date" => to_html_date($feetype->due_date),
                    "paid_date" => "",
                    "paid_amount" => 0,
                    "status" => 0,
                    "mode" => NULL,
                    "amount" => $feetype->amount,
                    "discount_id" => $request->discount_id,
                    "discounted_amount" => round($feetype->amount - $this->getDiscountVariant($request->discount_id, $feetype->id, $feetype->amount)),
                    "discount" => number_format((float)$this->getDiscountVariant($request->discount_id, $feetype->id, $feetype->amount), 2, '.', ''),
                    "discount_type" => $this->getDiscountVariantType($request->discount_id, $feetype->id)
                );
                array_push($collected_fees, $array);
            }
        }
        
        $new_collected_fees = array();
        $nid = $this->admin_model->dbSelect("nationality", "users", " id='$request->std_id' ")[0]->nationality;
        $admission_date = '0000-00-00';
        $rrr = $this->admin_model->dbSelect("joining_date", "users", " id='$request->std_id' AND deleted_at=0 ");
        if (count($rrr) > 0) {
            $admission_date = $rrr[0]->joining_date;
        }

        foreach ($collected_fees as $key => $cc) {
            $obj = (object) $cc;
            $fid = $obj->feetype_id;
            $obj->variant = false;
            $obj->nationality = null;
            $obj->admission_date = null;

            $varients = $this->admin_model->dbSelect("*", "fee_varients", " feetype_id='$fid' AND deleted_at IS NULL ");
            if (count($varients) > 0) {
                foreach ($varients as $v) {
                    if ($nid == $v->nationality) {
                        //found
                        if (dateInBetweenOrEqual($v->admission_from, $v->admission_to, $admission_date)) {
                            //percentage is actually fee amount 
                            $obj->amount = $v->percentage;
                            $obj->discounted_amount = round($obj->amount - $this->getDiscountVariant($request->discount_id, $fid, $obj->amount));
                            $obj->discount = number_format((float)$this->getDiscountVariant($request->discount_id, $fid, $obj->amount), 2, '.', '');
                            $obj->discount_type = $this->getDiscountVariantType($request->discount_id, $fid);
                            $obj->variant = true;
                            $obj->nationality = $this->db->select('country_name')->from('sh_countries')->where('id', $v->nationality)->get()->row()->country_name;
                            $obj->admission_date = DateTime::createFromFormat('Y-m-d', $admission_date)->format('d/m/Y');
                        }
                    }
                }
            }
            array_push($new_collected_fees, $obj);
        }
        
        $yasir_array = array();
        $prev_feetype_id = null;
        foreach($new_collected_fees as $key=>$rrr) {
            if($prev_feetype_id == null) {
                $yasir_array[$rrr->feetype_id] = array();
                array_push($yasir_array[$rrr->feetype_id], $rrr);
            } else {
                if($prev_feetype_id == $rrr->feetype_id){
                    array_push($yasir_array[$rrr->feetype_id], $rrr);
                } else {
                    $yasir_array[$rrr->feetype_id] = array();
                    array_push($yasir_array[$rrr->feetype_id], $rrr);
                }
            }
            $prev_feetype_id = $rrr->feetype_id;
        }
        
        $yasir_new_array = array();
        $paid_total = 0;
        $total_fees_amount = 0;
        foreach($yasir_array as $yyy){
            foreach($yyy as $key=>$yy){
                $paid_total += intval($yy->paid_amount);
                if($key == 0){
                    $total_fees_amount += intval($yy->discounted_amount);
                }
            }
        }
        $yasir_new_array["paid_total"] = $paid_total; 
        $yasir_new_array["total_fees_amount"] = $total_fees_amount;
        return $yasir_new_array;
    }

    public function getFeeType() {
        //--------------------
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
      
        //-------------------------
        // $id = "";
        // foreach ($request->classes as $key => $value) {
        //     $id .= $value->id . ",";
        // }
        // $ids = rtrim($id, ",");
         $ids = implode(',', $request->classes);
        if (!empty($ids) && $ids != "") {

            $data["feeTypes"] = $this->admin_model->dbSelect("DISTINCT ft.*, cls.name as class_name", "fee_types ft JOIN sh_classes cls ON cls.id = ft.class_id", "ft.class_id IN($ids) AND ft.school_id=" . $school_id . "  AND ft.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." AND ft.deleted_at IS NULL ");
        } else {
            $data = "";
        }
        echo json_encode($data);
       
    }
    public function getDiscountTypes() {
        $academic_year = $this->session->userdata("userdata")["academic_year"];

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["feeDiscount"] = $this->admin_model->dbSelect("DISTINCT *", "fee_discount", " school_id=" . $school_id . " AND deleted_at IS NULL");
        echo json_encode($data);
    }
     
    public function getCollector() {
        
        $sqly = "";
        $school_id = $this->session->userdata("userdata")["sh_id"];

        // $sqly = "Select Distinct collector_id from sh_fee_collection where school_id= ".$school_id." AND deleted_at IS NULL";

       $admin = "Select id, name from sh_users where role_id = 1 AND school_id= ".$school_id." AND deleted_at = 0";

        $emp = "Select id, name, permissions from sh_users where role_id = 4 AND school_id= ".$school_id." AND deleted_at = 0";

        $collector_new = $this->admin_model->dbQuery($admin);

     
        $emp1 = $this->admin_model->dbQuery($emp);

        foreach ($emp1 as $value) {
           
           $array = json_decode($value->permissions);
           if (isset($array)) {
          
                foreach ($array as $key => $value1) {

                    if (in_array('collection-allow', array($value1->permission)) && $value1->val == 'true') {
                        unset($value->permissions);
                      
                        array_push($collector_new, $value);
                    }
                    
                }
            }

           
        }
        
         $data["collectors"] = $collector_new;
         
        echo json_encode($data);
    }

   function generate_report() {
        $request = $this->input->post("formData");
        
        if(isset($request['name'])){
            $std_name = $request['name'];
        }
        else{
            $std_name = '';
        }
        
        $class_id = $request['class_id'];
        $batch_id = $request['batch_id'];
        $month = $request['month'];
        $academic_year_id = $request['academic_year_id'];

        $current_academic_year = $this->admin_model->dbSelect("start_date", "academic_years", " id='$academic_year_id' ")[0]->start_date;
        $year = (new DateTime($current_academic_year))->format("Y");
        $from = $year . "-" . $month . "-01";
        $to = date("Y-m-t", strtotime($from));
        
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
            . "FROM sh_students_".$school_id." u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
            . "u.role_id=" . STUDENT_ROLE_ID . " AND u.name like '%".$std_name."%' AND t.class_id='$class_id' AND t.batch_id='$batch_id' AND t.deleted_at is null GROUP by u.name ";
            
        $data["att"] = $this->admin_model->dbQuery($sql);
        
        foreach($data["att"] as $att){
            $attends_dates = explode(",", $att->attendance);

            
            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                $status = $date_and_status[1];
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
               
                array_push($dayss, $date_day);
            }
            
            $attendace = array();
            $title = array();
            $data["from"] = explode("0",(new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");
            
            for($ii=$data["from"]; $ii<=$data["to"]; $ii++){
                $ii = ($ii < 10 ? '0'.$ii : $ii);
                if(array_key_exists($ii, $statuss)){

                    $attendace[] = array("data" => $statuss[$ii]) ;
                     $title[] = array("title" => $ii);
                } else {
                    $attendace[] = array("data"=> '-');
                    $title[] = array("title" => $ii);
                }
            }
            $data['title']= $title;
            $att->attendance = $attendace;
        }
        $data['month'] = $month;
        echo json_encode($data);
    }


    public function exam_report(){
        $this->load->view('reports/examination/index');
    }

    public function getExamReport(){

        $request = $this->input->post("formData");   
         
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $active_academic_year = $request['academic_year_id'];
        $exam_id = $request['exam_id'];
        
        $classes = $request['classes'];
        $batches = implode(',', $request['batches']);
       
         // select studtents with student class relation
        $student_sql = "SELECT "
                . "u.id as student_id, "
                . "cr.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "cr.class_id, "
                . "cr.batch_id "
                . "FROM "
                . "sh_users u INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id " 
                . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
                . "WHERE "
                . "cr.class_id ='$classes' "
                . "AND cr.batch_id IN($batches) "
                . "AND cr.deleted_at IS NULL "
                . "AND u.school_id=$school_id "
                . "AND u.deleted_at=0";

        $shift_student_sql = "SELECT "
                . "u.id as student_id, "
                . "cr.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "cr.class_id, "
                . "cr.batch_id "
                . "FROM "
                . "sh_student_shifts sf LEFT JOIN sh_users u ON u.id=sf.student_id "
                . "INNER JOIN sh_student_class_relation cr on u.id = cr.student_id "
                . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
                . "LEFT JOIN sh_marksheets m on m.exam_id = " . $exam_id . " and m.student_id = u.id "
                . "WHERE "
                . "sf.class_id ='$classes' "
                . "AND sf.batch_id IN($batches)"
                . "AND m.exam_id=$exam_id "
                . "AND u.school_id=$school_id "
                . "AND cr.deleted_at IS NULL "
                . "AND cr.academic_year_id=$active_academic_year "
                . "AND sf.deleted_at IS NULL "
                . "AND u.deleted_at=0 "
                . "AND m.deleted_at is NULL";       

        $students = $this->admin_model->dbQuery($student_sql);    
        $subjects_sql = "SELECT id as subject_id, name as subject_name FROM sh_subjects WHERE school_id='$school_id' AND class_id  ='$classes' AND batch_id  IN($batches) AND deleted_at IS NULL ";

        $exams_sql = "SELECT e.id as exam_id,e.title as title, ed.id as exam_detail_id, e.title as examname, ed.subject_id, ed.type, ed.total_marks, ed.passing_marks,ed.start_time, ed.end_time, ed.exam_date, ed.class_id, ed.batch_id FROM sh_exams e LEFT JOIN sh_exam_details ed ON e.id=ed.exam_id WHERE e.deleted_at IS NULL AND ed.deleted_at IS NULL AND e.id='$exam_id' AND e.school_id='$school_id' AND e.academic_year_id='$active_academic_year' ";

        $subjects = $this->admin_model->dbQuery($subjects_sql);
        $exams = $this->admin_model->dbQuery($exams_sql);
        $students_from_shift_table = $this->admin_model->dbQuery($shift_student_sql);

      if (count($exams) > 0) {
            if ($exams[0]->exam_detail_id == NULL) {
                $data = array("status" => "error", "data" => array(), "message" => "No exams detail found");
            } else {
                $array2 = array();
                $exam_detail_ids = array();
                array_push($exam_detail_ids, $exam_id);
                $student_ids = array();
                foreach ($subjects as $key => $s) {
                    $subjects[$key]->exams = array();
                    foreach ($exams as $exam) {
                        if ($s->subject_id == $exam->subject_id && $exam->exam_id == $exam_id) {
                            $array = (object) array(
                                        'exam_id' => $exam->exam_id,
                                        'exam_name' => $exam->title,
                                        'exam_detail_id' => $exam->exam_detail_id,
                                        'total_marks' => $exam->total_marks,
                                        'passing_marks' => $exam->passing_marks,
                                        'type' => $exam->type,
                                        'start_time' => $exam->start_time,
                                        'end_time' => $exam->end_time,
                                        'exam_date' => $exam->exam_date,
                                        'obtained_marks' => NULL,
                                        'marksheet_status' => NULL,
                                        'grade' => NULL
                            );
                            array_push($subjects[$key]->exams, $array);
                            array_push($exam_detail_ids, $exam->exam_detail_id);
                        }
                    }
                }

                foreach ($students as $index => $std) {
                    array_push($student_ids, $std->student_id);
                    $students[$index]->subjects = $subjects;
                }

                $student_ids_string = 0;
                if (count($student_ids) > 0) {
                    $student_ids_string = implode(',', $student_ids);
                }

                $sql4 = "SELECT "
                        . "sh_marksheets.*,"
                        . "sh_remarks_and_positions.remark as teacher_remark, "
                        . "sh_remarks_and_positions.id as teacher_remark_id "
                        . "FROM sh_marksheets "
                        . "LEFT JOIN sh_remarks_and_positions ON sh_marksheets.student_id=sh_remarks_and_positions.student_id "
                        . "AND sh_marksheets.exam_id=sh_remarks_and_positions.exam_id "
                        . "WHERE sh_marksheets.exam_detail_id "
                        . "IN (" . implode(',', $exam_detail_ids) . ") "
                        . "AND sh_marksheets.deleted_at IS NULL AND sh_remarks_and_positions.deleted_at is null ";
                $marks = $this->admin_model->dbQuery($sql4);

                $array = array();
                foreach ($students as $key => $value) {
                    $teacher_remark = null;
                    $teacher_remark_id = null;
                    foreach ($value->subjects as $key2 => $value2) {
                        if (count($value2->exams) > 0) {
                            $marks_index = find_marks($value->student_id, $value2->exams[0]->exam_detail_id, $marks);
                            if ($marks_index != -1) {
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = $marks[$marks_index]->obtained_marks;
                                $students[$key]->subjects[$key2]->exams[0]->grade = $marks[$marks_index]->grade;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = $marks[$marks_index]->remarks;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = $marks[$marks_index]->status;
                                if (($marks[$marks_index]->teacher_remark != null && $marks[$marks_index]->teacher_remark_id != null) || (!empty($marks[$marks_index]->teacher_remark) && !empty($marks[$marks_index]->teacher_remark_id))) {
                                    $teacher_remark = $marks[$marks_index]->teacher_remark;
                                    $teacher_remark_id = $marks[$marks_index]->teacher_remark_id;
                                }
                            } else {
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = null;
                                $students[$key]->subjects[$key2]->exams[0]->grade = null;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = null;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = null;
                                $teacher_remark = null;
                                $teacher_remark_id = null;
                                
                            }
                        } else {
                            $array222 = (object) array(
                                        'exam_id' => $exam_id,
                                        'exam_detail_id' => NULL,
                                        'total_marks' => NULL,
                                        'passing_marks' => NULL,
                                        'start_time' => NULL,
                                        'type' => NULL,
                                        'end_time' => NULL,
                                        'exam_date' => NULL,
                                        'obtained_marks' => NULL,
                                        'remarks' => NULL,
                                        'marksheet_status' => NULL
                            );
                            array_push($students[$key]->subjects[$key2]->exams, $array222);
                        }
                    }
                    $is_shifted = check_student_shifted_multiple_exam_report($students_from_shift_table, $students[$key]->student_id, $exam_id, $batches);
                    $students[$key]->is_shifted = $is_shifted;
                    $students[$key]->teacher_remark = $teacher_remark;
                    $students[$key]->teacher_remark_id = $teacher_remark_id;
                    $students[$key]->position = null;
                    $students[$key]->obtained_total = null;
                    $students[$key]->result = null;
                    array_push($array, json_encode($value));
                }

                foreach ($array as $val) {
                    array_push($array2, json_decode($val));
                }

                if ($student_ids_string == 0) {
                    $data = array("status" => "error", "data" => $array2, "message" => lang("no_record"));
                } else {
                    $data = array("status" => "success", "data" => $array2, "message" => "data found");
                }
            }
        }
    
        //-------- handle subjects groups -------//
        if(count($data["data"]) > 0){
            $subject_group = array();
            foreach($data["data"] as $d){
                $subject_group = explode(",", $d->grouped_subjects);
                foreach($d->subjects as $ys){
                    if(!in_array($ys->subject_id, $subject_group)){
                        $ys->exams[0]->exam_detail_id = NULL;
                        $ys->exams[0]->total_marks = NULL;
                        $ys->exams[0]->passing_marks = NULL;
                        $ys->exams[0]->start_time = NULL;
                        $ys->exams[0]->type = NULL;
                        $ys->exams[0]->end_time = NULL;
                        $ys->exams[0]->exam_date = NULL;
                        $ys->exams[0]->obtained_marks = NULL;
                        $ys->exams[0]->remarks = NULL;
                        $ys->exams[0]->marksheet_status = NULL;
                        $ys->exams[0]->total_marks = '-';
                        $ys->exams[0]->obtained_marks = '-';
                    }
                }
            }
        }
        
        //----------- Start::Obtained total marks ------------//
        foreach ($data["data"] as $kkk => $ss) {
            $obtained_total = 0;
            foreach ($ss->subjects as $sub) {
                if($sub->exams[0]->type == 'number') {
                    $obtained_total = $sub->exams[0]->obtained_marks;
                    $data["data"][$kkk]->obtained_total += intval($obtained_total);
                }
            }
        }
        //----------- End::Obtained total marks ------------//

        //----------- Start::Exam total marks ------------//
        foreach ($data["data"] as $kkkk => $ss) {
            $data["exam_total_marks"] = null;
            $exam_total_total = 0;
            foreach ($ss->subjects as $sub) {
                $exam_total_total = $sub->exams[0]->total_marks;
                $data["exam_total_marks"] += intval($exam_total_total);
            }
            break;
        }
        //----------- End::Exam total marks ------------//
        
        //----------- Start::Result Pass or Fail According to Rules ------------//
        $passing_rules = $this->admin_model->dbSelect("*", "passing_rules", " class_id  ='$classes' AND batch_id IN($batches) AND school_id='$school_id' AND exam_id='$exam_id' AND deleted_at IS NULL ");
        $passing_rules_obj = null;
        if (count($passing_rules) > 0) {
            $passing_rules_obj = $passing_rules[0];
        }

        if (is_null($passing_rules_obj)) {
            foreach ($data["data"] as $kk => $ss) {
                $data["data"][$kk]->result = "";
            }
        } else {

            foreach ($data["data"] as $kk => $ss) {
                $grouped_subjects = null;
                if (!is_null($ss->grouped_subjects)) {
                    $grouped_subjects = explode(",", $ss->grouped_subjects);
                }

                $number_of_subjects_passed = 0;
                $exam_total_total = 0;
                foreach ($ss->subjects as $sub) {
                    if ($sub->exams[0]->marksheet_status == 'Pass') {
                        if (!is_null($grouped_subjects)) {
                            if (in_array($sub->subject_id, $grouped_subjects)) {
                                $exam_total_total += intval($sub->exams[0]->total_marks);
                            }
                        } else if (is_null($grouped_subjects)) {
                            $exam_total_total += intval($sub->exams[0]->total_marks);
                        }
                        $number_of_subjects_passed++;
                    } else {
                        if (!is_null($grouped_subjects)) {
                            if (in_array($sub->subject_id, $grouped_subjects)) {
                                $exam_total_total += intval($sub->exams[0]->total_marks);
                            }
                        } else if (is_null($grouped_subjects)) {
                            $exam_total_total += intval($sub->exams[0]->total_marks);
                        }
                    }
                }

                $data["data"][$kk]->result = lang("fail");
                $obtained_percentage = "";
                if ($exam_total_total != 0) {
                    $obtained_percentage = $ss->obtained_total * 100 / $exam_total_total;
                }
                if ($passing_rules_obj->operator == "AND") {
                    if ($number_of_subjects_passed >= $passing_rules_obj->minimum_subjects && $obtained_percentage >= $passing_rules_obj->minimum_percentage) {
                        $data["data"][$kk]->result = lang("pass");
                    }
                } else if ($passing_rules_obj->operator == "OR") {
                    if ($number_of_subjects_passed >= $passing_rules_obj->minimum_subjects || $obtained_percentage >= $passing_rules_obj->minimum_percentage) {
                        $data["data"][$kk]->result = lang("pass");
                    }
                }
                $data["data"][$kk]->obtained_total_old = $data["data"][$kk]->obtained_total;
                $data["data"][$kk]->obtained_total = $data["data"][$kk]->obtained_total . "/" . $exam_total_total;
                if ($exam_total_total != 0) {
                    $data["data"][$kk]->percentage = round((intval($data["data"][$kk]->obtained_total) * 100 ) / intval($exam_total_total), 2);
                } else {
                    $data["data"][$kk]->percentage = "";
                }
                
            }
        }
        //----------- Start::Result Pass or Fail According to Rules ------------//
        
        //----------- Start::Calculate position ------------//
        $arr = array();
        $arr2 = array();
        foreach ($data["data"] as $sk => $ssd) {
            if ($ssd->result == lang("pass")) {
                array_push($arr, $ssd->percentage);
            }
        }
        //---------By Umar---------//
        foreach ($data["data"] as $sk1 => $ssd1) {
            if ($ssd1->result == lang("fail")) {
                array_push($arr2, $ssd1->percentage);
            }
        }


        rsort($arr);
        $old_unique = array_unique($arr);
        $unique = array();
        foreach ($old_unique as $uuu) {
            array_push($unique, $uuu);
        }
        rsort($arr2);
        $old_unique2 = array_unique($arr2);
        $unique2 = array();
        foreach ($old_unique2 as $uuu2) {
            array_push($unique2, $uuu2);
        }


        foreach ($data["data"] as $k1 => $std1) {
            if ($std1->result == lang("pass")) {
                if (in_array($std1->percentage, $unique)) {
                    $position_key = array_search($std1->percentage, $unique);
                    $data["data"][$k1]->position = $position_key + 1;
                    $data["data"][$k1]->percentage .= "%";
                }
            }
        }

        // ----------- End::Calculate position ------------//

        foreach ($data["data"] as $key => $val) {
            $data["data"][$key]->new_position = $this->position_string($val->position);
        }
     
        //-----------Code Percentage-------------//
        foreach ($data["data"] as $k1 => $std1) {
            if ($std1->result == lang("fail")) {
                if (in_array($std1->percentage, $unique2)) {
                    $position_key = array_search($std1->percentage, $unique2);
                    $data["data"][$k1]->position = $position_key + 1000;
                    $data["data"][$k1]->percentage .= "%";
                }
            }
        }
        $i = 0;
        foreach ($data["data"] as $k2 => $std2) {
            $class_name = $this->admin_model->dbSelect("name", "classes", " id ='$std2->class_id' AND school_id='$school_id' AND deleted_at IS NULL ");
            $batch_name = $this->admin_model->dbSelect("name", "batches", " id ='$std2->batch_id' AND school_id='$school_id' AND deleted_at IS NULL ");
            $subject_group_name = $this->admin_model->dbSelect("group_name", "subject_groups", " id ='$std2->subject_group_id' AND school_id='$school_id' AND deleted_at IS NULL ");

            $remark_teacher = $this->admin_model->dbSelect("name", "users", " id ='$std2->teacher_remark_id' AND deleted_at IS NULL ");
            $std2->index = ++$i;
            if (count($class_name) > 0) {
                $std2->class_name = $class_name[0]->name;
            }
            if (count($batch_name) > 0) {
                $std2->batch_name = $batch_name[0]->name;
            }
            if (count($subject_group_name) > 0) {
                $std2->subject_group_name = $subject_group_name[0]->group_name;
            }
             if (count($remark_teacher) > 0) {
                $std2->remark_teacher = $remark_teacher[0]->name;
            }
            //print_r($std2);die();
        }
       
       
        echo json_encode($data['data']);
    }
   public function position_string($i) {
        if (empty($i) || is_null($i)) {
            return "";
        }
        $j = $i % 10;
        $k = $i % 100;
        if ($j == 1 && $k != 11) {
            return $i . "st";
        }
        if ($j == 2 && $k != 12) {
            return $i . "nd";
        }
        if ($j == 3 && $k != 13) {
            return $i . "rd";
        }
        return $i . "th";
    }

    public function payroll_report(){
        $this->load->view("reports/payroll/index");
    }

    public function fetchPayrollEmployees(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $request = $this->input->post("formData");
        $department_id = $request['department_id'];
        $category_id = $request['category_id'];
        $salary_type_id = $request['salary_type_id'];
        $admin_id = $request['admin_id'];
        $status_id = $request['status_id'];

        $payroll_users = $this->admin_model->dbQuery("select GROUP_CONCAT(employees) as employees from sh_payroll_groups where school_id = $school_id and deleted_at is null
            ");
        $payroll_users_ids = $payroll_users[0]->employees;
        $payroll_users_ids = explode(",", $payroll_users_ids);
        $sql = $this->db->select('users.*,
            departments.name as department_name,
            payroll.paid_by,
            payroll.status as p_status,
            payroll.other_deductions,
            payroll.additional_payment,
            payroll.basic_salary,
            payroll.amount_paid,
            payroll.date,
            payroll.receipt_no,
            payroll.mode,
            payroll.remarks as p_remarks,
            salary_types.name as salary_type_name,
            role_categories.category')->from('users')
        ->join('payroll','users.id=payroll.user_id', 'Left')
        ->join('departments', 'users.department_id=departments.id', 'Left')
        ->join('salary_types', 'payroll.salary_type_id=salary_types.id', 'Left')
        ->join('role_categories', 'users.role_category_id=role_categories.id', 'Left')
        ->where_in('users.id', $payroll_users_ids)
        ->where('payroll.deleted_at', NULL)
        ->where('salary_types.deleted_at', NULL);


        if ($department_id == "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id == "all" && $status_id == "all"){

            $data1 = $sql
            ->group_by('users.id')
            ->get()->result();
        }
        
        if ($department_id != "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id == "all" && $status_id == "all"){

            $data1 = $sql
            ->where('departments.id', $department_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id == "all" && $admin_id == "all" && $status_id == "all"){

            $data1 = $sql
            ->where('users.role_category_id', $category_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id != "all" && $admin_id == "all" && $status_id == "all"){

            $data1 = $sql
            ->where('users.role_category_id', $category_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id != "all" && $admin_id != "all" && $status_id == "all"){

            $data1 = $sql
            ->where('users.role_category_id', $category_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->where('payroll.paid_by', $admin_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id != "all" && $admin_id != "all" && $status_id != "all"){

            $data1 = $sql
            ->where('users.role_category_id', $category_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->where('payroll.paid_by', $admin_id)
            ->where('payroll.status', $status_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id == "all" && $status_id == "all"){

            $data1 = $sql
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id != "all" && $status_id == "all"){

            $data1 = $sql
            ->where('payroll.paid_by', $admin_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id == "all" && $status_id != "all"){

            if ($status_id == 0 ){
                $data1 = $sql
                ->where('payroll.status is null')
                ->group_by('users.id')
                ->get()->result();
            } else
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id == "all" && $status_id != "all"){
            $data1 = $sql
            ->where('departments.id', $department_id)
            ->where('payroll.status', $status_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id == "all" && $admin_id == "all" && $status_id != "all"){
            $data1 = $sql
            ->where('departments.id', $department_id)
            ->where('users.role_category_id', $category_id)
            ->where('payroll.status', $status_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id != "all" && $admin_id == "all" && $status_id != "all"){
            $data1 = $sql
            ->where('departments.id', $department_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->where('users.role_category_id', $category_id)
            ->where('payroll.status', $status_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id != "all" && $status_id == "all"){
            $data1 = $sql
            ->where('departments.id', $department_id)
            ->where('payroll.paid_by', $admin_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id == "all" && $status_id == "all"){
            $data1 = $sql
            ->where('departments.id', $department_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id != "all" && $status_id == "all"){
            $data1 = $sql
            ->where('payroll.paid_by', $admin_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id != "all" && $status_id == "all"){
            $data1 = $sql
            ->where('departments.id', $department_id)
            ->where('payroll.paid_by', $admin_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id != "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('payroll.paid_by', $admin_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id == "all" && $salary_type_id == "all" && $admin_id != "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('payroll.paid_by', $admin_id)
            ->where('departments.id', $department_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id == "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id != "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('payroll.paid_by', $admin_id)
            ->where('departments.id', $department_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id != "all" && $admin_id == "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('users.role_category_id', $category_id)
            ->where('departments.id', $department_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id != "all" && $category_id != "all" && $salary_type_id == "all" && $admin_id != "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('payroll.paid_by', $admin_id)
            ->where('departments.id', $department_id)
            ->where('users.role_category_id', $category_id)
            ->group_by('users.id')
            ->get()->result();
        }

        if ($department_id == "all" && $category_id == "all" && $salary_type_id != "all" && $admin_id != "all" && $status_id != "all"){
            $data1 = $sql
            ->where('payroll.status', $status_id)
            ->where('payroll.paid_by', $admin_id)
            ->where('payroll.salary_type_id', $salary_type_id)
            ->group_by('users.id')
            ->get()->result();
        }

        $data2 = $this->admin_model->dbQuery("SELECT p.paid_by, u.id, u.role_id, u.name as admin_name FROM sh_payroll p INNER JOIN sh_users u ON p.paid_by = u.id WHERE p.paid_by = u.id AND p.school_id='$school_id' AND p.deleted_at is NULL ");

        $array = json_decode(json_encode($data2), true);
        $array2 = json_decode(json_encode($data1), true);

        for ($i=0; $i<count($array2); $i++){
            foreach ($array as $key => $ar) {
                $array2[$i]['admin_name'] = $ar['admin_name']; 
            }
        } 
        $data = json_decode(json_encode($array2));

        echo json_encode($data); 


    }
    public function accounts_report(){
        $this->load->view("reports/accounts/index");
    }
    function getIncomeTypes(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // print_r($school_id);die();
        $data["income_types"] = $this->db->select('id,name')->from('sh_income_types')->where('school_id', $school_id)->get()->result();
        // print_r($data) ;die();
        echo json_encode($data);
    }
    function getIncomeCategories(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $income_type_id = $request->id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // print_r($income_type_id);die();
        if($income_type_id=='all')
        {
            $data["income_categories"] = $this->db->select('id, category_name')->from('sh_income_categories')->where('school_id', $school_id)->get()->result();
            // print_r($data) ;die();
            echo json_encode($data);
        }
        else {
        $data["income_categories"] = $this->db->select('id, category_name')->from('sh_income_categories')->where('income_type_id', $income_type_id)->get()->result();
        // print_r($data) ;die();
        echo json_encode($data);
            }
    }
    function getIncomeCollectedBy(){
        $school_id = $this->session->userdata("userdata")["sh_id"];

        // $this->db->select('name');
        // $this->db->from('sh_users');
        // $this->db->join('sh_incomes', 'sh_incomes.collected_by=sh_users.id', 'left');
        // $query = $this->db->get();
        // print_r($query->result());die();
        // foreach($ids as $i){
        //     $data['name'] = $this->db->select('name')->form('sh_users')->where('id' , $i)->get()->result();
            
        // }
        // print_r($data) ;die();
        $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')->join('sh_income_categories ic','i.income_category_id = ic.id')->join('sh_users u', 'u.id = i.collected_by','left')->join('sh_users u1', 'u1.id = i.created_by','left')->where('i.school_id', $school_id)->order_by('i.date','desc')->order_by('i.created_at','desc')->get()->result();
        $data["income_collectedBy"]=$incomes_result;
        // print_r($data) ;die();
        echo json_encode($data);
    
    }
    function fetchPayrollIncome(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $request = $this->input->post("formData");
        $income_id = $request['income_id'];
        $category_id = $request['category_id'];
        $collectedBy_id = $request['collectedBy_id'];
        // $collected_by_users = $this->db->select('id,name')->from('sh_users')->where('school_id' , $school_id)->get()->result();
        $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
        ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
        ->join('sh_income_categories ic','i.income_category_id = ic.id')
        ->join('sh_users u', 'u.id = i.collected_by','left')
        ->join('sh_users u1', 'u1.id = i.created_by','left')
        ->where('i.school_id', $school_id)->order_by('i.date','desc')
        ->order_by('i.created_at','desc')->get()->result();
        // print_r($incomes_result);die();

        if ($income_id == "all" && $category_id == "all" && $collectedBy_id == "all"){

            $data = $incomes_result;
        }
        if ($income_id != "all" && $category_id == "all" && $collectedBy_id == "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.income_id' , $income_id)->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }
        if ($income_id != "all" && $category_id != "all" && $collectedBy_id == "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.income_category_id' , $category_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }

        if ($income_id != "all" && $category_id != "all" && $collectedBy_id != "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.income_id' , $income_id)
            ->where('i.income_category_id' , $category_id)
            ->where('i.collected_by' , $collectedBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }
        if ($income_id == "all" && $category_id == "all" && $collectedBy_id != "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.collected_by' , $collectedBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }
        if ($income_id != "all" && $category_id == "all" && $collectedBy_id != "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.income_id' , $income_id)
            ->where('i.collected_by' , $collectedBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }
        if ($income_id == "all" && $category_id != "all" && $collectedBy_id != "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.income_category_id' , $category_id)
            ->where('i.collected_by' , $collectedBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }
        if ($income_id == "all" && $category_id != "all" && $collectedBy_id == "all"){
            $incomes_result = $this->db->select('"true" as income,i.id,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')
            ->join('sh_income_categories ic','i.income_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.collected_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.income_category_id' , $category_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $incomes_result;
            // print_r($data);die();
        }



        echo json_encode($data);




    }



    function getExpenseTypes(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // print_r($school_id);die();
        $data["expense_types"] = $this->db->select('id,name')->from('sh_expense_types')->where('school_id', $school_id)->get()->result();
        // print_r($data) ;die();
        echo json_encode($data);
    }

    function getExpenseCategories(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $expense_type_id = $request->id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // print_r($expense_type_id);die();
        if($expense_type_id=='all')
        {
            $data["expense_categories"] = $this->db->select('id, category_name')->from('sh_expense_categories')->where('school_id', $school_id)->get()->result();
            // print_r($data) ;die();
            echo json_encode($data);
        }
        else {
        $data["expense_categories"] = $this->db->select('id, category_name')->from('sh_expense_categories')->where('expense_type_id', $expense_type_id)->get()->result();
        // print_r($data) ;die();
        echo json_encode($data);
            }
    }
    function getExpensePaidBy(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')->join('sh_expense_categories ic','i.expense_category_id = ic.id')->join('sh_users u', 'u.id = i.paid_by','left')->join('sh_users u1', 'u1.id = i.created_by','left')->where('i.school_id', $school_id)->order_by('i.date','desc')->order_by('i.created_at','desc')->get()->result();
        $data["expense_paidBy"]=$expenses_result;
        // print_r($data) ;die();
        echo json_encode($data);
    
    }


    function fetchPayrollExpense(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $request = $this->input->post("formData");
        $expense_id = $request['expense_id'];
        $category_id = $request['category_id'];
        $paidBy_id = $request['paidBy_id'];
        $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
        ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
        ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
        ->join('sh_users u', 'u.id = i.paid_by','left')
        ->join('sh_users u1', 'u1.id = i.created_by','left')
        ->where('i.school_id', $school_id)->order_by('i.date','desc')
        ->order_by('i.created_at','desc')->get()->result();
        // print_r($expenses_result);die();

        if ($expense_id == "all" && $category_id == "all" && $paidBy_id == "all"){

            $data = $expenses_result;
        }
        if ($expense_id != "all" && $category_id == "all" && $paidBy_id == "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.expense_id' , $expense_id)->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }
        if ($expense_id != "all" && $category_id != "all" && $paidBy_id == "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.expense_category_id' , $category_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }

        if ($expense_id != "all" && $category_id != "all" && $paidBy_id != "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.expense_id' , $expense_id)
            ->where('i.expense_category_id' , $category_id)
            ->where('i.paid_by' , $paidBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }
        if ($expense_id == "all" && $category_id == "all" && $paidBy_id != "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.paid_by' , $paidBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }
        if ($expense_id != "all" && $category_id == "all" && $paidBy_id != "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.expense_id' , $expense_id)
            ->where('i.paid_by' , $paidBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }
        if ($expense_id == "all" && $category_id != "all" && $paidBy_id != "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.expense_category_id' , $category_id)
            ->where('i.paid_by' , $paidBy_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }
        if ($expense_id == "all" && $category_id != "all" && $paidBy_id == "all"){
            $expenses_result = $this->db->select('"true" as expense,i.id,expense_category_id,paid_by as paid_by_id,it.name as expense_type, category_name, amount,u.name as paid_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,',false)
            ->from('sh_expenses i')->join('sh_expense_types it','i.expense_id = it.id','left')
            ->join('sh_expense_categories ic','i.expense_category_id = ic.id')
            ->join('sh_users u', 'u.id = i.paid_by','left')
            ->join('sh_users u1', 'u1.id = i.created_by','left')
            ->where('i.school_id', $school_id)
            ->where('i.expense_category_id' , $category_id)
            ->order_by('i.date','desc')
            ->order_by('i.created_at','desc')->get()->result();
            $data = $expenses_result;
            // print_r($data);die();
        }
        echo json_encode($data);


}


}