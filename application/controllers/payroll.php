<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payroll extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
         // ism 108
        if($this->session->userdata("userdata")["sh_id"] == 108){
            redirect(site_url("dashboard"));
        }
        check_user_permissions();
    }

    public function index(){
        $this->load->view("payroll/index");
    }

    public function settings(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];
        /*$xcrud = xcrud_get_instance();
        $xcrud->table('sh_payroll_groups');
        $xcrud->where("school_id", $school_id)->where('deleted_at is null');
        $xcrud->fields('name,description,employees');
        $xcrud->columns('name,description,employees');
        $xcrud->pass_var('school_id', $school_id);
        $xcrud->relation('employees', 'sh_users', 'id', 'name', 'deleted_at=0 and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and role_id = 4', '', true);
        $xcrud->unset_title();
        $xcrud->replace_remove('soft_delete');
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $data["groups"] = $xcrud->render();*/
        
        if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) {

            $arr = $this->session->userdata("userdata")['persissions'];

            $array = json_decode($arr);
            if (isset($array)) {
                $settingsadd = 0;
                $settingsedit = 0;
                $settingsdelete = 0;
                foreach ($array as $key => $value) {
                    if (in_array('payroll-settingsadd', array($value->permission)) && $value->val == 'true') {
                        $settingsadd = 1;
                    } if (in_array('payroll-settingsedit', array($value->permission)) && $value->val == 'true') {
                        $settingsedit = 1;
                    }if (in_array('payroll-settingsdelete', array($value->permission)) && $value->val == 'true') {
                        $settingsdelete = 1;
                    }
                }
            }
        }
            
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_salary_types');
        $xcrud->where("school_id", $school_id)->where('deleted_at is null');
        $xcrud->fields('name,description,payroll_group_id,date');
        $xcrud->columns('name,description,payroll_group_id,date');
        
        $xcrud->pass_var('school_id', $school_id);
        $xcrud->pass_var('academic_year_id', $active_academic_year);
        $xcrud->relation('payroll_group_id', 'sh_payroll_groups', 'id', 'name', "deleted_at IS NULL AND school_id=" . $school_id,'', true);
      
        $xcrud->label('name',lang('lbl_name'))->label('description',lang('description'))->label('payroll_group_id',lang('lbl_payroll_group'))->label('date',lang('due_date'));
        
        $xcrud->unset_title();
        $xcrud->replace_remove('soft_delete');
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        if(isset($settingsadd)){
            if($settingsadd == '0'){
                $xcrud->unset_add();
            }
        }
        if(isset($settingsedit)){
            if($settingsedit == '0'){
                $xcrud->unset_edit();
            }
        }
        if(isset($settingsdelete)){
            if($settingsdelete == '0'){
                $xcrud->unset_remove();
            }
        }
       
        $data["salary_types"] = $xcrud->render();

        /*$variants = $xcrud->nested_table('variants','id','sh_salary_varients','salary_type_id');
        $variants->fields('name,description,amount,type,effect');
        $variants->columns('name,description,type,effect,amount');
        $variants->pass_var('school_id', $school_id);
        $variants->table_name('Salary Variants');
        // $variants->unset_title();
        $variants->replace_remove('soft_delete');
        $variants->unset_search();
        $variants->unset_limitlist();
        $variants->unset_print();
        $variants->unset_csv();
        */

        $rules = xcrud_get_instance();
        $rules->table('sh_salary_increment_rules');
        $rules->where("school_id", $school_id)->where('deleted_at is null');
        $rules->fields('name,apply_number_of_months,amount,payroll_group_id,type,effect');
        $rules->columns('name,apply_number_of_months,amount,payroll_group_id,type,effect');
        
         // language update at 10-08-2021 by azeem
        $rules->label('name',lang('lbl_name'))->label('apply_number_of_months',lang('apply_after_number_of_months'))->label('amount',lang('amount'))->label('payroll_group_id',lang('lbl_payroll_group'))->label('type',lang('type_dt'))->label('effect',lang('lbl_effect'));
        
        $rules->pass_var('school_id', $school_id);
        $rules->relation('payroll_group_id', 'sh_payroll_groups', 'id', 'name', 'deleted_at IS NULL AND school_id = ' . $school_id, '', true);
      
        
        
        $rules->unset_title();
        $rules->replace_remove('soft_delete');
        $rules->unset_search();
        $rules->unset_limitlist();
        $rules->unset_print();
        $rules->unset_csv();
        if(isset($settingsadd)){
            if($settingsadd == '0'){
                $xcrud->unset_add();
            }
        }
        if(isset($settingsedit)){
            if($settingsedit == '0'){
                $xcrud->unset_edit();
            }
        }
        if(isset($settingsdelete)){
            if($settingsdelete == '0'){
                $xcrud->unset_remove();
            }
        }
        $data["increment_rules"] = $rules->render();

        $this->load->view('payroll/settings', $data);
    }

    public function fetchPayrollEmployees() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // condition added by sheraz #AYP
        if ($request->academic_year_id == "") {
            $active_academic_year = $this->session->userdata("userdata")["academic_year"];   
        } else {
            $active_academic_year = $request->academic_year_id;
        }
        $department_id = $request->department_id;
        $unpaid_flag = $request->unpaid;
        $category_id = $request->category_id;
        $searchBy = $request->searchBy;
        $employees = array();

        //$sql = "Select u.id,u.department_id,u.role_category_id,u.avatar,u.name,u.rollno,u.job_title,c.category,d.name as department FROM sh_users u INNER JOIN sh_departments d ON u.department_id=d.id INNER JOIN sh_role_categories c ON u.role_category_id=c.id WHERE u.school_id='$school_id' AND u.department_id='$department_id' AND u.role_category_id='$category_id' AND u.name like '%".$searchBy."%' AND u.role_id=".EMPLOYEE_ROLE_ID." AND u.deleted_at=0 ";
        //$employees = $this->admin_model->dbQuery($sql);
        $salary_types_count = "(SELECT COUNT(*) FROM sh_salary_types st WHERE st.deleted_at IS NULL AND academic_year_id='$active_academic_year' AND st.date < '" . date('Y-m-d') . "') AS stCount,";
        $salary_paid_count = "(SELECT COUNT(*) FROM sh_payroll pyr INNER JOIN sh_salary_types st ON st.id = pyr.salary_type_id WHERE pyr.deleted_at IS NULL AND st.deleted_at IS NULL AND st.date < '" . date('Y-m-d') . "') AS scCount ";

        
        $sql = "SELECT " 
            ."u.id,u.department_id, "
            ."u.role_category_id as category_id, "
            ."u.avatar,u.name, "
            ."u.rollno, "
            ."u.job_title, "
            ."u.mobile_phone, "
            ."u.rollno, "
            ."date_format(u.joining_date,'%Y/%m/%d') as joining_date, "
            ."c.category,d.name as department, "
            ."g.id as payroll_group_id, "
            ."g.name as group_name, "
            ."u.basic_salary as basic_salary " 
            ."FROM sh_users u 
            INNER JOIN sh_departments d ON u.department_id=d.id 
            INNER JOIN sh_role_categories c ON u.role_category_id=c.id 
            LEFT JOIN sh_payroll_groups g ON FIND_IN_SET(u.id, g.employees) > 0 AND g.deleted_at IS NULL AND g.academic_year_id=$active_academic_year
            WHERE u.school_id='$school_id'
            AND u.name like '%".$searchBy."%' 
            AND u.role_id=".EMPLOYEE_ROLE_ID." 
            AND u.deleted_at=0 ";
        if($department_id != "all"){
            $sql .= "AND u.department_id='$department_id' ";
        } 
        if($category_id != "all"){
            $sql .= "AND u.role_category_id='$category_id' ";
        }

        /*if($unpaid_flag){
            $sql .= " AND stCount > scCount ";
        }*/
        $employees = $this->admin_model->dbQuery($sql);
        
        
        foreach($employees as $key=>$emp){
            if(isset($emp->payroll_group_id)){
                // echo '<pre>';
                // print_r($emp);
                // die;
                $payroll_group_id = $emp->payroll_group_id;
                
                $sql = "SELECT * FROM sh_salary_types WHERE FIND_IN_SET($payroll_group_id, payroll_group_id)> 0 AND deleted_at IS NULL AND academic_year_id='$active_academic_year' AND school_id='$school_id'";
                
                $stCount = $this->admin_model->dbQuery($sql);
                
                $salary_type_ids = array();
                if(count($stCount) > 0){
                    foreach($stCount as $c){
                        array_push($salary_type_ids, $c->id);
                    }
                    $stCount = count($stCount);
                } else {
                    $stCount = 0;
                }
                
                // $sql = "SELECT count(*) as scCount FROM sh_payroll WHERE user_id='$emp->id' AND school_id='$school_id' AND deleted_at IS NULL AND academic_year_id='$active_academic_year'";
                $sql = "SELECT * FROM sh_payroll WHERE user_id='$emp->id' AND school_id='$school_id' AND deleted_at IS NULL AND academic_year_id='$active_academic_year'";
                if(count($salary_type_ids) > 0){
                    $sql .= "AND salary_type_id IN (".implode(",",$salary_type_ids).")";
                }
                $scCount = $this->admin_model->dbQuery($sql);
                $unique_arrPayroll=array(); 
                
                $payroll_fStatus=0;
                $verify_amount_paid=0;
                $verify_total_amount=0;
                
                if(sizeof($scCount) > 0){
                    
                    foreach($scCount as $akey => $sscCount)
                    {
                        $unique_arrPayroll[$sscCount->salary_type_id] = $sscCount;
                        $verify_amount_paid=$verify_amount_paid+$sscCount->amount_paid;
                    }
                    
                    $unique_arrPayroll=array_values($unique_arrPayroll);
                    foreach($unique_arrPayroll as $uni_salary_proll)
                    {
                        $verify_total_amount=$verify_total_amount+$uni_salary_proll->total_amount;
                    }
                    
                
                    if($verify_total_amount <= $verify_amount_paid)
                    {
                        $payroll_fStatus=1;
                    }
                    else if($verify_total_amount > $verify_amount_paid)
                    {
                        $payroll_fStatus=0;
                    }
                    
                    $scCount = sizeof($scCount);
                } else {
                    $scCount = 0;
                }
                $employees[$key]->scCount = $scCount;
                $employees[$key]->stCount = $stCount;
                $employees[$key]->payroll_status = $payroll_fStatus;
                $employees[$key]->verify_total_amount = $verify_total_amount;
                $employees[$key]->verify_amount_paid = $verify_amount_paid;
            } else {
                $employees[$key]->scCount = 0;
                $employees[$key]->stCount = -1;
                $employees[$key]->payroll_status = 0;
            }
            

        }

        /*if($unpaid_flag){
            foreach($employees as $mp){

            }
        }*/
        echo json_encode($employees);
    }

    public function getEmpPayrollRecrods() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // condition added by sheraz #AYP
        if ($request->academic_year_id == "") {
            $active_academic_year = $this->session->userdata("userdata")["academic_year"];   
        } else {
            $active_academic_year = $request->academic_year_id;
        }
        $employee_id = $request->emp_id;
        $current_salary = intval($request->basic_salary);
        
        $allowance = 0;
        $deducation = 0;
        $p_groups = $this->admin_model->dbQuery("SELECT id from sh_payroll_groups WHERE FIND_IN_SET($employee_id,employees)>0 AND academic_year_id='$active_academic_year' AND deleted_at IS NULL");
        if(count($p_groups) > 0){
            $p_groups = $p_groups[0]->id;
            $varients = $this->admin_model->dbSelect("*","salary_varients"," payroll_group_id='$p_groups' AND deleted_at IS NULL ");
            foreach($varients as $v){
                if($v->type == "number" && $v->effect == "positive"){
                    $allowance += $v->amount;
                } else if($v->type == "number" && $v->effect == "negative"){
                    $deducation -= $v->amount;
                } else if($v->type == "percentage" && $v->effect == "positive"){
                    $temp = $current_salary * $v->amount / 100;
                    $allowance += $temp;
                } else if($v->type == "percentage" && $v->effect == "negative") {
                    $temp = $current_salary * $v->amount / 100;
                    $deducation -= $temp;
                }
            }
        } else {
            $p_groups = 0;
        }
        
        $collected_payrolls = $this->admin_model->dbQuery("SELECT p.*,u.name as paid_by_name FROM sh_payroll p INNER JOIN sh_users u ON p.paid_by=u.id WHERE p.user_id='$employee_id' AND p.deleted_at IS NULL");
        
        $salaryTypes = $this->admin_model->dbSelect("*", "salary_types", " school_id='$school_id' AND FIND_IN_SET($p_groups, payroll_group_id) AND deleted_at IS NULL AND academic_year_id='$active_academic_year' ");
        

        foreach($salaryTypes as $key=>$row){
            $salaryTypes[$key]->allowance = $allowance;
            $salaryTypes[$key]->deducation = $deducation;
            $salaryTypes[$key]->user_id = $employee_id;
            $salaryTypes[$key]->payable_amount = $current_salary+$allowance+$deducation;
            $salaryTypes[$key]->payroll_id = 'null';
            $salaryTypes[$key]->status = 0;
            $salaryTypes[$key]->date = to_html_date($salaryTypes[$key]->date);
        }
        
        $records = array();
        $data = array();
        if(count($salaryTypes) > 0) { 
            foreach($salaryTypes as $key=>$row1){
                $arr = (object)array();
                $arr->payroll_group_id = $row1->payroll_group_id;
                $arr->salary_type_id = $row1->id;
                $arr->salary_name = $row1->name;
                $arr->due_date = $row1->date;
                $arr->amount = $row1->amount;
                $arr->allowance = $row1->allowance;
                $arr->deducation = $row1->deducation;
                $arr->payable_amount = $row1->payable_amount;
                $arr->payroll_record = array();
                $arr->total_paid_amount = 0;
                $arr->employee_id = $employee_id;
                $arr->other_deductions = 0;
                $arr->additional_payment = 0;
                $arr->total_other_deductions = 0;
                $arr->total_additional_payment = 0;
                $arr->basic_salary = $current_salary;

                foreach($collected_payrolls as $r){
                    $arr2 = (object)array();
                    if($r->salary_type_id == $row1->id){
                        $arr2->payroll_id = $r->id;
                        $arr2->paid_by_id = $r->paid_by;
                        $arr2->paid_by_name = $r->paid_by_name;
                        $arr2->paid_amount = $r->amount_paid;
                        $arr2->remarks = $r->remarks;
                        $arr2->dedection_remarks = $r->dedection_remarks;
                        $arr2->receipt_no = $r->receipt_no;
                        $arr2->created_at = to_html_date(explode(" ",$r->created_at)[0]);
                        $arr2->deducation_amount = $r->deduction_amount;
                        $arr2->status = $r->status;
                        $arr2->basic_salary = $r->basic_salary;
                        $arr->other_deductions = $r->other_deductions;
                        $arr->additional_payment = $r->additional_payment;
                        $arr->total_paid_amount += $r->amount_paid;
                        array_push($arr->payroll_record, $arr2);
                    }
                }
                $arr->payable_amount = $arr->payable_amount - $arr->other_deductions + $arr->additional_payment; 
                $arr->balance = $arr->total_paid_amount - $arr->payable_amount;
                if($arr->balance > 0){
                    $arr->balance = '+'.$arr->balance;
                }
                array_push($records, $arr);
            }
            foreach($records as $key=>$rcd){
                if(count($rcd->payroll_record)==0){
                    $yarr = (object)array("payroll_id"=>"null","status"=>0);
                    array_push($records[$key]->payroll_record, $yarr);
                } else {
                    foreach($rcd->payroll_record as $yas){
                        if(($yas->paid_amount + ($yas->deducation_amount * -1)) == $yas->basic_salary){
                            $records[$key]->balance = 0;
                            $records[$key]->payable_amount = $yas->basic_salary+$allowance+$deducation;
                        }
                    }
                }
            }

            //print_r($records); die();

            /*****************************************************
             * Anual Incremet Calculation According to the Rules *
             *****************************************************/
            $payroll_group_id = isset($records[0]->payroll_group_id)?$records[0]->payroll_group_id:0;
            $joining_date = $this->admin_model->dbSelect("joining_date","users"," id='$request->emp_id' AND deleted_at=0 ");
            $total_services_of_months = 0;
            $total_services_of_years = 0;
            

            if(count($joining_date) > 0){
                $current_date = date('Y-m-d');
                $d1 = new DateTime($current_date);
                $d2 = new DateTime($joining_date[0]->joining_date);
                
                // echo 'd1 <pre>';print_r($d1);
                // echo ' d2 <pre>';print_r($d2);
                // echo ' diff <pre>';print_r($d2->diff($d1));
                
                $diff = $d2->diff($d1);
                $total_services_of_years = $diff->y;

                $joining_date = strtotime($joining_date[0]->joining_date);
                $current_date = strtotime(date("Y-m-d"));
                $year1 = date("Y", $joining_date);
                $year2 = date("Y", $current_date);
                $month1 = date("m", $joining_date);
                $month2 = date("m", $current_date);
                $total_services_of_months = (($year2-$year1) * 12) + ($month2 - $month1);
                
                // echo '<pre>';
                // print_r($total_services_of_months);
                // die;
            }
            
            $anual_increment = 0;
            // echo 'here';die;
            
            $exp_payroll_group_id=explode(',',$payroll_group_id);
            
            if(sizeof($exp_payroll_group_id) > 0)
            {
                $rule='';
                if(sizeof($exp_payroll_group_id) == 1)
                {
                    $rule = $this->admin_model->dbQuery("SELECT * FROM sh_salary_increment_rules WHERE deleted_at IS NULL AND FIND_IN_SET($payroll_group_id,payroll_group_id)");
                }
                else if(sizeof($exp_payroll_group_id) > 1)
                {
                    $rule = $this->admin_model->dbQuery("SELECT * FROM sh_salary_increment_rules WHERE payroll_group_id IN ($payroll_group_id) AND deleted_at IS NULL ");
                }
                
                if(count($rule) > 0){
                    $rule = $rule[0];
                    if($total_services_of_months > $rule->apply_number_of_months){
                        if($rule->type == "number" && $rule->effect == "positive"){
                            // $anual_increment += $v->amount;
                            $anual_increment += $rule->amount;
                        } else if($rule->type == "number" && $rule->effect == "negative"){
                            // $anual_increment -= $v->amount;
                            $anual_increment -= $rule->amount;
                        } else if($rule->type == "percentage" && $rule->effect == "positive"){
                            $temp = $current_salary * $rule->amount / 100;
                            $anual_increment += $temp;
                        } else if($rule->type == "percentage" && $rule->effect == "negative") {
                            $temp = $current_salary * $rule->amount / 100;
                            $anual_increment -= $temp;
                        }
                        $anual_increment = intval($anual_increment * $total_services_of_years);
                    }
                }
                
            }
            else
            {
                $data = array("status"=>"error","data"=>array(),"message"=>"Salary Group is not assigned");
                echo json_encode($data);
            }
            
            

            foreach($records as $key=>$rcd2){
                $records[$key]->total_other_deductions += $records[$key]->other_deductions;
                $records[$key]->total_additional_payment += $records[$key]->additional_payment;
                $records[$key]->payable_amount = $records[$key]->payable_amount + $anual_increment;
                $records[$key]->balance = $records[$key]->balance - $anual_increment;
                $records[$key]->balance = number_format((float)$records[$key]->balance, 2, '.', '');
                $records[$key]->anual_increment = $anual_increment;
            }
            
            $data = array("status"=>"success","message"=>"data found","data"=>$records);
        } else {
            $data = array("status"=>"error","data"=>array(),"message"=>"Please add salary type under payroll settings tab.");
        }
        echo json_encode($data);
    }

    public function collect(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $request->academic_year_id;
        $receipt_no = $this->db->select('payroll_receipt')->from('sh_school')->where('id',$school_id)->get()->row()->payroll_receipt;
        $status = 0;
        if (!isset($request->additional_payment) AND !isset($request->other_deducation)) {
            $request->additional_payment = 0;
            $request->other_deducation = 0; 
        }
        if($request->payroll_record[0]->payroll_id == 'null'){
            if(intval($request->paid_amount) < intval($request->payable_amount)){
                $status = '2';
            } else if(intval($request->paid_amount) == intval($request->payable_amount)){
                $status = '1';
            }
        } else if($request->payroll_record[0]->payroll_id != 'null'){
            if($request->paid_amount == ((-1*$request->balance)+$request->additional_payment - $request->other_deducation)){
                $status = '1';
            } else if($request->paid_amount < ((-1*$request->balance)+$request->additional_payment - $request->other_deducation)){
                $status = '2';
            }
        }
        
        $data = array(
            'paid_by' => $this->session->userdata("userdata")["user_id"],
            'date' => to_mysql_date($request->due_date), 
            'amount_paid' => $request->paid_amount, 
            'remarks' => $request->comment, 
            'deduction_amount' => $request->deducation,
            'dedection_remarks' => isset($request->other_deduction_remarks)?$request->other_deduction_remarks:"", 
            'total_amount' => $request->payable_amount, 
            'mode' => $request->mode,
            'status' => $status, 
            'salary_type_id' => $request->salary_type_id, 
            'user_id' => $request->employee_id, 
            'receipt_no' => $receipt_no, 
            'school_id' => $school_id,
            'other_deductions' => isset($request->other_deductions)?$request->other_deductions:0,
            'additional_payment' => isset($request->additional_payment)?$request->additional_payment:0,
            'other_deduction_remarks' => isset($request->other_deduction_remarks)?$request->other_deduction_remarks:"",
            'additional_payment_remarks' => isset($request->additional_payment_remarks)?$request->additional_payment_remarks:"",
            'academic_year_id' => $active_academic_year,
            'basic_salary' => $request->basic_salary
        );

        $res = $this->common_model->insert("sh_payroll", $data);
        $this->db->set('payroll_receipt', $receipt_no + 1)->where('id', $school_id)->update('sh_school');
        $response = array();
        // if(count($res) > 0){
        if($res){
            $response = array("status"=>"success","message"=>"Payment successfully!");
            if($request->is_send_email_to_employee){
                $employee_id = $request->employee_id;
                $email = $this->admin_model->dbSelect("email","users"," id='$employee_id' AND deleted_at=0 ")[0]->email;
                $this->sendEmailToEmployee($email, $request->paid_amount);
            }
        } else {
            $response = array("status"=>"error","message"=>"Error");
        }
        echo json_encode($response);
    }


    public function sendEmailToEmployee($email, $amount){
        $subject = "Payment Notification";
        $data = array(
            "dear_sir" => lang('tmp_dear_sir'),
            "msg" => "The payment of ".$amount." has been made successfully.",
            "thanks" => lang('tmp_thanks'),
            "poweredBy" => lang('tmp_power'),
            "unsub" => lang('tmp_unsub')
        );
        $message = $this->load->view('email_templates/payroll.php', $data, TRUE);
        $this->email_modal->emailSend($email, $message, $subject, "payroll-payment");
    }

    public function softDelete(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $result = false;
        foreach($request->ids as $id){
            $res = $this->common_model->update_where("sh_payroll", array("id"=>$id), array("deleted_at"=>date("Y-m-d h:i:s")));
            if($res){
                $result = true;
            }
        }
        if($result){
            echo "success";
        } else {
            echo "error";
        }
    }

    public function fetchSchoolEmployeesForPayroll(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];
        $employees = $this->admin_model->dbSelect("id,name","users"," school_id='$school_id' AND deleted_at=0 AND role_id='".EMPLOYEE_ROLE_ID."' ");
        $groups = $this->admin_model->dbSelect("*","payroll_groups"," school_id='$school_id' AND academic_year_id='$active_academic_year' AND deleted_at IS NULL ");
        foreach($employees as $key=>$emp){
            foreach($groups as $g){
                $arr = explode(",",$g->employees);
                if(in_array($emp->id, $arr)){
                    unset($employees[$key]);
                }
            }
        }
        $response = array();
        if(count($employees) > 0){
            $arr = array();
            foreach($employees as $mp){
                array_push($arr, $mp);
            }
            $response = array("status"=>"success","message"=>"data found","data"=>$arr);
        } else {
            $response = array("status"=>"error","message"=>lang("no_record"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function fetchSalaryTypesOfSchool(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];

        $sql = "SELECT t.*, date_format(t.date, '%d/%m/%Y') as formated_date, g.name as group_name FROM sh_salary_types t INNER JOIN sh_payroll_groups g ON t.payroll_group_id=g.id WHERE t.school_id='$school_id' AND t.deleted_at IS NULL AND t.academic_year_id='$active_academic_year' ";
        $data = $this->admin_model->dbQuery($sql);
        //$data = $this->admin_model->dbSelect("*","salary_types"," school_id='$school_id' AND deleted_at IS NULL ");
        $response = array();
        if(count($data) > 0){
            $response = array("status"=>"success","message"=>"data found","data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>lang("no_record"),"data"=>$data);
        }
        echo json_encode($response);
    }

    public function fetchPayrollGroupsOfSchool(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        if (isset($request)) {
            $active_academic_year = $request;    
        } else {
            $active_academic_year = $this->session->userdata("userdata")["academic_year"];
        }
        $sql = "SELECT g.*,GROUP_CONCAT(u.name) as employees2, GROUP_CONCAT(u.avatar) as employees_avatars FROM sh_payroll_groups g  INNER JOIN sh_users u ON FIND_IN_SET(u.id, g.employees) > 0 WHERE g.school_id='$school_id' AND g.deleted_at IS NULL AND g.academic_year_id='$active_academic_year' group by g.id ";
        $data = $this->admin_model->dbQuery($sql);
        $response = array();
        if(count($data) > 0){
            foreach($data as $d){
                $new_array = array();
                $employee_ids = explode(",", $d->employees);
                $employee_names = explode(",", $d->employees2);
                $employee_avatars = explode(",", $d->employees_avatars);
                foreach($employee_ids as $key=>$yy){
                    $arr = array(
                        "id" => $yy,
                        "name" => isset($employee_names[$key])?$employee_names[$key]:"Unknown",
                        "avatar" => isset($employee_avatars[$key])?$employee_avatars[$key]:"profile.png"
                    );
                    array_push($new_array, $arr);
                }
                $d->employee_details = $new_array;
            }
            $response = array("status"=>"success","message"=>"data found","data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>lang("no_record"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function updateSalaryType(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $data = array(
            "payroll_group_id"=>$request->payroll_group_id,
            "name"=>$request->name,
            "date"=>to_mysql_date($request->formated_date),
            "description"=>$request->description
        );
        $where = array("id"=>$request->id);
        $res = $this->common_model->update_where("sh_salary_types", $where, $data);
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>"Salary type updated Successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Salary type can't update!");
        }
        echo json_encode($response);
    }

    public function updatePayrollGroup(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $updated_employees_list = array();
        foreach($request->employee_details as $d){
            array_push($updated_employees_list, $d->id);
        }
        $data = array(
            "name"=>$request->name,
            "employees"=>implode(",",$updated_employees_list),
            "description"=>$request->description
        );

        $where = array("id"=>$request->id);
        $res = $this->common_model->update_where("sh_payroll_groups", $where, $data);
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>"Payroll group updated Successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Payroll group can't update!");
        }
        echo json_encode($response);
    }

    public function softDeleteSalaryType(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->update_where("sh_salary_types", array("id"=>$request->id), array("deleted_at"=>date("Y-m-d h:i:s")));
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>"Salary type deleted Successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Salary type can't delete!");
        }
        echo json_encode($response);
    }

    public function softDeletePayrollGroup(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->update_where("sh_payroll_groups", array("id"=>$request->id), array("deleted_at"=>date("Y-m-d h:i:s")));
        $response = array();
        if($res){
            $this->common_model->update_where("sh_salary_types", array("payroll_group_id"=>$request->id), array("deleted_at"=>date("Y-m-d h:i:s")));
            $response = array("status"=>"success","message"=>"Payroll group deleted Successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Payroll group can't delete!");
        }
        echo json_encode($response);
    }
    

    public function softDeleteSalaryTypeVarient(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->update_where("sh_salary_varients", array("id"=>$request->id), array("deleted_at"=>date("Y-m-d h:i:s")));
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>"Salary type varient deleted successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Salary type varient can't delete!");
        }
        echo json_encode($response);
    }

    public function addNewSalaryType(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];
        
        $response = array();
        foreach($request->payroll_group_ids as $payroll_group_id) {
            $data = array(
                "payroll_group_id"=>$payroll_group_id,
                "name"=>$request->name,
                "date"=>to_mysql_date($request->date),
                "description"=>$request->description,
                "academic_year_id" => $active_academic_year,
                "school_id"=>$school_id
            );
            $id = $this->common_model->insert("sh_salary_types",$data);
            if($id > 0){
                array_push($response, array("status"=>"success","message"=>"Salary type added Successfully!"));
            } else {
                array_push($response, array("status"=>"error","message"=>"Salary type can't add!"));
            }
        }
        echo json_encode($response);
    }

    public function addNewPayrollGroup(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];

        $data = array(
            'name' => $request->name, 
            'description' => $request->description, 
            'school_id' => $school_id, 
            'employees' => implode(",",$request->employees), 
            'academic_year_id' => $active_academic_year
        );
        $id = $this->common_model->insert("sh_payroll_groups",$data);
        $response = array();
        if($id > 0){
            $response = array("status"=>"success","message"=>"Payroll group added successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Payroll group can't add!");
        }
        echo json_encode($response);
    }

    public function saveSalaryVarient(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = array(
            "amount"=>$request->amount,
            "description"=>$request->description,
            "effect"=>$request->effect,
            "name"=>$request->name,
            "payroll_group_id"=>$request->payroll_group_id,
            "type"=>$request->type,
            "school_id"=>$school_id
        );
        $id = $this->common_model->insert("sh_salary_varients",$data);
        $response = array();
        if($id > 0){
            $response = array("status"=>"success","message"=>"Salary type varient added successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Salary type can't add!");
        }
        echo json_encode($response);
    }

    public function fetchSalaryTypesVarients(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","salary_varients"," school_id='$school_id' AND deleted_at IS NULL AND payroll_group_id='$request->id' ");
        $response = array();
        if(count($data) > 0){
            $response = array("status"=>"success","message"=>"data found", "data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>"data not found", "data"=>array());
        }
        echo json_encode($response);
    }

    public function updatePayrollGroupVarient(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = array(
            "name"=>$request->name,
            "description"=>$request->description,
            "payroll_group_id"=>$request->payroll_group_id,
            "amount"=>$request->amount,
            "effect"=>$request->effect,
            "type"=>$request->type,
        );
        $where = array("id"=>$request->id);
        $res = $this->common_model->update_where("sh_salary_varients", $where, $data);
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>"Salary type varient updated Successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Salary type varient can't update!");
        }
        echo json_encode($response);
    }

    public function updateEmployeeBasicSalary(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $payroll_group_id = $request->payroll_group_id;
       
        $where = array("id"=>$request->id);
        $where2 = array("id"=>$payroll_group_id);

        $employees = $this->admin_model->dbSelect("employees","payroll_groups"," id='$payroll_group_id' AND deleted_at IS NULL ")[0]->employees;
        if(!in_array($request->id, explode(",", $employees))) {
            $employees .= ",".$request->id;
        }
        
        $data2 = array("employees" => $employees);
        $data = array("basic_salary" => $request->basic_salary);
        $res = $this->common_model->update_where("sh_users", $where, $data);
        $res2 = $this->common_model->update_where("sh_payroll_groups", $where2, $data2);
        $response = array();
        if($res && $res2){
            $response = array("status"=>"success","message"=>"Basic salary updated successfully!");
        } else {
            $response = array("status"=>"error","message"=>"Basic salary can't update!");
        }
        echo json_encode($response);
    }

    public function getEmployeeAttendanceReport(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $employee_id = $request->employee_id;
        $arr = explode("to", $request->month);
        $from = date("Y-m-d", strtotime($arr[0]));
        $to = date("Y-m-d", strtotime($arr[1]));;
        $sql = "SELECT count(*) as count, status FROM sh_attendance WHERE user_id='$employee_id' AND (date BETWEEN '$from' AND '$to') AND deleted_at IS NULL GROUP BY status ";
        $rdata = $this->admin_model->dbQuery($sql);
        $response = array();
        $arr2 = (object)array("Present"=>0,"Absent"=>0,"Late"=>0,"Leave"=>0);
        if(count($rdata) > 0){
            foreach($rdata as $dd){
                switch($dd->status){
                    case "Present":
                        $arr2->Present = $dd->count;
                        break;
                    case "Late":
                        $arr2->Late = $dd->count;
                        break;
                    case "Leave":
                        $arr2->Leave = $dd->Leave;
                        break;
                    case "Absent":
                        $arr2->Absent = $dd->count;
                        break;
                }
            }
            $response = array("status"=>"success","message"=>"data found", "data"=> $arr2);
        } else {
            $response = array("status"=>"error","message"=>"no data found", "data"=> $arr2);
        }
        echo json_encode($response);
    }
}