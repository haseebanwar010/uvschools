<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    header('Access-Control-Allow-Origin: *');
    

class mobileapi extends CI_Controller {
    
     
    
    public function showapi(){
        $id = $_GET["id"];
        $page = $_GET["requested_page"];
        // $school_id = $this->session->userdata("userdata")["sh_id"];
        $school_id = $_GET["school_id"];
        $logged_in_user = $_GET["id"];
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
        // if($this->session->userdata("site_lang") != "english") {
        //     $language_where = " language_id=2 ";
        //     $selected_language_id = 2;
        // }
        
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
            
            // $sql = "SELECT "
            //     . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
            //     . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
            //     . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.obtained_marks, m.grade, m.status, m.remarks, "
            //     . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type "
            //     . "FROM sh_marksheets m "
            //     . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
            //     . "INNER JOIN sh_users u ON m.student_id=u.id "
            //     . "INNER JOIN sh_classes c ON m.class_id=c.id "
            //     . "INNER JOIN sh_batches b ON m.batch_id=b.id "
            //     . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
            //     . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
            //     . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
            //     . "WHERE m.student_id='$id' "
            //     . "AND m.exam_detail_id IN (".rtrim($exam_detail_ids, ",").") "
            //     . "AND m.class_id='$class_id' "
            //     . "AND m.school_id='$school_id' "
            //     . "AND m.batch_id='$batch_id' "
            //     . "AND s.id IN (".$std_reading_subjects.") "
            //     . "AND m.deleted_at IS NULL "
            //     . "AND rp.exam_id='$exam_id' ";       
            
            // $sql = "SELECT "
            //     . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
            //     . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
            //     . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.total_obtained_marks, m.obtained_marks, m.total_grade, m.status, m.remarks, m.activities,"
            //     . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type, d.total_exam_marks as total_written_marks"
            //     . "FROM sh_marksheets m "
            //     . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
            //     . "INNER JOIN sh_users u ON m.student_id=u.id "
            //     . "INNER JOIN sh_classes c ON m.class_id=c.id "
            //     . "INNER JOIN sh_batches b ON m.batch_id=b.id "
            //     . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
            //     . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
            //     . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
            //     . "WHERE m.student_id='$id' "
            //     . "AND m.exam_detail_id IN (".rtrim($exam_detail_ids, ",").") "
            //     . "AND m.class_id='$class_id' "
            //     . "AND m.school_id='$school_id' "
            //     . "AND m.batch_id='$batch_id' "
            //     . "AND s.id IN (".$std_reading_subjects.") "
            //     . "AND m.deleted_at IS NULL "
            //     . "AND rp.exam_id='$exam_id' ";
            
            
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
                
                // echo '<pre>';
                // print_r($res);
                // die;
                
            $info = array();
            if(count($res) > 0){
                $sql22 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE relation='father' AND student_id=".$res[0]->student_id;
                $res22 = $this->admin_model->dbQuery($sql22);
                $guardian_name = NULL;
                if(count($res22)>0){
                    $guardian_name = $res22[0]->guardian_name;
                }
                
                $school_details_query="select * from sh_school where id=".$school_id;
                $school_details = $this->admin_model->dbQuery($school_details_query);
                $school_details=$school_details[0];
                
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
                $info["school_id"] = $school_details->id;
                $info["school_name"] = $school_details->name;
                $info["school_logo"] = $school_details->logo;
                $info["school_email"] = $school_details->email;
                $info["school_phone"] = $school_details->phone;
                $info["school_address"] = $school_details->address;
                $info["school_url"] = $school_details->url;
                $info["school_country"] = $school_details->country;
                $info["school_city"] = $school_details->city;
                $info["details"] = array();
                foreach($res as $r){
                    $ar = (object) array();
                    $ar->subjnect_id = $r->subject_id;
                    $ar->subjnect_name = $r->subject_name;
                    $ar->total_obtained_marks = $r->total_obtained_marks;
                    $ar->obtained_marks = $r->obtained_marks;
                    $ar->type = $r->type;
                    $ar->grade = $r->total_grade;
                    $ar->status = $r->status;
                    $ar->remarks = $r->remarks;
                    $ar->exam_date = $r->exam_date;
                    $ar->start_time = $r->start_time;
                    $ar->end_time = $r->end_time;
                    $ar->total_marks = $r->total_marks;
                    $ar->passing_marks = $r->passing_marks;
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
            
            // echo '<pre>';
            // print_r($info);
            // die;
            
            $rendered_form_html = render_resultcard_tags_api($data, $data["template"]);
            
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
       
    


}