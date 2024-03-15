<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forms extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }
    
    public function all(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sql = "SELECT temp.*,cat.name as form_category, cat.tag as form_category_type FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.school_id='$school_id' AND temp.deleted_at IS NULL ";
        $data["templates"] = $this->admin_model->dbQuery($sql);
        $this->load->view("forms/view",$data);
    }
    
    public function show(){
        $id = $_GET["id"];
        $page = $_GET["requested_page"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $languages = $this->admin_model->dbSelect("*","language"," 1 ");
        $class_id = 0;
        $exam_id = 0;
        $batch_id = 0;
        $student_id = 0;
        $employee_id = 0;
        $salary_type_id = 0;
        $data = array();
        
        $selected_language_id = 1;
        $language_where = " language_id=1 ";
        if($this->session->userdata("site_lang") != "english") {
            $language_where = " language_id=2 ";
            $selected_language_id = 2;
        }
        
        $data["request_page"] = $page; 
        if($page == 'forms'){
            $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$id' ";
            $data["template"] = $this->admin_model->dbQuery($sql)[0];
            $rendered_form_html = render_universal_tags($data["template"],$data["template"]->html);
            $data["html"] =  $rendered_form_html;
        } else if($page == 'single_fee'){
            $sql = "SELECT "
                . "col.*, "
                . "std.name as student_name, "
                . "std.mobile_phone as mobile_phone, "
                . "std.avatar as avatar, "
                . "std.rollno as rollno, "
                . "ftype.name as ftype, "
                . "ftype.amount as amount, "
                . "dis.name as discount_type, "
                . "dis.amount as discount_percentage, "
                . "thr.name as collector_name "
                . "FROM sh_fee_collection col "
                . "INNER JOIN sh_users std ON col.student_id=std.id "
                . "INNER JOIN sh_fee_types ftype ON col.feetype_id=ftype.id "
                . "INNER JOIN sh_users thr ON col.collector_id=thr.id "
                . "LEFT JOIN sh_student_class_relation cr ON cr.academic_year_id = ftype.academic_year_id and cr.student_id = std.id "
                . "LEFT JOIN sh_fee_discount dis ON  cr.discount_id = dis.id "
                . "WHERE col.id='$id' AND col.deleted_at IS NULL ";

            $data['data'] = $this->admin_model->dbQuery($sql)[0];
            
            //sheraz added new code for total paid amount of partially paid fee
            $std_id = $data['data']->student_id;
            $ft_id = $data['data']->feetype_id;
            $cls_id = $data['data']->class_id;
            $bch_id = $data['data']->batch_id;

            $query_sheraz = "SELECT * FROM `sh_fee_collection` WHERE school_id='$school_id' AND student_id='$std_id' AND feetype_id ='$ft_id' AND class_id ='$cls_id' AND batch_id ='$bch_id' AND deleted_at is NULL ";
            $collected_fee = $this->admin_model->dbQuery($query_sheraz);
            if (count($collected_fee) > 1){
                $sizeof_collectedfee=sizeof($collected_fee);
                $collected_fee=$collected_fee[$sizeof_collectedfee-1];
                $data['data']->paid_amount = $collected_fee->paid_amount;
            }
            
            
            // if (sizeof($collected_fee) > 0) {
                $total_paid = 0;
                // foreach ($collected_fee as $key => $fee) {
                    // $total_paid += $fee->paid_amount;
                // }
                // $data['data'] = $data['data'];
                // $data['data']->paid_amount = $total_paid;
            // }
            //end here
            
            $data['data']->class_name = $_GET["class_name"];
            $data['data']->batch_name = $_GET["batch_name"];
            $data['class_name'] = $_GET["class_name"];
            $data['batch_name'] = $_GET["batch_name"];
            $sql2 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE student_id=".$data["data"]->student_id;
            $res = $this->admin_model->dbQuery($sql2);
            if(count($res)>0){
                $data['data']->guardian_name = $res[0]->guardian_name;
            } else {
                $data['data']->guardian_name = NULL;
            }
            
            $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp "
                . "INNER JOIN "
                . "sh_form_categories cat ON temp.form_category_id=cat.id "
                . "WHERE "
                . "temp.tag='single_fee' "
                . "AND "
                . "temp.school_id='$school_id' "
            . "AND".$language_where;

            $data["template"] = $this->admin_model->dbQuery($sql)[0];
            $rendered_form_html = render_fee_tags($data,$data["template"], $selected_language_id);
            $data["html"] =  $rendered_form_html;
        } else if($page == "result_card_old"){
            $dd = json_decode($_GET["data"]);
            $rendered_form_html = render_resultcard($dd);
            $data = array('template'=>'','html'=>$rendered_form_html);
        } else if($page == "result_card"){
            $student_id = $_GET["id"];
            $selected_academic_year_id = $_GET["academic_year_id"];
            $class_id = isset($_GET["class_id"])?$_GET["class_id"]:NULL;
            $exam_id = isset($_GET["exam_id"])?$_GET["exam_id"]:NULL;
            $batch_id = isset($_GET["batch_id"])?$_GET["batch_id"]:NULL;
            $sq = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.tag='result_card' AND temp.school_id='$school_id' AND".$language_where;
            $r = $this->admin_model->dbQuery($sq);
            if(count($r) > 0){
                $data["template"] = $r[0];
            } else {
                $form_category_id = $this->admin_model->dbSelect("id","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag='is_system' ")[0]->id;
                $d = array("name"=>"Result Card English","is_custom"=>"Yes","language_id"=>1,"tag"=>"result_card","html"=> system_result_card_template(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
                $dd = array("name"=>"Result Card Arabic","is_custom"=>"Yes","language_id"=>2,"tag"=>"result_card","html"=> system_result_card_template_arabic(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
                $inserted_id = $this->common_model->insert("sh_templates",$d);
                $inserted_id2 = $this->common_model->insert("sh_templates",$dd);
                $selected_lang_tempate_inserted_id = $inserted_id;
                if($this->session->userdata("site_lang") != "english") {
                    $selected_lang_tempate_inserted_id = $inserted_id2;
                }
                $data["template"] = $this->admin_model->dbSelect("*","templates"," id='$selected_lang_tempate_inserted_id' ")[0];
            }

            $exam_detail_ids = "";
            $exam_details = $this->admin_model->dbSelect("*","exam_details"," exam_id='$exam_id' AND class_id='$class_id' AND batch_id='$batch_id' AND deleted_at IS NULL ");
            foreach($exam_details as $e){
                $exam_detail_ids .= $e->id . ",";
            }

            // old query to fetch subject group id
            //$subjectgroupid = $this->admin_model->dbSelect("subject_group_id","students_".$school_id," id='$student_id' ");
            
            // new query to fetch subject group id
            $subjectgroupid = $this->admin_model->dbSelect("*","student_class_relation", " student_id='$student_id' AND academic_year_id='$selected_academic_year_id' ");
            $std_reading_subjects = 0;
            if(count($subjectgroupid) > 0){
                $subjectgroupid = $subjectgroupid[0]->subject_group_id;
                $subjectss = $this->admin_model->dbSelect("subjects","subject_groups"," id='$subjectgroupid' ");
                if(count($subjectss) > 0){
                    $std_reading_subjects = $subjectss[0]->subjects;
                }
            }
            
            //update query sheraz for activities
            $sql = "SELECT "
                . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
                . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
                . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.total_obtained_marks, m.obtained_marks, m.total_grade, m.status, m.remarks, m.activities, "
                . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type, d.total_exam_marks as total_written_marks "
                . "FROM sh_marksheets m "
                . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
                . "INNER JOIN sh_users u ON m.student_id=u.id "
                . "INNER JOIN sh_classes c ON m.class_id=c.id "
                . "INNER JOIN sh_batches b ON m.batch_id=b.id "
                . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
                . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
                . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
                . "WHERE m.student_id='$id' "
                . "AND m.exam_detail_id IN (".rtrim($exam_detail_ids, ",").") "
                . "AND m.class_id='$class_id' "
                . "AND m.school_id='$school_id' "
                . "AND m.batch_id='$batch_id' "
                . "AND s.id IN (".$std_reading_subjects.") "
                . "AND m.deleted_at IS NULL "
                . "AND rp.exam_id='$exam_id' ";
            
                $res = $this->admin_model->dbQuery($sql);
            $info = array();
            if(count($res) > 0){
                $sql22 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE relation='father' AND student_id=".$res[0]->student_id;
                $res22 = $this->admin_model->dbQuery($sql22);
                $guardian_name = NULL;
                if(count($res22)>0){
                    $guardian_name = $res22[0]->guardian_name;
                } 
                $info["student_id"] = $res[0]->student_id;
                $info["student_name"] = $res[0]->student_name;
                $info["father_name"] = $guardian_name;
                $info["rollno"] = $res[0]->rollno;
                $info["mobile_phone"] = $res[0]->mobile_phone;
                $info["avatar"] = $res[0]->avatar;
                $info["class_id"] = $res[0]->class_id;
                $info["batch_id"] = $res[0]->batch_id;
                $info["class_name"] = $res[0]->class_name;
                $info["section_name"] = $res[0]->section_name;
                $info["exam_name"] = $res[0]->exam_name;
                $info["exam_id"] = $res[0]->exam_id;
                $info["position"] = $res[0]->position;
                $info["class_teacher_remarks"] = $res[0]->teacher_remark;
                $info["academic_year_id"] = $selected_academic_year_id;
                $info["details"] = array();
                foreach($res as $r){
                    $ar = (object) array();
                    $ar->subjnect_id = $r->subject_id;
                    $ar->subjnect_name = $r->subject_name;
                    // update activities sheraz
                    $ar->total_obtained_marks = $r->total_obtained_marks;
                    $ar->obtained_marks = $r->obtained_marks;
                    $ar->grade = $r->total_grade;
                    $ar->status = $r->status;
                    $ar->remarks = $r->remarks;
                    $ar->exam_date = $r->exam_date;
                    $ar->start_time = $r->start_time;
                    $ar->end_time = $r->end_time;
                    $ar->total_marks = $r->total_marks;
                    $ar->passing_marks = $r->passing_marks;
                     // update for activities sheraz
                    $ar->type = $r->type;
                    if ($r->total_written_marks == 0 ) {
                        $ar->total_written_marks = $r->total_marks;
                    } else {
                        $ar->total_written_marks = $r->total_written_marks;
                    }
                    $ar->activities = json_decode($r->activities);
                    array_push($info["details"], $ar);
                }
            }
            $data["data"] = $info;
           
            $rendered_form_html = render_resultcard_tags($data, $data["template"]);


            $data["html"] =  $rendered_form_html;
        } else if($page == "single_payroll"){ 
            $employee_id = $_GET["id"];
            $salary_type_id = $_GET["salary_type_id"];
            $sql = "SELECT 
                p.*, 
                sty.name as salary_name,
                sty.date as due_date,
                sty.amount as salary_amount,
                u.name as employee_name,
                uu.name as paid_by,
                d.name as department_name,
                rc.category as category_name  
                FROM sh_payroll p 
                INNER JOIN sh_salary_types sty ON p.salary_type_id=sty.id 
                INNER JOIN sh_users u ON p.user_id=u.id 
                INNER JOIN sh_users uu ON p.paid_by=uu.id 
                INNER JOIN sh_departments d ON u.department_id=d.id 
                INNER JOIN sh_role_categories rc ON u.role_category_id=rc.id 
                WHERE 
                p.user_id='$employee_id' 
                AND p.salary_type_id='$salary_type_id' 
                AND p.deleted_at IS NULL ";
            $data["data"] = $this->admin_model->dbQuery($sql);

            $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp "
                . "INNER JOIN "
                . "sh_form_categories cat ON temp.form_category_id=cat.id "
                . "WHERE "
                . "temp.tag='payroll_card' "
                . "AND "
                . "temp.school_id='$school_id' "
            . "AND".$language_where;
            $temp_arr = $this->admin_model->dbQuery($sql);
            if(count($temp_arr) > 0){
                
                //template exists
                $data["template"] = $this->admin_model->dbQuery($sql)[0];

            } else {
                
                //template does not exists
                $form_category_id = $this->admin_model->dbSelect("id","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag='is_system' ")[0]->id;
                $d = array("name"=>"Payroll English","is_custom"=>"Yes","language_id"=>1,"tag"=>"payroll_card","html"=> system_payroll_template_english(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
                $dd = array("name"=>"Payroll Arabic","is_custom"=>"Yes","language_id"=>2,"tag"=>"payroll_card","html"=> system_payroll_template_arabic(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
                $inserted_id = $this->common_model->insert("sh_templates",$d);
                $inserted_id2 = $this->common_model->insert("sh_templates",$dd);
                $selected_lang_tempate_inserted_id = $inserted_id;
                if($this->session->userdata("site_lang") != "english") {
                    $selected_lang_tempate_inserted_id = $inserted_id2;
                }
                $data["template"] = $this->admin_model->dbQuery("SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$selected_lang_tempate_inserted_id' ")[0];
            }
            $rendered_form_html = render_payroll_tags($data["data"],$data["template"], $selected_language_id);
            $data["html"] =  $rendered_form_html;
        }
        
        $data["fee_get_id_from_url"] = $id;
        $data["student_id"] = $student_id;
        $data["employee_id"] = $employee_id;
        $data["salary_type_id"] = $salary_type_id;
        $data["class_id"] = $class_id;
        $data["exam_id"] = $exam_id;
        $data["batch_id"] = $batch_id;
        $data["languages"] = $languages;
        $data["is_print_all"] = isset($_GET["is_print_all"])?"true":"false";
        
        //echo "<pre/>"; print_r($data); die();
        $this->load->view("forms/show",$data);
    }
    
    public function create(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["formCategories"] = $this->admin_model->dbSelect("*","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag IS NULL ");
        $this->load->view("forms/form",$data);
    }
    
    public function save(){
        $title = $this->input->post("title");
        $form_category_id = $this->input->post("category_id");
        $html = $this->input->post("html");
        
        $data = array("name"=>$title,"is_custom"=>'No',"html"=>$html,"school_id"=>$this->session->userdata('userdata')['sh_id'],"form_category_id"=>$form_category_id);
        
        $res = $this->common_model->insert('sh_templates', $data);
        if($res){
            $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('new_form')));
            redirect("forms/all","refresh");
        }
    }
    
    public function category_create(){
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_form_categories');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name');
        $xcrud->fields('name');
        $xcrud->label('name', lang('lbl_name'));
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->replace_remove('form_category_delete');
        $xcrud->table_name(lang('lbl_form_categories'));
        $xcrud->load_view("view", "customview.php");
        
        
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data["form_categories"] = $xcrud->render();
        $this->load->view("forms/categories",$data);
    }
    
    public function delete(){
        $id = $this->input->post("id");
        $record = $this->admin_model->dbSelect("*","templates"," id='$id' ")[0];
        if($record->is_custom === 'Yes'){
            $this->session->set_flashdata('alert', array("status" => "danger", "message" => lang('system_delete')));
        }else{
            $this->common_model->update_where("sh_templates", array("id" => $id), array("deleted_at" => date("Y-m-d h:i:s")));
            $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('form_delete')));
        }
        echo "success";
    }
    
    public function edit(){
        $id = $_GET["id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["formCategories"] = $this->admin_model->dbSelect("*","form_categories"," school_id='$school_id' AND deleted_at IS NULL ");
        
        $sql = "SELECT temp.*,cat.name as form_category, cat.tag as form_category_type, cat.id as form_category_id FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id = cat.id WHERE temp.id='$id'";
        $data["template"] = $this->admin_model->dbQuery($sql)[0]; 
        //$data["template"] = $this->admin_model->dbSelect("*","templates"," id='$id' ")[0];
        $this->load->view("forms/edit",$data);
    }
    
    public function update(){
        $id = $this->input->post("id");
        $title = $this->input->post("title");
        $category_id = $this->input->post("category_id");
        $html = $this->input->post("html");

        $data = array("name"=>$title,"form_category_id"=>$category_id,"html"=>$html);
        $res = $this->common_model->update_where("sh_templates",array("id"=>$id),$data);
        if($res){
            $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('form_update')));
            redirect("forms/all","refresh");
        }
    }
    
    public function get_form_language_wise(){
        $tag = $this->input->post("tag");
        $lang_id = $this->input->post("lang_id");
        $student_id = $this->input->post("student_id");
        $fee_id = $this->input->post("fee_id");
        $class_id = $this->input->post("class_id");
        $batch_id = $this->input->post("batch_id");
        $class_name = $this->input->post("class_name");
        $batch_name = $this->input->post("batch_name");
        $exam_id = $this->input->post("exam_id");
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $employee_id = $this->input->post("employee_id");
        $salary_type_id = $this->input->post("salary_type_id");
        
        if($tag == "result_card") { 
            $template = $this->admin_model->dbSelect("*","templates"," tag='$tag' AND school_id='$school_id' AND language_id='$lang_id' AND deleted_at IS NULL ");
            $exam_detail_ids = "";
            $exam_details = $this->admin_model->dbSelect("*","exam_details"," exam_id='$exam_id' AND deleted_at IS NULL ");
            foreach($exam_details as $e){
                $exam_detail_ids .= $e->id . ",";
            }
            $subjectgroupid = $this->admin_model->dbSelect("subject_group_id","students_".$school_id," id='$student_id' ");
            $std_reading_subjects = 0;
            if(count($subjectgroupid) > 0){
                $subjectgroupid = $subjectgroupid[0]->subject_group_id;
                $subjectss = $this->admin_model->dbSelect("subjects","subject_groups"," id='$subjectgroupid' ");
                if(count($subjectss) > 0){
                    $std_reading_subjects = $subjectss[0]->subjects;
                }
            }
            $sql = "SELECT "
                . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
                . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
                . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.obtained_marks, m.grade, m.status, m.remarks, "
                . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type "
                . "FROM sh_marksheets m "
                . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
                . "INNER JOIN sh_users u ON m.student_id=u.id "
                . "INNER JOIN sh_classes c ON m.class_id=c.id "
                . "INNER JOIN sh_batches b ON m.batch_id=b.id "
                . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
                . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
                . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
                . "WHERE m.student_id='$student_id' "
                . "AND m.exam_detail_id IN (".rtrim($exam_detail_ids, ",").") "
                . "AND m.class_id='$class_id' "
                . "AND m.school_id='$school_id' "
                . "AND m.batch_id='$batch_id' "
                . "AND s.id IN (".$std_reading_subjects.") "
                . "AND m.deleted_at IS NULL "
                . "AND rp.exam_id='$exam_id' ";

            $res = $this->admin_model->dbQuery($sql);

            $info = array();
            if(count($res) > 0){
                $sql22 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE relation='father' AND student_id=".$res[0]->student_id;
                $res22 = $this->admin_model->dbQuery($sql22);
                $guardian_name = NULL;
                if(count($res22)>0) {
                    $guardian_name = $res22[0]->guardian_name;
                } 
                $info["student_id"] = $res[0]->student_id;
                $info["student_name"] = $res[0]->student_name;
                $info["father_name"] = $guardian_name;
                $info["rollno"] = $res[0]->rollno;
                $info["mobile_phone"] = $res[0]->mobile_phone;
                $info["avatar"] = $res[0]->avatar;
                $info["class_id"] = $res[0]->class_id;
                $info["batch_id"] = $res[0]->batch_id;
                $info["class_name"] = $res[0]->class_name;
                $info["section_name"] = $res[0]->section_name;
                $info["exam_name"] = $res[0]->exam_name;
                $info["exam_id"] = $res[0]->exam_id;
                $info["position"] = $res[0]->position;
                $info["class_teacher_remarks"] = $res[0]->teacher_remark;
                $info["details"] = array();
                foreach($res as $r){
                    $ar = (object) array();
                    $ar->subjnect_id = $r->subject_id;
                    $ar->subjnect_name = $r->subject_name;
                    $ar->obtained_marks = $r->obtained_marks;
                    $ar->grade = $r->grade;
                    $ar->status = $r->status;
                    $ar->remarks = $r->remarks;
                    $ar->exam_date = $r->exam_date;
                    $ar->start_time = $r->start_time;
                    $ar->end_time = $r->end_time;
                    $ar->total_marks = $r->total_marks;
                    $ar->passing_marks = $r->passing_marks;
                    $ar->type = $r->type;
                    array_push($info["details"], $ar);
                }
            }
            $data["data"] = $info;
            $data["template"] = $template[0];
            $rendered_form_html = render_resultcard_tags($data, $data["template"]);
            $data["html"] =  $rendered_form_html;
        } else if($tag == "single_fee"){
            $sql = "SELECT "
                . "col.*, "
                . "std.name as student_name, "
                . "std.mobile_phone as mobile_phone, "
                . "std.avatar as avatar, "
                . "std.rollno as rollno, "
                . "ftype.name as ftype, "
                . "ftype.amount as amount, "
                . "dis.name as discount_type, "
                . "dis.amount as discount_percentage, "
                . "thr.name as collector_name "
                . "FROM sh_fee_collection col "
                . "INNER JOIN sh_users std ON col.student_id=std.id "
                . "INNER JOIN sh_fee_types ftype ON col.feetype_id=ftype.id "
                . "INNER JOIN sh_users thr ON col.collector_id=thr.id "
                . "LEFT JOIN sh_student_class_relation cr ON cr.academic_year_id = ftype.academic_year_id and cr.student_id = std.id "
                . "LEFT JOIN sh_fee_discount dis ON  cr.discount_id = dis.id "
                . "WHERE col.id='$fee_id'";
            $data['data'] = $this->admin_model->dbQuery($sql)[0];
            $sql2 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE grd.student_id=".$data["data"]->student_id;
            $res = $this->admin_model->dbQuery($sql2);
            if(count($res)>0){
                $data['data']->guardian_name = $res[0]->guardian_name;
            } else {
                $data['data']->guardian_name = NULL;
            }
            $data["data"]->fee_in_paid_class_name = $class_name;
            $data["data"]->fee_in_paid_batch_name = $batch_name;
            $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.tag='$tag' AND temp.school_id='$school_id' AND temp.language_id='$lang_id' AND temp.deleted_at IS NULL ";
            $data["template"] = $this->admin_model->dbQuery($sql)[0];
            $rendered_form_html = render_fee_tags($data,$data["template"],$lang_id);
            $data["html"] =  mb_convert_encoding($rendered_form_html, "UTF-8", "auto");

        } else if($tag == "payroll_card"){
            $sql = "SELECT 
                p.*, 
                sty.name as salary_name,
                sty.date as due_date,
                sty.amount as salary_amount,
                u.name as employee_name,
                uu.name as paid_by,
                d.name as department_name,
                rc.category as category_name  
                FROM sh_payroll p 
                INNER JOIN sh_salary_types sty ON p.salary_type_id=sty.id 
                INNER JOIN sh_users u ON p.user_id=u.id 
                INNER JOIN sh_users uu ON p.paid_by=uu.id 
                INNER JOIN sh_departments d ON u.department_id=d.id 
                INNER JOIN sh_role_categories rc ON u.role_category_id=rc.id 
                WHERE 
                p.user_id='$employee_id' 
                AND p.salary_type_id='$salary_type_id' 
                AND p.deleted_at IS NULL ";
            $data["data"] = $this->admin_model->dbQuery($sql);
            $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp "
                . "INNER JOIN "
                . "sh_form_categories cat ON temp.form_category_id=cat.id "
                . "WHERE "
                . "temp.tag='payroll_card' "
                . "AND "
                . "temp.school_id='$school_id' "
            . "AND temp.language_id='$lang_id';";
            $data["template"] = $this->admin_model->dbQuery($sql)[0];
            $rendered_form_html = render_payroll_tags($data["data"],$data["template"], $lang_id);
            $data["html"] =  $rendered_form_html;
        }
        
        $response = array();
        if(count($data) > 0 ){
            $response = array("status"=>"success", "message"=>"Form found successfully!", "data"=>$data["html"]);
        } else {
            $response = array("status"=>"error", "message"=>lang("no_record"), "data"=>array());
        }
        echo json_encode($response);
    }
    
    public function print_all_result_cards(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $class_id = $request->obj->class_id;
        $batch_id = $request->obj->batch_id;
        $exam_id = $request->obj->exam_id;
        $std_reading_subjects = 0;
        $selected_academic_year_id = $request->obj->academic_year_id;

        $selected_language_id = 1;
        $language_where = " language_id=1 ";
        if($this->session->userdata("site_lang") != "english") {
            $language_where = " language_id=2 ";
            $selected_language_id = 2;
        }
        
        $sq = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.tag='result_card' AND temp.school_id='$school_id' AND".$language_where;

        $data["template"] = $this->admin_model->dbQuery($sq)[0];

        $result_cards = array();
        $exam_detail_ids = array();
        $exam_details = $this->admin_model->dbSelect("*","exam_details"," exam_id=$exam_id AND class_id=$class_id AND batch_id=$batch_id AND deleted_at IS NULL ");
        foreach($exam_details as $e){
            array_push($exam_detail_ids, $e->id); 
        }

        foreach($request->ids as $student_id){
            $subjectgroupid = $this->admin_model->dbSelect("subject_group_id","student_class_relation"," class_id='$class_id' AND batch_id='$batch_id' AND student_id = '$student_id' AND deleted_at IS NULL ");
            //$subjectgroupid = $this->admin_model->dbSelect("subject_group_id","students_".$school_id," id='$student_id' ");
            if(count($subjectgroupid) > 0){
                $subjectgroupid = $subjectgroupid[0]->subject_group_id;
                $subjectss = $this->admin_model->dbSelect("subjects","subject_groups"," id='$subjectgroupid' ");
                if(count($subjectss) > 0){
                    $std_reading_subjects = $subjectss[0]->subjects;
                }else{
                    $std_reading_subjects = 0;
                }
            }else{
                $subjectss = $this->admin_model->dbSelect("group_concat(id) as subjects","subjects"," class_id = $class_id and batch_id = '$batch_id' and deleted_at is null ");
                if(count($subjectss) > 0){
                    $std_reading_subjects = $subjectss[0]->subjects;
                }else{
                    $std_reading_subjects = 0;
                }
                
            }
            // query update for activities
            $ysql = "SELECT "
                . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
                . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
                . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.total_obtained_marks, m.obtained_marks, m.total_grade, m.status, m.remarks, m.activities, "
                . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type, d.total_exam_marks as total_written_marks "
                . "FROM sh_marksheets m "
                . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
                . "INNER JOIN sh_users u ON m.student_id=u.id "
                . "INNER JOIN sh_classes c ON m.class_id=c.id "
                . "INNER JOIN sh_batches b ON m.batch_id=b.id "
                . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
                . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
                . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
                . "WHERE m.student_id='$student_id' "
                . "AND m.exam_detail_id IN (". implode(",", $exam_detail_ids).") "
                . "AND m.class_id='$class_id' "
                . "AND m.school_id='$school_id' "
                . "AND m.batch_id='$batch_id' "
                . "AND s.id IN ($std_reading_subjects) "
                . "AND m.deleted_at IS NULL "
                . "AND rp.exam_id='$exam_id' ";
            $res = $this->admin_model->dbQuery($ysql);
            
            $info = array();
            if(count($res) > 0){
                $sql22 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE relation='father' AND student_id=".$student_id;
                $res22 = $this->admin_model->dbQuery($sql22);
                $guardian_name = NULL;
                if(count($res22)>0){
                    $guardian_name = $res22[0]->guardian_name;
                } 
                $info["student_id"] = $res[0]->student_id;
                $info["student_name"] = $res[0]->student_name;
                $info["father_name"] = $guardian_name;
                $info["rollno"] = $res[0]->rollno;
                $info["mobile_phone"] = $res[0]->mobile_phone;
                $info["avatar"] = $res[0]->avatar;
                $info["class_id"] = $res[0]->class_id;
                $info["batch_id"] = $res[0]->batch_id;
                $info["class_name"] = $res[0]->class_name;
                $info["section_name"] = $res[0]->section_name;
                $info["exam_name"] = $res[0]->exam_name;
                $info["exam_id"] = $res[0]->exam_id;
                $info["position"] = $res[0]->position;
                $info["class_teacher_remarks"] = $res[0]->teacher_remark;
                $info["academic_year_id"] = $selected_academic_year_id;
                $info["details"] = array();
                foreach($res as $r){
                    $ar = (object) array();
                    $ar->subjnect_id = $r->subject_id;
                    $ar->subjnect_name = $r->subject_name;
                    // update for activities sheraz
                    $ar->total_obtained_marks = $r->total_obtained_marks;
                    $ar->obtained_marks = $r->obtained_marks;
                    $ar->grade = $r->total_grade;
                    $ar->status = $r->status;
                    $ar->remarks = $r->remarks;
                    $ar->exam_date = $r->exam_date;
                    $ar->start_time = $r->start_time;
                    $ar->end_time = $r->end_time;
                    $ar->total_marks = $r->total_marks;
                    $ar->passing_marks = $r->passing_marks;
                    $ar->type = $r->type;
                    // update for activites sheraz
                    if ($r->total_written_marks == 0 ) {
                        $ar->total_written_marks = $r->total_marks;
                    } else {
                        $ar->total_written_marks = $r->total_written_marks;
                    }
                    $ar->activities = json_decode($r->activities);
                    array_push($info["details"], $ar);
                }
            }
            $data["data"] = $info;
            
            if (count($data["data"]) > 0) {
                $rendered_form_html = render_resultcard_tags($data, $data["template"]);
            
                array_push($result_cards, $rendered_form_html);
            }
        }
        echo json_encode($result_cards);
    }

    public function print_all_student_evaluation_cards(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $class_name = $request->class_name;
        $batch_id = $request->batch_id;
        $batch_name = $request->batch_name;
        $evaluation_id = $request->evaluation_type;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $term_id = $request->term_id;
        
        // insert new template in sh_template it not exists
        $user_selected_lang = 1;
        if($this->session->userdata("userdata")["language"] != "english"){
            $user_selected_lang = 2;
        }
        $selected_language_id = 1;
        $language_where = " language_id=1 ";
        if($this->session->userdata("site_lang") != "english") {
            $language_where = " language_id=2 ";
            $selected_language_id = 2;
        }
        $sq = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.tag='student_evaluation_card' AND temp.school_id='$school_id' AND".$language_where;
        $result = $this->admin_model->dbQuery($sq);
        if(count($result) > 0){
            $data["template"] = $result[0];
        } else {
            $form_category_id = $this->admin_model->dbSelect("id","form_categories"," tag='is_system' AND school_id='$school_id' AND deleted_at IS NULL ")[0]->id;
            $std_evaluation_form_arabic = array("name"=>"Student Evaluation Form Arabic","is_custom"=>"Yes","tag"=>"student_evaluation_card","html"=> system_student_evaluation_template_arabic(),"school_id"=>$school_id,"language_id"=>2,"form_category_id"=>$form_category_id);
            $std_evaluation_form_english = array("name"=>"Student Evaluation Form English","is_custom"=>"Yes","tag"=>"student_evaluation_card","html"=> system_student_evaluation_template_english(),"school_id"=>$school_id,"language_id"=>1,"form_category_id"=>$form_category_id);
            $inserted_id_arabic = $this->admin_model->dbInsert("templates",$std_evaluation_form_arabic);
            $inserted_id_english = $this->admin_model->dbInsert("templates",$std_evaluation_form_english);
            if($this->session->userdata("userdata")["language"] == "english"){
                $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$inserted_id_english'";
            } else if($this->session->userdata("userdata")["language"] == "arabic"){
                $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$inserted_id_arabic'";
            }
            $data["template"] = $this->admin_model->dbQuery($sql)[0];
        }
        // insert new template in sh_template it not exists

        $grades =  array( array("stars" => 1, "legend" => "Weak"),
            array("stars" => 2, "legend" => "Fair"),
            array("stars" => 3, "legend" => "Good"),
            array("stars" => 4, "legend" => "Excellent"),
            array("stars" => 5, "legend" => "Exceptional"));

        // $sql = "SELECT u.id,u.subject_group_id as group_id,u.avatar as student_avatar, u.name, u.rollno,u.class_id, u.batch_id from sh_students_".$school_id." u WHERE u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $class_id . " AND u.batch_id=" . $batch_id . " AND u.deleted_at=0 AND u.school_id=" . $school_id . " ";

        $sql = "SELECT 
                scr.student_id as id,
                scr.subject_group_id as group_id,
                u.avatar as student_avatar, 
                u.name, u.rollno,scr.class_id, 
                scr.batch_id 
                from sh_student_class_relation scr
                left join sh_users u on u.id=scr.student_id 
                WHERE u.role_id=" . STUDENT_ROLE_ID . " 
                AND scr.class_id=" . $class_id . " 
                AND scr.batch_id=" . $batch_id . " 
                AND scr.deleted_at is NULL 
                AND u.school_id=" . $school_id . " ";

        $students = $this->admin_model->dbQuery($sql);
        
        if($evaluation_id == "all"){
            $evaluation1 = $this->db->select('id')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where('type', 'subject')->where('term_id', $term_id)->where('deleted_at is null')->get()->row()->id;
            $evaluation2 = $this->db->select('id')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where('type', 'non-subject')->where('term_id', $term_id)->where('deleted_at is null')->get()->row()->id;
            $allsubjects1 = $this->db->select('id,name')->from('sh_subjects')->where('batch_id', $batch_id)->get()->result();
            $type1 = "subject";
            $type2 = "non-subject";
            $allsubjects2 = array();
            $dummy_subject = new stdClass();
            $dummy_subject->id = 0;
            $dummy_subject->name = lang('lbl_evaluation');
            $allsubjects2[] = $dummy_subject;
            $reports = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation1)->get()->result();
            $reports2 = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation2)->get()->result();
            $categories = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation1)->where("deleted_at is null")->get()->result();
            $categories2 = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation2)->where("deleted_at is null")->get()->result();
            if($categories){
                $temp = new stdClass();
                $temp->id = "";
                $temp->db = "false";
                $temp->category_name = "Summary of Evaluation";
                $categories[] = $temp;
            }

        
                $temp = new stdClass();
                $temp->id = "";
                $temp->db = "false";
                $temp->category_name = "Summary of Evaluation";
                $categories2[] = $temp;

            foreach ($students as $sss) {
                $his_subjects = 0;
                $his_subjects2 = 0;
                $avg_total = 0;
                $avg_total2 = 0;
                $sss->activities = $categories;
                $sss->activities2 = $categories2;
                $evaluations = array();
                $evaluations2 = array();
                foreach ($allsubjects1 as $sub) {
                    $single_subject = new stdClass();
                    $single_subject->report = array();
                    if ($sss->group_id != null || !empty($sss->group_id)) {
                        $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                        $subjects_array = explode(",", $subjects);
                        if (in_array($sub->id, $subjects_array)) {
                            $single_subject->is_read = 'true';
                        } else {
                            $single_subject->is_read = 'false';
                        }
                    } else if ($sss->group_id == null || empty($sss->group_id)) {
                        $single_subject->is_read = 'true';
                    }
                    if($single_subject->is_read == 'true'){
                        $total = 0;
                        $his_subjects++;
                        foreach ($categories as $cat) {
                            if($cat->db == "true"){
                                $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports, $sss->id, $cat->id, $sub->id);
                                $total += $temp1;
                            }else{
                                $average_for_subject = round($total/(count($categories) - 1 ));
                                $single_subject->report[] = $average_for_subject;
                                $avg_total += $average_for_subject;
                            }
                        }
                    }
                    $evaluations[] = $single_subject;
                }

                foreach ($allsubjects2 as $sub) {
                    $single_subject = new stdClass();
                    $single_subject->report = array();
                    $single_subject->is_read = 'true';
                    $total = 0;
                    $his_subjects2++;
                    foreach ($categories2 as $cat) {
                        if($cat->db == "true"){

                            $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports2, $sss->id, $cat->id, $sub->id);
                            $total += $temp1;
                        }else{
                            $average_for_subject = 0;
                            if(count($categories2) > 1){
                                $average_for_subject = round($total/(count($categories2) - 1 ));
                            }
                            $single_subject->report[] = $average_for_subject;
                            $avg_total2 += $total;
                        }
                    }
                    $evaluations2[] = $single_subject;
                }
                
            $overall_categories = count($categories2) - 1;
            
                $sss->evaluations = $evaluations;
                $sss->evaluations2 = $evaluations2;
                $f_avg_number = 0;
                $f_avg = 0;
                $f_grade = 0;
                if($his_subjects == 0){
                    $sss->final_avg_number = 0;
                    $sss->final_avg = 0;
                    $sss->grade = "";
                }else{
                    $sss->final_avg_number = round($avg_total/$his_subjects,2);
                    $f_avg_number += $avg_total/$his_subjects;
                    $sss->final_avg = round($avg_total/$his_subjects);
                    $f_avg += $avg_total/$his_subjects;
                    $sss->legend = $this->getLegend($grades, $sss->final_avg);
                }

            if($overall_categories == 0){
                    $sss->final_avg_number2 = 0;
                    $sss->final_avg2 = 0;
                    $sss->grade2 = "";
                }else{
                $sss->final_avg_number2 = $avg_total2/$overall_categories;
                $f_avg_number += $avg_total2/$overall_categories;
                $sss->final_avg2 = round($avg_total2/$overall_categories,2);
                $f_avg += $avg_total2/$overall_categories;
                    $sss->legend2 = $this->getLegend($grades, $sss->final_avg2);
                }
                $f_grade = $this->getLegend($grades, round($f_avg/2));
                $sss->f_avg_number = $f_avg_number/2;
                $sss->f_avg = round($f_avg/2);
                $sss->f_grade = $f_grade;
            }
            $span1 = count($categories);
            if($span1 == 0) { $span1 = 1; }
            $span2 = count($categories2);
            if($span2 == 0) { $span2 = 1; }

            $overall_span = count($categories);
            if($overall_span == 0){
                $overall_span = 1;
            }else{
                $overall_span -= 1;
            }

            $overall_span2 = count($categories2);
            if($overall_span2 == 0){
                $overall_span2 = 1;
            }else{
                $overall_span2 -= 1;
            }
            $non_subject_span = count($allsubjects1) + 2;
            $data["students"] = $students;
            $data["subjects"] = $allsubjects1;
            $data["subjects2"] = $allsubjects2;
            $data["evaluation"] = lang('lbl_all');
            $data["span"] = $span1;
            $data["name_span"] = $span1 + 1;
            $data["span2"] = $span2;
            $data["non_subject_span"] = $non_subject_span;
            $data["overall_span"] = $overall_span;
            $data["overall_span2"] = $overall_span2;
            $data["all_evaluation"] = true;
        }else{
            $activities2 = array();
            $evaluations2 = array();
            $evaluation = $this->db->select('evaluation_name')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->evaluation_name;
            $type = $this->db->select('type')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->type;
            if($type == 'subject'){
                $allsubjects = $this->db->select('id,name')->from('sh_subjects')->where('batch_id', $batch_id)->get()->result();
            }else{
                $allsubjects = array();
                $dummy_subject = new stdClass();
                $dummy_subject->id = 0;
                $dummy_subject->name = lang('lbl_evaluation');
                $allsubjects[] = $dummy_subject;
            }
            $reports = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation_id)->get()->result();
            $categories = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation_id)->where("deleted_at is null")->get()->result();
            if($categories){
                $temp = new stdClass();
                $temp->id = "";
                $temp->db = "false";
                $temp->category_name = "Summary of Evaluation";
                $categories[] = $temp;
            }

            foreach ($students as $sss) {
                $his_subjects = 0;
                $avg_total = 0;
                $sss->activities = $categories;
                $sss->activities2 = $activities2;
                $evaluations = array();
                foreach ($allsubjects as $sub) {
                    $single_subject = new stdClass();
                    $single_subject->report = array();
                    if($type == 'subject'){
                        if ($sss->group_id != null || !empty($sss->group_id)) {
                            $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                            $subjects_array = explode(",", $subjects);
                            if (in_array($sub->id, $subjects_array)) {
                                $single_subject->is_read = 'true';
                            } else {
                                $single_subject->is_read = 'false';
                            }
                        } else if ($sss->group_id == null || empty($sss->group_id)) {
                            $single_subject->is_read = 'true';
                        }
                    }else{
                        $single_subject->is_read = 'true';
                    }

                    if($single_subject->is_read == 'true'){
                        $total = 0;
                        $his_subjects++;
                        foreach ($categories as $cat) {

                            if($cat->db == "true"){
                                $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports, $sss->id, $cat->id, $sub->id);
                                $total += $temp1;
                            }else{
                                $average_for_subject = round($total/(count($categories) - 1 ));
                                $single_subject->report[] = $average_for_subject;
                                $avg_total += $average_for_subject;
                            }

                        }
                    }
                    $evaluations[] = $single_subject;
                }

                if($type == "subject"){
                    $sss->evaluations = $evaluations;
                }else{
                    $temp_evaluation = new stdClass();
                    $temp_evaluation->report = [0,0];
                    $sss->evaluations = [$temp_evaluation];
                    $sss->activities = array();
                    $sss->activities2 = $categories;
                    $sss->evaluations2 = $evaluations;
                }
                if($his_subjects == 0){
                    $sss->final_avg_number = 0;
                    $sss->final_avg = 0;
                    $sss->grade = "";
                    $sss->final_avg_number2 = "";
                    $sss->final_avg2 = "";
                    $sss->legend2 = "";
                    $sss->f_avg_number = 0;
                    $sss->f_avg = 0;
                    $sss->f_grade = "";
                }else{
                    if($type == "subject"){
                        $sss->final_avg_number = round($avg_total/$his_subjects,2);
                        $sss->final_avg = round($avg_total/$his_subjects);
                        $sss->legend = $this->getLegend($grades, $sss->final_avg);
                        $sss->final_avg_number2 = "";
                        $sss->final_avg2 = "";
                        $sss->legend2 = "";
                        $sss->f_avg_number = $sss->final_avg_number;
                        $sss->f_avg = $sss->final_avg;
                        $sss->f_grade = $sss->legend;
                    }else{
                        $sss->final_avg_number = "";
                        $sss->final_avg = "";
                        $sss->legend = "";
                        $sss->final_avg_number2 = round($avg_total/$his_subjects,2);
                        $sss->final_avg2 = round($avg_total/$his_subjects);
                        $sss->legend2 = $this->getLegend($grades, $sss->final_avg);
                        $sss->f_avg_number = $sss->final_avg_number;
                        $sss->f_avg = $sss->final_avg;
                        $sss->f_grade = $sss->legend;
                    }
                    
                }
            }
            $span = count($categories);
            if($span == 0) { $span = 1; }

            $overall_span = count($categories);
            if($overall_span == 0){
                $overall_span = 1;
            }else{
                $overall_span -= 1;
            }
            $data["students"] = $students;
            $data["evaluation"] = $evaluation;
            $data["subjects"] = $allsubjects;
            $data["span"] = $span;
            $data["overall_span"] = $overall_span;
            $data["all_evaluation"] = false;
        }
        foreach($data["students"] as $std){
            $std->class_name = $class_name;
            $std->batch_name = $batch_name;
        }

        

        
        
        $data = render_student_evaluation_card_tags($data, $data["template"], $user_selected_lang);
        echo json_encode($data);
    }

    public function getStarsSubjectWise($reports, $student_id, $category_id, $subject_id){
        $stars = 0;
        foreach($reports as $rep) {
            if ($rep->student_id == $student_id && $rep->category_id == $category_id && $rep->subject_id == $subject_id) {
                $stars = $rep->stars;
                break;
            }
        }
        return $stars;
    }

    public function getLegend($grades, $star) {
        foreach ($grades as $key => $val) {
            if ($val['stars'] == $star) {
                return $val['legend'];
            }
        }
        return "";
    }
    
    public function print_result_cards_forParent(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student_id = $request->student_id;

        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

        $exam_id = $request->exam_id;
        
        
        $selected_language_id = 1;
        $language_where = " language_id=1 ";
        if($this->session->userdata("site_lang") == "urdu" || $this->session->userdata("site_lang") == "arabic") {
            $language_where = " language_id=2 ";
            $selected_language_id = 2;
        }
        
        $sq = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.tag='result_card' AND temp.school_id='$school_id' AND".$language_where;
        $data["template"] = $this->admin_model->dbQuery($sq)[0];
        
        $result_cards = array();
        $exam_detail_ids = array();
        $exam_details = $this->admin_model->dbSelect("*","exam_details"," exam_id=$exam_id AND class_id=$class_id AND batch_id=$batch_id AND deleted_at IS NULL ");
        foreach($exam_details as $e){
            array_push($exam_detail_ids, $e->id);
        }
        
            // update query for activites sheraz
            $sql = "SELECT "
                . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
                . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
                . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.total_obtained_marks, m.obtained_marks, m.total_grade, m.status, m.remarks, m.activities, "
                . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type, d.total_exam_marks as total_written_marks "
                . "FROM sh_marksheets m "
                . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
                . "INNER JOIN sh_users u ON m.student_id=u.id "
                . "INNER JOIN sh_classes c ON m.class_id=c.id "
                . "INNER JOIN sh_batches b ON m.batch_id=b.id "
                . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
                . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
                . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
                . "WHERE m.student_id='$student_id' "
                . "AND m.exam_detail_id IN (". implode(",", $exam_detail_ids).") "
                . "AND m.class_id='$class_id' "
                . "AND m.school_id='$school_id' "
                . "AND m.batch_id='$batch_id' "
                . "AND m.deleted_at IS NULL "
                . "AND rp.exam_id='$exam_id' ";
            $res = $this->admin_model->dbQuery($sql);
            $info = array();
            if(count($res) > 0){
                $sql22 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE relation='father' AND student_id=".$student_id;
                $res22 = $this->admin_model->dbQuery($sql22);
                $guardian_name = NULL;
                if(count($res22)>0){
                    $guardian_name = $res22[0]->guardian_name;
                } 
                $info["student_id"] = $res[0]->student_id;
                $info["student_name"] = $res[0]->student_name;
                $info["father_name"] = $guardian_name;
                $info["rollno"] = $res[0]->rollno;
                $info["mobile_phone"] = $res[0]->mobile_phone;
                $info["avatar"] = $res[0]->avatar;
                $info["class_id"] = $res[0]->class_id;
                $info["batch_id"] = $res[0]->batch_id;
                $info["class_name"] = $res[0]->class_name;
                $info["section_name"] = $res[0]->section_name;
                $info["exam_name"] = $res[0]->exam_name;
                $info["exam_id"] = $res[0]->exam_id;
                $info["position"] = $res[0]->position;
                $info["class_teacher_remarks"] = $res[0]->teacher_remark;
                $info["academic_year_id"] = $request->academic_year_id;
                $info["details"] = array();
                foreach($res as $r){
                    $ar = (object) array();
                    $ar->subjnect_id = $r->subject_id;
                    $ar->subjnect_name = $r->subject_name;
                    // update for activities sheraz
                    $ar->total_obtained_marks = $r->total_obtained_marks;
                    $ar->obtained_marks = $r->obtained_marks;
                    $ar->grade = $r->total_grade;
                    $ar->status = $r->status;
                    $ar->remarks = $r->remarks;
                    $ar->exam_date = $r->exam_date;
                    $ar->start_time = $r->start_time;
                    $ar->end_time = $r->end_time;
                    $ar->total_marks = $r->total_marks;
                    $ar->passing_marks = $r->passing_marks;
                    $ar->type = $r->type;
                     // update for activities sheraz
                    if ($r->total_written_marks == 0 ) {
                        $ar->total_written_marks = $r->total_marks;
                    } else {
                        $ar->total_written_marks = $r->total_written_marks;
                    }
                    $ar->activities = json_decode($r->activities);
                    array_push($info["details"], $ar);
                }
            }
            $data["data"] = $info;
            if(count($data["data"]) > 0) { 
                $rendered_form_html = render_resultcard_tags($data, $data["template"]);
                array_push($result_cards, $rendered_form_html);
            }
        
        echo json_encode($result_cards);
    }

    public function restore(){
        $id = $this->input->post("id");
        $record = $this->admin_model->dbSelect("*","templates"," id='$id' ")[0];
        $html = null;
        if($record->tag == 'single_fee' && $record->language_id == 1) {
            $html = system_fee_template();
        }
        else if($record->tag == 'single_fee' && $record->language_id == 2) {
            $html = system_fee_template_arabic();
        }
        else if($record->tag == 'result_card' && $record->language_id == 1) {
            $html = system_result_card_template();
        }
        else if($record->tag == 'result_card' && $record->language_id == 2) {
            $html = system_result_card_template_arabic();
        } else if($record->tag == 'payroll_card' && $record->language_id == 1) {
            $html = system_payroll_template_english();
        } else if($record->tag == 'payroll_card' && $record->language_id == 2) {
            $html = system_payroll_template_arabic();
        } else if($record->tag == 'student_evaluation_card' && $record->language_id == 1){
            $html = system_student_evaluation_template_english();
        } else if($record->tag == 'student_evaluation_card' && $record->language_id == 2){
            $html = system_student_evaluation_template_arabic();
        }
        $res = $this->common_model->update_where("sh_templates", array("id"=>$id,"language_id"=>$record->language_id), array("html"=>$html));
        if($res){
            $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('lbl_form_restore_successfully')));
            echo "success";
        }
    }
}