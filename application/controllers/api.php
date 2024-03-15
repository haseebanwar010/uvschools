<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');   
    

class Api extends CI_Controller {
    
     
    
    // public function showapi(){
    //     $id = $_GET["id"];
    //     $page = $_GET["requested_page"];
    //     // $school_id = $this->session->userdata("userdata")["sh_id"];
    //     $school_id = $_GET["school_id"];
    //     $logged_in_user = $_GET["id"];
    //     $languages = $this->admin_model->dbSelect("*","language"," 1 ");
    //     $class_id = 0;
    //     $exam_id = 0;
    //     $batch_id = 0;
    //     $student_id = 0;
    //     $employee_id = 0;
    //     $salary_type_id = 0;
    //     $data = array();
        
    //     $selected_language_id = 1;
    //     $language_where = " language_id=1 ";
    //     // if($this->session->userdata("site_lang") != "english") {
    //     //     $language_where = " language_id=2 ";
    //     //     $selected_language_id = 2;
    //     // }
        
    //     $data["request_page"] = $page; 
    //     if($page == 'forms'){
    //         $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$id' ";
    //         $data["template"] = $this->admin_model->dbQuery($sql)[0];
    //         $rendered_form_html = render_universal_tags($data["template"],$data["template"]->html);
    //         $data["html"] =  $rendered_form_html;
    //     } else if($page == 'single_fee'){
    //         $sql = "SELECT "
    //             . "col.*, "
    //             . "std.name as student_name, "
    //             . "std.mobile_phone as mobile_phone, "
    //             . "std.avatar as avatar, "
    //             . "std.rollno as rollno, "
    //             . "ftype.name as ftype, "
    //             . "ftype.amount as amount, "
    //             . "dis.name as discount_type, "
    //             . "dis.amount as discount_percentage, "
    //             . "thr.name as collector_name "
    //             . "FROM sh_fee_collection col "
    //             . "INNER JOIN sh_users std ON col.student_id=std.id "
    //             . "INNER JOIN sh_fee_types ftype ON col.feetype_id=ftype.id "
    //             . "INNER JOIN sh_users thr ON col.collector_id=thr.id "
    //             . "LEFT JOIN sh_student_class_relation cr ON cr.academic_year_id = ftype.academic_year_id and cr.student_id = std.id "
    //             . "LEFT JOIN sh_fee_discount dis ON  cr.discount_id = dis.id "
    //             . "WHERE col.id='$id' AND col.deleted_at IS NULL ";

    //         $data['data'] = $this->admin_model->dbQuery($sql)[0];
    //         $data['data']->class_name = $_GET["class_name"];
    //         $data['data']->batch_name = $_GET["batch_name"];
    //         $data['class_name'] = $_GET["class_name"];
    //         $data['batch_name'] = $_GET["batch_name"];
    //         $sql2 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE student_id=".$data["data"]->student_id;
    //         $res = $this->admin_model->dbQuery($sql2);
    //         if(count($res)>0){
    //             $data['data']->guardian_name = $res[0]->guardian_name;
    //         } else {
    //             $data['data']->guardian_name = NULL;
    //         }
            
    //         $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp "
    //             . "INNER JOIN "
    //             . "sh_form_categories cat ON temp.form_category_id=cat.id "
    //             . "WHERE "
    //             . "temp.tag='single_fee' "
    //             . "AND "
    //             . "temp.school_id='$school_id' "
    //         . "AND".$language_where;

    //         $data["template"] = $this->admin_model->dbQuery($sql)[0];
    //         $rendered_form_html = render_fee_tags($data,$data["template"], $selected_language_id);
    //         $data["html"] =  $rendered_form_html;
    //     } else if($page == "result_card_old"){
    //         $dd = json_decode($_GET["data"]);
    //         $rendered_form_html = render_resultcard($dd);
    //         $data = array('template'=>'','html'=>$rendered_form_html);
    //     } else if($page == "result_card"){
            
    //         $student_id = $_GET["id"];
    //         $selected_academic_year_id = $_GET["academic_year_id"];
    //         $class_id = isset($_GET["class_id"])?$_GET["class_id"]:NULL;
    //         $exam_id = isset($_GET["exam_id"])?$_GET["exam_id"]:NULL;
    //         $batch_id = isset($_GET["batch_id"])?$_GET["batch_id"]:NULL;
    //         $sq = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.tag='result_card' AND temp.school_id='$school_id' AND".$language_where;
    //         $r = $this->admin_model->dbQuery($sq);

    //         if(count($r) > 0){
    //             $data["template"] = $r[0];
    //         } else {
    //             $form_category_id = $this->admin_model->dbSelect("id","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag='is_system' ")[0]->id;
    //             $d = array("name"=>"Result Card English","is_custom"=>"Yes","language_id"=>1,"tag"=>"result_card","html"=> system_result_card_template(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
                
                
                
    //             $dd = array("name"=>"Result Card Arabic","is_custom"=>"Yes","language_id"=>2,"tag"=>"result_card","html"=> system_result_card_template_arabic(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
    //             $inserted_id = $this->common_model->insert("sh_templates",$d);
    //             $inserted_id2 = $this->common_model->insert("sh_templates",$dd);
    //             $selected_lang_tempate_inserted_id = $inserted_id;
    //             if($this->session->userdata("site_lang") != "english") {
    //                 $selected_lang_tempate_inserted_id = $inserted_id2;
    //             }
    //             $data["template"] = $this->admin_model->dbSelect("*","templates"," id='$selected_lang_tempate_inserted_id' ")[0];
    //         }

    //         $exam_detail_ids = "";
    //         $exam_details = $this->admin_model->dbSelect("*","exam_details"," exam_id='$exam_id' AND class_id='$class_id' AND batch_id='$batch_id' AND deleted_at IS NULL ");
    //         foreach($exam_details as $e){
    //             $exam_detail_ids .= $e->id . ",";
    //         }

    //         // old query to fetch subject group id
    //         //$subjectgroupid = $this->admin_model->dbSelect("subject_group_id","students_".$school_id," id='$student_id' ");
            
    //         // new query to fetch subject group id
    //         $subjectgroupid = $this->admin_model->dbSelect("*","student_class_relation", " student_id='$student_id' AND academic_year_id='$selected_academic_year_id' ");
    //         $std_reading_subjects = 0;
    //         if(count($subjectgroupid) > 0){
    //             $subjectgroupid = $subjectgroupid[0]->subject_group_id;
    //             $subjectss = $this->admin_model->dbSelect("subjects","subject_groups"," id='$subjectgroupid' ");
    //             if(count($subjectss) > 0){
    //                 $std_reading_subjects = $subjectss[0]->subjects;
    //             }
    //         }
            
    //         $sql = "SELECT "
    //             . "u.id as student_id, u.name as student_name, u.rollno, u.mobile_phone, u.avatar, "
    //             . "c.id as class_id, c.name as class_name, b.id as batch_id, b.name as section_name, "
    //             . "e.title as exam_name,e.id as exam_id,s.id as subject_id, s.name as subject_name, m.obtained_marks, m.grade, m.status, m.remarks, "
    //             . "rp.remark as teacher_remark,rp.position, d.exam_date, d.start_time, d.end_time, d.total_marks, d.passing_marks, d.type "
    //             . "FROM sh_marksheets m "
    //             . "INNER JOIN sh_remarks_and_positions rp ON m.student_id=rp.student_id "
    //             . "INNER JOIN sh_users u ON m.student_id=u.id "
    //             . "INNER JOIN sh_classes c ON m.class_id=c.id "
    //             . "INNER JOIN sh_batches b ON m.batch_id=b.id "
    //             . "INNER JOIN sh_exam_details d ON m.exam_detail_id=d.id "
    //             . "RIGHT JOIN sh_exams e ON d.exam_id=e.id "
    //             . "INNER JOIN sh_subjects s ON d.subject_id=s.id "
    //             . "WHERE m.student_id='$id' "
    //             . "AND m.exam_detail_id IN (".rtrim($exam_detail_ids, ",").") "
    //             . "AND m.class_id='$class_id' "
    //             . "AND m.school_id='$school_id' "
    //             . "AND m.batch_id='$batch_id' "
    //             . "AND s.id IN (".$std_reading_subjects.") "
    //             . "AND m.deleted_at IS NULL "
    //             . "AND rp.exam_id='$exam_id' ";
            
    //             $res = $this->admin_model->dbQuery($sql);
    //         $info = array();
    //         if(count($res) > 0){
    //             $sql22 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE relation='father' AND student_id=".$res[0]->student_id;
    //             $res22 = $this->admin_model->dbQuery($sql22);
    //             $guardian_name = NULL;
    //             if(count($res22)>0){
    //                 $guardian_name = $res22[0]->guardian_name;
    //             }
                
    //             $school_details_query="select * from sh_school where id=".$school_id;
    //             $school_details = $this->admin_model->dbQuery($school_details_query);
    //             $school_details=$school_details[0];
                
    //             $info["student_id"] = $res[0]->student_id;
    //             $info["student_name"] = $res[0]->student_name;
    //             $info["father_name"] = $guardian_name;
    //             $info["rollno"] = $res[0]->rollno;
    //             $info["mobile_phone"] = $res[0]->mobile_phone;
    //             $info["avatar"] = $res[0]->avatar;
    //             $info["class_id"] = $res[0]->class_id;
    //             $info["batch_id"] = $res[0]->batch_id;
    //             $info["class_name"] = $res[0]->class_name;
    //             $info["section_name"] = $res[0]->section_name;
    //             $info["exam_name"] = $res[0]->exam_name;
    //             $info["exam_id"] = $res[0]->exam_id;
    //             $info["position"] = $res[0]->position;
    //             $info["class_teacher_remarks"] = $res[0]->teacher_remark;
    //             $info["academic_year_id"] = $selected_academic_year_id;
    //             $info["school_id"] = $school_details->id;
    //             $info["school_name"] = $school_details->name;
    //             $info["school_logo"] = $school_details->logo;
    //             $info["school_email"] = $school_details->email;
    //             $info["school_phone"] = $school_details->phone;
    //             $info["school_address"] = $school_details->address;
    //             $info["school_url"] = $school_details->url;
    //             $info["school_country"] = $school_details->country;
    //             $info["school_city"] = $school_details->city;
    //             $info["details"] = array();
    //             foreach($res as $r){
    //                 $ar = (object) array();
    //                 $ar->subjnect_id = $r->subject_id;
    //                 $ar->subjnect_name = $r->subject_name;
    //                 $ar->obtained_marks = $r->obtained_marks;
    //                 $ar->type = $r->type;
    //                 $ar->grade = $r->grade;
    //                 $ar->status = $r->status;
    //                 $ar->remarks = $r->remarks;
    //                 $ar->exam_date = $r->exam_date;
    //                 $ar->start_time = $r->start_time;
    //                 $ar->end_time = $r->end_time;
    //                 $ar->total_marks = $r->total_marks;
    //                 $ar->passing_marks = $r->passing_marks;
    //                 array_push($info["details"], $ar);
    //             }
    //         }
    //         $data["data"] = $info;
            
    //         $rendered_form_html = render_resultcard_tags_api($data, $data["template"]);
            
    //         $data["html"] =  $rendered_form_html;
    //     } else if($page == "single_payroll"){ 
    //         $employee_id = $_GET["id"];
    //         $salary_type_id = $_GET["salary_type_id"];
    //         $sql = "SELECT 
    //             p.*, 
    //             sty.name as salary_name,
    //             sty.date as due_date,
    //             sty.amount as salary_amount,
    //             u.name as employee_name,
    //             uu.name as paid_by,
    //             d.name as department_name,
    //             rc.category as category_name  
    //             FROM sh_payroll p 
    //             INNER JOIN sh_salary_types sty ON p.salary_type_id=sty.id 
    //             INNER JOIN sh_users u ON p.user_id=u.id 
    //             INNER JOIN sh_users uu ON p.paid_by=uu.id 
    //             INNER JOIN sh_departments d ON u.department_id=d.id 
    //             INNER JOIN sh_role_categories rc ON u.role_category_id=rc.id 
    //             WHERE 
    //             p.user_id='$employee_id' 
    //             AND p.salary_type_id='$salary_type_id' 
    //             AND p.deleted_at IS NULL ";
    //         $data["data"] = $this->admin_model->dbQuery($sql);

    //         $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp "
    //             . "INNER JOIN "
    //             . "sh_form_categories cat ON temp.form_category_id=cat.id "
    //             . "WHERE "
    //             . "temp.tag='payroll_card' "
    //             . "AND "
    //             . "temp.school_id='$school_id' "
    //         . "AND".$language_where;
    //         $temp_arr = $this->admin_model->dbQuery($sql);
    //         if(count($temp_arr) > 0){
    //             //template exists
    //             $data["template"] = $this->admin_model->dbQuery($sql)[0];
    //         } else {
    //             //template does not exists
    //             $form_category_id = $this->admin_model->dbSelect("id","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag='is_system' ")[0]->id;
    //             $d = array("name"=>"Payroll English","is_custom"=>"Yes","language_id"=>1,"tag"=>"payroll_card","html"=> system_payroll_template_english(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
    //             $dd = array("name"=>"Payroll Arabic","is_custom"=>"Yes","language_id"=>2,"tag"=>"payroll_card","html"=> system_payroll_template_arabic(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
    //             $inserted_id = $this->common_model->insert("sh_templates",$d);
    //             $inserted_id2 = $this->common_model->insert("sh_templates",$dd);
    //             $selected_lang_tempate_inserted_id = $inserted_id;
    //             if($this->session->userdata("site_lang") != "english") {
    //                 $selected_lang_tempate_inserted_id = $inserted_id2;
    //             }
    //             $data["template"] = $this->admin_model->dbQuery("SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$selected_lang_tempate_inserted_id' ")[0];
    //         }
    //         $rendered_form_html = render_payroll_tags($data["data"],$data["template"], $selected_language_id);
    //         $data["html"] =  $rendered_form_html;
    //     }
        
    //     $data["fee_get_id_from_url"] = $id;
    //     $data["student_id"] = $student_id;
    //     $data["employee_id"] = $employee_id;
    //     $data["salary_type_id"] = $salary_type_id;
    //     $data["class_id"] = $class_id;
    //     $data["exam_id"] = $exam_id;
    //     $data["batch_id"] = $batch_id;
    //     $data["languages"] = $languages;
    //     $data["is_print_all"] = isset($_GET["is_print_all"])?"true":"false";
        
    //     //echo "<pre/>"; print_r($data); die();
    //     $this->load->view("forms/show",$data);
    // }
       
    
    
    public function auth(){
        $request = $this->input->post();
        $postdata = json_decode($request['postdata']);
        $email = $postdata->email;
        $password = $postdata->password;
        $response = array();

        if($password == "default"){
            $data = $this->common_model->admin_login2_2($email, $password);
            if(count($data) > 0){
                //reset password query
                $response = array("status"=>"reset","message"=>"Reset your password!","data"=>array("user_id" => $data[0]->user_id));
            } else {
                //error message
                $response = array("status"=>"error","message"=>lang("lbl_invalid_user_or_password"),"data"=>array());
            }
        } else {
            // authenticate userSendCode
            $data = $this->common_model->admin_login2_2($email, $password);
            if(count($data) > 0){
                $data = $data[0];
                $userdata = array(
                    "user_id" => $data->user_id,
                    "name" => $data->name,
                    "email" => $data->email,
                    "contact" => $data->contact,
                   // "language" => $data->language,
                    "avatar" => $data->avatar,
                    //"email_verified" => $data->email_verified,
                    //"status" => $data->status,
                   //"role_id" => $data->role_id,
                    //"role_name" => $data->role_name,
                    //"deleted_at" => $data->deleted_at,
                    //"created_at" => $data->created_at,
                    //"updated_at" => $data->updated_at,
                    //"token" => $data->token,
                    //"sh_id" => $data->school_id,
                    //"sh_url" => $data->sh_url,
                    "sh_name" => $data->sh_name,
                    "sh_logo" => $data->sh_logo,
                    "sh_address" => $data->sh_address,
                    "sh_phone" => $data->sh_phone,
                    //"licence_type" => $data->licence_type,
                    //"start_date" => encrypt($data->start_date),
                    //"end_date" => encrypt($data->end_date),
                    //"licence_type" => $data->licence_type,
                    //"teacher_dept_id" => $data->teacher_dept_id,
                    //"currency_symbol" => $data->currency_symbol,
                    //"theme_color" => $data->theme_color,
                    //"persissions" => $data->permissions,
                    //"time_zone" => $data->time_zone,
                    //"academic_year" => $data->academic_year,
                    //"academic_year_name" => $data->academic_year_name,
                    //"side_bar" => true
                );
                $response = array("status"=>"success","message"=>"data found!","data"=>$userdata);
            } else {
                $response = array("status"=>"error","message"=>lang("lbl_invalid_user_or_password"),"data"=>array());
            }
        }
        echo json_encode($response);
    }

    public function auth_old(){
        
        $request = $this->input->post();
        $postdata = json_decode($request['postdata']);
        
        $email = $postdata->email;
        $password = $postdata->password;
        
        $response = array();

        $data = $this->common_model->admin_login2_2($email, $password);
        if (count($data) > 0) {
            $data = $data[0];
            if ($data->email_verified != 'Y') {
                $response = array("status"=>"error","message"=>lang("lbl_verify_email"),"data"=>array());
            } else {
                if ($data->deleted_at == 1) {
                    $response = array("status"=>"error","message"=>lang("lbl_user_deleted"),"data"=>array());
                } else {
                    $valid = true;
                    //$valid = $this->licenceCalcultor($data->start_date, $data->end_date);
                    if ($valid) {
                        $userdata = array(
                            "user_id" => $data->user_id,
                            "name" => $data->name,
                            "email" => $data->email,
                            "contact" => $data->contact,
                            "language" => $data->language,
                            "avatar" => $data->avatar,
                            "email_verified" => $data->email_verified,
                            "status" => $data->status,
                            "role_id" => $data->role_id,
                            "role_name" => $data->role_name,
                            "deleted_at" => $data->deleted_at,
                            "created_at" => $data->created_at,
                            "updated_at" => $data->updated_at,
                            "token" => $data->token,
                            "sh_id" => $data->school_id,
                            "sh_url" => $data->sh_url,
                            "sh_name" => $data->sh_name,
                            "sh_logo" => $data->sh_logo,
                            "sh_address" => $data->sh_address,
                            "sh_phone" => $data->sh_phone,
                            "licence_type" => $data->licence_type,
                            "start_date" => encrypt($data->start_date),
                            "end_date" => encrypt($data->end_date),
                            "licence_type" => $data->licence_type,
                            "teacher_dept_id" => $data->teacher_dept_id,
                            "currency_symbol" => $data->currency_symbol,
                            "theme_color" => $data->theme_color,
                            "persissions" => $data->permissions,
                            "time_zone" => $data->time_zone,
                            "academic_year" => $data->academic_year,
                            "academic_year_name" => $data->academic_year_name,
                            "side_bar" => true
                        );
                        $response = array("status"=>"success","message"=>lang("lbl_valid_license"),"data"=>$userdata);
                    } else {
                        $response = array("status"=>"error","message"=>lang("lbl_exp_license"),"data"=>array());
                    }
                }
            }
        } else {
            $response = array("status"=>"error","message"=>lang("lbl_invalid_user_or_password"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function set_new_password(){
        $request = $this->input->post();
        $postdata = json_decode($request['postdata']);
        
        $confirm_password = $postdata->confirm_password;
        $password = $postdata->password;
        $user_id = $postdata->user_id;
        
        // $password = $this->input->post("password");
        // $confirm_password = $this->input->post("confirm_password");
        // $user_id = $this->input->post("user_id");
        $response = array();

        if($password == $confirm_password){
            $this->common_model->update_where("sh_users",array("id" => $user_id),array("password" => md5($confirm_password)));
            $response = array("status"=>"success","message"=>lang("pswrd_changed_success"),"data"=>array());
        } else {
            $response = array("status"=>"error","message"=>lang("match_error"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function get_parent_profile(){
        $id = $this->input->get("id");
        $result = $this->admin_model->dbSelect("*","users"," id='$id' AND deleted_at=0 ");
        $response = array();
        if(count($result) > 0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$result[0]);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function update_parent_profile(){
        $user_id = $this->input->post("id");
        $data = $this->input->post("data");
        $response = array();
        if(array_key_exists("email", $data)){
            $response = array("status"=>"error","message"=>lang("api_cannot_change_email_error"),"data"=>array());
        } else {
            if(array_key_exists("password", $data)){
                $response = array("status"=>"error","message"=>lang("api_cannot_change_password_error"),"data"=>array());
            } else {
                if(array_key_exists("avatar", $data)){
                    $uploaded_file_name = explode("uploads/user/",save_image($data["avatar"]))[1];
                    $data["avatar"] = $uploaded_file_name;
                }
                $result = $this->common_model->update_where("sh_users",array("id" => $user_id), $data);
                if($result){
                    $response = array("status"=>"success","message"=>lang("api_update_success"),"data"=>array());
                } else {
                    $response = array("status"=>"error","message"=>lang("api_update_error"),"data"=>array());
                }
            }
        }
        
        echo json_encode($response);
    }

    public function get_parent_children(){
        $id = $this->input->get("id");
       
        $sql = "SELECT DISTINCT
            sg.*, u.name as student_name, u.avatar,
            cr.class_id, c.name as class_name, 
            cr.batch_id, b.name as batch_name 
            FROM `sh_student_guardians` sg 
            INNER JOIN sh_users u ON sg.student_id=u.id 
            LEFT JOIN sh_student_class_relation cr ON sg.student_id=cr.student_id 
            AND cr.academic_year_id=(SELECT id FROM sh_academic_years 
            WHERE sh_academic_years.school_id=u.school_id 
            AND is_active='Y' AND deleted_at IS NULL ) 
            INNER JOIN sh_classes c ON cr.class_id=c.id 
            INNER JOIN sh_batches b ON cr.batch_id=b.id 
            WHERE sg.guardian_id='$id' AND sg.deleted_at is null AND u.deleted_at = 0";
        // $result = $this->admin_model->dbQuery($sql);
        // echo "<pre>";
        // print_r($result);
        // echo "</per>";
        // $user_id = $this->session->userdata("userdata")["user_id"];

        $result = $this->db->select('school_id')->from('sh_users')->where('id', $id)->where('deleted_at = 0')->get()->result_array();
        
        if($result){
            $school_id = $result[0]['school_id'];
            // echo "<pre>";
            // print_r($school_id);
            // echo "</per>";
        }

        $childrens = $this->db->select('sg.*, student_id,s.name as student_name,dob, rollno, status, gender, contact, c.name as class_name,b.name as batch_name,avatar')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_classes c', 's.class_id = c.id')->join('sh_batches b', 's.batch_id = b.id')->where('guardian_id', $id)->get()->result();
        
        $response = array();
        if(count($childrens) > 0){
            $response = array("status"=>"success","message"=>lang("api_update_success"),"data"=>$childrens);
        } else {
            $response = array("status"=>"error","message"=>lang("api_update_error"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function get_student_profile(){
        $student_id = $this->input->get("student_id");
        //print_r($student_id);die();
        $result = $this->admin_model->dbSelect("*","users"," id='$student_id' AND role_id=3 ");
        $response = array();
        //$result = $this->admin_model->dbSelect("*","schools_"," id='$student_id' ");
        //print_r($school_id);die();
        if(count($result) > 0){
            $result = $result[0];
            // if($result->role_id != STUDENT_ROLE_ID){
                // $response = array("status"=>"error","message"=>"This is not student.","data"=>array());    
            // } else {
                //$active_academic_year_id = $this->admin_model->dbSelect("id","academic_years"," school_id='$school_id' AND is_active='Y' AND deleted_at IS NULL ");
                $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$result);    
            
        } else {
            $response = array("status"=>"error","message"=>lang("api_update_error"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function get_student_profile_new(){
        // $request = json_decode($this->input->post("postdata"));
        // $student_id = $request->student_id;
         $student_id = $this->input->get("student_id");
        $response = array();
        $school_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        
        $school_id=0;
        if(count($school_arr)>0){
            $school_id = $school_arr[0]->school_id;
        }
        $sql = "SELECT s.*,c.name as classname, b.name as batchname FROM sh_students_".$school_id." s INNER JOIN sh_classes c ON s.class_id=c.id INNER JOIN sh_batches b ON s.batch_id=b.id WHERE s.id='$student_id'"; 
        $result = $this->admin_model->dbQuery($sql);
        
        if(count($result) > 0){
            $result = $result[0];
            if($result->role_id != STUDENT_ROLE_ID){
                $response = array("status"=>"error","message"=>"This is not student.","data"=>array());    
            } else {
                $result->subjects = array();
                $subjects_group_arr = $this->admin_model->dbSelect("*","subject_groups"," id='$result->subject_group_id' ");
                if(count($subjects_group_arr)>0){
                    $subjects = $subjects_group_arr[0]->subjects;
                    $sql2 =  "SELECT name, code, weekly_classes FROM sh_subjects WHERE id IN ($subjects)";
                    $subjects = $this->admin_model->dbQuery($sql2);
                    $result->subjects = $subjects;
                }
                $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$result);    
            }
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function get_student_attendance() {
        $month=date("m");
        $year = date("Y");
        $response = array();
        if($this->input->get("month") != null){
            $month = $this->input->get("month");
        } 
        
        if($this->input->get("year") != null){
            $year = $this->input->get("year");
        }

        $student_id = $this->input->get("student_id");
        
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $batch_id=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
        }
        
        $from = $year."-".$month."-01";
        $lastday = date('t',strtotime($from));
        $to = $year."-".$month."-".$lastday;
        
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_students_".$school_id." u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . STUDENT_ROLE_ID . " AND t.class_id='$class_id' AND t.batch_id='$batch_id' AND u.id='$student_id' AND t.deleted_at is null GROUP by u.name ";

        $data["att"] = $this->admin_model->dbQuery($sql);
        
        if(count($data["att"]) != 0){
            foreach ($data["att"] as $att) {
                $attends_dates = explode(",", $att->attendance);
                
                $dayss = array();
                $statuss = array();
                $total_present=0;
                $total_absent=0;
                $total_leave=0;
                $total_late=0;

                for ($i = 0; $i < count($attends_dates); $i++) {
                    $date_and_status = explode("=>", $attends_dates[$i]);
                    $date = $date_and_status[0];
                    $status = lang('lbl_'.strtolower($date_and_status[1]));;
                    $date_day = explode("-", $date)[2];
                    
                    switch($date_and_status[1]){
                        case 'Present':
                            $total_present++;
                            break;
                        case "Absent":
                            $total_absent++;
                            break;
                        case "Late":
                            $total_late++;
                            break;
                        case "Leave":
                            $total_leave++;
                            break;
                    }

                    $statuss[$date_day] = $status;
                    array_push($dayss, $date_day);
                }

                $attendace = array();
                $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
                $data["to"] = (new DateTime($to))->format("d");

                for ($ii = $data["from"]; $ii <= $data["to"]; $ii++) {
                    $obj = (object) array();
                    $ii = ($ii < 10 ? '0' . $ii : $ii);
                    $date = $year."-".$month."-".$ii;
                    $day = date('l', strtotime($year."-".$month."-".$ii));
                    if (array_key_exists($ii, $statuss)) {
                        $obj->status = $statuss[$ii];
                        $obj->day = $day;
                        $obj->date = $date;
                        //$attendace["'" . $ii . "'"] = $statuss[$ii];
                    } else {
                        $obj->status = "-";
                        $obj->day = $day;
                        $obj->date = $date;
                        //$attendace["'" . $ii . "'"] = "-";
                    }
                    array_push($attendace, $obj);
                }
                $att->attendance = $attendace;
                $att->total_present = $total_present;
                $att->total_absent = $total_absent;
                $att->total_leave = $total_leave;
                $att->total_late = $total_late;
                $att->month = intVal($month);
                $att->current_date = date("Y-n-d");
            }
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$data["att"]);
        }else{
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
            /*$students = $this->db->select("name,id")->from('sh_students_'.$school_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('role_id',STUDENT_ROLE_ID)->where('deleted_at',0)->where('id',$student_id)->get()->result();
            if(count($students) != 0){
                $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
                $data["to"] = (new DateTime($to))->format("d");
                for($i = $data["from"] ; $i <= $data["to"] ; $i++){
                    $i = ($i < 10 ? '0' . $i : $i);
                    $attendance["'" . $i . "'"] = "-";
                }
                foreach ($students as $val) {
                    $val->attendance = $attendance;
                }

                $data["att"] = $students;
            }*/
        }
        echo json_encode($response);
    }

    public function get_parent_notifications(){
        $parent_id = $this->input->get("parent_id");
        
        $childs = $this->admin_model->dbSelect("*","student_guardians"," guardian_id='$parent_id' AND deleted_at IS NULL ");
        foreach($childs as $k=>$ch){
            $school_id = $this->admin_model->dbSelect("school_id","users"," id='$ch->student_id' ")[0]->school_id;
            $arr = $this->admin_model->dbSelect("class_id, batch_id, name","students_".$school_id," id='$ch->student_id' ");
            $childs[$k]->class_id = null;
            $childs[$k]->batch_id = null;
            $childs[$k]->name = null;
            if(count($arr) > 0){
                $arr = $arr[0];
                $childs[$k]->class_id = $arr->class_id;
                $childs[$k]->batch_id = $arr->batch_id;
                $childs[$k]->name = $arr->name;
            }
        }
        
        $this->db->select('d.id,n.msg_key, n.data, n.created_at as dateTime, d.is_read as is_read, u.name as sender,u.avatar as user_img, n.url as notiUrl, u.email, c.name as class_name, b.name as batch_name, s.name as subject_name, c.id as class_id, b.id as batch_id, s.id as subject_id');
        $this->db->from('sh_notifications n');
        $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'left');
        $this->db->join('sh_users u', 'd.receiver_id = u.id', 'left');
        $this->db->join('sh_classes c', 'd.class_id = c.id', 'left');
        $this->db->join('sh_batches b', 'd.batch_id = b.id', 'left');
        $this->db->join('sh_subjects s', 'd.subject_id = s.id', 'left');
        $this->db->where('d.receiver_id', $parent_id);
        $this->db->where('n.sender_id !=', $parent_id);
        $this->db->where('d.deleted_at', NULL);
        $this->db->order_by('n.created_at', 'DESC');
        $query = $this->db->get();
        $res = $query->result();
      
        foreach ($res as $v) {
            $v->student_id = null;
            $v->student_name = null;
            foreach($childs as $ch){
                if($ch->class_id == $v->class_id && $ch->batch_id == $v->batch_id){
                    $v->student_id = $ch->student_id;
                    $v->student_name = $ch->name;
                }
            }
            
            $message = lang($v->msg_key);
            $msg_key = $v->msg_key;
            //print_r((array)json_decode($v->data));
            $msg_data = (array)json_decode($v->data);
            
            foreach ($msg_data as $d => $vv) {
                $message = str_replace("{{" . $d . "}}", '"' . $vv . '"', $message);
            }
            $v->message = $message;
            $v->data = $msg_data;
        }
        $response = array();
        if(count($res)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$res);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }
    
    public function mark_read_parent_all_notifications(){
        $parent_id = $this->input->get("parent_id");
        
        $res = $this->common_model->update_where("sh_notification_details",array("receiver_id" => $parent_id,"is_read" => 0),array("is_read" => 1));
        $response = array();
        if($res){
            $this->db->select('d.id');
            $this->db->from('sh_notifications n');
            $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'left');
            $this->db->join('sh_users u', 'd.receiver_id = u.id', 'left');
            $this->db->where('d.receiver_id', $parent_id);
            $this->db->where('n.sender_id !=', $parent_id);
            $this->db->where("u.role_id",PARENT_ROLE_ID);
            $this->db->where('d.deleted_at', NULL);
            $this->db->where('d.is_read', 0);
            $this->db->order_by('n.created_at', 'DESC');
            $query = $this->db->get();
            $res = $query->result();
        
            $count = count($res);
            if(count($res)>0){
                $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>array("count"=>$count));
            } else {
                $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array("count"=>0));
            }
        }else{
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array("count"=>0));
        }
        
        echo json_encode($response);

    }
    
    public function get_student_notifications_count(){
        //print_r($this->input->get());
        $student_id = $this->input->get("student_id");
        
        $this->db->select('d.id');
        $this->db->from('sh_notifications n');
        $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'left');
        $this->db->join('sh_users u', 'd.receiver_id = u.id', 'left');
        $this->db->where('d.receiver_id', $student_id);
        $this->db->where('n.sender_id !=', $student_id);
        $this->db->where("u.role_id",STUDENT_ROLE_ID);
        $this->db->where('d.deleted_at', NULL);
        $this->db->where('d.is_read', 0);
        $this->db->order_by('n.created_at', 'DESC');
        $query = $this->db->get();
        $res = $query->result();
        
        $count = count($res);
        $response = array();
        if(count($res)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>array("count"=>$count));
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array("count"=>0));
        }
        echo json_encode($response);
    }
    
    public function get_single_student_notifications(){
        $student_id = $this->input->get("student_id");
        $school_id = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ")[0]->school_id;
        $arr = $this->admin_model->dbSelect("class_id, batch_id, name","students_".$school_id," id='$student_id' ");
        
        $this->db->select('d.id,n.msg_key, n.data, n.created_at as dateTime, d.is_read as is_read, u.name as student_name,u.avatar as user_img, n.url as notiUrl, u.email, c.name as class_name, b.name as batch_name, s.name as subject_name, c.id as class_id, b.id as batch_id, s.id as subject_id');
        $this->db->from('sh_notifications n');
        $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'left');
        $this->db->join('sh_users u', 'd.receiver_id = u.id', 'left');
        $this->db->join('sh_classes c', 'd.class_id = c.id', 'left');
        $this->db->join('sh_batches b', 'd.batch_id = b.id', 'left');
        $this->db->join('sh_subjects s', 'd.subject_id = s.id', 'left');
        $this->db->where('d.receiver_id', $student_id);
        $this->db->where('n.sender_id !=', $student_id);
        $this->db->where('d.deleted_at', NULL);
        $this->db->order_by('n.created_at', 'DESC');
        $query = $this->db->get();
        $res = $query->result();
     
        foreach ($res as $v) {
            
            // $v->student_id = null;
            // $v->student_name = null;
            // foreach($childs as $ch){
            //     if($ch->class_id == $v->class_id && $ch->batch_id == $v->batch_id){
            //         $v->student_id = $ch->student_id;
            //         $v->student_name = $ch->name;
            //     }
            // }
            
            $message = lang($v->msg_key);
            $msg_key = $v->msg_key;
            //print_r((array)json_decode($v->data));
            $msg_data = (array)json_decode($v->data);
            
            foreach ($msg_data as $d => $vv) {
                $message = str_replace("{{" . $d . "}}", '"' . $vv . '"', $message);
            }
            $v->message = $message;
            $v->data = $msg_data;
           
        }

        $response = array();
        if(count($res)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$res);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function get_student_fee_types(){
        // $student_id = $this->input->post("student_id");
        $student_id = $this->input->get("student_id");
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $academic_year_id=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $academic_year_id = $yasir_result_arr[0]->academic_year_id;
        }
        $response = array();
        $result = $this->admin_model->dbSelect("*","fee_types"," class_id='$class_id' AND school_id='$school_id' AND academic_year_id='$academic_year_id' AND deleted_at IS NULL ");
        if(count($result)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$result);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function get_student_fee_details() {
        $student_id = $this->input->get("student_id");
        
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        $discount_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
            
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $academic_year_id=0;
        $batch_id=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
            $academic_year_id = $yasir_result_arr[0]->academic_year_id;
            $discount_id = $yasir_result_arr[0]->discount_id;
        }
        
        $feetypes = $this->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND class_id='$class_id' AND deleted_at IS NULL ");
        //feestypes join with discount and discount variants

        $sql = "SELECT "
                . "COALESCE(cls.id,'NULL') as class_id, "
                . "COALESCE(bth.id,'NULL') as batch_id, "
                . "COALESCE(cls.name,'NULL') as class_name, "
                . "COALESCE(bth.name,'NULL') as batch_name, "
                . "IFNULL(v.percentage,0) as discount,"
                . "COALESCE(c.id,'NULL') as fee_collection_id, "
                . "COALESCE(d.id,'NULL') as discount_id, "
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
                . "COALESCE(d.amount,0) as discounted_amount "
                . "FROM sh_fee_types ft "
                . "LEFT JOIN sh_fee_collection c ON c.feetype_id=ft.id "
                . "LEFT JOIN sh_fee_discount d ON c.discount_id=d.id "
                . "LEFT JOIN sh_discount_varients v ON ft.id=v.fee_type_id and d.id=v.discount_id "
                . "INNER JOIN sh_users u ON c.collector_id=u.id "
                . "LEFT JOIN sh_classes cls ON c.class_id=cls.id "
                . "LEFT JOIN sh_batches bth ON c.batch_id=bth.id "
                . "WHERE ft.school_id='$school_id' AND "
                //. "ft.class_id='$request->class_id' AND "
                . "c.deleted_at IS NULL AND "
                . "ft.deleted_at IS NULL AND c.student_id='$student_id'";
        $collected_fees = $this->admin_model->dbQuery($sql);
        
        $paid_feetype_names = array();
        foreach ($collected_fees as $c) {
            array_push($paid_feetype_names, $c->feetype);
        }
        
        foreach ($feetypes as $feetype) {
            if (!in_array($feetype->name, $paid_feetype_names)) {
                //$d = $feetype->amount * ($this->getDiscountVariant($request->discount_id, $feetype->id) / 100);
                //$dd = $feetype->amount - $d;
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
                    "discount_id" => $discount_id,
                    "discounted_amount" => $feetype->amount - $feetype->amount * ($this->getDiscountVariant($discount_id, $feetype->id) / 100),
                    "discount" => $this->getDiscountVariant($discount_id, $feetype->id)
                );
                array_push($collected_fees, $array);
            }
        }

        $new_collected_fees = array();
        $nid = $this->admin_model->dbSelect("nationality", "users", " id='$student_id' ")[0]->nationality;
        $admission_date = '0000-00-00';
        $rrr = $this->admin_model->dbSelect("joining_date", "users", " id='$student_id' AND deleted_at=0 ");
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
                            $obj->discounted_amount = $obj->amount - ($obj->amount * $obj->discount / 100);
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
        
        $sql_next_fee = "SELECT * FROM sh_fee_types WHERE school_id=$school_id AND class_id=$class_id AND academic_year_id=$academic_year_id AND deleted_at IS NULL AND due_date > DATE_FORMAT(CURDATE(), '%Y-%m-%d') ";
        $next_fee_arr = $this->admin_model->dbQuery($sql_next_fee);
        $next_fee = 0;
        if(count($next_fee_arr) >0){
            $next_fee = $next_fee_arr[0]->amount;
        }

        $due_fee=0;
        $total_fee=0;
        foreach($yasir_array as $row){
            $due_fee += (intval($row[0]->amount) - intval($row[0]->paid_amount));
            $total_fee += $row[0]->amount;
        }
        
        $response = array();
        if(count($yasir_array)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$yasir_array, "next_fee"=>$next_fee, "due_fee"=>$due_fee, "total_fee" => $total_fee);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }

        echo json_encode($response);
    }

    public function getDiscountVariant($discount_id, $fees_type_id) {
        $varients = 0;
        $res = $this->admin_model->dbSelect("*", "discount_varients", " discount_id='$discount_id' AND fee_type_id='$fees_type_id' AND deleted_at IS NULL ");
        if (count($res) > 0) {
            $varients = $res[0]->percentage;
        }
        return $varients;
    }

    function get_student_syllabus(){
        // $request = json_decode($this->input->post("postdata"));
        $student_id = $this->input->get("student_id");
        $subject_id = isset($_GET["subject_id"])?$_GET["subject_id"]:0;
        //$student_id = $request->student_id;
        //$subject_id = $request->subject_id;

        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        $discount_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
            
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $academic_year_id=0;
        $batch_id=0;
        $subject_group_id=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
            $academic_year_id = $yasir_result_arr[0]->academic_year_id;
            $subject_group_id = $yasir_result_arr[0]->subject_group_id;
            $discount_id = $yasir_result_arr[0]->discount_id;
        }

        if($subject_id == 0){
            $subjects = $this->admin_model->dbSelect("subjects","subject_groups"," id=$subject_group_id ")[0]->subjects;
            $subjects = explode(",",$subjects);
            $subject_id = $subjects[0];
        }

        $working_days_json = $this->admin_model->dbSelect("working_days","school"," id='$school_id' ")[0]->working_days;
        $var = json_decode($working_days_json);
        $not_working_days = array();
        foreach($var as $val){
            if($val->val == 'false'){
                array_push($not_working_days, $val->label);
            }
        }

        $weeks = $this->admin_model->dbSelect("*", "syllabus_weeks", " class_id='$class_id' AND batch_id='$batch_id' AND subject_id='$subject_id' AND school_id='$school_id' AND deleted_at IS NULL ");
        $sql = "SELECT "
            . "wk.id as week_id,"
            . "wkd.id as week_detail_id, "
            . "s.name as subject_name, "
            . "wk.name as weekname, "
            . "wk.start_date, "
            . "wk.end_date, "
            . "wkd.date, "
            . "wkd.edit, "
            . "wkd.topic, "
            . "wkd.status, "
            . "wkd.comments "
            . "FROM "
            . "sh_syllabus_weeks wk "
            . "INNER JOIN sh_syllabus_week_details wkd ON wk.id=wkd.syllabus_week_id "
            . "LEFT JOIN sh_subjects s ON s.id=wk.subject_id "
            . "WHERE wk.class_id='$class_id' "
            . "AND wk.batch_id='$batch_id' "
            . "AND wk.subject_id='$subject_id' "
            . "AND wk.school_id='$school_id' "
            . "AND wk.deleted_at IS NULL ";
        $weeks_details = $this->admin_model->dbQuery($sql);
        $ydata = array();
        //print_r($weeks_details); die();
        
        foreach($weeks as $w){
            $arr = array();
            $ydata[$w->id]["id"] = $w->id;
            $ydata[$w->id]["week"] = $w->name;
            $ydata[$w->id]["start_date"] = $w->start_date;
            $ydata[$w->id]["end_date"] = $w->end_date;
            $ydata[$w->id]["class_id"] = $w->class_id;
            $ydata[$w->id]["batch_id"] = $w->batch_id;
            $ydata[$w->id]["subject_id"] = $w->subject_id;
            
            $index=0;
            foreach($weeks_details as $d){
                if($w->id == $d->week_id){
                    $arra = array(
                        "week_detail_id"=>$d->week_detail_id,
                        "date"=>$d->date,
                        "day"=>date('l', strtotime($d->date)),
                        "topic"=>$d->topic,
                        "status"=>$d->status,
                        "comments"=>$d->comments,
                        "is_working_day"=>true,
                        "edit"=>$d->edit
                    );
                    $arr[$index++] =  $arra;
                }
            }
            
            $saved_dates = array();
            foreach($arr as $t){
                array_push($saved_dates, $t["date"]);
            }
            $dates = $this->dateDays($ydata[$w->id]["start_date"], $ydata[$w->id]["end_date"]);
            $not_saved_dates = array_diff($dates, $saved_dates);
            foreach($not_saved_dates as $n){
                
                $is_working_day = true;
                if(in_array(date('l', strtotime($n)), $not_working_days)){
                    $is_working_day = false;
                }
                
                
                $dumyArray = array(
                    "week_detail_id"=>NULL,
                    "date"=>$n,
                    "day"=>date('l', strtotime($n)),
                    "subject"=>NULL,
                    "status"=>NULL,
                    "comments"=>NULL,
                    "is_working_day"=>$is_working_day
                );
                $arr[$index++] = $dumyArray;
            }
            $ydata[$w->id]["data"] = $arr;
        }
        foreach($ydata as $key => $value){
           array_multisort( array_column($ydata[$key]['data'], "date"), SORT_ASC, $ydata[$key]['data'] );
        }

        $first_week_id=0;
        if(count($weeks) > 0){
            $first_week_id = $weeks[0]->id;
        }
        
        $response = array();
        if(count($ydata) > 0){
            $default_syllabus = $ydata[$first_week_id];
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$ydata, "default"=>$default_syllabus);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }

    function dateDays($start, $end){
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($end)
        );
        $arr = array();
        foreach ($period as $value) {
            array_push($arr, $value->format('Y-m-d'));
        }
        array_push($arr, $end);
        return $arr;
    }

    public function get_student_timetable() {
        // $request = json_decode($this->input->post("postdata"));
        // $student_id = $request->student_id;
        $student_id = $this->input->get("student_id");
        $reponse = array();

        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $batch_id=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
        }

        $days_of_week = $this->getDaysOfWeek($school_id);
        $periods = $this->admin_model->dbSelect("*,time_format(start_time,'%h:%i %p') as start_time2,time_format(end_time,'%h:%i %p') as end_time2", "periods", " school_id='$school_id' AND class_id='$class_id' AND batch_id='$batch_id' AND deleted_at IS NULL ORDER BY start_time ");

        if (count($periods) === 0) {
            $response = array("status" => "error", "message" => lang('periods_not_set'));
            echo json_encode($response);
            exit;
        }

        $final_arr = array();
        foreach ($days_of_week as $day) {
            foreach ($periods as $p) {
                $array = array(
                    "period_id" => $p->id,
                    "start_time" => $p->start_time,
                    "end_time" => $p->end_time,
                    "start_time2" => $p->start_time2,
                    "end_time2" => $p->end_time2,
                    "class_id" => $class_id,
                    "batch_id" => $batch_id,
                    "is_break" => $p->is_break,
                    "period_order" => $p->title,
                    "timetable_id" => NULL,
                    "teacher_id" => NULL,
                    "teacher_name" => NULL,
                    "day_of_week" => $day,
                    "sub_id" => NULL,
                    "room_no" => NULL,
                    "sub_name" => NULL,
                    "sub_code" => NULL
                );
                $final_arr[$day][$p->id] = $array;
            }
        }

        $daysofweekstring = null;
        foreach ($days_of_week as $_d) {
            $daysofweekstring .= "'" . $_d . "',";
        }


        $sql = "SELECT "
                . "p.id as period_id, "
                . "p.start_time as start_time, "
                . "time_format(p.start_time,'%h:%i %p') as start_time2, "
                . "time_format(p.end_time,'%h:%i %p') as end_time2, "
                . "p.end_time as end_time, "
                . "p.class_id as class_id, "
                . "p.batch_id as batch_id, "
                . "p.is_break as is_break, "
                . "p.title as period_order, "
                . "t.id as timetable_id, "
                . "t.day_of_week as day_of_week, "
                . "t.subject_id as sub_id, "
                . "t.room_no as room_no, "
                . "s.name as sub_name, "
                . "s.code as sub_code, "
                . "asn.teacher_id as teacher_id, "
                . "u.name as teacher_name "
                . "FROM sh_periods p "
                . "LEFT JOIN sh_timetable_new t ON p.id=t.period_id "
                . "INNER JOIN sh_subjects s ON t.subject_id=s.id "
                . "LEFT JOIN sh_assign_subjects asn ON t.subject_id=asn.subject_id and p.class_id = asn.class_id and p.batch_id = asn.batch_id "
                . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
                . "WHERE "
                . "p.school_id='$school_id' "
                . "AND p.class_id='$class_id' "
                . "AND p.batch_id='$batch_id' "
                . "AND p.deleted_at IS NULL "
                . "AND t.day_of_week in (" . rtrim($daysofweekstring, ",") . ") "
                . "ORDER BY p.start_time ASC ";

        $dbTimetables = $this->admin_model->dbQuery($sql);

        foreach ($dbTimetables as $tb) {
            $index1 = $tb->day_of_week;
            $index2 = $tb->period_id;
            $final_arr[$index1][$index2] = $tb;
        }

        foreach ($final_arr as $key => $value) {
            $i = 0;
            foreach ($value as $row_key => $row_value) {
                $final_arr[lang($key)][$i++] = $row_value;
                unset($final_arr[$key]);
                unset($final_arr[$key][$row_key]);
            }
        }
        if(count($periods)>0 && count($final_arr)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>array("periods"=>$periods,"timetable"=>$final_arr));
        } else{
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }

    public function getDaysOfWeek($school_id) {
        $days_of_week = array();
        $dddd = json_decode($this->admin_model->dbSelect("working_days", "school", " id='$school_id' ")[0]->working_days);
        foreach ($dddd as $row) {
            if ($row->val == 'true') {
                array_push($days_of_week, strtolower($row->label));
            }
        }
        $sorted_days_of_week = array();
        $start_day_of_week = strtolower($this->admin_model->dbSelect("start_day_of_the_week", "school", " id='$school_id' AND deleted_at=0 ")[0]->start_day_of_the_week);
        $index = array_search($start_day_of_week, $days_of_week);
        $loop1 = count($days_of_week) - $index;

        for ($i = 0; $i < $loop1; $i++) {
            array_push($sorted_days_of_week, $days_of_week[$index + $i]);
        }

        for ($j = 0; $j < $index; $j++) {
            array_push($sorted_days_of_week, $days_of_week[$j]);
        }
        return $sorted_days_of_week;
    }

    public function get_student_result_card(){
        $request = json_decode($this->input->get("postdata"));
        $student_id = $request->student_id;
        //$student_id = $this->input->post("student_id");
        $reponse = array();
       
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $batch_id=0;
        $active_academic_year=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
            $active_academic_year = $yasir_result_arr[0]->academic_year_id;
        }

        $range_start_date = date("Y-m-01");
        $range_end_date = date("Y-m-t");
        if(isset($request->month)){
            $range_start_date = date("Y-".$request->month."-01");
            $range_end_date = date("Y-".$request->month."-t", strtotime($range_start_date));
        } 
        
        $sql1="";
        if(isset($request->exam_id)){
            $sql1 = "SELECT d.*, s.name as subject_name FROM sh_exams e LEFT JOIN sh_exam_details d ON e.id=d.exam_id INNER JOIN sh_subjects s ON d.subject_id=s.id WHERE e.id='$request->exam_id';";
        } else {
            $sql1 = "SELECT d.*, s.name as subject_name FROM sh_exams e LEFT JOIN sh_exam_details d ON e.id=d.exam_id INNER JOIN sh_subjects s ON d.subject_id=s.id WHERE e.start_date>='$range_start_date' AND e.end_date<='$range_end_date' AND e.school_id=$school_id AND e.academic_year_id=$active_academic_year;";
        }
        $examDetails = $this->admin_model->dbQuery($sql1);
        
        $data = array();
        if(count($examDetails) > 0){
            foreach($examDetails as $row){
                $sql2 = "SELECT * FROM sh_marksheets sh INNER JOIN sh_remarks_and_positions pos ON sh.exam_id=pos.exam_id AND sh.student_id=pos.student_id WHERE sh.exam_id='$row->exam_id' AND sh.exam_detail_id='$row->id' AND sh.student_id='$student_id' AND sh.school_id='$row->school_id' AND sh.class_id='$row->class_id' AND sh.batch_id='$row->batch_id' AND sh.deleted_at IS NULL ";
                $res = $this->admin_model->dbQuery($sql2);
                if(count($res) > 0){
                    $res[0]->subject_name = $row->subject_name;
                    array_push($data, $res[0]);
                }
            }
            $response = array("status"=>"error","message"=>lang("api_success_message"),"data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        $response = array("status"=>"error","message"=>"Result card will be display after school permissions!","data"=>array());
        echo json_encode($response);
    }
    
     public function get_study_material(){
        
        $student_id = $_GET["student_id"];
        $subject_id = isset($_GET["subject_id"]) ? $_GET["subject_id"] : 0;
      
        $type = isset($_GET["type"]) ? $_GET["type"] : "Assignment";
        $date = $_GET["date"];
        $data = "";
           
        if(!empty($date) || $date != ""){
            $date = date("Y-m-d", strtotime(explode("T", $date)[0]));
        }
        // echo "<pre>";
        //     print_r($date);
        //     echo "<pre>";
        //     die;
        //$req = array("student_id"=>$student_id,"subject_id"=>$subject_id,"type"=>$type, "date"=>$date, "get"=> $_GET["date"]);
        
        
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        
        $sub_result = $this->db->select('code')->from('sh_subjects')->where('id', $subject_id)->where('deleted_at is NULL')->get()->result_array();
        if($sub_result){
            //  echo "<pre>";
            // print_r($sub_result['0']);
            // echo "<pre>";
            // die;
             $sub_code = $sub_result[0]['code'];
             
            $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
            $yasir_result_arr = $this->admin_model->dbQuery($sql);
            $class_id=0;
            $batch_id=0;
            $subject_group_id = 0;
            if(count($yasir_result_arr) > 0) {
                $class_id = $yasir_result_arr[0]->class_id;
                $batch_id = $yasir_result_arr[0]->batch_id;
                $subject_group_id = $yasir_result_arr[0]->subject_group_id;
            }
            
            if($subject_id == 0){
                $subjects = $this->admin_model->dbSelect("subjects","subject_groups"," id=$subject_group_id ")[0]->subjects;
                $subjects = explode(",",$subjects);
                $subject_id = $subjects[0];
            }
            
            $data = $this->study_model->filter_study_material_app($school_id, $class_id, $batch_id, $subject_id, $sub_code, $type, $date);
            
            if($data){
                for ($i = 0; $i < count($data); $i++) {
                    //"<a href="https://www.youtube.com/embed/h2dUjjAPNK4">https://www.youtube.com/embed/h2dUjjAPNK4</a>"
                    //"<a href="https://drive.google.com/file/d/136hYJqd734CtxKm63NTrAHFOWMgzt-Ej/view?usp=sharing">https://drive.google.com/file/d/136hYJqd734CtxKm63NTrAHFOWMgzt-Ej/view?usp=sharing</a>"
                    $data[$i]['files'] = explode(",", $data[$i]['files']);
                    $text = strip_tags($data[$i]['details']);
                    $url_link = $this->displayTextWithLinks($text);
                    if($url_link){
                        $data[$i]['custom_url_links'] = $url_link;
                        $r = parse_url($url_link);
                        
                        $r = substr($r['path'], strrpos($r['path'], '//'));
                        
                        $exp_url = explode('/', $r);
                        
                        if(in_array("drive.google.com", $exp_url)){
                            $data[$i]['custom_url_links'] = $r;
                            $data[$i]['custom_url_links_type'] = 'google';
                        }else if(in_array("www.youtube.com", $exp_url)){
                            
                            $data[$i]['custom_url_links'] = rtrim($r,"</a>");;
                            $data[$i]['custom_url_links_type'] = 'youtube';
                          }
                       
                    }else{
                        $data[$i]['custom_url_links'] = "";
                        $data[$i]['custom_url_links_type'] = "";
                    }
                    
                    
                }
            }
        }
       
        
        $response = array();
        if(count($data)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$data);
        } else{
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }
        echo json_encode($response);
    }
    
    function displayTextWithLinks($s) {
      return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1">$1</a>', $s);
    }
    public function get_student_subjects(){
        $student_id = $this->input->get("student_id");
        $reponse = array();

        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $subject_group_id=0;
        $class_id=0;
        $batch_id=0;
        $academic_year_id=0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
            $subject_group_id = $yasir_result_arr[0]->subject_group_id;
            $academic_year_id = $yasir_result_arr[0]->academic_year_id;
        }

        $subject_groups = $this->admin_model->dbSelect("*","subject_groups"," id='$subject_group_id' ");
        if(count($subject_groups) > 0){
            $subjects = $subject_groups[0]->subjects;
            $subjects = $this->admin_model->dbSelect("*","subjects"," id IN ($subjects) AND deleted_at IS NULL ");
            if(count($subjects) > 0){
                $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>$subjects);
            } else{
                $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
            }
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array());
        }

        echo json_encode($response);
    }
    
    public function get_notifications_count(){
        $parent_id = $this->input->get("parent_id");
        
        $this->db->select('d.id');
        $this->db->from('sh_notifications n');
        $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'left');
        $this->db->join('sh_users u', 'd.receiver_id = u.id', 'left');
        $this->db->where('d.receiver_id', $parent_id);
        $this->db->where('n.sender_id !=', $parent_id);
        $this->db->where("u.role_id",PARENT_ROLE_ID);
        $this->db->where('d.deleted_at', NULL);
        $this->db->where('d.is_read', 0);
        $this->db->order_by('n.created_at', 'DESC');
        $query = $this->db->get();
        $res = $query->result();
        
        $count = count($res);
        $response = array();
        if(count($res)>0){
            $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>array("count"=>$count));
        } else {
            $response = array("status"=>"error","message"=>lang("api_error_message"),"data"=>array("count"=>0));
        }
        echo json_encode($response);
    }
    
    public function change_notification_status(){
        $id = $this->input->get("id");
        $this->common_model->update_where("sh_notification_details",array("id" => $id),array("is_read" => 1));
        $response = array("status"=>"success","message"=>lang("api_success_message"),"data"=>array());
        echo json_encode($response);
    }

     public function appVersion(){
        $myfile = simplexml_load_file("https://uvschools.com/appSettings/update.xml");
        // print_r($myfile);
        // $response = array('newVersion' => '0.3', 'name' => 'UVSchools', 'url' => 'https://uvschools.com/testing/appSetting/UVSchoolsUpdate.apk');
        echo json_encode($myfile);
    }
    
    //---------Start Exam API------//

    public function get_student_exams(){
        $student_id = $this->input->get("student_id");
        
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $active_academic_year = 0;
        if(count($yasir_result_arr) > 0) {
            $active_academic_year = $yasir_result_arr[0]->academic_year_id;
        }

        $exams = $this->admin_model->dbSelect("*","exams"," school_id='$school_id' AND academic_year_id='$active_academic_year' AND deleted_at IS NULL ");
        $response = array();
        if(count($exams) > 0){
            $response = array("status"=>"success","message"=>"data found", "data"=> $exams);
        } else {
            $response = array("status"=>"error","message"=>"data not found", "data"=> array());
        }

        echo json_encode($response);
    }

    public function get_exam_details(){
        $student_id = $this->input->get("student_id");
        $exam_id = $this->input->get("exam_id");
        
        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $batch_id=0;
        $active_academic_year = 0;
        $subject_group_id = 0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
            $active_academic_year = $yasir_result_arr[0]->academic_year_id;
            $subject_group_id = $yasir_result_arr[0]->subject_group_id;
        }

        $subjects = $this->admin_model->dbSelect("subjects","subject_groups"," id='$subject_group_id' AND deleted_at IS NULL ");
        if(count($subjects) > 0){
            $subjects = explode(",",$subjects[0]->subjects);
        }
        
        $sql = "SELECT d.*, c.name as class_name, b.name as batch_name, s.name as subject_name FROM sh_exam_details d INNER JOIN sh_classes c ON d.class_id=c.id INNER JOIN sh_batches b ON d.batch_id=b.id INNER JOIN sh_subjects s ON d.subject_id=s.id WHERE d.class_id='$class_id' AND d.batch_id='$batch_id' AND d.academic_year_id='$active_academic_year' AND d.exam_id='$exam_id' AND d.deleted_at IS NULL";
        $details = $this->admin_model->dbQuery($sql);
        $new_details = array();
        if(count($details) > 0){
            foreach($details as $d){
                if(in_array($d->subject_id,$subjects)){
                    array_push($new_details, $d);
                }
            }
        }
        
        $response = array();
        if(count($details) > 0){
            $response = array("status"=>"success","message"=>"data found","data"=>$new_details);
        } else {
            $response = array("status"=>"error","message"=>"data not found","data"=>array());
        }

        echo json_encode($response);
    }

    public function get_marksheet(){
        $student_id = $this->input->get("student_id");
        $exam_id = $this->input->get("exam_id");

        $school_id_arr = $this->admin_model->dbSelect("school_id","users"," id='$student_id' ");
        $school_id = 0;
        if(count($school_id_arr) > 0){
            $school_id = $school_id_arr[0]->school_id;
        }
        $sql = "SELECT * FROM sh_students_".$school_id." WHERE id='$student_id'";
        $yasir_result_arr = $this->admin_model->dbQuery($sql);
        $class_id=0;
        $batch_id=0;
        $active_academic_year = 0;
        $subject_group_id = 0;
        if(count($yasir_result_arr) > 0) {
            $class_id = $yasir_result_arr[0]->class_id;
            $batch_id = $yasir_result_arr[0]->batch_id;
            $active_academic_year = $yasir_result_arr[0]->academic_year_id;
            $subject_group_id = $yasir_result_arr[0]->subject_group_id;
        }

        $std_subjects = $this->admin_model->dbSelect("subjects","subject_groups"," id='$subject_group_id' AND deleted_at IS NULL ");
        if(count($std_subjects) > 0){
            $std_subjects = explode(",",$std_subjects[0]->subjects);
        }

        $data = array();
        $sql1_new = "SELECT "
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
            . "cr.class_id=$class_id "
            . "AND cr.batch_id=$batch_id "
            . "AND u.school_id=$school_id "
            . "AND u.deleted_at=0";

        $sql1_1 = "SELECT "
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
                . "sf.class_id=$class_id "
                . "AND sf.batch_id=$batch_id "
                . "AND m.exam_id=$exam_id "
                . "AND u.school_id=$school_id "
                . "AND cr.academic_year_id=$active_academic_year "
                . "AND sf.deleted_at IS NULL "
                . "AND u.deleted_at=0 "
                . "AND m.deleted_at is NULL";

        $sql2 = "SELECT id as subject_id, name as subject_name FROM sh_subjects WHERE school_id='$school_id' AND class_id='$class_id' AND batch_id='$batch_id' AND deleted_at IS NULL ";
        $sql3 = "SELECT e.id as exam_id,e.title as title, ed.id as exam_detail_id, e.title as examname, ed.subject_id,ed.total_marks, ed.passing_marks,ed.start_time, ed.end_time, ed.exam_date, ed.class_id, ed.batch_id FROM sh_exams e LEFT JOIN sh_exam_details ed ON e.id=ed.exam_id WHERE e.deleted_at IS NULL AND ed.deleted_at IS NULL AND e.id='$exam_id' AND e.school_id='$school_id' AND e.academic_year_id='$active_academic_year' ";
        $students = $this->admin_model->dbQuery($sql1_new . " union " . $sql1_1);
        $students_from_shift_table = $this->admin_model->dbQuery($sql1_1);
        $subjects_old = $this->admin_model->dbQuery($sql2);
        $subjects = array();
        foreach($subjects_old as $_sub){
            if(in_array($_sub->subject_id,$std_subjects)){
                array_push($subjects, $_sub);
            }
        }
        
        $exams = $this->admin_model->dbQuery($sql3);
        $new_data = array();
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
                                'start_time' => $exam->start_time,
                                'end_time' => $exam->end_time,
                                'exam_date' => $exam->exam_date,
                                'obtained_marks' => NULL,
                                'marksheet_status' => NULL
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
                                $students[$key]->subjects[$key2]->exams[0]->remarks = $marks[$marks_index]->remarks;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = $marks[$marks_index]->status;
                                if (($marks[$marks_index]->teacher_remark != null && $marks[$marks_index]->teacher_remark_id != null) || (!empty($marks[$marks_index]->teacher_remark) && !empty($marks[$marks_index]->teacher_remark_id))) {
                                    $teacher_remark = $marks[$marks_index]->teacher_remark;
                                    $teacher_remark_id = $marks[$marks_index]->teacher_remark_id;
                                }
                            } else {
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = null;
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
                                'end_time' => NULL,
                                'exam_date' => NULL,
                                'obtained_marks' => NULL,
                                'remarks' => NULL,
                                'marksheet_status' => NULL
                            );
                            array_push($students[$key]->subjects[$key2]->exams, $array222);
                        }
                    }
                    $is_shifted = check_student_shifted($students_from_shift_table, $students[$key]->student_id, $exam_id, $batch_id);
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
                    // hide result card on pesc school
                    // if($school_id == 29){
                    //     $array2 = (object) array();
                    //     $data = array("status" => "error", "data" => $array2, "message" => "not found found");
                    // }else{
                    //     $data = array("status" => "success", "data" => $array2, "message" => "data found");
                    // }
                    $data = array("status" => "success", "data" => $array2, "message" => "data found");
                }
            }
        }

        if(count($data) > 0) {
            //----------- Start::All subjects marks added or not ------------//
            foreach ($data["data"] as $keey1 => $d) {
                $data["data"][$keey1]->is_all_subjects_marks_added = 'true';
                /* foreach ($d->subjects as $u) {
                if ($u->exams[0]->obtained_marks == null) {
                $data["data"][$keey1]->is_all_subjects_marks_added = 'false';
                break;
                }
                } */
            }
            //----------- End::All subjects marks added or not ------------//
            
            
            //----------- Start::Obtained total marks ------------//
            foreach ($data["data"] as $kkk => $ss) {
                $obtained_total = 0;
                foreach ($ss->subjects as $sub) {
                    $obtained_total = $sub->exams[0]->obtained_marks;
                    $data["data"][$kkk]->obtained_total += intval($obtained_total);
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
            $passing_rules = $this->admin_model->dbSelect("*", "passing_rules", " class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND exam_id='$exam_id' AND deleted_at IS NULL ");
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
                        $data["data"][$kk]->percentage = round(($data["data"][$kk]->obtained_total * 100 ) / $exam_total_total, 2);
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

            //----------- End::Calculate position ------------//
            foreach ($data["data"] as $key => $val) {
                $data["data"][$key]->new_position = $this->position_string($val->position);
            }

            //-----------Code by Umar-------------//
            foreach ($data["data"] as $k1 => $std1) {
                if ($std1->result == lang("fail")) {
                    if (in_array($std1->percentage, $unique2)) {
                        $position_key = array_search($std1->percentage, $unique2);
                        $data["data"][$k1]->position = $position_key + 1000;
                        $data["data"][$k1]->percentage .= "%";
                    }
                }
            }
            
            foreach($data["data"] as $d){
                if($d->student_id == $student_id){
                    $d->exam_total_marks = $data["exam_total_marks"];
                    $data["data"] = $d;
                    break;
                } else {
                    $data["data"] = array();
                }
            }

            if(count($data["data"]) > 0){
                $new_data["student_name"] = $data["data"]->student_name;
                $new_data["student_avatar"] = $data["data"]->student_avatar;
                $new_data["rollno"] = $data["data"]->rollno;
                $new_data["obtained_total"] = $data["data"]->obtained_total;
                $new_data["remarks"] = $data["data"]->teacher_remark;
                
                if(count($data["data"]->subjects) > 0){
                    $new_data["exam_name"] = isset($data["data"]->subjects[0]->exams[0]->exam_name)?$data["data"]->subjects[0]->exams[0]->exam_name:null;
                    $new_data["subjects"] = array();
                    foreach($data["data"]->subjects as $subb){
                        $arr = array();
                        $arr["subject_name"] = $subb->subject_name;
                        if(count($subb->exams) > 0){
                            $arr["obtain_marks"] = $subb->exams[0]->obtained_marks;
                            $arr["total_marks"] = $subb->exams[0]->total_marks;
                            $arr["passing_marks"] = $subb->exams[0]->passing_marks;
                            $arr["status"] = $subb->exams[0]->marksheet_status;
                        }
                        array_push($new_data["subjects"], $arr);
                    }
                }

                $data["data"] = $new_data;
            }
        } else {
            $data = array("status" => "error", "data" => array(), "message" => lang("no_record"));
        }

        
        echo json_encode($data);
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
    
    public function SendCode(){
        
        $request = $this->input->post();
        $postdata = json_decode($request['postdata']);
        
        $user = $this->db->select('id as user_id, email')->from('sh_users')->where('email', $postdata->email)->where('role_id = 2')->where('deleted_at = 0')->get()->row();
    
        if($user){  
            $code = rand(100000,999999);
            $subject = 'Reset Password Code';
            $data = array(
                "dear_sir" => "Dear Sir/Madam",
                "msg" => "Your password reset code at UVSchools Mobile App. Please use the following code to reset Password",
                "thanks" => "- Thanks (UVSchools Team)",
                "poweredBy" => "Powered by united-vision.net",
                "code" => $code
            );
         
            $message = $this->load->view('email_templates/app_password_recovery', $data, TRUE);

            $this->email_modal->emailSend($postdata->email, $message, $subject, "Reset Password");
            $data = array("status" => 'success','code' => $code, 'user' => $user);
        }else{
             $code = '';
            $data = array("status" => 'error','code' => $code, 'user' => $user);
        }
        echo json_encode($data);
    }
    
    //---------End Exam API-------//
    
    // ---------- annocement API---------//
     public function get_announcements(){
        $parent_id = $this->input->get("parent_id");
        $school_id = $this->db->select('school_id')->from('sh_users')->where('id', $parent_id)->where('deleted_at = 0')->get()->row()->school_id;
        $date = date("Y-m-d");

        if($school_id){
           
            $active_academic_year_id = $this->db->select('id')->from('sh_academic_years')->where('school_id', $school_id)->where("is_active = 'Y'")->where('deleted_at IS NULL')->get()->row()->id;
            if($active_academic_year_id){
                $annocements = array();
                // fetch announcements against parent 
                $annocements = $this->db->select('*')->from('sh_announcements')->where('school_id', $school_id)->where('academic_year_id', $active_academic_year_id)->where("status = 'active'")->where("level = 'all' OR level='parents'")->where("('$date' BETWEEN from_date AND to_date)")->get()->result();
                // fetch childrens against parent 
                $childs =  $this->db->select('student_id, std.class_id, std.batch_id')->from('sh_student_guardians stg')->join("sh_students_".$school_id.' std', 'student_id=std.id')->where('stg.guardian_id', $parent_id)->where('stg.deleted_at is NULL')->where('std.deleted_at = 0 ')->get()->result();
                
                if($childs){
                    foreach ($childs as $key => $ch) {
                        // fetch announcements against childrens
                        $ch_annocements = $this->db->select('*')->from('sh_announcements')->where('school_id', $school_id)->where('academic_year_id', $active_academic_year_id)->where("status = 'active' ")->where("level ='students'")->where("FIND_IN_SET($ch->class_id, classes)" )->where("FIND_IN_SET($ch->batch_id, sections)" )->where("('$date' BETWEEN from_date AND to_date)")->get()->result();

                        if($ch_annocements){
                            // pushing childrens anncouncements into the parent annoucement array
                            foreach ($ch_annocements as $key1 => $ch_anc) {
                               array_push($annocements, $ch_anc);
                            }
                            
                        }
                    }
                }
            
                $data = array("status" => "success", "message" => "Announcements found", "data" => $annocements);
                
            }else{
                $data = array("status" => "error", "message" => "school academic year not set", "data" => "");
            }
        }else{
                $data = array("status" => "error", "message" => "Institute not found", "data" => "");
            }
        
        echo json_encode($data);
         
     }
}