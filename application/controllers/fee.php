<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fee extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        
        check_user_permissions();
    }

    public function refresh(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $query = "select id,class_id,batch_id,joining_date,nationality,COALESCE(discount_id, 0) as discount_id from sh_students_".$school_id;
        $students = $this->db->query($query)->result();
        foreach ($students as $s) {
            $s->joining_date = ($s->joining_date == null) ? "0000-00-00" : $s->joining_date;
            $query = "select ft.id,COALESCE(fv.percentage,amount) as amount, COALESCE(dv.percentage,0) as discount,CASE WHEN dv.type = 'number' THEN COALESCE(fv.percentage,amount) - COALESCE(dv.percentage,0)
            WHEN dv.type = 'percentage' THEN COALESCE(fv.percentage,amount) - (COALESCE(fv.percentage,amount) * COALESCE(dv.percentage,0) / 100 ) ELSE COALESCE(fv.percentage,amount)
            END as final_amount from sh_fee_types ft left join sh_fee_varients fv on ft.id = fv.feetype_id and fv.nationality = ".$s->nationality." and fv.admission_from <= '".$s->joining_date."' and fv.admission_to >= '".$s->joining_date."' left join sh_discount_varients dv on ft.id = dv.fee_type_id and dv.discount_id = ".$s->discount_id." where ft.class_id = ".$s->class_id." and ft.deleted_at is null and fv.deleted_at is null";
            $feetypes = $this->db->query($query)->result();
            foreach ($feetypes as $f) {
                if($f->final_amount == 0){
                    $exist = $this->db->select('id')->from('sh_fee_collection')->where('student_id',$s->id)->where('feetype_id',$f->id)->where('status','1')->where('deleted_at is null')->get()->result();
                    if(!$exist){
                        $fee_receipt = $this->db->select('fee_receipt')->from('sh_school')->where('id',$school_id)->get()->row()->fee_receipt;        
                        $data = array("student_id" => $s->id,
                            "school_id" => $school_id,
                            "feetype_id" => $f->id,
                            "collector_id" => $user_id,
                            "status" => "1",
                            "discount_id" => $s->discount_id,
                            "paid_amount" => 0,
                            "discount_amount" => 0,
                            "feetype_amount" => $f->amount,
                            "mode" => "cash",
                            "class_id" => $s->class_id,
                            "batch_id" => $s->batch_id,
                            "receipt_no" => $fee_receipt,
                            "comment" => "");
                        $this->db->insert('sh_fee_collection',$data);
                        $this->db->set('fee_receipt', $fee_receipt + 1)->where('id', $school_id)->update('sh_school');
                    }
                    
                }

                // fee amount change adjustments

                $this->db->set('discount_amount', $f->final_amount)->where('feetype_id', $f->id)->where('student_id', $s->id)->where('deleted_at is null')->where('feetype_amount <> discount_amount')->update('sh_fee_collection');

                $fee_exist = $this->db->select('sum(paid_amount) as paid_amount')->from('sh_fee_collection')->where('feetype_id', $f->id)->where('student_id', $s->id)->where('deleted_at is null')->get()->row();

                if($fee_exist->paid_amount != null){
                    if($fee_exist->paid_amount < $f->final_amount){

                        $this->db->set('status', '2')->set('feetype_amount', $f->amount)->set('discount_amount', $f->final_amount)->where('feetype_id',$f->id)->where('student_id', $s->id)->update('sh_fee_collection');
                    }else if($fee_exist->paid_amount > $f->final_amount){
                        $this->db->set('status', '2')->set('feetype_amount', $f->amount)->set('discount_amount', $f->final_amount)->where('feetype_id',$f->id)->where('student_id', $s->id)->update('sh_fee_collection');

                        $adjust_amount = $f->final_amount - $fee_exist->paid_amount;
                        $fee_receipt = $this->db->select('fee_receipt')->from('sh_school')->where('id',$school_id)->get()->row()->fee_receipt;
                        $adjust_data = array("student_id" => $s->id,
                            "school_id" => $school_id,
                            "feetype_id" => $f->id,
                            "collector_id" => $user_id,
                            "status" => "1",
                            "discount_id" => $s->discount_id,
                            "paid_amount" => $adjust_amount,
                            "discount_amount" => $f->final_amount,
                            "feetype_amount" => $f->amount,
                            "mode" => "cash",
                            "class_id" => $s->class_id,
                            "batch_id" => $s->batch_id,
                            "receipt_no" => $fee_receipt,
                            "comment" => "Fee adjustment");
                        $this->db->insert('sh_fee_collection',$adjust_data);
                        $this->db->set('fee_receipt', $fee_receipt + 1)->where('id', $school_id)->update('sh_school');
                    }
                }
            }
        }
        $data = array();
        $data["success"] = true;
        $data["msg"] = "Fee refreshed successfully";
        echo json_encode($data);
    }

    public function refreshFeeBalance(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $query = "select id,class_id,batch_id,joining_date,nationality,COALESCE(discount_id, 0) as discount_id from sh_students_".$school_id;
        $students = $this->db->query($query)->result();
        foreach ($students as $s) {
            $s->joining_date = ($s->joining_date == null) ? "0000-00-00" : $s->joining_date;
            $query = "select ft.id,COALESCE(fv.percentage,amount) as amount, COALESCE(dv.percentage,0) as discount,COALESCE(fv.percentage,amount) - (COALESCE(fv.percentage,amount) * COALESCE(dv.percentage,0) / 100 ) as final_amount from sh_fee_types ft left join sh_fee_varients fv on ft.id = fv.feetype_id and fv.nationality = ".$s->nationality." and fv.admission_from <= '".$s->joining_date."' and fv.admission_to >= '".$s->joining_date."' left join sh_discount_varients dv on ft.id = dv.fee_type_id and dv.discount_id = ".$s->discount_id." where ft.class_id = ".$s->class_id." and ft.deleted_at is null and fv.deleted_at is null";

            $feetypes = $this->db->query($query)->result();

            foreach ($feetypes as $f) {
                $exist = $this->db->select('sum(paid_amount) as paid_amount')->from('sh_fee_collection')->where('feetype_id', $f->id)->where('student_id', $s->id)->where('deleted_at is null')->get()->row();

                if($exist->paid_amount != null){
                    if($exist->paid_amount < $f->final_amount){
                        // $status_1 = $this->db->select('id')->from('sh_fee_collection')->where('feetype_id', $f->id)->where('student_id', $s->id)->where('status', '1')->where('deleted_at is null')->get()->row();

                        $this->db->set('status', '2')->set('feetype_amount', $f->final_amount)->where('feetype_id',$f->id)->where('student_id', $s->id)->update('sh_fee_collection');
                    }else if($exist->paid_amount > $f->final_amount){
                        $this->db->set('status', '2')->set('feetype_amount', $f->final_amount)->where('feetype_id',$f->id)->where('student_id', $s->id)->update('sh_fee_collection');

                        $adjust_amount = $f->final_amount - $exist->paid_amount;
                        $fee_receipt = $this->db->select('fee_receipt')->from('sh_school')->where('id',$school_id)->get()->row()->fee_receipt;
                        $adjust_data = array("student_id" => $s->id,
                            "school_id" => $school_id,
                            "feetype_id" => $f->id,
                            "collector_id" => $user_id,
                            "status" => "1",
                            "discount_id" => $s->discount_id,
                            "paid_amount" => $adjust_amount,
                            "discount_amount" => 0,
                            "feetype_amount" => $f->final_amount,
                            "mode" => "cash",
                            "class_id" => $s->class_id,
                            "batch_id" => $s->batch_id,
                            "receipt_no" => $fee_receipt,
                            "comment" => "Fee adjustment");
                        $this->db->insert('sh_fee_collection',$adjust_data);
                        $this->db->set('fee_receipt', $fee_receipt + 1)->where('id', $school_id)->update('sh_school');
                    }
                }

                // $exist = $this->db->select('id,paid_amount')->from('sh_fee_collection')->where('feetype_id', $f->id)->where('student_id', $s->id)->where('status' , '1')->where('deleted_at is null')->get()->row();
                // if($exist){

                //     if($exist->paid_amount > $f->final_amount){
                //         $this->db->set('paid_amount', $f->final_amount)->set('feetype_amount', $f->final_amount)->where('id', $exist->id)->update('sh_fee_collection');
                //     }
                // }
            }
        }
        $data = array();
        $data["success"] = true;
        $data["msg"] = "Fee refreshed successfully";
        echo json_encode($data);
    }

    public function show() {
        // ism 108
        if($this->session->userdata("userdata")["sh_id"] == 108){
            redirect(site_url("dashboard"));
        }
        $this->load->view('fee/show');
    }

    public function get_discounts() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $response["discounts"] = $this->admin_model->dbSelect("*", "fee_discount", "school_id = ".$school_id." and deleted_at is NULL");
        echo json_encode($response);
    }

    public function collection() {
        // ism 108
        if($this->session->userdata("userdata")["sh_id"] == 108){
            redirect(site_url("dashboard"));
        }
        //$data["feetypes"] = $this->admin_model->dbSelect("*","fee_types"," school_id='$school_id' AND deleted_at IS NULL ");
        $this->load->view('fee/collection');
    }
     public function collection_parent() {
        
        $this->load->view('fee/parentfeeview');
    }
    public function collection_student() {
        
        $this->load->view('fee/studentfeeview');
    }

    public function getAllFeetypes() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sql = "Select ft.*,cls.name as classname From sh_fee_types ft INNER JOIN sh_classes cls ON ft.class_id=cls.id WHERE ft.school_id='$school_id' AND ft.deleted_at IS NULL ";
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }

    public function getSpecificFeetypes() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $academic_year_id = $request->academic_year_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sql = "Select ft.*,cls.name as classname From sh_fee_types ft INNER JOIN sh_classes cls ON ft.class_id=cls.id WHERE ft.school_id='$school_id' AND ft.class_id='$class_id' AND ft.deleted_at IS NULL AND ft.academic_year_id='$academic_year_id' ";
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }

    public function getClassFeetypes() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];
        if($this->session->userdata("userdata")["academic_year"] == null){
            $data = array();
        }else{
            $data = $this->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND class_id='$request->class_id' AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        }
        
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->due_date = to_html_date($data[$i]->due_date);
        }
        //print_r($data);
        //die();
        echo json_encode($data);
    }

    public function getSchoolFeetypes() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND deleted_at IS NULL ");
        echo json_encode($data);
    }

    public function save() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $res;
        foreach ($request->checkall as $key => $value) {
            if ($value == 1) {
                if (!isset($request->description)) {
                    $data = array("name" => $request->name, "due_date" => to_mysql_date($request->due_date), "amount" => $request->amount, "class_id" => $key, "school_id" => $school_id,"academic_year_id" => $this->session->userdata("userdata")["academic_year"]);
                } else {
                    $data = array("name" => $request->name, "due_date" => to_mysql_date($request->due_date), "description" => $request->description, "amount" => $request->amount, "class_id" => $key, "school_id" => $school_id,"academic_year_id" => $this->session->userdata("userdata")["academic_year"]);
                }
                $res = $this->common_model->insert("fee_types", $data);
            }
        }

        if ($res > 0) {
            $data = array("status" => "success", "message" => lang('lbl_save_fee'));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang('lbl_fee_error_save_fee'));
            echo json_encode($data);
        }
    }

    public function update() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $data = array("due_date" => to_mysql_date($request->due_date), "description" => $request->description, "name" => $request->name, "amount" => $request->amount);

        $res = $this->common_model->update_where("sh_fee_types", array("id" => $request->id), $data);
        if ($res) {
            $data = array("status" => "success", "message" => lang('lbl_fee_update'));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang('lbl_error_fee_update'));
            echo json_encode($data);
        }
    }

    public function softDelete() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $res = $this->common_model->update_where("sh_fee_types", array("id" => $request->id), array("deleted_at" => date("Y-m-d h:i:s")));
        if ($res) {
            $data = array("status" => "success", "message" => lang('lbl_delete_fee'));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang('lbl_error_delete_fee'));
            echo json_encode($data);
        }
    }

    public function sofeDeleteCollectedFee() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        if($request->type == "null"){
            //single record deleted from paartially paid
            foreach($request->ids as $id){
                $response = $this->common_model->update_where("sh_fee_collection", array("id" => $id), array("deleted_at" => date("Y-m-d h:i:s")));
            }
            $iddd = $request->ids[0];
            $d = $this->admin_model->dbSelect("*","fee_collection", "id=$iddd")[0];
            $std_id = $d->student_id;
            $f_id = $d->feetype_id;
            $response = $this->db->set('status','2')->where('student_id',$std_id)->where('feetype_id',$f_id)->where('deleted_at is null')->update('sh_fee_collection');
        } else {
            // deleted all record
            foreach($request->ids as $id){
                $response = $this->common_model->update_where("sh_fee_collection", array("id" => $id), array("deleted_at" => date("Y-m-d h:i:s")));
            }
        }

        // if ($request->exemption_id != "") {
        //     $response = $this->common_model->update_where("sh_fee_exemption", array("id" => $request->exemption_id), array("deleted_at" => date("Y-m-d h:i:s")));
        // }
        // if ($request->request_log_id != "") {
        //     $response = $this->common_model->update_where("sh_request_log", array("id" => $request->request_log_id), array("deleted_at" => date("Y-m-d h:i:s")));
        // }
        
        if ($request->exemption_id != "") {
            $this->db->where('id', $request->exemption_id);
            $this->db->delete('sh_fee_exemption');
            // $response = $this->common_model->update_where("sh_fee_exemption", array("id" => $request->exemption_id), array("deleted_at" => date("Y-m-d h:i:s")));
        }
        if ($request->request_log_id != "") {
            $this->db->where('id', $request->request_log_id);
            $this->db->delete('sh_request_log');
            // $response = $this->common_model->update_where("sh_request_log", array("id" => $request->request_log_id), array("deleted_at" => date("Y-m-d h:i:s")));
        }
        
        /*$idd = $request->ids[0];
        $d = $this->admin_model->dbSelect("*","fee_collection", "id=$idd AND deleted_at IS NULL ")[0];
        
        if(count($request->ids) == 1) {
            //----- Partially paid single record delete ------//
            $all_partial_fees = $this->admin_model->dbSelect("*","fee_collection"," student_id='$d->student_id' AND feetype_id='$d->feetype_id' AND deleted_at IS NULL AND id!=$id ");
            $response = $this->common_model->update_where("sh_fee_collection", array("id" => $request->ids[0]), array("deleted_at" => date("Y-m-d h:i:s")));
        } else {
            //----- All delete record of single fee -----//
            
        }*/
        
        $data = array();
        if ($response) {
            $data = array("status" => "success", "message" => lang('lbl_collect_fee'));
        } else {
            $data = array("status" => "error", "message" => lang('lbl_error_collect_fee'));
        }
        echo json_encode($data);
    }

    public function fetchfeeCollectionStudents() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $feetype_where1 = "";
        $feetype_where2 = "";
        $feetype_status = "";
        if ($request->specificFeeType != "all") {
            $feetype_where1 = "(SELECT COUNT(*) FROM sh_fee_types ft WHERE ft.id=" . $request->specificFeeType . " AND cr.class_id = ft.class_id AND ft.deleted_at IS NULL) AS ftCount,";
            $feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE fc.feetype_id=" . $request->specificFeeType . " AND s.id = fc.student_id AND fc.deleted_at IS NULL AND ft.deleted_at IS NULL) AS fcCount ";
        } else {
            $feetype_where1 = "(SELECT COUNT(*) FROM sh_fee_types ft WHERE cr.class_id = ft.class_id AND ft.deleted_at IS NULL) AS ftCount,";

            $feetype_status = "(SELECT COUNT(*) FROM sh_feetype_status fs WHERE fs.student_id=s.id AND fs.deleted_at IS NULL AND status='1') AS fdCount,";
            // old Query
            //$feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE s.id = fc.student_id AND cr.class_id = ft.class_id AND fc.deleted_at IS NULL AND fc.status='1' AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS fcCount ";
            // new Query
            $feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE s.id = fc.student_id AND cr.academic_year_id = ft.academic_year_id AND fc.deleted_at IS NULL AND fc.status='1' AND ft.deleted_at IS NULL) AS fcCount ";
        }

        $sql = "SELECT "
        . "s.*,cr.discount_id, fd.name as discount_name, fd.amount as discount_amount, "
        . "g.guardian_id, "
        . "uu.name as father_name, "
        . "cls.name as class_name, "
        . "bth.name as batch_name, "
        . "cr.class_id, cr.batch_id, "
        . "cr.id as student_class_relation_id, "
        . $feetype_where1
        . $feetype_status
        . $feetype_where2
        . "FROM "
        . "sh_users s INNER JOIN sh_student_class_relation cr ON s.id=cr.student_id "
        . "LEFT JOIN sh_student_guardians g ON s.id=g.student_id "
        . "LEFT JOIN sh_users uu On uu.id=g.guardian_id "
        . "INNER JOIN sh_classes cls On cr.class_id=cls.id "
        . "INNER JOIN sh_batches bth ON cr.batch_id=bth.id "
        . "LEFT JOIN sh_fee_discount fd ON cr.discount_id=fd.id "
        . "WHERE ";


        if ($request->class_id !== 'all') {
            $sql .= "cr.class_id='$request->class_id' ";
        } else {
            //-------------------------
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
                if (count(login_user()->t_data->classes) > 0) {
                    $sql .= "cr.class_id IN (" . implode(',', login_user()->t_data->classes) . ") ";
                } else {
                    $sql .= "cr.class_id ";
                }
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_acountant_dept_id() == login_user()->user->teacher_dept_id) {
                $sql .= "cr.class_id ";
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
                $sql .= "cr.class_id ";
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
                $sql .= "cr.class_id ";
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }
            //-------------------------
        }

        if ($request->batch_id !== "all") {
            $sql .= "AND cr.batch_id='$request->batch_id' ";
        } else {
            //-------------------------
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
                if (count(login_user()->t_data->batches) > 0) {
                    $sql .= "AND cr.batch_id IN (" . implode(',', login_user()->t_data->batches) . ") ";
                } else {
                    $sql .= "AND cr.batch_id ";
                }
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_acountant_dept_id() == login_user()->user->teacher_dept_id) {
                $sql .= "AND cr.batch_id ";
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
                $sql .= "AND cr.batch_id ";
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {

            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
                $sql .= "AND cr.batch_id ";
            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }
            //-------------------------
        }

        $sql .= "AND cr.deleted_at is null AND "
        . "cr.academic_year_id=".$request->academic_year_id." "
        . "AND s.role_id=" . STUDENT_ROLE_ID . " "
        . "AND s.school_id='$school_id' "
        . "AND (s.rollno LIKE '%$request->searchBy%' "
        . "OR s.name LIKE '%$request->searchBy%') ";

        if ($request->isDue) {
            $sql .= " HAVING ftCount > fcCount ";
        }

        $data = $this->admin_model->dbQuery($sql);
        
        foreach ($data as $key => $val) {
            $val->fcCount = $val->fcCount + $val->fdCount;
        }

        echo json_encode($data);
    }

    public function getDiscountVariant($discount_id, $fees_type_id, $fee_amount) {
        $varients = 0;
        $discount_id = explode(",", $discount_id);
        foreach ($discount_id as $key => $d_id) {
            $res = $this->admin_model->dbSelect("*", "discount_varients", " discount_id='$d_id' AND fee_type_id='$fees_type_id' AND deleted_at IS NULL ");
            if (count($res) > 0) {
                $res = $res[0];
                if($res->type == "number"){
                    $varients += $res->percentage;
                } else if($res->type == "percentage"){
                    $varients += ($res->percentage/100)*$fee_amount;
                }  
            }
        }
        return $varients;
    }

    public function getDiscountVariantType($discount_id, $fees_type_id) {
        $discount_type = 'number';
        $discount_id = explode(",", $discount_id);
        foreach ($discount_id as $key => $d_id) {
            $res = $this->admin_model->dbSelect("*", "discount_varients", " discount_id='$d_id' AND fee_type_id='$fees_type_id' AND deleted_at IS NULL ");
            if (count($res) > 0) {
                $res = $res[0];
                $discount_type = $res->type;
            }
        }
        return $discount_type;
    }

    public function getStudentFeeRecrods() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];

        $discount_ids = $this->admin_model->dbSelect("discount_id", "student_class_relation", " id='$request->scr_id'");
        if (count($discount_ids) > 0) {
            $request->discount_id = $discount_ids[0]->discount_id;
        }

        $feetypes = $this->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND class_id='$request->class_id' AND academic_year_id='$request->academic_year_id' AND deleted_at IS NULL ");

        foreach ($feetypes as $key => $fe) {

            $exmp = $this->admin_model->dbSelect("*", "fee_exemption", " feetype_id='$fe->id' AND student_id='$request->std_id' ");
            if (count($exmp) > 0) {
                $feetypes[$key]->exemption_amount = $exmp[0]->amount;
            } else {
                $feetypes[$key]->exemption_amount = "";
            }

            $ft_status = $this->admin_model->dbSelect("*", "feetype_status", " feetype_id='$fe->id' AND student_id='$request->std_id' ");
            if (count($ft_status) > 0) {
                $feetypes[$key]->feetype_status = $ft_status[0]->status;
            } else {
                $feetypes[$key]->feetype_status = "";
            }

            $rl_status = $this->admin_model->dbSelect("*", "request_log", " feetype_id='$fe->id' AND student_id='$request->std_id' ");
            if (count($rl_status) > 0) {
                $feetypes[$key]->exemption_status = $rl_status[0]->status;
            } else {
                $feetypes[$key]->exemption_status = "";
            }
            
        } 
     
        //feestypes join with discount and discount variants
        $sql = "SELECT "
        . "COALESCE(cls.id,'NULL') as class_id, "
        . "COALESCE(bth.id,'NULL') as batch_id, "
        . "COALESCE(cls.name,'NULL') as class_name, "
        . "COALESCE(bth.name,'NULL') as batch_name, "

        // . "replace(format(CASE WHEN v.type = 'number' THEN COALESCE(v.percentage,0)
        //     WHEN v.type = 'percentage' THEN COALESCE(c.feetype_amount,0) * COALESCE(v.percentage,0) / 100
        //     ELSE COALESCE(v.percentage,0)
        //     END , 2),',','') as discount, "

        //. "IFNULL(v.percentage,0) as discount,"
        . "COALESCE(c.id,'NULL') as fee_collection_id, "
        // . "COALESCE(d.id,'NULL') as discount_id, "
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
        //. "fe.amount as exemption_amount, "
        // . "fe.id as exemption_id, "
        // . "rl.status as exemption_status, "
        // . "rl.id as request_log_id, "
        // . "fs.status as feetype_status "
        . "FROM sh_fee_types ft "
        . "LEFT JOIN sh_fee_collection c ON c.feetype_id=ft.id "
        //. "LEFT JOIN sh_fee_discount d ON c.discount_id=d.id "
        //. "LEFT JOIN sh_discount_varients v ON ft.id=v.fee_type_id and d.id=v.discount_id "
        . "INNER JOIN sh_users u ON c.collector_id=u.id "
        . "LEFT JOIN sh_classes cls ON c.class_id=cls.id "
        . "LEFT JOIN sh_batches bth ON c.batch_id=bth.id "
        // . "LEFT JOIN sh_fee_exemption fe ON ft.id=fe.feetype_id "
        // . "LEFT JOIN sh_request_log rl ON ft.id=rl.feetype_id "
        // . "LEFT JOIN sh_feetype_status fs ON ft.id=fs.feetype_id "
        . "WHERE ft.school_id='$school_id' AND "
        . "ft.academic_year_id='$request->academic_year_id' AND "
        . "c.deleted_at IS NULL AND "
        . "c.refund_fee='0' AND "
        . "ft.deleted_at IS NULL AND c.student_id='$request->std_id' ";
        $collected_fees = $this->admin_model->dbQuery($sql);

        foreach ($collected_fees as $key => $c_f) {
            
            $exmp = $this->admin_model->dbSelect("*", "fee_exemption", " feetype_id='$c_f->feetype_id' AND student_id='$request->std_id' ");
            if (count($exmp) > 0) {
                $collected_fees[$key]->exemption_amount = $exmp[0]->amount;
                $collected_fees[$key]->exemption_id = $exmp[0]->id;
            } else {
                $collected_fees[$key]->exemption_amount = "";
                $collected_fees[$key]->exemption_id = "";
            }

            $ft_status = $this->admin_model->dbSelect("*", "feetype_status", " feetype_id='$c_f->feetype_id' AND student_id='$request->std_id' ");
            if (count($ft_status) > 0) {
                $collected_fees[$key]->feetype_status = $ft_status[0]->status;
            } else {
                $collected_fees[$key]->feetype_status = "";
            }

            $rl_status = $this->admin_model->dbSelect("*", "request_log", " feetype_id='$c_f->feetype_id' AND student_id='$request->std_id' ");
            if (count($rl_status) > 0) {
                $collected_fees[$key]->exemption_status = $rl_status[0]->status;
                $collected_fees[$key]->request_log_id = $rl_status[0]->id;
            } else {
                $collected_fees[$key]->exemption_status = "";
                $collected_fees[$key]->request_log_id = "";
            }

            $collected_fees[$key]->discount = number_format((float)$this->getDiscountVariant($request->discount_id, $c_f->feetype_id, $c_f->amount), 2, '.', '');

        }
        
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
                    "discounted_amount" => round($feetype->amount - $this->getDiscountVariant($request->discount_id, $feetype->id, $feetype->amount), 2),
                    "discount" => number_format((float)$this->getDiscountVariant($request->discount_id, $feetype->id, $feetype->amount), 2, '.', ''),
                    "discount_type" => $this->getDiscountVariantType($request->discount_id, $feetype->id),
                    "exemption_amount" => $feetype->exemption_amount,
                    "exemption_status" => $feetype->exemption_status,
                    "feetype_status" => $feetype->feetype_status
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
                            $obj->discounted_amount = round($obj->amount - $this->getDiscountVariant($request->discount_id, $fid, $obj->amount), 2);
                            $obj->discount = number_format((float)$this->getDiscountVariant($request->discount_id, $fid, $obj->amount), 2, '.', '');
                            $obj->discount_type = $this->getDiscountVariantType($request->discount_id, $fid);
                            //$obj->discounted_amount = $obj->amount - ($obj->amount * $obj->discount / 100);
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
        
        $notes = array();
        $prev_academic_year = get_previous_academic_year($request->academic_year_id);
        if(count($prev_academic_year) > 0){
            foreach($prev_academic_year as $prev){
                $query = "SELECT * from sh_fee_types WHERE class_id=(select class_id from sh_student_class_relation WHERE student_id='$request->std_id' AND academic_year_id='$prev->id' AND deleted_at is null ) AND id NOT IN (SELECT ft.id FROM sh_fee_collection c INNER JOIN sh_fee_types ft ON ft.id=c.feetype_id WHERE c.student_id='$request->std_id' AND ft.academic_year_id='$prev->id' AND c.deleted_at IS NULL AND ft.deleted_at IS NULL) AND deleted_at IS NULL AND school_id='$school_id'";
                $query1 = "SELECT ft.*,sum(case when status = '2' then paid_amount else 0 end) as total_paid FROM sh_fee_collection c INNER JOIN sh_fee_types ft ON ft.id=c.feetype_id WHERE c.student_id='$request->std_id' AND ft.academic_year_id='$prev->id' AND ft.class_id=(select class_id from sh_student_class_relation WHERE student_id='$request->std_id' AND academic_year_id='$prev->id' AND deleted_at is null ) AND c.deleted_at IS NULL AND ft.deleted_at IS NULL group by ft.id having sum(case when status = '2' then 1 else 0 end) > 0 and sum(case when status = '1' then 1 else 0 end) = 0";
                $unpaid = $this->admin_model->dbQuery($query);
                $partial = $this->admin_model->dbQuery($query1);

                if($unpaid || $partial){
                    $notes[$prev->name]["unpaid"] = $this->admin_model->dbQuery($query);
                    $notes[$prev->name]["partial"] = $this->admin_model->dbQuery($query1);
                }
                
            }
        }
        
        foreach ($yasir_array as $key => $ray) {
            $ray[0]->new_discounted_amount = $ray[0]->discounted_amount;
            if ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'approved' && $ray[0]->status == '0') {
                $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->exemption_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'not-approved' && $ray[0]->status != '0') {
                $ray[0]->discounted_amount =  $ray[0]->exemption_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'inprocess' && $ray[0]->status == '0') {
                $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->exemption_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'inprocess' && $ray[0]->status == 1) {
                $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->paid_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'inprocess' && $ray[0]->status == 2) {
                if (count($ray) > 1) {
                    $total_paid_amount = 0;
                    foreach ($ray as $key2 => $a) {
                        $total_paid_amount += $a->paid_amount;
                    }
                    $ray[0]->discounted_amount = $ray[0]->discounted_amount - $total_paid_amount;
                } else {
                    $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->paid_amount;
                }
            }
        }

        foreach ($yasir_array as $key => $r) {

            if ($r[0]->exemption_amount != "" && $r[0]->exemption_status == 'approved') {
                $exemption_amount = $r[0]->exemption_amount;
            } else {
                $exemption_amount = 0;
            }

            if (count($r) > 1) {
                $total_paid_amount = 0;
                foreach ($r as $key => $a) {
                    $total_paid_amount += $a->paid_amount;
                }
                $index = count($r) - 1;
                if ($r[$index]->status == '1' && $r[$index]->exemption_status == 'not-approved' && $r[$index]->feetype_amount > $total_paid_amount) {
                    $fee_collection_id = $r[$index]->fee_collection_id;
                    $this->db->query("UPDATE sh_fee_collection SET status='2' WHERE id='$fee_collection_id'");
                    $r[$index]->status = '2';
                }

                if($r[$index]->exemption_status == 'not-approved' && $r[$index]->feetype_amount == $total_paid_amount){
                    $fee_collection_id = $r[$index]->fee_collection_id;
                    $this->db->query("UPDATE sh_fee_collection SET status='1' WHERE id='$fee_collection_id'");
                    $r[$index]->status = '1';
                }
                // comment due to overall fee status
                // if ($r[0]->discounted_amount == $total_paid_amount + $exemption_amount && $r[0]->status == 2) {
                //     $r[0]->status = 1;
                //     $fee_collection_id = $r[0]->fee_collection_id;
                //     $this->db->query("UPDATE sh_fee_collection SET status='1' WHERE id='$fee_collection_id'");
                // }
                
            } else {
                if ($r[0]->discounted_amount > $r[0]->paid_amount + $exemption_amount && $r[0]->status == 1) {
                    $r[0]->status = 2;
                    $fee_collection_id = $r[0]->fee_collection_id;
                    $this->db->query("UPDATE sh_fee_collection SET status='2' WHERE id='$fee_collection_id'");
                }
                elseif ($r[0]->status == 1 && $r[0]->exemption_status == 'not-approved') {
                    $fee_collection_id = $r[0]->fee_collection_id;
                    $this->db->query("UPDATE sh_fee_collection SET status='2' WHERE id='$fee_collection_id'");
                }

            }

            if ($r[0]->feetype_status == "") {
                $r[0]->feetype_status = 0;
            }

        } 

        $data["notes"] = $notes;
        $data["records"] = $yasir_array;
        echo json_encode($data);
    }



    public function countMonths($academic_year_start, $academic_year_end) {
        $diff = $academic_year_start->diff($academic_year_end)->m + ($academic_year_start->diff($academic_year_end)->y * 12);
        $academic_year_months = array();
        $count = 0;
        $starting_year_real = $academic_year_start->format('Y');
        $starting_month_real = $academic_year_start->format('m');
        $starting_year = $academic_year_start->format('Y');
        $ending_year = $academic_year_end->format('Y');
        $starting_month = $academic_year_start->format('m');
        $ending_month = $academic_year_end->format('m');
        $starting_day = $academic_year_start->format('d');
        $ending_day = $academic_year_end->format('d');
        for ($i = 0; $i <= $diff; $i++) {
            if ($count < 12) {
                $date = $starting_year . "-" . ($starting_month + $count) . "-1";
                $m = array(
                    "id" => NULL,
                    "feetype_id" => NULL,
                    "feetype" => "Monthly Fee",
                    "due_date" => $date,
                    "paid_amount" => "-",
                    "amount" => "-",
                    "paid_date" => "-",
                    "status" => "-",
                    "current_date" => date('m/d/Y'),
                    "discount_id" => NULL,
                    "discount_name" => "-",
                    "discount_amount" => "-"
                );
                $academic_year_months[$starting_year . $starting_month + $count] = $m;
                //array_push($academic_year_months, $date);
                $count++;
            } else {
                $count = 0;
                $starting_year++;
            }
        }
        $end_date = $ending_year . "-" . $ending_month . "-" . $ending_day;
        $mm = array(
            "id" => NULL,
            "feetype_id" => NULL,
            "feetype" => "Monthly Fee",
            "due_date" => $end_date,
            "paid_amount" => "-",
            "amount" => "-",
            "paid_date" => "-",
            "status" => "-",
            "current_date" => date('m/d/Y'),
            "discount_id" => NULL,
            "discount_name" => "-",
            "discount_amount" => "-"
        );

        $keys = array_keys($academic_year_months);
        $last = end($keys);
        $newlast = substr($last, 4, 2);
        if ($newlast === "12") {
            $academic_year_months[($starting_year + 1) . '01'] = $mm;
        } else {
            $academic_year_months[$last + 1] = $mm;
        }
        
        return $academic_year_months;
    }

    public function editCollectFee(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request); die();
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $paid_status = '0';
        $array_data = array();

        if($request->obj->paid_amount < $request->obj->amount - ($request->obj->amount-$request->obj->discounted_amount))
        {
            $paid_status = '2';
        } 
        else if($request->obj->paid_amount == $request->obj->amount - ($request->obj->amount-$request->obj->discounted_amount))
        {
            $paid_status = '1';
        }
        $array_data = array(
                "school_id" => $school_id,
                "feetype_id" => $request->obj->feetype_id,
                "collector_id" => $user_id,
                "class_id" => $request->obj->class_id,
                "batch_id" => $request->obj->batch_id,
                "status" => $paid_status,
                "discount_id" => isset($request->obj->discount_id) ? $request->obj->discount_id : 0,
                "paid_amount" => $request->obj->paid_amount,
                "feetype_amount" => $request->obj->amount,
                "mode" => $request->obj->mode,
                "receipt_no" => $request->obj->receipt_no,
                "comment" => $request->obj->comment
            );

        $res = $this->db->set($array_data)->where('id', $request->obj->fee_collection_id)->update('sh_fee_collection');

        if ($res) {
            $data = array("status" => "success", "message" => "Fee updated successfully!");
            echo json_encode($data);
        }

    }

    public function collectFee() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $paid_status = '0';
        $array_data = array();
        $fee_receipt = $this->db->select('fee_receipt')->from('sh_school')->where('id',$school_id)->get()->row()->fee_receipt;
        if($request->obj->discount_id == "NULL" || is_null($request->obj->discount_id)){
            $request->obj->discount_id = 0;
        }


        if($request->obj->fee_collection_id !== 'NULL' || is_null($request->obj->fee_collection_id)){
            $std_id = $request->obj->student_id;
            $f_id = $request->obj->feetype_id;
            $class_id = $request->class_id;
            $batch_id = $request->batch_id;
            $res = $this->admin_model->dbSelect("*","fee_collection"," student_id='$std_id' AND feetype_id='$f_id' AND class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND deleted_at IS NULL ");
            
            if(count($res) > 0){
                $total_paid_amount_yet = 0;
                foreach ($res as $val){
                    $total_paid_amount_yet += intval($val->paid_amount);
                }

                //new code added by sheraz to change the status
                $student_id = $res[0]->student_id;
                $feetype_id = $res[0]->feetype_id;
                $exemption = $this->admin_model->dbSelect("amount","fee_exemption", " student_id=$student_id AND feetype_id=$feetype_id AND academic_year_id=$academic_year_id ");
                if (count($exemption) > 0) {
                    $exemp_amount = $exemption[0]->amount;   
                } else {
                    $exemp_amount = 0;
                }
                $new_total = $total_paid_amount_yet + $request->obj->paid_amount + $exemp_amount;
                
                //correct formula to check the fee status added by sheraz
                $fee_discount_amount_total = $request->obj->feetype_amount-$request->obj->discount;
                if($new_total < $fee_discount_amount_total){
                    $paid_status = '2';
                } else if($new_total == $fee_discount_amount_total){
                    $paid_status = '1';
                }
            }//close

            $discounted_amount_new = 0;
            
            //correct formula to check the fee status added by sheraz
            if(isset($request->obj->discount_type) && $request->obj->discount_type == "number"){
                $discounted_amount_new = $request->obj->discount;
            }else{
                $discounted_amount_new = $request->obj->feetype_amount*$request->obj->discount/100;
            }
            
            $array_data = array(
                "student_id" => $request->obj->student_id,
                "school_id" => $school_id,
                "feetype_id" => $request->obj->feetype_id,
                "collector_id" => $user_id,
                "class_id" => $request->class_id,
                "batch_id" => $request->batch_id,
                "status" => $paid_status,
                "discount_id" => isset($request->obj->discount_id) ? $request->obj->discount_id : 0,
                "paid_amount" => $request->obj->paid_amount,
                "discount_amount" => $discounted_amount_new,
                "feetype_amount" => $request->obj->amount,
                "mode" => $request->obj->mode,
                "receipt_no" => $fee_receipt,
                "comment" => $request->comment
            );


            
        } else {


            if($request->obj->paid_amount < $request->obj->amount - ($request->obj->amount-$request->obj->discounted_amount)){
                $paid_status = '2';
            } else if($request->obj->paid_amount == $request->obj->amount - ($request->obj->amount-$request->obj->discounted_amount)){
                $paid_status = '1';
            }
            $array_data = array(
                "student_id" => $request->obj->student_id,
                "school_id" => $school_id,
                "feetype_id" => $request->obj->feetype_id,
                "collector_id" => $user_id,
                "class_id" => $request->class_id,
                "batch_id" => $request->batch_id,
                "status" => $paid_status,
                "discount_id" => isset($request->obj->discount_id) ? $request->obj->discount_id : 0,
                "paid_amount" => $request->obj->paid_amount,
                "discount_amount" => $request->obj->discounted_amount,
                "feetype_amount" => $request->obj->amount,
                "mode" => $request->obj->mode,
                "receipt_no" => $fee_receipt,
                "comment" => $request->comment
            );
        }

        
        
        $res = $this->common_model->insert("sh_fee_collection", $array_data);

        $this->db->set('fee_receipt', $fee_receipt + 1)->where('id', $school_id)->update('sh_school');
        
        //---- Start::Send email to parent on student fee collection -----//
        if (isset($request->is_send_email) && $request->is_send_email) {
            $student_id = $request->obj->student_id;
            $parent_id = $this->admin_model->dbSelect("guardian_id", "student_guardians", " student_id=$student_id ")[0]->guardian_id;
            $parent = $this->admin_model->dbSelect("*", "users", " id=$parent_id ")[0];

            $stdInfo = $this->admin_model->dbSelect("*", "students_".$school_id, " id=$student_id ")[0];
            $stdClassName = $this->admin_model->dbSelect("name", "classes", " id=$stdInfo->class_id ")[0]->name;
            $stdBatchName = $this->admin_model->dbSelect("name", "batches", " id=$stdInfo->batch_id ")[0]->name;
            $collectorName = $this->admin_model->dbSelect("name", "users", " id=$user_id ")[0]->name;

            $subject = "Fee Submation";
            $data = array(
                "dear_sir" => lang('tmp_dear_sir'),
                "msg" => "Your child school fee submation invoice details.",
                "thanks" => lang('tmp_thanks'),
                "poweredBy" => lang('tmp_power'),
                "unsub" => lang('tmp_unsub'),
                "stdInfo" => $stdInfo,
                "stdclass" => $stdClassName,
                "stdbatch" => $stdBatchName,
                "feetype" => $request->obj->feetype,
                "fathername" => $parent->name,
                "amount" => $request->obj->amount,
                "discountamount" => $request->obj->discounted_amount,
                "collectorname" => $collectorName
            );
            $message = $this->load->view('email_templates/fee_collection.php', $data, TRUE);
            $this->email_modal->emailSend($parent->email, $message, $subject, "fee-collection");
        }

        if ($res > 0) {
            $data2 = array("status" => "success", "message" => lang("fee_collect_successfully"));
            echo json_encode($data2);
        }
    }

    public function editPartialCollectFee(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request); die();
        $std_id = $request->student_id;
        $feetype_id = $request->obj->feetype_id;
        $class_id = $request->obj->class_id;
        $batch_id = $request->obj->batch_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $paid_status = '0';
        $array_data = array();

        $res = $this->admin_model->dbSelect("*","fee_collection"," student_id='$std_id' AND feetype_id='$feetype_id' AND class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND deleted_at IS NULL ");
    
        if(count($res) > 0){
            $total_paid_amount_yet = 0;
            foreach ($res as $val){
                if ($val->id != $request->obj->fee_collection_id) {
                    $total_paid_amount_yet += intval($val->paid_amount);   
                }
            }
            $total_paid_amount_yet = $total_paid_amount_yet + $request->obj->paid_amount;
            if($total_paid_amount_yet < intval($request->obj->amount) - intval($request->obj->discount)){
                $paid_status = '2';
            } else if($total_paid_amount_yet === intval($request->obj->amount) - intval($request->obj->discount)){
                $paid_status = '1';
            }
        }
          
        $array_data = array(
            "school_id" => $school_id,
            "feetype_id" => $request->obj->feetype_id,
            "collector_id" => $user_id,
            "class_id" => $request->obj->class_id,
            "batch_id" => $request->obj->batch_id,
            "status" => $paid_status,
            "discount_id" => isset($request->obj->discount_id) ? $request->obj->discount_id : 0,
            "paid_amount" => $request->obj->paid_amount,
            "feetype_amount" => $request->obj->amount,
            "mode" => $request->obj->mode,
            "receipt_no" => $request->obj->receipt_no,
            "comment" => $request->obj->comment
        );

        $res2 = $this->db->set($array_data)->where('id', $request->obj->fee_collection_id)->update('sh_fee_collection');

        if ($res2) {
            $data = array("status" => "success", "message" => "Fee updated successfully!");
            echo json_encode($data);
        }

    }

    public function collectedFeeUpdate() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $data = array(
            "created_at" => to_mysql_date($request->obj->paid_date),
            "mode" => $request->obj->mode,
            "class_id" => $request->class_id,
            "batch_id" => $request->batch_id,
            "paid_amount" => $request->obj->paid_amount
        );
        
        if(!is_null($request->obj->fee_collection_id) || $request->obj->fee_collection_id != 'NULL'){
            $res = $this->common_model->update_where("sh_fee_collection", array("id" => $request->obj->fee_collection_id), $data);
            //print_r($res);
            //die();
        } else {
            // simple update the record
        }

        //---- Start::Send email to parent on student fee collection -----//
        if (isset($request->is_send_email) && $request->is_send_email) {
            $student_id = $request->obj->student_id;
            $parent_id = $this->admin_model->dbSelect("guardian_id", "student_guardians", " student_id=$student_id ")[0]->guardian_id;
            $parent = $this->admin_model->dbSelect("*", "users", " id=$parent_id ")[0];

            $stdInfo = $this->admin_model->dbSelect("*", "users", " id=$student_id ")[0];
            $stdClassName = $this->admin_model->dbSelect("name", "classes", " id=$stdInfo->class_id ")[0]->name;
            $stdBatchName = $this->admin_model->dbSelect("name", "batches", " id=$stdInfo->batch_id ")[0]->name;
            $collectorName = $this->admin_model->dbSelect("name", "users", " id=$user_id ")[0]->name;

            $subject = "Fee Submation";
            $data = array(
                "dear_sir" => lang('tmp_dear_sir'),
                "msg" => "Your child school fee submation invoice details.",
                "thanks" => lang('tmp_thanks'),
                "poweredBy" => lang('tmp_power'),
                "unsub" => lang('tmp_unsub'),
                "stdInfo" => $stdInfo,
                "stdclass" => $stdClassName,
                "stdbatch" => $stdBatchName,
                "feetype" => $request->obj->feetype,
                "fathername" => $parent->name,
                "amount" => $request->obj->amount,
                "discountamount" => $request->obj->d_amount,
                "collectorname" => $collectorName
            );
            $message = $this->load->view('email_templates/fee_collection.php', $data, TRUE);
            $this->email_modal->emailSend($parent->email, $message, $subject, "fee-collection");
        }
        //---- End::Send email to parent on student fee collection -----//
        
        if ($res) {
            $data2 = array("status" => "success", "message" => lang("lbl_collected_fee_update_successfully"));
            echo json_encode($data2);
        }
    }

    public function getCountries() {
        $res = $this->admin_model->dbSelect("*", "countries", " 1 ");
        echo json_encode($res);
    }

    public function getFeetypeVarients() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $sql = "SELECT v.*,v.nationality as n_id,t.name as feetype_name, c.country_name as nationality FROM sh_fee_varients v INNER JOIN sh_fee_types t ON v.feetype_id=t.id INNER JOIN sh_countries c ON v.nationality=c.id WHERE v.feetype_id='$request->id' AND v.deleted_at IS NULL ";
        $res = $this->admin_model->dbQuery($sql);
        foreach ($res as $r) {
            $r->admission_from = to_html_date($r->admission_from);
            $r->admission_to = to_html_date($r->admission_to);
        }
        echo json_encode($res);
    }

    public function updateVariant(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = array('title' => $request->title,
                        'admission_from' => to_mysql_date($request->admission_from),
                        'admission_to' => to_mysql_date($request->admission_to),
                        'percentage' => $request->percentage,
                        'nationality' => $request->n_id);
        $this->db->where('id', $request->id)->update('sh_fee_varients', $data);
    }

    public function saveVarients() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $feetypes = $request->fee_types;
        $nationalities = $request->nationality;
        $id = 0;
        foreach ($feetypes as $f) {
            foreach ($nationalities as $value) {
                $data = array(
                    "feetype_id" => $f,
                    "title"=>$request->title,
                    "admission_from" => to_mysql_date($request->admission_from),
                    "admission_to" => to_mysql_date($request->admission_to),
                    "nationality" => $value,
                    "percentage" => $request->percentage
                );

                $id = $this->admin_model->dbInsert("sh_fee_varients", $data);
            }
        }

        
        $response = array();
        if ($id > 0) {
            $response = array("status" => "success", "message" => lang("lbl_save_fee_type"));
        } else {
            $response = array("status" => "error", "message" => lang("lbl_error_fee_type"));
        }
        echo json_encode($response);
    }

    public function deleteVarient() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $this->common_model->update_where("sh_fee_varients", array("id" => $request->id), array("deleted_at" => date("Y-m-d h:i:s")));
        $response = array("status" => "error", "message" => lang("lbl_delete_fee_varient"));
        echo json_encode($response);
    }

    public function getDiscountVarients() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        if (isset($request->class_id) && $request->class_id != null) {
            $result = "";
            $sql = 'SELECT id, name as fee_type FROM sh_fee_types ft  WHERE  ft.school_id = ' . $school_id . ' AND ft.class_id = ' . $request->class_id . ' AND ft.deleted_at IS NULL order by name';
            $feetypes = $this->admin_model->dbQuery($sql);
            if (count($feetypes) > 0) {
                foreach ($feetypes as $fee) {
                    $v = $this->admin_model->dbSelect("id as varient_id, percentage, type", "discount_varients", "discount_id='$request->discount_id' AND fee_type_id = $fee->id AND class_id= '$request->class_id' AND deleted_at IS NULL ");
                    if ($v) {
                        $fee->varient_id = $v[0]->varient_id;
                        $fee->percentage = $v[0]->percentage;
                        $fee->type = $v[0]->type;
                    }
                }
                $data["feetypes"] = $feetypes;
            }
        } else {
            $data["feetypes"] = "";
            $data["message"] = array("status" => "error", "message" => lang("lbl_error_no_fee_type"));
        }
        echo json_encode($data);
    }

    public function saveDiscountVarents() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $varients = $request->varients;
        $class = $request->class_id;
        $discount_id = $request->discount_id;
        $flag = 0;
        if (isset($class) && $class != null) {
            foreach ($varients as $varient) {
                if (isset($varient->percentage) && isset($varient->type) && !empty($varient->type)) {
                    if($varient->type == 'percentage'){
                        if ($varient->percentage >= 0 && $varient->percentage <= 100) {
                            $flag += 1;
                            $res = $this->admin_model->dbQuery("SELECT id FROM `sh_discount_varients` WHERE discount_id = $discount_id AND fee_type_id = $varient->id");
                            if (count($res) > 0) {
                                $id = $res[0]->id;
                                $this->common_model->update_where("discount_varients", array("id" => $id), array("discount_id" => "$discount_id", "percentage" => "$varient->percentage", "type"=>'percentage', "deleted_at" => null));
                            } else {
                                $data = $this->db->query("INSERT INTO `sh_discount_varients`(`discount_id`, `class_id`, `fee_type_id`, `percentage`,`type`) VALUES ($discount_id, $class,$varient->id,$varient->percentage, '$varient->type') ");
                            }
                        } else {
                            $data = array("status" => "error", "message" => lang("lbl_error_no_fee_type"));
                        }
                    } else if($varient->type == 'number'){
                        $flag += 1;
                        $res = $this->admin_model->dbQuery("SELECT id FROM `sh_discount_varients` WHERE discount_id = $discount_id AND fee_type_id = $varient->id");
                        if (count($res) > 0) {
                            $id = $res[0]->id;
                            $this->common_model->update_where("discount_varients", array("id" => $id), array("discount_id" => "$discount_id", "percentage" => "$varient->percentage", "type"=>'number', "deleted_at" => null));
                        } else {
                            $data = $this->db->query("INSERT INTO `sh_discount_varients`(`discount_id`, `class_id`, `fee_type_id`, `percentage`,`type`) VALUES ($discount_id, $class,$varient->id,$varient->percentage, '$varient->type') ");
                        }
                    }
                }
            }
        }
        if ($flag != 0) {
            $data = array("status" => "success", "message" => lang("lbl_save_varients"));
        } else {
            $data = array("status" => "error", "message" => lang("lbl_error_empty_varients"));
        }
        echo json_encode($data);
    }

    public function resetVarents() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        if (isset($request->varient_id)) {
            $id = $this->admin_model->dbSelect("fee_type_id, discount_id", "discount_varients", "id=$request->varient_id");

            // $res = $this->common_model->delete("id", $request->varient_id, "sh_discount_varients");

            $this->common_model->update_where("discount_varients", array("id" => $request->varient_id), array("deleted_at" => date("Y-m-d h:i:s")));

            $data = $id;
        }
        echo json_encode($data);
    }

    public function saveDiscount() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $response = array();
        if (isset($request)) {
            $data = array(
                "name" => $request->name,
                "school_id" => $this->session->userdata("userdata")["sh_id"],
                "description" => $request->description
            );
            $result = $this->admin_model->dbInsert("sh_fee_discount", $data);
            $response = array("status" => "success", "message" => lang("lbl_save_discount"));
        } else {
            $response = array("status" => "error", "message" => lang("lbl_error_discount"));
        }
        echo json_encode($response);
    }

    public function editDiscount() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $data = array("name" => $request->name, "class_id" => $request->class_id, "description" => $request->description);
        $res = $this->common_model->update_where("sh_fee_discount", array("id" => $request->id), $data);

        if (count($res) > 0) {
            $response = array("status" => "success", "message" => lang("lbl_error_update_discount"));
        } else {
            $response = array("status" => "error", "message" => lang("lbl_error_update_discount"));
        }

        echo json_encode($response);
    }

    public function softDeleteDiscount() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $res = $this->common_model->update_where("sh_fee_discount", array("id" => $request->id), array("deleted_at" => date("Y-m-d h:i:s")));
        $this->db->set('discount_id',0)->where('discount_id',$request->id)->update('sh_users');
        if ($res) {
            $data = array("status" => "success", "message" => lang("lbl_delete_fee"));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang("lbl_error_delete_fee"));
            echo json_encode($data);
        }
    }

    public function discounts() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = $this->admin_model->dbSelect("*", "fee_discount", "id='$request->id' AND deleted_at IS NULL ");
        echo json_encode($data);
    }

    public function statistics(){
        // ism 108
        if($this->session->userdata("userdata")["sh_id"] == 108){
            redirect(site_url("dashboard"));
        }
        $this->load->view("fee/statistics");
    }

    public function fee_statistics(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        if (isset($request->academic_year_id)) {
            $academic_year_id = $request->academic_year_id;
        } else {
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }

        $this->db->select('cr.student_id as id,cr.class_id,cr.batch_id,u.joining_date,u.nationality,COALESCE(cr.discount_id, 0) as discount_id',false)->from('sh_student_class_relation cr')->join('sh_users u','u.id=cr.student_id','inner')->where('cr.deleted_at',NULL)->where('cr.academic_year_id',$academic_year_id)->where('u.school_id',$school_id);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('batch_id', $request->batch_id);
        }
        $students = $this->db->get()->result();
        $total_fee_amount = 0;
        $total_fee_defaulters = 0;
        $d_f = 0;
        $total_exemption_amount = 0;

        foreach ($students as $s) {

            $s->joining_date = ($s->joining_date == null) ? "0000-00-00" : $s->joining_date;
            $query = "select ft.id,COALESCE(fv.percentage,amount) as amount from sh_fee_types ft left join sh_fee_varients fv on ft.id = fv.feetype_id and fv.nationality = ".$s->nationality." and fv.admission_from <= '".$s->joining_date."' and fv.admission_to >= '".$s->joining_date."' where ft.class_id = ".$s->class_id." and ft.deleted_at is null and fv.deleted_at is null";
            $feetypes = $this->db->query($query)->result();
            $discount = 0;
            foreach ($feetypes as $key => $ft) {

               $discount = number_format((float)$this->getDiscountVariant($s->discount_id, $ft->id, $ft->amount), 2, '.', '');
               $feetypes[$key]->discount = $discount;
               $feetypes[$key]->final_amount = $ft->amount - $discount;

            } 

            //to deduuct the deactivted fee_types of student added by sheraz
            foreach ($feetypes as $key => $ftp) {
                $feetype_status = array();
                $amount = array();
                $q = "SELECT * FROM sh_feetype_status WHERE feetype_id='".$ftp->id."' AND student_id='".$s->id."' AND status='1' AND school_id='".$school_id."' AND deleted_at is NULL ";
                $feetype_status = $this->db->query($q)->result();
                if (count($feetype_status) > 0) {
                       unset($feetypes[$key]);
                       $d_f++;
                   }

                $q2 = "SELECT amount FROM sh_fee_exemption WHERE feetype_id='".$ftp->id."' AND student_id='".$s->id."' AND school_id='".$school_id."' AND deleted_at is NULL ";

                $amount = $this->db->query($q2)->result();

                if (count($amount) > 0) {
                    
                    $total_exemption_amount += $amount[0]->amount; 
                }
            } 

            foreach ($feetypes as $f) {
                $total_fee_amount = $total_fee_amount + round($f->final_amount, 2);
            }

            $due_fee_types = $this->db->select('id')->from('sh_fee_types')->where('class_id', $s->class_id)->where('due_date < curdate()')->where('deleted_at is null')->get()->num_rows();
            $paid_due_fees = $this->db->select('ft.id')->from('sh_fee_types ft')->join('sh_fee_collection fc', 'ft.id = fc.feetype_id')->where('ft.class_id', $s->class_id)->where('due_date < curdate()')->where('ft.deleted_at is null')->where('fc.deleted_at is null')->where('status', '1')->where('student_id', $s->id)->get()->num_rows();

            if($due_fee_types != $paid_due_fees){
                $total_fee_defaulters++;
            }


        }

        $data["total_fee_amount"] = $total_fee_amount - $total_exemption_amount;

        $data["total_fee_defaulters"] = $total_fee_defaulters;


        



        // $this->db->select('count(*) as total_school_fee');
        // $this->db->from('sh_fee_types ft');
        // $this->db->JOIN('sh_classes cls', 'ft.class_id = cls.id', 'INNER');
        // $this->db->JOIN('sh_students_'.$school_id.' u', 'u.class_id = cls.id', 'INNER');
        // $this->db->where('cls.academic_year_id',$academic_year_id);
        // $this->db->where('cls.deleted_at is null');
        // $this->db->where('ft.deleted_at is null');
        // $this->db->where('u.deleted_at =', '0');
        // $this->db->where('u.role_id', '3');
        // $this->db->where('ft.school_id', $school_id);
        // $this->db->where('cls.school_id', $school_id);
        // $this->db->where('u.school_id', $school_id);
        // if(isset($request->class_id) && $request->class_id != "all"){
        //     $this->db->where('ft.class_id', $request->class_id);
        // }
        // if(isset($request->batch_id) && $request->batch_id != "all"){
        //     $this->db->where('u.batch_id', $request->batch_id);
        // }
        // $data['total_school_fee'] = $this->db->get()->result()[0]->total_school_fee;

        $this->db->select('count(*) as total_school_fee');
        $this->db->from('sh_fee_types ft');
        $this->db->JOIN('sh_classes cls', 'ft.class_id = cls.id', 'INNER');
        $this->db->JOIN('sh_student_class_relation cr', 'cr.class_id = cls.id', 'INNER');
        $this->db->JOIN('sh_users u', 'cr.student_id = u.id', 'LEFT');
        $this->db->where('cr.academic_year_id',$academic_year_id);
        $this->db->where('cr.deleted_at',NULL);
        $this->db->where('u.school_id',$school_id);
        $this->db->where('ft.deleted_at',NULL);
        $this->db->where('cls.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('ft.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }
        $data['total_school_fee'] = $this->db->get()->result()[0]->total_school_fee;


        $this->db->select('ft.*');
        $this->db->from('sh_fee_types ft');
        $this->db->JOIN('sh_classes cls', 'ft.class_id = cls.id', 'INNER');
        $this->db->JOIN('sh_student_class_relation cr', 'cr.class_id = cls.id', 'INNER');
        $this->db->JOIN('sh_users u', 'cr.student_id = u.id', 'LEFT');
        $this->db->where('cr.academic_year_id',$academic_year_id);
        $this->db->where('cr.deleted_at',NULL);
        $this->db->where('ft.deleted_at',NULL);
        $this->db->where('ft.due_date < curdate()');
        $this->db->where('u.role_id', '3');
        $this->db->where('ft.school_id', $school_id);
        $this->db->where('cls.school_id', $school_id);
        $this->db->where('u.school_id', $school_id);
        $this->db->where('cls.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('ft.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }

        $due_ft = $this->db->get()->result();

        $total_due_fees = count($due_ft) - $d_f;

        $this->db->select('count(*) as paid_due_fees');
        $this->db->from('sh_fee_collection f');
        $this->db->join('sh_student_class_relation cr','cr.student_id = f.student_id', 'inner');
        $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
        $this->db->where('f.deleted_at is null');
        $this->db->where('ft.deleted_at is null');
        $this->db->where('ft.due_date < curdate()');
        $this->db->where('ft.academic_year_id',$academic_year_id);
        $this->db->where('f.status','1');
        $this->db->where('cr.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('cr.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }

        $paid_due_fees = $this->db->get()->row()->paid_due_fees;

        $data["due_fees"] = $total_due_fees - $paid_due_fees;
        

        $this->db->select('count(*) as total_full_paid_fee');
        $this->db->from('sh_fee_collection f');
        $this->db->join('sh_student_class_relation cr','cr.student_id = f.student_id', 'inner');
        $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
        $this->db->where('f.deleted_at is null');
        $this->db->where('ft.deleted_at is null');
        $this->db->where('ft.academic_year_id',$academic_year_id);
        $this->db->where('cr.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('cr.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }
        $this->db->group_by('f.student_id,f.feetype_id');

        $data['total_full_paid_fee'] = count($this->db->get()->result());
        // echo $this->db->last_query();
        // die();
        $this->db->select('count(*) as fully_collected_fee');
        $this->db->from('sh_fee_collection f');
        $this->db->join('sh_student_class_relation cr','cr.student_id = f.student_id', 'inner');
        $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
        $this->db->where('f.deleted_at is null');
        $this->db->where('ft.deleted_at is null');
        $this->db->where('ft.academic_year_id',$academic_year_id);
        $this->db->where('f.status','1');
        $this->db->where('cr.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('cr.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }
        
        
        $data['fully_collected_fee'] = $this->db->get()->result()[0]->fully_collected_fee;

        
        $this->db->select('count(*) as partially_collected_fee,feetype_amount');
        $this->db->from('sh_fee_collection f');
        $this->db->join('sh_student_class_relation cr','cr.student_id = f.student_id', 'inner');
        $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
        $this->db->where('f.deleted_at is null');
        $this->db->where('ft.deleted_at is null');
        $this->db->where('ft.academic_year_id',$academic_year_id);
        $this->db->where('cr.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id!= "all"){
            $this->db->where('cr.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }
        $this->db->group_by('f.student_id,f.feetype_id');
        $this->db->having('sum(paid_amount) <> f.feetype_amount');
        $data['partially_collected_fee'] = count($this->db->get()->result());

        $this->db->select('sum(paid_amount) as total_paid_cash');
        $this->db->from('sh_fee_collection f');
        $this->db->join('sh_student_class_relation cr','cr.student_id = f.student_id', 'inner');
        $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
        $this->db->where('f.deleted_at is null');
        $this->db->where('ft.deleted_at is null');
        $this->db->where('ft.academic_year_id',$academic_year_id);
        $this->db->where('ft.deleted_at',NULL);
        if(isset($request->class_id) && $request->class_id != "all"){
            $this->db->where('cr.class_id', $request->class_id);
        }
        if(isset($request->batch_id) && $request->batch_id != "all"){
            $this->db->where('cr.batch_id', $request->batch_id);
        }
        $data['total_paid_cash'] = $this->db->get()->result()[0]->total_paid_cash;

        

        echo json_encode($data);
    }

    public function getAcademicYears(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","academic_years"," school_id='$school_id' AND deleted_at IS NULL ");
        $academic_year_id = "-1";
        if(isset($this->session->userdata("userdata")["academic_year"]) && !empty($this->session->userdata("userdata")["academic_year"])){
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }
        $response = array('current_academic_year_id'=>$academic_year_id,"data"=>$data);
        echo json_encode($response);
    }

    
    public function getClasses() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //-------------------------
        $data = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            if (count(login_user()->t_data->classes) > 0) {
                $data = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ") AND academic_year_id = ".$request->academic_year_id." ORDER BY name asc ");
            }
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=".login_user()->user->sh_id." AND academic_year_id = ".$request->academic_year_id." AND deleted_at is null ORDER BY name asc ");
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND academic_year_id = ".$request->academic_year_id." AND deleted_at IS NULL ORDER BY name asc ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

        }
        //-------------------------
        
        echo json_encode($data);
    }
    
    public function getClassBatches() {
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

        if ($request->class_id != "") {
            $query = "  school_id=" . login_user()->user->sh_id . " AND class_id='$request->class_id' AND academic_year_id=".$request->academic_year_id." AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=".$request->academic_year_id." AND deleted_at IS NULL ";
        }

        $where_part .= " ORDER BY name ASC  ";
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        echo json_encode($data);
    }

    public function getAcademicYearsParentFee(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","academic_years"," school_id='$school_id' AND deleted_at IS NULL ");
        $academic_year_id = "-1";
        if(isset($this->session->userdata("userdata")["academic_year"]) && !empty($this->session->userdata("userdata")["academic_year"])){
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }
        $response = array('current_academic_year_id'=>$academic_year_id,"data"=>$data);
        echo json_encode($response);
    }
    public function getChilds() {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $academic_year_id = $request->academic_year_id;
        


        $childrens = $this->db->select('student_id,s.name')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_academic_years ac','s.academic_year_id=ac.id')->where('guardian_id', $user_id)->where('academic_year_id',$academic_year_id)->get()->result();
        
        $data['student_ids'] = $childrens;

        echo json_encode($data);
    }

    public function getBatchesForParent(){

        $school_id = $this->session->userdata("userdata")["sh_id"];
       
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student = $request->student_id;
        $academic_year = $request->academic_year_id;


        
        if ($request->student_id != "") {
            $query = "  school_id=" . login_user()->user->sh_id . "AND id=".$request->student_id." AND deleted_at IS NULL ";

        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND id=".$request->student_id." AND deleted_at IS NULL ";

        }

      

        $data["batches"] = $this->db->select('batch_id,b.name')->from('students_'.$school_id.' s')->join('sh_batches b','s.batch_id=b.id')->where('s.id',$student)->get()->result();
        
       
        echo json_encode($data);
    }

    public function fetchfeeCollectionforParents() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        

        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }
        

        $feetype_where1 = "";
        $feetype_where2 = "";
        $feetype_status = "";
        if ($request->specificFeeType != "all") {
            $feetype_where1 = "(SELECT COUNT(*) FROM sh_fee_types ft WHERE ft.id=" . $request->specificFeeType . " AND cr.class_id = ft.class_id AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS ftCount,";
            $feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE fc.feetype_id=" . $request->specificFeeType . " AND s.id = fc.student_id AND fc.deleted_at IS NULL AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS fcCount ";
        } else {
            $feetype_where1 = "(SELECT COUNT(*) FROM sh_fee_types ft WHERE cr.class_id = ft.class_id AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS ftCount,";
            $feetype_status = "(SELECT COUNT(*) FROM sh_feetype_status fs WHERE fs.student_id=s.id AND fs.deleted_at IS NULL AND status='1') AS fdCount,";
            //$feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE s.id = fc.student_id AND fc.deleted_at IS NULL AND fc.status='1' AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS fcCount ";
            $feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE s.id = fc.student_id AND cr.academic_year_id = ft.academic_year_id AND fc.deleted_at IS NULL AND fc.status='1' AND ft.deleted_at IS NULL) AS fcCount ";
        }

        $sql = "SELECT "
        . "s.*, cr.discount_id, fd.name as discount_name, fd.amount as discount_amount, "
        . "g.guardian_id, "
        . "uu.name as father_name, "
        . "cls.name as class_name, "
        . "bth.name as batch_name, "
        . "cr.class_id, cr.batch_id, "
        . $feetype_where1
        . $feetype_status
        . $feetype_where2
        . "FROM "
        . "sh_users s INNER JOIN sh_student_class_relation cr ON s.id=cr.student_id "
        . "LEFT JOIN sh_student_guardians g ON s.id=g.student_id "
        . "LEFT JOIN sh_users uu On uu.id=g.guardian_id "
        . "INNER JOIN sh_classes cls On cr.class_id=cls.id "
        . "INNER JOIN sh_batches bth ON cr.batch_id=bth.id "
        . "LEFT JOIN sh_fee_discount fd ON cr.discount_id=fd.id "
        . "WHERE "
        . "s.deleted_at=0 AND cr.deleted_at is null AND "
        . "cr.academic_year_id=".$request->academic_year_id." "
        . "AND s.role_id=" . STUDENT_ROLE_ID . " "
        . "AND s.school_id='$school_id' "
        . "AND cr.class_id=$class_id "
        . "AND cr.batch_id=$batch_id "
        . "AND s.id=$request->student_id "
        
        
        ;

        if ($request->isDue) {
            $sql .= " HAVING ftCount > fcCount ";
        }

        $data = $this->admin_model->dbQuery($sql);

        foreach ($data as $key => $val) {
            $val->fcCount = $val->fcCount + $val->fdCount;
        }

        echo json_encode($data);
    }


    public function fetchfeeCollectionforStudent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        

        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }
        

        $feetype_where1 = "";
        $feetype_where2 = "";
        $feetype_status = "";
        if ($request->specificFeeType != "all") {
            $feetype_where1 = "(SELECT COUNT(*) FROM sh_fee_types ft WHERE ft.id=" . $request->specificFeeType . " AND cr.class_id = ft.class_id AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS ftCount,";
            $feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE fc.feetype_id=" . $request->specificFeeType . " AND s.id = fc.student_id AND fc.deleted_at IS NULL AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS fcCount ";
        } else {
            $feetype_where1 = "(SELECT COUNT(*) FROM sh_fee_types ft WHERE cr.class_id = ft.class_id AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS ftCount,";
            $feetype_status = "(SELECT COUNT(*) FROM sh_feetype_status fs WHERE fs.student_id=s.id AND fs.deleted_at IS NULL AND status='1') AS fdCount,";
            $feetype_where2 = "(SELECT COUNT(*) FROM sh_fee_collection fc join sh_fee_types ft on ft.id = fc.feetype_id WHERE s.id = fc.student_id AND fc.deleted_at IS NULL AND fc.status='1' AND ft.deleted_at IS NULL AND ft.due_date < '" . date('Y-m-d') . "') AS fcCount ";
        }

        $sql = "SELECT "
        . "s.*, cr.discount_id, fd.name as discount_name, fd.amount as discount_amount, "
        . "g.guardian_id, "
        . "uu.name as father_name, "
        . "cls.name as class_name, "
        . "bth.name as batch_name, "
        . "cr.class_id, cr.batch_id, "
        . $feetype_where1
        . $feetype_status
        . $feetype_where2
        . "FROM "
        . "sh_users s INNER JOIN sh_student_class_relation cr ON s.id=cr.student_id "
        . "LEFT JOIN sh_student_guardians g ON s.id=g.student_id "
        . "LEFT JOIN sh_users uu On uu.id=g.guardian_id "
        . "INNER JOIN sh_classes cls On cr.class_id=cls.id "
        . "INNER JOIN sh_batches bth ON cr.batch_id=bth.id "
        . "LEFT JOIN sh_fee_discount fd ON cr.discount_id=fd.id "
        . "WHERE "
        . "s.deleted_at=0 AND cr.deleted_at is null AND "
        . "cr.academic_year_id=".$request->academic_year_id." "
        . "AND s.role_id=" . STUDENT_ROLE_ID . " "
        . "AND s.school_id='$school_id' "
        . "AND cr.class_id=$class_id "
        . "AND cr.batch_id=$batch_id "
        . "AND s.id=$request->student_id "
        
        
        ;

        if ($request->isDue) {
            $sql .= " HAVING ftCount > fcCount ";
        }

        $data = $this->admin_model->dbQuery($sql);

        foreach ($data as $key => $val) {
            $val->fcCount = $val->fcCount + $val->fdCount;
        }
        
        echo json_encode($data);
    }

    public function getStudentFeeRecrodsForParent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];

        $feetypes = $this->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND class_id='$request->class_id' AND academic_year_id='$request->academic_year_id' AND deleted_at IS NULL ");

        foreach ($feetypes as $key => $fe) {

            $exmp = $this->admin_model->dbSelect("*", "fee_exemption", " feetype_id='$fe->id' AND student_id='$request->std_id' ");
            if (count($exmp) > 0) {
                $feetypes[$key]->exemption_amount = $exmp[0]->amount;
            } else {
                $feetypes[$key]->exemption_amount = "";
            }

            $ft_status = $this->admin_model->dbSelect("*", "feetype_status", " feetype_id='$fe->id' AND student_id='$request->std_id' ");
            if (count($ft_status) > 0) {
                $feetypes[$key]->feetype_status = $ft_status[0]->status;
            } else {
                $feetypes[$key]->feetype_status = "";
            }

            $rl_status = $this->admin_model->dbSelect("*", "request_log", " feetype_id='$fe->id' AND student_id='$request->std_id' ");
            if (count($rl_status) > 0) {
                $feetypes[$key]->exemption_status = $rl_status[0]->status;
            } else {
                $feetypes[$key]->exemption_status = "";
            }
            
        } 
       
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
        . "ft.deleted_at IS NULL AND c.student_id='$request->std_id' ";
        $collected_fees = $this->admin_model->dbQuery($sql);

        foreach ($collected_fees as $key => $c_f) {
            
            $exmp = $this->admin_model->dbSelect("*", "fee_exemption", " feetype_id='$c_f->feetype_id' AND student_id='$request->std_id' ");
            if (count($exmp) > 0) {
                $collected_fees[$key]->exemption_amount = $exmp[0]->amount;
                $collected_fees[$key]->exemption_id = $exmp[0]->id;
            } else {
                $collected_fees[$key]->exemption_amount = "";
                $collected_fees[$key]->exemption_id = "";
            }

            $ft_status = $this->admin_model->dbSelect("*", "feetype_status", " feetype_id='$c_f->feetype_id' AND student_id='$request->std_id' ");
            if (count($ft_status) > 0) {
                $collected_fees[$key]->feetype_status = $ft_status[0]->status;
            } else {
                $collected_fees[$key]->feetype_status = "";
            }

            $rl_status = $this->admin_model->dbSelect("*", "request_log", " feetype_id='$c_f->feetype_id' AND student_id='$request->std_id' ");
            if (count($rl_status) > 0) {
                $collected_fees[$key]->exemption_status = $rl_status[0]->status;
                $collected_fees[$key]->request_log_id = $rl_status[0]->id;
            } else {
                $collected_fees[$key]->exemption_status = "";
                $collected_fees[$key]->request_log_id = "";
            }

        }

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
                    "discounted_amount" => round($feetype->amount - $this->getDiscountVariant($request->discount_id, $feetype->id, $feetype->amount), 2),
                    "discount" => number_format((float)$this->getDiscountVariant($request->discount_id, $feetype->id, $feetype->amount), 2, '.', ''),
                    "discount_type" => $this->getDiscountVariantType($request->discount_id, $feetype->id),
                    "exemption_amount" => $feetype->exemption_amount,
                    "exemption_status" => $feetype->exemption_status,
                    "feetype_status" => $feetype->feetype_status
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
                            $obj->discounted_amount = round($obj->amount - $this->getDiscountVariant($request->discount_id, $fid, $obj->amount), 2);
                            $obj->discount = number_format((float)$this->getDiscountVariant($request->discount_id, $fid, $obj->amount), 2, '.', '');
                            $obj->discount_type = $this->getDiscountVariantType($request->discount_id, $fid);
                            //$obj->discounted_amount = $obj->amount - ($obj->amount * $obj->discount / 100);
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
        
        $notes = array();
        $prev_academic_year = get_previous_academic_year($request->academic_year_id);
        if(count($prev_academic_year) > 0){
            foreach($prev_academic_year as $prev){
                $query = "SELECT * from sh_fee_types WHERE class_id=(select class_id from sh_student_class_relation WHERE student_id='$request->std_id' AND academic_year_id='$prev->id' AND deleted_at is null ) AND id NOT IN (SELECT ft.id FROM sh_fee_collection c INNER JOIN sh_fee_types ft ON ft.id=c.feetype_id WHERE c.student_id='$request->std_id' AND ft.academic_year_id='$prev->id' AND c.deleted_at IS NULL AND ft.deleted_at IS NULL) AND deleted_at IS NULL AND school_id='$school_id'";
                $query1 = "SELECT ft.*,sum(case when status = '2' then paid_amount else 0 end) as total_paid FROM sh_fee_collection c INNER JOIN sh_fee_types ft ON ft.id=c.feetype_id WHERE c.student_id='$request->std_id' AND ft.academic_year_id='$prev->id' AND ft.class_id=(select class_id from sh_student_class_relation WHERE student_id='$request->std_id' AND academic_year_id='$prev->id' AND deleted_at is null ) AND c.deleted_at IS NULL AND ft.deleted_at IS NULL group by ft.id having sum(case when status = '2' then 1 else 0 end) > 0 and sum(case when status = '1' then 1 else 0 end) = 0";
                $unpaid = $this->admin_model->dbQuery($query);
                $partial = $this->admin_model->dbQuery($query1);

                if($unpaid || $partial){
                    $notes[$prev->name]["unpaid"] = $this->admin_model->dbQuery($query);
                    $notes[$prev->name]["partial"] = $this->admin_model->dbQuery($query1);
                }
                
            }
        }
        
        foreach ($yasir_array as $key => $ray) {
            $ray[0]->new_discounted_amount = $ray[0]->discounted_amount;
            if ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'approved' && $ray[0]->status == 0) {
                $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->exemption_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'not-approved' && $ray[0]->status != 0) {
                $ray[0]->discounted_amount = $ray[0]->exemption_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'inprocess' && $ray[0]->status == 0) {
                $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->exemption_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'inprocess' && $ray[0]->status == 1) {
                $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->paid_amount;
            } elseif ($ray[0]->exemption_amount != "" && $ray[0]->exemption_status == 'inprocess' && $ray[0]->status == 2) {
                if (count($ray) > 1) {
                    $total_paid_amount = 0;
                    foreach ($ray as $key2 => $a) {
                        $total_paid_amount += $a->paid_amount;
                    }
                    $ray[0]->discounted_amount = $ray[0]->discounted_amount - $total_paid_amount;
                } else {
                    $ray[0]->discounted_amount = $ray[0]->discounted_amount - $ray[0]->paid_amount;
                }
            }
        }

        foreach ($yasir_array as $key => $r) {

            if ($r[0]->exemption_amount != "" && $r[0]->exemption_status == 'approved') {
                $exemption_amount = $r[0]->exemption_amount;
            } else {
                $exemption_amount = 0;
            }

            if (count($r) > 1) {
                $total_paid_amount = 0;
                foreach ($r as $key => $a) {
                    $total_paid_amount += $a->paid_amount;
                }
// comment due to overall fee status
                // if ($r[0]->discounted_amount == $total_paid_amount + $exemption_amount && $r[0]->status == 2) {
                //     $r[0]->status = 1;
                //     $fee_collection_id = $r[0]->fee_collection_id;
                //     $this->db->query("UPDATE sh_fee_collection SET status='1' WHERE id='$fee_collection_id'");
                // }
                
            } else {
                if ($r[0]->discounted_amount > $r[0]->paid_amount + $exemption_amount && $r[0]->status == 1) {
                    $r[0]->status = 2;
                    $fee_collection_id = $r[0]->fee_collection_id;
                    $this->db->query("UPDATE sh_fee_collection SET status='2' WHERE id='$fee_collection_id'");
                }
                elseif ($r[0]->status == 1 && $r[0]->exemption_status == 'not-approved') {
                    $fee_collection_id = $r[0]->fee_collection_id;
                    $this->db->query("UPDATE sh_fee_collection SET status='2' WHERE id='$fee_collection_id'");
                }

            }

            if ($r[0]->feetype_status == "") {
                $r[0]->feetype_status = 0;
            }

        } 
        
        $data["notes"] = $notes;
        $data["records"] = $yasir_array;
        echo json_encode($data);
    }

    public function getClasses2() {
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        //-------------------------
        $data = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            if (count(login_user()->t_data->classes) > 0) {
                $data = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ") AND academic_year_id = ".$academic_year_id." ORDER BY name asc ");
            }
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=".login_user()->user->sh_id." AND academic_year_id = ".$academic_year_id." AND deleted_at is null ORDER BY name asc ");
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND academic_year_id = ".$academic_year_id." AND deleted_at IS NULL ORDER BY name asc ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

        }
        //-------------------------
        echo json_encode($data);
    }

    public function requestFeeExemption(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $sender = $this->session->userdata("userdata")["name"];

        $admin_record = $this->db->query("SELECT id FROM sh_users WHERE school_id='$school_id' AND role_id = 1")->result();

        $exemption_request = $this->admin_model->dbSelect("*", "request_log", "class_id= " . $request->class_id . " AND batch_id=" . $request->batch_id . " AND school_id=" . $school_id . " AND feetype_id=" . $request->feetype_id . " AND  type='fee_exemption' AND student_id =" . $request->student_id . "");

        $exemption_record = $this->admin_model->dbSelect("*", "fee_exemption", "feetype_id= " . $request->feetype_id . " AND student_id=" . $request->student_id . " AND  school_id=" . $school_id . " AND academic_year_id=" . $academic_year_id . " AND deleted_at is NULL ");

        if (count($exemption_record) > 0) {
            
            $record_id = $exemption_record[0]->id;
            $sql_new = "UPDATE sh_fee_exemption SET amount = " . $request->exemption_amount . ", user_id= " . $user_id . " WHERE  id = " . $record_id . " AND deleted_at is NULL";
            $result = $this->common_model->query($sql_new);

            $data['fee_record_id'] = $record_id;

        } else{

            $rcd = array(
                "school_id" => $school_id,
                "feetype_id" => $request->feetype_id,
                "student_id" => $request->student_id,
                "user_id" => $user_id,
                "academic_year_id" => $academic_year_id,
                "amount" => $request->exemption_amount
            );

            $record_id = $this->admin_model->dbInsert("fee_exemption", $rcd);
            $data['fee_record_id'] = $record_id;

        }

        if (count($exemption_request) > 0) {
            //request found
            $r_id = $exemption_request[0]->id;
            $sql1 = "UPDATE sh_request_log SET status = 'inprocess', request_time = '" . date('Y-m-d H:i:s') . "', edit_reason= '" . $request->exemption_reason . "' WHERE  class_id= " . $request->class_id . " AND batch_id=" . $request->batch_id . " AND school_id=" . $school_id . " AND feetype_id=" . $request->feetype_id . " ";
            $result = $this->common_model->query($sql1);
            $data['edit'] = 'inprocess';
            $data["message"] = lang('request_exemption');
            $data["status"] = 'success';
            $data["disable"] = "TRUE";
            $data["r_id"] = $r_id;
            $data["part"] = $admin_record[0]->id;
            $data["sender"] = $sender;
        } else {
            //request not found
            $record = array(
                "user_id" => $user_id,
                "school_id" => $school_id,
                "type" => 'fee_exemption',
                "class_id" => $request->class_id,
                "batch_id" => $request->batch_id,
                "feetype_id" => $request->feetype_id,
                "status" => 'inprocess',
                'request_time' => date('Y-m-d H:i:s'),
                'student_id' => $request->student_id,
                "edit_reason" => $request->exemption_reason
            );
            $r_id = $this->admin_model->dbInsert("request_log", $record);
            $data["r_id"] = $r_id;
            $data["message"] = lang('request_exemption');
            $data["status"] = 'success';
            $data["disable"] = "TRUE";
            $data['edit'] = 'inprocess';
            $data["part"] = explode(",", $admin_record[0]->id);
            $data["sender"] = $sender;
        }

        echo json_encode($data);

    }


    public function fetchExemption(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        // print_r($request); die();
        
        $sql = "SELECT s.*,g.guardian_id,uu.name as guardian_name,d.name as discount_name,d.amount as discount_amount,cls.name as class_name,bth.name as batch_name,cr.class_id, cr.batch_id, ft.amount as fee_amount,ft.name as feetype_name,fe.amount as exemption_amount FROM sh_users s LEFT JOIN sh_student_guardians g ON s.id=g.student_id LEFT JOIN sh_users uu ON g.guardian_id=uu.id LEFT JOIN sh_fee_discount d ON s.old_discount_id=d.id LEFT JOIN sh_student_class_relation cr ON s.id=cr.student_id LEFT JOIN sh_classes cls On cr.class_id=cls.id LEFT JOIN sh_batches bth ON cr.batch_id=bth.id LEFT JOIN sh_fee_types ft ON cls.id=ft.class_id LEFT JOIN sh_fee_exemption fe ON fe.feetype_id=ft.id WHERE s.id = '$request->student_id' AND cr.academic_year_id = '$request->academic_year' AND ft.id='$request->feetype_id'";
        
        $data = $this->admin_model->dbQuery($sql);

        $data[0]->discounted_amount = $data[0]->fee_amount - $data[0]->discount_amount;

        echo json_encode($data);

    }

    public function feeActivate(){
        $feetype_id = $this->input->post("id");
        $student_id = $this->input->post("student_id");
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $resp = $this->db->query("SELECT * FROM sh_feetype_status WHERE feetype_id='$feetype_id' AND student_id='$student_id' AND school_id='$school_id' AND deleted_at is NULL")->result();

        if (count($resp) > 0) {
            
            $id = $resp[0]->id;

            $sql1 = "UPDATE sh_feetype_status SET status = '0' WHERE id='$id' ";
            $result = $this->common_model->query($sql1);

            $data['status'] = "success"; 
            $data['message'] = "Fee activated successfully!"; 

        } else {
            //request not found
            $record = array(
                "feetype_id" => $feetype_id,
                "school_id" => $school_id,
                "student_id" => $student_id,
                "status" => '0'
            );
            $r_id = $this->admin_model->dbInsert("feetype_status", $record);
            $data["r_id"] = $r_id;
            $data["message"] = "Fee activated successfully!";
            $data["status"] = 'success';
        }


        echo json_encode($data);

    }

    public function feeDeactivate(){
        $feetype_id = $this->input->post("id");
        $student_id = $this->input->post("student_id");
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $resp = $this->db->query("SELECT * FROM sh_feetype_status WHERE feetype_id='$feetype_id' AND student_id='$student_id' AND school_id='$school_id' AND deleted_at is NULL")->result();

        if (count($resp) > 0) {
            
            $id = $resp[0]->id;

            $sql1 = "UPDATE sh_feetype_status SET status = '1' WHERE id='$id' ";
            $result = $this->common_model->query($sql1);

            $data['status'] = "success"; 
            $data['message'] = "Fee deactivated successfully!"; 

        } else {
            //request not found
            $record = array(
                "feetype_id" => $feetype_id,
                "school_id" => $school_id,
                "student_id" => $student_id,
                "status" => '1'
            );
            $r_id = $this->admin_model->dbInsert("feetype_status", $record);
            $data["r_id"] = $r_id;
            $data["message"] = "Fee deactivated successfully!";
            $data["status"] = 'success';
        }


        echo json_encode($data);

    }

    public function applyDiscounts(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student_class_relation_id = $request->student_class_relation_id;
        $discount_ids = implode(",", $request->discount_ids);

        $class_id = $request->class_id;
        $result = array();
        foreach ($request->discount_ids as $key => $did) {
            $results = $this->db->query("SELECT * FROM sh_discount_varients WHERE class_id='$class_id' AND discount_id='$did' AND deleted_at is NULL ")->result();
            if (count($results) > 0) {
                array_push($result, $results[0]);   
            }
        } 
        $discounts = array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $x = new stdClass();
                $feetype_id = $value->fee_type_id;
                $feetype_amount = $this->db->query("SELECT amount FROM sh_fee_types WHERE id='$feetype_id' AND deleted_at is NULL ")->result();
                $feetype_amount = $feetype_amount[0]->amount;

                $discount = number_format((float)$this->getDiscountVariant($value->discount_id, $feetype_id, $feetype_amount), 2, '.', '');
                $x->discount = $discount;
                array_push($discounts, $x);
                $discounts[$key]->feetype_id = $feetype_id;
            }
        }
        
        $sheraz_array = array();
        if (count($discounts) > 1) {
            for($i=0; $i<=count($discounts)-1; $i++){
                for ($i=$i+1; $i<=count($discounts)-1; $i++) {
                    $sheraz = new stdClass(); 
                    $sheraz2 = new stdClass(); 
                    if ($discounts[$i-1]->feetype_id == $discounts[$i]->feetype_id) {
                        $sheraz->total_amount = $discounts[$i-1]->discount + $discounts[$i]->discount; 
                        $sheraz->feetype_id = $discounts[$i-1]->feetype_id;
                        array_push($sheraz_array, $sheraz); 
                    } elseif(count($discounts) == 2) {
                        $sheraz->total_amount = $discounts[$i]->discount;
                        $sheraz->feetype_id = $discounts[$i]->feetype_id;
                        $sheraz2->total_amount = $discounts[$i-1]->discount;
                        $sheraz2->feetype_id = $discounts[$i-1]->feetype_id;
                        array_push($sheraz_array, $sheraz);
                        array_push($sheraz_array, $sheraz2);
                    } else {
                        $sheraz->total_amount = $discounts[$i]->discount;
                        $sheraz->feetype_id = $discounts[$i]->feetype_id;
                        array_push($sheraz_array, $sheraz);
                    }
                }
            }

        } elseif(count($discounts) == 1) {
            $sheraz = new stdClass();
            $sheraz->total_amount = $discounts[0]->discount;
            $sheraz->feetype_id = $discounts[0]->feetype_id;
            array_push($sheraz_array, $sheraz);

        } else {

            $sheraz = new stdClass();
            $sheraz->total_amount = "";
            $sheraz->feetype_id = "";
            array_push($sheraz_array, $sheraz);
            
        }
        if ($sheraz_array[0]->total_amount != "") {
            foreach ($sheraz_array as $key => $val) {
                $feetype_amount = $this->db->query("SELECT amount FROM sh_fee_types WHERE id='$val->feetype_id' AND deleted_at is NULL ")->result();
                $feetype_amount = $feetype_amount[0]->amount;   
                if ($val->total_amount > $feetype_amount) {

                    $data1 = array(
                        "discount_id" => NULL
                    );

                    $res = $this->common_model->update_where("sh_student_class_relation", array("id" => $student_class_relation_id), $data1);

                    if ($res) {
                        $data = array("status" => "error", "message" => lang('discounts_error'));
                        echo json_encode($data); die();
                    }

                }
            }
        }
        
        $data1 = array(
            "discount_id" => $discount_ids
        );

        $res = $this->common_model->update_where("sh_student_class_relation", array("id" => $student_class_relation_id), $data1);
        
        if ($res) {
            $data = array("status" => "success", "message" => lang('discounts_updated'));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang('discounts_updated_error'));
            echo json_encode($data);
        }

    }

    public function refundCollectedFee(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        if($request->type == "null"){
            //single record deleted from paartially paid
            foreach($request->ids as $id){
                $response = $this->common_model->update_where("sh_fee_collection", array("id" => $id), array("refund_fee" => '1'));
            }
            $iddd = $request->ids[0];
            $d = $this->admin_model->dbSelect("*","fee_collection", "id=$iddd")[0];
            $std_id = $d->student_id;
            $f_id = $d->feetype_id;
            $response = $this->db->set('status','2')->where('student_id',$std_id)->where('feetype_id',$f_id)->where('deleted_at is null')->update('sh_fee_collection');
        } else {
            // deleted all record
            foreach($request->ids as $id){
                $response = $this->common_model->update_where("sh_fee_collection", array("id" => $id), array("refund_fee" => '1'));
            }
        }

        // if ($request->exemption_id != "") {
        //     $response = $this->common_model->update_where("sh_fee_exemption", array("id" => $request->exemption_id), array("deleted_at" => date("Y-m-d h:i:s")));
        // }
        // if ($request->request_log_id != "") {
        //     $response = $this->common_model->update_where("sh_request_log", array("id" => $request->request_log_id), array("deleted_at" => date("Y-m-d h:i:s")));
        // }
        
        if ($request->exemption_id != "") {
            $this->db->where('id', $request->exemption_id);
            $this->db->delete('sh_fee_exemption');
            // $response = $this->common_model->update_where("sh_fee_exemption", array("id" => $request->exemption_id), array("deleted_at" => date("Y-m-d h:i:s")));
        }
        if ($request->request_log_id != "") {
            $this->db->where('id', $request->request_log_id);
            $this->db->delete('sh_request_log');
            // $response = $this->common_model->update_where("sh_request_log", array("id" => $request->request_log_id), array("deleted_at" => date("Y-m-d h:i:s")));
        }
        
        /*$idd = $request->ids[0];
        $d = $this->admin_model->dbSelect("*","fee_collection", "id=$idd AND deleted_at IS NULL ")[0];
        
        if(count($request->ids) == 1) {
            //----- Partially paid single record delete ------//
            $all_partial_fees = $this->admin_model->dbSelect("*","fee_collection"," student_id='$d->student_id' AND feetype_id='$d->feetype_id' AND deleted_at IS NULL AND id!=$id ");
            $response = $this->common_model->update_where("sh_fee_collection", array("id" => $request->ids[0]), array("deleted_at" => date("Y-m-d h:i:s")));
        } else {
            //----- All delete record of single fee -----//
            
        }*/
        
        $data = array();
        if ($response) {
            $data = array("status" => "success", "message" => lang('lbl_collect_fee'));
        } else {
            $data = array("status" => "error", "message" => lang('lbl_error_collect_fee'));
        }
        echo json_encode($data);


    }
    
}
