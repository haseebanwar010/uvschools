<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Online_exams extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    function student(){
        $class_id=0;
        $batch_id=0;
        $academic_year_id=0;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student_id = $this->session->userdata("userdata")["user_id"];
        $record = $this->admin_model->dbSelect("class_id,batch_id,academic_year_id,subject_group_id","students_".$school_id," id='$student_id' ");
        if(count($record) > 0){
            $class_id = $record[0]->class_id;
            $batch_id = $record[0]->batch_id;
            $academic_year_id = $record[0]->academic_year_id;
            $subject_group_id = $record[0]->subject_group_id;
            if($subject_group_id == null || $subject_group_id == 0){
                $subjects_result = $this->db->select('id')->from('sh_subjects')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('deleted_at is null')->get()->result();
                $subjects = array();
                if(count($subjects_result) > 0){
                    foreach($subjects_result as $s){
                        $subjects[] = $s->id;
                    }
                }
            }else{
                $subjects = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $subject_group_id)->get()->row()->subjects;
                $subjects = explode(",", $subjects);
            }

            $final_subjects = array();

            foreach($subjects as $s){
                $final_subjects[] = $s;

                $code = $this->db->select('code')->from('sh_subjects')->where('id', $s)->get()->row()->code;

                $same_subjects = $this->db->select('id')->from('sh_subjects')->where('code', $code)->where('class_id', $class_id)->where('deleted_at is null')->where('id <>', $s)->get()->result();

                foreach($same_subjects as $ss){
                    $final_subjects[] = $ss->id;
                }
            }

            $final_subjects = implode(",", $final_subjects);

            $sql = "SELECT d.*, e.title, e.start_date, e.end_date, s.name as subject_name FROM sh_online_exam_details d INNER JOIN sh_online_exams e ON d.exam_id=e.id INNER JOIN sh_subjects s ON d.subject_id=s.id WHERE d.class_id='$class_id' AND find_in_set('$batch_id', d.batch_id) AND d.school_id='$school_id' AND d.academic_year_id='$academic_year_id' AND d.deleted_at IS NULL AND d.subject_id in ($final_subjects) AND d.published = 'yes'";
            $exams = $this->admin_model->dbQuery($sql);
            foreach($exams as $e){
                $e->exam_submited = false;
                $isExamSubmited = $this->admin_model->dbSelect("*","online_exam_answers"," student_id='$student_id' AND exam_id='$e->exam_id' AND paper_id='$e->id' AND deleted_at IS NULL ");
                if(count($isExamSubmited) > 0){
                    $e->exam_submited = true;
                }
                $attempts = 0;
                $attempts_result = $this->db->select('attempts')->from('sh_online_attempts')->where('student_id', $student_id)->where('paper_id', $e->id)->get()->row();

                if($attempts_result){
                    $attempts = $attempts_result->attempts;
                }
                $e->attempts = $attempts;
            }
            $data["exams"] = $exams;
        }else{
            $data["exams"] = array();
        }

        
        $this->load->view('online_exams/student_view', $data);
    }

    function settings() {
        $role_id = $this->session->userdata("userdata")["role_id"];

        if($this->session->userdata("userdata")["department_id"] == get_teacher_dept_id()){
            $arr = $this->session->userdata("userdata")["persissions"];
            $array = json_decode($arr);
            if (isset($array)) {
                $OexamAddSettings = $OexamEdit = $OexamDelete = 0;
                foreach ($array as $key => $value) {
                    if (in_array('online_exams-addSettings', array($value->permission)) && $value->val == 'true') {
                        $OexamAddSettings = '1';
                    }if (in_array('online_exams-settingsEdit', array($value->permission)) && $value->val == 'true') {
                        $OexamEdit = '1';
                    }if (in_array('online_exams-settingsDelete', array($value->permission)) && $value->val == 'true') {
                        $OexamDelete = '1';
                    }
                }
            }
        }

        if($role_id == 1){
            $exams = xcrud_get_instance();
            $exams->table('sh_online_exams');
            $exams->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
            $exams->show_primary_ai_field(false);
            $exams->columns('title,start_date,end_date,classes');
            $exams->fields('title,start_date,end_date,classes');
            $exams->label('title', lang('lbl_exam_session'))->label('start_date', lang('start_date'))->label('end_date', lang('end_date'))->label('classes', lang('lbl_classes'));
            $exams->relation('classes', 'sh_classes', 'id', 'name', "sh_classes.deleted_at is null AND sh_classes.school_id='" . $this->session->userdata("userdata")["sh_id"] . "' AND sh_classes.academic_year_id = '".$this->session->userdata("userdata")["academic_year"]."'", '', true, '', '', '', '');
            $exams->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $exams->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
            $exams->before_insert('check_exam_name');
            $exams->before_update('check_exam_name_update');
            $exams->load_view("view", "customview.php");
            $exams->unset_print();
            $exams->replace_remove('soft_delete');
            $exams->unset_csv();
            $exams->unset_title();
            $exams->table_name("Online Exams");
            $data["exams_xcrud"] = $exams->render();


            $exams = xcrud_get_instance();
            $exams->table('sh_online_exam_details');
            $exams->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
            $exams->show_primary_ai_field(false);
            $exams->columns('exam_id,class_id,batch_id,subject_id,paper_name,number_of_questions,duration_in_minutes');
            $exams->fields('exam_id,class_id,batch_id,subject_id,paper_name,number_of_questions,duration_in_minutes');
            $exams->relation('exam_id', 'sh_online_exams', 'id', 'title', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
            $exams->relation('class_id', 'sh_classes_exams', 'id', 'name', '', '', '', '', '', 'exam_id', 'exam_id');
            $exams->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', true, '', '', 'class_id', 'class_id');
            $exams->relation('subject_id', 'sh_subjects', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
            $exams->label('exam_id', lang('lbl_exam_session'))->label('class_id', lang('lbl_class'))->label('batch_id', lang('lbl_batch'))->label('paper_name', lang('paper_name'))->label('number_of_questions', lang('number_of_questions'))->label('duration_in_minutes', lang('duration_in_minuts'));
            $exams->label('subject_id', lang('lbl_subject'));
            $exams->disabled('number_of_questions','edit');
            $exams->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $exams->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
            $exams->before_insert('check_exam_details_name');
            $exams->before_update('check_exam_details_name_update');
            $exams->load_view("view", "customview.php");
            $exams->unset_print();
            $exams->replace_remove('soft_delete');
            $exams->unset_csv();
            $exams->unset_title();
            $exams->table_name("Online Exam Details");
            $data["exam_details"] = $exams->render();
        }else if($role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){

            $teacher_data = get_subjs_batches_classes($this->session->userdata("userdata")["user_id"]);
            $classes = array_values($teacher_data["classes"]);
            $classes_coma = implode(",", $classes);
            $classes_string = "";
            foreach ($classes as $key => $class) {
                $temp = "find_in_set(".$class.", classes)";
                if(isset($classes[$key + 1])){
                    $temp = $temp." or ";
                }
                $classes_string .= $temp;
            }
            
            $batches = array_values($teacher_data["batches"]);
            $batches_coma = implode(",", $batches);
            $batches_string = "";
            foreach ($batches as $key => $batch) {
                $temp = "find_in_set(".$batch.", batch_id)";
                if(isset($batches[$key + 1])){
                    $temp = $temp." or ";
                }
                $batches_string .= $temp;
            }
            $subjects = $teacher_data["subjects"];
            $new_subjects = array();
            foreach ($subjects as $subject) {
                $new_subjects[] = $subject;
                //$code = $this->db->select('code')->from('sh_subjects')->where('id', $subject)->get()->row();
                $code = $this->db->select('code')->from('sh_subjects')->where('id', $subject)->where('deleted_at is null', null, false)->get()->row();
                if($code){
                    $code = $code->code;
                    $similar_subjects = $this->db->select('id')->from('sh_subjects')->where('code', $code)->where_in('class_id', $classes)->get()->result();
                    foreach($similar_subjects as $sim){
                        $new_subjects[] = $sim->id;
                    }
                }
                
            }
            $new_subjects = array_unique($new_subjects);
            $new_subjects = array_values($new_subjects);
            $subjects_coma = implode(",", $new_subjects);
            $exams = xcrud_get_instance();
            $exams->table('sh_online_exams');
            $exams->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
            $exams->where($classes_string);
            
            $exams->show_primary_ai_field(false);
            $exams->columns('title,start_date,end_date,classes');
            $exams->fields('title,start_date,end_date,classes');
            $exams->label('title', lang('lbl_exam_session'))->label('start_date', lang('start_date'))->label('end_date', lang('end_date'))->label('classes', lang('lbl_classes'));
            $exams->relation('classes', 'sh_classes', 'id', 'name', "sh_classes.deleted_at is null AND sh_classes.school_id='" . $this->session->userdata("userdata")["sh_id"] . "' AND sh_classes.id in (".$classes_coma.") AND sh_classes.academic_year_id = '".$this->session->userdata("userdata")["academic_year"]."'", '', true, '', '', '', '');
            $exams->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $exams->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
            $exams->before_insert('check_exam_name');
            $exams->before_update('check_exam_name_update');
            $exams->load_view("view", "customview.php");
            $exams->unset_print();
            $exams->replace_remove('soft_delete');
            $exams->unset_csv();
            $exams->unset_title();
            if(isset($OexamAddSettings)){
                if($OexamAddSettings == 0){
                    $exams->unset_add();
                }
            }
            if(isset($OexamEdit)){
                if($OexamEdit == 0){
                    $exams->unset_edit();
                }
            }
            if(isset($OexamDelete)){
                if($OexamDelete == 0){
                    $exams->unset_remove();
                }
            }
            $exams->table_name("Online Exams");
            
            $data["exams_xcrud"] = $exams->render();


            $exams = xcrud_get_instance();
            $exams->table('sh_online_exam_details');
            $exams->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
            $exams->show_primary_ai_field(false);
            $exams->columns('exam_id,class_id,batch_id,subject_id,paper_name,number_of_questions,duration_in_minutes');
            $exams->fields('exam_id,class_id,batch_id,subject_id,paper_name,number_of_questions,duration_in_minutes');
            $exams->where('class_id', $classes);
            $exams->where($batches_string);
            $exams->where('subject_id', $new_subjects);
            $exams->relation('exam_id', 'sh_online_exams', 'id', 'title', '('.$classes_string.') AND deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
            $exams->relation('class_id', 'sh_classes_exams', 'id', 'name', 'sh_classes_exams.id in ('.$classes_coma.')', '', '', '', '', 'exam_id', 'exam_id');
            $exams->relation('batch_id', 'sh_batches', 'id', 'name', 'sh_batches.id in ('.$batches_coma.') AND deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', true, '', '', 'class_id', 'class_id');
            $exams->relation('subject_id', 'sh_subjects', 'id', 'name', 'sh_subjects.id in ('.$subjects_coma.') AND deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
            $exams->label('exam_id', lang('lbl_exam_session'))->label('class_id', lang('lbl_class'))->label('batch_id', lang('lbl_batch'));
            $exams->label('subject_id', lang('lbl_subject'));
            $exams->disabled('number_of_questions','edit');
            $exams->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $exams->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
            $exams->before_insert('check_exam_details_name');
            $exams->before_update('check_exam_details_name_update');
            $exams->load_view("view", "customview.php");
            $exams->unset_print();
            $exams->replace_remove('soft_delete');
            $exams->unset_csv();
            $exams->unset_title();
            if(isset($OexamAddSettings)){
                if($OexamAddSettings == '0'){
                    $exams->unset_add();
                }
            }
            if(isset($OexamEdit)){
                if($OexamEdit == '0'){
                    $exams->unset_edit();
                }
            }
            if(isset($OexamDelete)){
                if($OexamDelete == '0'){
                    $exams->unset_remove();
                }
            }
            $exams->table_name("Online Exam Details");
            
            $data["exam_details"] = $exams->render();
        }else{
            $data["exams"] = "You don't have access.";
            $data["exam_details"] = "You don't have access.";
        }
        
        
        $pending_exams=$this->db->select('id,title')->from('sh_online_exams')->where('school_id',$this->session->userdata("userdata")["sh_id"])->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where('deleted_at is NULL',NULL)->get()->result();
        $data['pending_exams']=$pending_exams;
        
        $this->load->view('online_exams/settings', $data);
    }

    public function add_question (){
        $this->load->view("online_exams/add_question_form");
    }

    public function getExams(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $data = $this->admin_model->dbSelect("*","online_exams","school_id='$school_id' AND academic_year_id='$academic_year_id' AND deleted_at IS NULL ");
        if(count($data) > 0){
            $response = array("status"=>"success","message"=>"data found!", "data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>"Not found any online exam!", "data"=>array());
        }
        echo json_encode($response);
    }


    function getSpecificExams() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        //$active_academic_year = $this->admin_model->dbSelect("id", "academic_years", " school_id='$school_id' AND deleted_at IS NULL AND is_active='Y' ")[0]->id;
        $active_academic_year = $request->academic_year_id;
        $code = $this->db->select('code')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->code;
        $subjects = $this->db->select('id')->from('sh_subjects')->where('code', $code)->where('class_id', $request->class_id)->where('deleted_at is null')->get()->result();
        $subjects_ids = array();
        foreach($subjects as $s){
            $subjects_ids[] = $s->id;
        }
        $subjects_ids = implode(",", $subjects_ids);
        $sql = "SELECT * FROM sh_online_exams e "
        . "LEFT JOIN sh_online_exam_details d ON e.id=d.exam_id "
        . "WHERE "
        . "d.class_id='$request->class_id' "
        . "AND e.academic_year_id='$active_academic_year' "
        . "AND find_in_set('$request->batch_id',d.batch_id) "
        . "AND d.subject_id in ($subjects_ids) "
        . "AND d.school_id='$school_id' "
        . "AND e.deleted_at IS NULL "
        . "AND d.deleted_at IS NULL ";
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }

    
    public function getExamsForStudent(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $user_id = $this->session->userdata("userdata")["user_id"];

        $student = $this->db->select('s.id, s.class_id, s.batch_id', false)->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->join('sh_classes c', 'c.id = s.class_id')->join('sh_batches b', 'b.id = s.batch_id')->where('s.id', $user_id)->get()->row();

        if($student){
            $data = $this->admin_model->dbSelect("*","online_exams","school_id='$school_id' AND academic_year_id='$academic_year_id' AND deleted_at IS NULL AND find_in_set(".$student->class_id.",classes)");
            if(count($data) > 0){
                $response = array("status"=>"success","message"=>"data found!", "data"=>$data);
            } else {
                $response = array("status"=>"error","message"=>"Not found any online exam!", "data"=>array());
            }
        }else{
            $response = array("status"=>"error","message"=>"Not found any online exam!", "data"=>array());
        }
        
        echo json_encode($response);
    }

    public function getPapersForStudent() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $exam_id = $request->exam_id;

        $user_id = $this->session->userdata("userdata")["user_id"];
        $student = $this->db->select('s.id, s.class_id, s.batch_id,s.subject_group_id', false)->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->join('sh_classes c', 'c.id = s.class_id')->join('sh_batches b', 'b.id = s.batch_id')->where('s.id', $user_id)->get()->row();

        $subject_group_id = $student->subject_group_id;
        if($subject_group_id == null || $subject_group_id == 0){
            $subjects_result = $this->db->select('id')->from('sh_subjects')->where('class_id', $student->class_id)->where('batch_id', $student->batch_id)->where('deleted_at is null')->get()->result();
            $subjects = array();
            if(count($subjects_result) > 0){
                foreach($subjects_result as $s){
                    $subjects[] = $s->id;
                }
            }
        }else{
            $subjects = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $student->subject_group_id)->get()->row()->subjects;
            $subjects = explode(",", $subjects);
        }

        $final_subjects = array();

        foreach($subjects as $s){
            $final_subjects[] = $s;

            $code = $this->db->select('code')->from('sh_subjects')->where('id', $s)->get()->row()->code;

            $same_subjects = $this->db->select('id')->from('sh_subjects')->where('code', $code)->where('class_id', $student->class_id)->where('deleted_at is null')->where('id <>', $s)->get()->result();

            foreach($same_subjects as $ss){
                $final_subjects[] = $ss->id;
            }
        }

        

        $data = $this->db->select('id, paper_name')->from('sh_online_exam_details')->where('class_id', $student->class_id)->where('find_in_set('.$student->batch_id.',batch_id) > 0')->where_in('subject_id', $final_subjects)->where('exam_id', $exam_id)->where('published', 'yes')->where('deleted_at is null')->get()->result();

        if(count($data) > 0){
            $response = array("status"=>"success","message"=>"data found", "data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>"data not found!", "data"=>array());
        }
        echo json_encode($response);
    }


    public function getPapers() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
           $where_part =  " AND s.id IN (". implode(',', login_user()->t_data->subjects) .") AND b.id IN (". implode(',', login_user()->t_data->batches) .")";
        //   echo '<pre>';
        //   print_r(login_user()->t_data->subjects);
        //   echo '<pre>';
        //   print_r(login_user()->t_data->batches);
        //   die;
        //   echo json_encode('die');
        //   die;
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 

        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){

        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }

        $sql = "SELECT ed.*, c.name as class_name, s.name as subject_name FROM sh_online_exam_details ed INNER JOIN sh_classes c ON ed.class_id=c.id JOIN sh_batches b ON b.id = ed.batch_id INNER JOIN sh_subjects s ON ed.subject_id=s.id WHERE ed.exam_id='$request->exam_id' AND ed.deleted_at IS NULL AND ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.class_id = $class_id";
        
        // echo $sql.$where_part;
        // die;
        // echo json_encode('die');
        // die;
        
        $data = $this->admin_model->dbQuery($sql);
        echo '';
        foreach($data as $d){
            $batch_names = array();
            $batch_ids = explode(",", $d->batch_id);
            foreach ($batch_ids as $bi) {
                $batch_names[] = $this->db->select('name')->from('sh_batches')->where('id', $bi)->get()->row()->name;
            }
            $batch_names = implode(",", $batch_names);
            $d->batch_name = $batch_names;

        }
        if(count($data) > 0){
            $response = array("status"=>"success","message"=>"data found", "data"=>$data);
        } else {
            $response = array("status"=>"error","message"=>"data not found!", "data"=>array());
        }
        echo json_encode($response);
    }

    public function saveQuestion(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $isExists = $this->admin_model->dbSelect("*","online_questions"," exam_id='$request->exam_id' AND paper_id='$request->paper_id' AND school_id='$school_id' AND academic_year_id='$academic_year_id' AND question_no='$request->question_no' AND deleted_at IS NULL ");
        if(count($isExists) > 0){
            // Question already exists
            $where = array("id" => $isExists[0]->id);
            if($request->question_type == 'true_false'){
                $stdClass = new stdClass();
                $stdClass->option_1 = "";
                $stdClass->option_2 = "";
                $stdClass->option_3 = "";
                $stdClass->option_4 = "";
                $request->options = $stdClass;
            } else if($request->question_type == 'single_fill_in_the_blank'){
                $stdClass = new stdClass();
                $stdClass->option_1 = "";
                $stdClass->option_2 = "";
                $stdClass->option_3 = "";
                $stdClass->option_4 = "";
                $request->options = $stdClass;

                $request->correct_answer_1 = null;
                $request->correct_answer_2 = null;
                $request->correct_answer_3 = null;
                $request->correct_answer_4 = null;
            } else if($request->question_type == 'double_fill_in_the_blank'){
                $stdClass = new stdClass();
                $stdClass->option_1 = "";
                $stdClass->option_2 = "";
                $stdClass->option_3 = "";
                $stdClass->option_4 = "";
                $request->options = $stdClass;
                $request->correct_answer = "";
                $request->correct_answer_3 = null;
                $request->correct_answer_4 = null;
            } else if($request->question_type == 'multi_answer') {
                $request->correct_answer = "";
                $request->correct_answer_1 = $request->correct_answer_1 == true ? 'a':null;
                $request->correct_answer_2 = $request->correct_answer_2 == true ? 'b':null;
                $request->correct_answer_3 = $request->correct_answer_3 == true ? 'c':null;
                $request->correct_answer_4 = $request->correct_answer_4 == true ? 'd':null;
                $request->correct_answer_5 = $request->correct_answer_5 == true ? 'e':null;
            }
            $data = array(
                'question_marks' => $request->question_marks, 
                'question' => $request->question, 
                'question_type' => $request->question_type, 
                'options' => json_encode($request->options), 
                'correct_answer' => $request->correct_answer, 
                'correct_answer_1' => $request->correct_answer_1,
                'correct_answer_2' => $request->correct_answer_2, 
                'correct_answer_3' => $request->correct_answer_3, 
                'correct_answer_4' => $request->correct_answer_4, 
                'correct_answer_5' => $request->correct_answer_5, 
                'created_by' => $this->session->userdata("userdata")["user_id"], 
                'school_id' => $school_id, 
                'academic_year_id' => $academic_year_id, 
                'exam_id' => $request->exam_id, 
                'paper_id' => $request->paper_id
            );
            $this->common_model->update_where("sh_online_questions",$where, $data);
            $response = array("status"=>"success","message"=>"Question number ".$isExists[0]->question_no." updated successfully!");
        } else {
            // Question does't exists
            if($request->question_type == 'double_fill_in_the_blank'){
                $stdClass = new stdClass();
                $stdClass->option_1 = "";
                $stdClass->option_2 = "";
                $stdClass->option_3 = "";
                $stdClass->option_4 = "";
                $request->options = $stdClass;
                $request->correct_answer = "";
                $request->correct_answer_3 = null;
                $request->correct_answer_4 = null;
            } else if($request->question_type == 'multi_answer') {
                $request->correct_answer = "";
                $request->correct_answer_1 = $request->correct_answer_1 == true ? 'a':null;
                $request->correct_answer_2 = $request->correct_answer_2 == true ? 'b':null;
                $request->correct_answer_3 = $request->correct_answer_3 == true ? 'c':null;
                $request->correct_answer_4 = $request->correct_answer_4 == true ? 'd':null;
                $request->correct_answer_5 = $request->correct_answer_5 == true ? 'e':null;
            }
            $data = array(
                'question_no' => $request->question_no,
                'question_marks' => $request->question_marks,
                'question' => $request->question, 
                'question_type' => $request->question_type, 
                'options' => json_encode($request->options), 
                'correct_answer' => $request->correct_answer, 
                'correct_answer_1' => $request->correct_answer_1,
                'correct_answer_2' => $request->correct_answer_2, 
                'correct_answer_3' => $request->correct_answer_3, 
                'correct_answer_4' => $request->correct_answer_4, 
                'correct_answer_5' => $request->correct_answer_5, 
                'created_by' => $this->session->userdata("userdata")["user_id"], 
                'school_id' => $school_id, 
                'academic_year_id' => $academic_year_id, 
                'exam_id' => $request->exam_id, 
                'paper_id' => $request->paper_id
            );
            $this->admin_model->dbInsert("online_questions",$data);
            $response = array("status"=>"success","message"=>"Question ".$request->question_no." saved successfully!");
        }
        echo json_encode($response);
    }

    public function getPaperQuestions(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $sql = "SELECT q.*, e.title, d.paper_name,d.number_of_questions,d.duration_in_minutes, ss.name as subject_name FROM sh_online_exams e INNER JOIN sh_online_exam_details d ON e.id=d.exam_id INNER JOIN sh_subjects ss ON d.subject_id=ss.id LEFT JOIN sh_online_questions q ON q.exam_id=e.id AND q.paper_id=d.id WHERE e.id=$request->exam_id AND d.id=$request->paper_id AND q.deleted_at IS NULL ";
        $data = $this->admin_model->dbQuery($sql);

        $published = $this->db->select('published')->from('sh_online_exam_details')->where('id', $request->paper_id)->get()->row()->published;
        
        $newData = array();
        if(count($data) > 0){

            // numbers of questions 1 to number_of_questions
            $arr = array();
            for($i=1; $i<=$data[0]->number_of_questions; $i++){
              
                $arr2 = array("value"=>$i, "title"=> "Question".$i);
                array_push($arr, $arr2);
            }

            for($j=1; $j<=$data[0]->number_of_questions; $j++){
                $options = new stdClass();
                $options->option_1 = "";
                $options->option_2 = "";
                $options->option_3 = "";
                $options->option_4 = "";
                $options->option_5 = "";

                $obj = new stdClass();
                $obj->id = null;
                $obj->question_no = strval($j);
                $obj->question_marks = '';
                $obj->question = "";
                $obj->question_type = "";
                $obj->options = $options;
                $obj->correct_answer = "";
                $obj->correct_answer_1 = null;
                $obj->correct_answer_2 = null;
                $obj->correct_answer_3 = null;
                $obj->correct_answer_4 = null;
                $obj->correct_answer_5 = null;
                $obj->created_by = "";
                $obj->exam_id = $request->exam_id;
                $obj->paper_id = $request->paper_id;
                $obj->question_form_id = "question-form-".$j;
                $obj->question_numbers = $arr;
                $obj->duration_in_minutes = $data[0]->duration_in_minutes;
                $obj->number_of_questions = $data[0]->number_of_questions;
                $obj->subject_name = $data[0]->subject_name;
                $obj->paper_name = $data[0]->paper_name;
                $newData[$j] = $obj;
            }
            
            foreach($newData as $key=>$dd){
                foreach($data as $d){
                    if($key == $d->question_no){
                        $dd->id = $d->id;
                        $dd->question_no = $d->question_no;
                        $dd->question_marks = intval($d->question_marks);
                        $dd->question = $d->question;
                        $dd->question_type = $d->question_type;
                        $dd->options = json_decode($d->options);
                        $dd->correct_answer = $d->correct_answer;
                        $dd->correct_answer_1 = $d->correct_answer_1;
                        $dd->correct_answer_2 = $d->correct_answer_2;
                        $dd->correct_answer_3 = $d->correct_answer_3;
                        $dd->correct_answer_4 = $d->correct_answer_4;
                        $dd->correct_answer_5 = $d->correct_answer_5;
                        $dd->created_by = $d->created_by;
                    }
                }
            } 
        }
        echo json_encode(array("status"=>"success","data"=>$newData,"published"=>$published));
    }

    function getPaperQuestionsForStudent(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $user_id = $this->session->userdata("userdata")["user_id"];
        $sql = "SELECT q.*, ea.id as answer_id, ea.answer, ea.second_answer, e.title, d.paper_name,d.number_of_questions,d.duration_in_minutes, ss.name as subject_name FROM sh_online_exams e INNER JOIN sh_online_exam_details d ON e.id=d.exam_id INNER JOIN sh_subjects ss ON d.subject_id=ss.id LEFT JOIN sh_online_questions q ON q.exam_id=e.id AND q.paper_id=d.id LEFT JOIN sh_online_exam_answers ea on q.id = ea.question_id AND ea.student_id = $user_id AND ea.deleted_at is null WHERE e.id=$request->exam_id AND d.id=$request->paper_id AND q.deleted_at IS NULL  ";
        $data = $this->admin_model->dbQuery($sql);

        $published = $this->db->select('published')->from('sh_online_exam_details')->where('id', $request->paper_id)->get()->row()->published;

        $check = false;

        $paper = false;
        $number_of_questions = $data[0]->number_of_questions;
        $added_questions = 0;

        $alphabets = array();
        $alphabets["option_1"] = "a";
        $alphabets["option_2"] = "b";
        $alphabets["option_3"] = "c";
        $alphabets["option_4"] = "d";
        $alphabets["option_5"] = "e";

        $alphabets_reference = array();
        $alphabets_reference[] = "a";
        $alphabets_reference[] = "b";
        $alphabets_reference[] = "c";
        $alphabets_reference[] = "d";
        $alphabets_reference[] = "e";

        foreach ($data as $d) {
            if($d->answer_id != null && $d->answer_id != ""){
                $check = true;
            }

            if($d->id != null && $d->id != ""){
                $added_questions++;
            }

            $d->options = json_decode($d->options);
            $d->alphabets = $alphabets;
            $d->alphabets_reference = $alphabets_reference;
            if($d->question_type == "double_fill_in_the_blank"){
                $temp = new stdClass();
                $temp->first = $d->answer;
                $temp->second = $d->second_answer;
                $d->answer = $temp; 
            }else if($d->question_type == "multi_answer"){
                $answer = $d->answer;
                $temp = array();
                $temp[0] = false;
                $temp[1] = false;
                $temp[2] = false;
                $temp[3] = false;
                $temp[4] = false;
                
                if($answer != ""){
                    $answers = explode(",", $answer);
                    if(in_array("a", $answers)) $temp[0] = true;
                    if(in_array("b", $answers)) $temp[1] = true;
                    if(in_array("c", $answers)) $temp[2] = true;
                    if(in_array("d", $answers)) $temp[3] = true;
                    if(in_array("e", $answers)) $temp[4] = true;
                }
                $d->answer = $temp;
            }
        }

        if($number_of_questions == $added_questions){
            $paper = true;
        }



        echo json_encode(array("status"=>"success","data"=>$data, "submitted"=>$check, "paper"=>$paper, "published" => $published));
    }

    function submitPaper(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $user_id = $this->session->userdata("userdata")["user_id"];
        $paper_id = $request[0]->paper_id;
        $attempts = 1;
        $attempts_result = $this->db->select('attempts')->from('sh_online_attempts')->where('student_id', $user_id)->where('paper_id', $paper_id)->get()->row();

        if($attempts_result){
            $attempts = $attempts_result->attempts + 1;
        }

        $attempt_data = array("student_id" => $user_id, "paper_id" => $paper_id, "attempts" => $attempts);
        $this->db->replace("sh_online_attempts", $attempt_data);
        $alphabets = array("a", "b", "c", "d", "e");
        foreach ($request as $r) {
            $paper_data = array();
            $paper_data = array("student_id" => $user_id,
                "school_id" => $r->school_id,
                "academic_year_id" => $r->academic_year_id,
                "exam_id" => $r->exam_id,
                "paper_id" => $r->paper_id,
                "question_id" => $r->id);
            if($r->question_type == "single_answer" || $r->question_type == "true_false" || $r->question_type == "single_fill_in_the_blank"){
                $paper_data["answer"] = $r->answer;
            }else if($r->question_type == "multi_answer"){
                $temp = array();
                foreach($r->answer as $key => $value){
                    if($value){
                        $temp[] = $alphabets[$key];
                    }
                }
                $paper_data["answer"] = implode(",", $temp);
            }else if($r->question_type == "double_fill_in_the_blank"){
                $paper_data["answer"] = $r->answer->first;
                $paper_data["second_answer"] = $r->answer->second;
            }
            if($r->answer_id == null){
                $this->db->insert('sh_online_exam_answers', $paper_data);
            }else{
                $this->db->where('id', $r->answer_id)->update('sh_online_exam_answers', $paper_data);
            }
        }
        $this->session->set_flashdata("success_message", "Your paper submited successfully!");
        echo json_encode(array("status"=>"success", "message"=>"Paper submited successfully!"));
    }

    public function start_exam($exam_id=0, $paper_id=0) {
        $data["exam_id"] = $exam_id;
        $data["paper_id"] = $paper_id;
        $user_id = $this->session->userdata("userdata")["user_id"];
        $sql = "SELECT q.*, ea.id as answer_id, ea.answer, ea.second_answer, e.title, d.paper_name,d.number_of_questions,d.duration_in_minutes, ss.name as subject_name FROM sh_online_exams e INNER JOIN sh_online_exam_details d ON e.id=d.exam_id INNER JOIN sh_subjects ss ON d.subject_id=ss.id LEFT JOIN sh_online_questions q ON q.exam_id=e.id AND q.paper_id=d.id LEFT JOIN sh_online_exam_answers ea on q.id = ea.question_id AND ea.student_id = $user_id AND ea.deleted_at is null WHERE e.id=$exam_id AND d.id=$paper_id AND q.deleted_at IS NULL  ";
        $data2 = $this->admin_model->dbQuery($sql);

        if(count($data2) == 0){
            $this->session->set_flashdata("paper_message", "Paper not found!");
            redirect('online_exams/student');
        }

        $published = $this->db->select('published')->from('sh_online_exam_details')->where('id', $paper_id)->get()->row()->published;

        if($published == "no"){
            $this->session->set_flashdata("published_message", "This paper is not published!");
            redirect('online_exams/student');
        }

        $submitted = false;
        
        $alphabets = array();
        $alphabets["option_1"] = "a";
        $alphabets["option_2"] = "b";
        $alphabets["option_3"] = "c";
        $alphabets["option_4"] = "d";
        $alphabets["option_5"] = "e";

        foreach ($data2 as $d) {
            if($d->answer_id != null && $d->answer_id != ""){
                $submitted = true;
            }
        }
        if($submitted){
            $this->session->set_flashdata("submitted_message", "This paper is submitted already!");
            redirect('online_exams/student');
        }
        $this->load->view("online_exams/start_exam", $data);
    }

    public function view_exam($exam_id=0, $paper_id=0) {
        $data["exam_id"] = $exam_id;
        $data["paper_id"] = $paper_id;
        $this->load->view("online_exams/view_exam", $data);
    }

    public function results () {
        $this->load->view("online_exams/results");
    }

    public function major_sheet(){
        $this->load->view("online_exams/major_sheet");
    }

    public function getMainExams(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;

        $data["exams"] = $this->db->select('id, title')->from('sh_online_exams')->where('find_in_set('.$class_id.', classes)')->where('deleted_at is null')->get()->result();
        echo json_encode($data);
    }

    public function getCode($list, $id){
        foreach($list as $l){
            if($l->id == $id){
                return $l->code;
            }
        }
        return "";
    }

    public function getSameSubjects($list, $code, $id){
        $result = array();
        foreach($list as $l){
            if($l->code == $code && $l->id != $id){
                $result[] = $l;
            }
        }
        return $result;
    }

    function save_teacher_remarks() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $result = $this->db->select('id')->from('sh_remarks_and_positions')->where('student_id', $request->student_id)->where('online_exam_id', $request->exam_id)->get()->row();
        if($result){
            $res = $this->common_model->update_where("sh_remarks_and_positions", array("id" => $result->id), array("remark" => $request->remark));
        }else{
            $data = array("student_id" => $request->student_id, "online_exam_id" => $request->exam_id, "school_id" => $school_id, "remark" => $request->remark);
            $this->db->insert("sh_remarks_and_positions", $data);
        }
        
        echo json_encode(array("status" => "success", "message" => "Teacher remarks added successfully!"));
        
    }

    public function getMajorSheet(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $academic_year_id = $request->academic_year_id;
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $data["exam_id"] = $exam_id = $request->exam_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $data["class_name"] = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
        $data["batch_name"] = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
        $data["exam_name"] = $this->db->select('title')->from('sh_online_exams')->where('id', $exam_id)->get()->row()->title;
        
        $sql = "SELECT s.id,name,avatar,class_id,batch_id,subject_group_id,rollno, remark FROM sh_students_".$school_id." s left join sh_remarks_and_positions r on r.online_exam_id = $exam_id and r.student_id = s.id WHERE class_id='$class_id' AND batch_id='$batch_id' AND s.deleted_at=0";
        $students = $this->admin_model->dbQuery($sql);

        $papers = $this->db->select('id, paper_name, subject_id')->from('sh_online_exam_details')->where('exam_id', $exam_id)->where('class_id', $class_id)->where('find_in_set('.$batch_id.', batch_id) > 0')->where('published', 'yes')->where('deleted_at is null')->get()->result();

        $code_list = $this->db->select('id, code')->from('sh_subjects')->where('class_id', $class_id)->where('batch_id', $batch_id)->get()->result();

        $same_subjects_list = $this->db->select('id, code')->from('sh_subjects')->where('class_id', $class_id)->where('deleted_at is null')->get()->result();

        foreach($students as $std){
            $subject_group_id = $std->subject_group_id;
            if($subject_group_id == null || $subject_group_id == 0){
                $subjects_result = $this->db->select('id')->from('sh_subjects')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('deleted_at is null')->get()->result();
                $subjects = array();
                if(count($subjects_result) > 0){
                    foreach($subjects_result as $s){
                        $subjects[] = $s->id;
                    }
                }
            }else{
                $subjects = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $subject_group_id)->get()->row()->subjects;
                $subjects = explode(",", $subjects);
            }

            $final_subjects = array();

            foreach($subjects as $s){
                $final_subjects[] = $s;

                // $code = $this->db->select('code')->from('sh_subjects')->where('id', $s)->get()->row()->code;
                $code = $this->getCode($code_list, $s);

                // $same_subjects = $this->db->select('id')->from('sh_subjects')->where('code', $code)->where('class_id', $class_id)->where('deleted_at is null')->where('id <>', $s)->get()->result();

                $same_subjects = $this->getSameSubjects($same_subjects_list, $code, $s);

                foreach($same_subjects as $ss){
                    $final_subjects[] = $ss->id;
                }
            }


            $current_students_papers = array();
            $std->total_marks = 0;
            $std->obtained_marks = 0;
            foreach($papers as $c_p){

                $temp = new stdClass();
                if(in_array($c_p->subject_id, $final_subjects)){
                    $temp->valid = $valid = true;
                }else{
                    $temp->valid = $valid = false;
                }
                $sql = "SELECT a.*, q.correct_answer, q.question_type, q.correct_answer_1, q.correct_answer_2, q.correct_answer_3, q.correct_answer_4, q.correct_answer_5, q.question_marks,q.question, q.question_no, q.options FROM sh_online_exam_answers a INNER JOIN sh_online_questions q ON a.question_id=q.id WHERE a.student_id='$std->id' AND a.school_id='$school_id' AND a.academic_year_id='$academic_year_id' AND a.exam_id='$exam_id' AND a.paper_id='$c_p->id' AND a.deleted_at IS NULL ORDER BY a.question_id ASC ";
                $paperData = $this->admin_model->dbQuery($sql);




                $temp->number_of_correct_answers = 0;
                $temp->number_of_incorrect_answers = 0;
                $temp->obtained_marks = 0;
                $temp->total_marks = 0;
                if(count($paperData) == 0 && $valid){
                    $temp->total_marks = $this->db->select('coalesce(sum(question_marks), 0) as total', false)->from('sh_online_questions')->where('paper_id', $c_p->id)->where('deleted_at is null')->get()->row()->total;
                }
                foreach($paperData as $p){
                    $temp->total_marks += $p->question_marks;
                    if($p->question_type == "single_answer" || $p->question_type == "true_false" || $p->question_type == "single_fill_in_the_blank") {
                        $newArray = array();
                        if(strtolower($p->answer) == strtolower($p->correct_answer)){
                            $temp->number_of_correct_answers++;
                            $temp->obtained_marks += $p->question_marks;
                        } else {
                            $temp->number_of_incorrect_answers++;
                        }
                    } else if($p->question_type == "double_fill_in_the_blank") {
                        if(strtolower($p->answer) == strtolower($p->correct_answer_1) && strtolower($p->second_answer) == strtolower($p->correct_answer_2)){
                            $temp->number_of_correct_answers++;
                            $temp->obtained_marks += $p->question_marks;
                        } else {
                            $temp->number_of_incorrect_answers++;
                        }
                    } else if($p->question_type == "multi_answer"){
                        $get_correct = array();
                        $get_correct[] = $p->correct_answer_1;
                        $get_correct[] = $p->correct_answer_2;
                        $get_correct[] = $p->correct_answer_3;
                        $get_correct[] = $p->correct_answer_4;
                        $get_correct[] = $p->correct_answer_5;

                        $correct_answers = implode(",", array_filter($get_correct));

                        if($p->answer == $correct_answers){
                            $temp->number_of_correct_answers++;
                            $temp->obtained_marks += $p->question_marks;
                        } else {
                            $temp->number_of_incorrect_answers++;
                        }
                    }
                } 
                $current_students_papers[] = $temp;
                $std->total_marks += $temp->total_marks;
                $std->obtained_marks += $temp->obtained_marks;
            }
            $std->paper_data = $current_students_papers;

            $std->percentage = "";
            if($std->total_marks != 0){
                $std->percentage = round(($std->obtained_marks/$std->total_marks) * 100, 2);
            }
        }

        usort($students, function($a, $b) {return $a->percentage < $b->percentage;});
        $position = 1;
        $prev_percentage = -1;
        foreach($students as $s){
            $s->position = "-";
            if($s->percentage != 0){
                if($prev_percentage >= 0 && $prev_percentage != $s->percentage) $position++;
                $s->position = $this->position_string($position);
                $prev_percentage = $s->percentage;
            }
        }

        $data["students"] = $students;
        $data["papers"] = $papers;
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

    public function get_results(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $sql = "SELECT id,name,avatar,class_id,batch_id,subject_group_id,rollno FROM sh_students_".$school_id." WHERE class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at=0 ";
        $students = $this->admin_model->dbQuery($sql);
        $number_of_questions = 0;
        $numberOfQuestionArray = $this->admin_model->dbSelect("number_of_questions","online_exam_details"," id='$request->exam_detail_id'");
        if(count($numberOfQuestionArray) > 0){
            $number_of_questions = $numberOfQuestionArray[0]->number_of_questions;
        }
        
        if(count($students) > 0){
            foreach($students as $std){
                $request_log = $this->db->select('status')->from('sh_request_log')->where('online_exam_detail_id', $request->exam_detail_id)->where('student_id', $std->id)->where('marked', 'N')->where('type', 'online_exam_edit')->where('user_id', $this->session->userdata("userdata")["user_id"])->where('deleted_at is null')->get()->row();
                if($request_log){
                    $std->edit_status = $request_log->status;
                }else{
                    $std->edit_status = "not-approved";
                }

                $request_log2 = $this->db->select('status')->from('sh_request_log')->where('online_exam_detail_id', $request->exam_detail_id)->where('student_id', $std->id)->where('marked', 'N')->where('type', 'online_exam_retake')->where('user_id', $this->session->userdata("userdata")["user_id"])->where('deleted_at is null')->get()->row();
                if($request_log2){
                    $std->retake_status = $request_log2->status;
                }else{
                    $std->retake_status = "not-approved";
                }
                $std->paper_record = "";
                $sql = "SELECT a.*, q.correct_answer, q.question_type, q.correct_answer_1, q.correct_answer_2, q.correct_answer_3, q.correct_answer_4, q.correct_answer_5, q.question_marks,q.question, q.question_no, q.options FROM sh_online_exam_answers a INNER JOIN sh_online_questions q ON a.question_id=q.id WHERE a.student_id='$std->id' AND a.school_id='$school_id' AND a.academic_year_id='$request->academic_year_id' AND a.exam_id='$request->exam_id' AND a.paper_id='$request->exam_detail_id' AND a.deleted_at IS NULL ORDER BY a.question_id ASC ";
                $paperData = $this->admin_model->dbQuery($sql);

                $attempts = 0;
                $attempts_result = $this->db->select('attempts')->from('sh_online_attempts')->where('student_id', $std->id)->where('paper_id', $request->exam_detail_id)->get()->row();

                if($attempts_result){
                    $attempts = $attempts_result->attempts;
                }
                $std->attempts = $attempts;
                $std->number_of_correct_answers = 0;
                $std->number_of_incorrect_answers = 0;
                $std->obtained_marks = 0;

                $alphabets_reference = array();
                $alphabets_reference[] = "a";
                $alphabets_reference[] = "b";
                $alphabets_reference[] = "c";
                $alphabets_reference[] = "d";
                $alphabets_reference[] = "e";

                if(count($paperData) > 0){
                    foreach($paperData as $p){
                        if($p->question_type == "single_answer" || $p->question_type == "true_false" || $p->question_type == "single_fill_in_the_blank") {
                            $newArray = array();
                            $p->options = json_decode($p->options);
                            foreach($p->options as $key=>$opt){
                                if($key == "option_1") {
                                    $newArray["a"] = $opt;
                                } else if($key == "option_2") {
                                    $newArray["b"] = $opt;
                                } else if($key == "option_3") {
                                    $newArray["c"] = $opt;
                                } else if($key == "option_4") {
                                    $newArray["d"] = $opt;
                                }
                            }
                            $p->options = $newArray;
                            if(strtolower($p->answer) == strtolower($p->correct_answer)){
                                $std->number_of_correct_answers++;
                                $std->obtained_marks += $p->question_marks;
                            } else {
                                $std->number_of_incorrect_answers++;
                            }
                        } else if($p->question_type == "double_fill_in_the_blank") {
                            if(strtolower($p->answer) == strtolower($p->correct_answer_1) && strtolower($p->second_answer) == strtolower($p->correct_answer_2)){
                                $std->number_of_correct_answers++;
                                $std->obtained_marks += $p->question_marks;
                            } else {
                                $std->number_of_incorrect_answers++;
                            }
                        } else if($p->question_type == "multi_answer"){

                            $p->alphabets_reference = $alphabets_reference;

                            $get_correct = array();
                            $get_correct[] = $p->correct_answer_1;
                            $get_correct[] = $p->correct_answer_2;
                            $get_correct[] = $p->correct_answer_3;
                            $get_correct[] = $p->correct_answer_4;
                            $get_correct[] = $p->correct_answer_5;

                            $correct_answers = implode(",", array_filter($get_correct));

                            $student_answers = $p->answer;
                            $student_answers = explode(",", $student_answers);

                            $p->correct_answer = $correct_answers;


                            $newArray = array();
                            $p->options = json_decode($p->options);
                            foreach($p->options as $key=>$opt){
                                if($key == "option_1") {
                                    $newArray["a"] = $opt;
                                } else if($key == "option_2") {
                                    $newArray["b"] = $opt;
                                } else if($key == "option_3") {
                                    $newArray["c"] = $opt;
                                } else if($key == "option_4") {
                                    $newArray["d"] = $opt;
                                } else if($key == "option_5") {
                                    $newArray["e"] = $opt;
                                }
                            }
                            $p->options = $newArray;
                            $p->updated_answers = array(
                                "a" => in_array("a", $student_answers)?true:false,
                                "b" => in_array("b", $student_answers)?true:false,
                                "c" => in_array("c", $student_answers)?true:false,
                                "d" => in_array("d", $student_answers)?true:false,
                                "e" => in_array("e", $student_answers)?true:false
                            );
                            if($p->answer == $correct_answers){
                                $std->number_of_correct_answers++;
                                $std->obtained_marks += $p->question_marks;
                            } else {
                                $std->number_of_incorrect_answers++;
                            }
                        }
                    }
                    $std->paper_record = $paperData; 

                } else {
                    $array = array();
                    for($i=1; $i<=$number_of_questions; $i++){
                        $obj = new stdClass();
                        $obj->academic_year_id = $request->academic_year_id;
                        $obj->answer = null;
                        $obj->correct_answer = null;
                        $obj->correct_answer_1 = null;
                        $obj->correct_answer_2 = null;
                        $obj->correct_answer_3 = null;
                        $obj->correct_answer_4 = null;
                        $obj->correct_answer_5 = null;
                        $obj->exam_id = $request->exam_id;
                        $obj->id = null;
                        $obj->paper_id = $request->exam_detail_id;
                        $obj->question_id = $i;
                        $obj->question_no = $i;
                        $obj->options = new stdClass();
                        $obj->question_marks = null;
                        $obj->question_type = null;
                        $obj->school_id = $school_id;
                        $obj->second_answer = null;
                        $obj->student_id = $std->id;
                        $obj->question = null;
                        array_push($array, $obj);
                    }
                    $std->paper_record = $array;
                }
            }
            $response = array("status" => "success", "message"=> "data found", "data" => $students, "number_of_questions" => $number_of_questions);
        } else {
            $response = array("status"=> "error", "message" => "Not found any student", "data" => $students);
        }
        echo json_encode($response);
    }

    public function update_paper(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $student_id = $request->id;
        $online_exam_detail_id = $request->paper_record[0]->paper_id;
        $user_id = $this->session->userdata('userdata')['user_id'];

        $this->db->set('status', 'not-approved')->where('student_id', $student_id)->where('online_exam_detail_id', $online_exam_detail_id)->where('user_id', $user_id)->where('marked', 'N')->where('type', 'online_exam_edit')->update('sh_request_log');

        foreach($request->paper_record as $record){
            $data = array();
            if($record->question_type == "single_answer" || $record->question_type == "single_fill_in_the_blank" || $record->question_type == "true_false") {
                $data["answer"] = $record->answer;
            } else if($record->question_type == "double_fill_in_the_blank") {
                $data["answer"] = $record->answer;
                $data["second_answer"] = $record->second_answer;
            } else if($record->question_type == "multi_answer"){
                $student_answers = array();
                foreach ($record->updated_answers as $key => $value) {
                    if($value) $student_answers[] = $key;
                }
                $student_answers = implode(",", $student_answers);
                $data["answer"] = $student_answers;
            }
            $this->common_model->update_where("sh_online_exam_answers",array("id"=>$record->id), $data);
        }
        $response = array("status"=> "success", "message"=> "Result updated successfully!");
        echo json_encode($response);
    }

    public function softDelete() {
        $student_id = $this->input->post("id");
        $exam_id = $this->input->post("exam_id");
        $paper_id = $this->input->post("exam_detail_id");
        $online_exam_detail_id = $paper_id;
        $user_id = $this->session->userdata('userdata')['user_id'];

        $this->db->set('status', 'not-approved')->where('student_id', $student_id)->where('online_exam_detail_id', $online_exam_detail_id)->where('user_id', $user_id)->where('marked', 'N')->where('type', 'online_exam_retake')->update('sh_request_log');
        $data = array("deleted_at" => date("Y-m-d h:i:s"));
        $where = array("student_id" => $student_id, "exam_id" => $exam_id, "paper_id" => $paper_id);
        $this->common_model->update_where("sh_online_exam_answers",$where, $data);
        echo "success";
    }

    public function inProcessEdit() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $student_id = $request->student_id;
        $exam_detail_id = $request->exam_detail_id;
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $type = "online_exam_edit";
        $reason = $request->reason;

        $check = $this->db->select('id')->from('sh_request_log')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('student_id', $student_id)->where('online_exam_detail_id', $exam_detail_id)->where('user_id', $this->session->userdata('userdata')['user_id'])->where('marked', 'N')->where('type', $type)->get()->row();

        if($check){
            $updated_data = array("status" => "inprocess","request_time" => date("Y-m-d H:i:s"),"edit_reason" => $reason);
            $this->db->where('id', $check->id)->update('sh_request_log', $updated_data);
            $r_id = $check->id;
        }else{



            $log_data = array("class_id" => $class_id,
                "batch_id" => $batch_id,
                "student_id" => $student_id,
                "online_exam_detail_id" => $exam_detail_id,
                "school_id" => $school_id,
                "type" => $type,
                "status" => "inprocess",
                "request_time" => date("Y-m-d H:i:s"),
                "user_id" => $this->session->userdata('userdata')['user_id'],
                "edit_reason" => $reason);

            $this->db->insert('sh_request_log', $log_data);

            $r_id = $this->db->insert_id();
        }
        $data["message"] = lang('edit_attendance');
        $data["status"] = 'success';
        $data["r_id"] = $r_id;
        echo json_encode($data);
    }

    public function inProcessRetake() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $student_id = $request->student_id;
        $exam_detail_id = $request->exam_detail_id;
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $type = "online_exam_retake";
        $reason = $request->reason;

        $check = $this->db->select('id')->from('sh_request_log')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('student_id', $student_id)->where('online_exam_detail_id', $exam_detail_id)->where('user_id', $this->session->userdata('userdata')['user_id'])->where('marked', 'N')->where('type', $type)->get()->row();

        if($check){
            $updated_data = array("status" => "inprocess","request_time" => date("Y-m-d H:i:s"),"edit_reason" => $reason);
            $this->db->where('id', $check->id)->update('sh_request_log', $updated_data);
            $r_id = $check->id;
        }else{
            $log_data = array("class_id" => $class_id,
                "batch_id" => $batch_id,
                "student_id" => $student_id,
                "online_exam_detail_id" => $exam_detail_id,
                "school_id" => $school_id,
                "type" => $type,
                "status" => "inprocess",
                "request_time" => date("Y-m-d H:i:s"),
                "user_id" => $this->session->userdata('userdata')['user_id'],
                "edit_reason" => $reason);

            $this->db->insert('sh_request_log', $log_data);

            $r_id = $this->db->insert_id();
        }

        

        
        $data["message"] = lang('edit_attendance');
        $data["status"] = 'success';
        $data["r_id"] = $r_id;
        echo json_encode($data);
    }

    function publish_papers(){
        $this->load->view('online_exams/publish_papers');
    }

    function getPapersForPublish(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $papers = $this->db->select('ed.*,c.name as class_name, group_concat(b.name order by b.name) as batch_name,s.name as subject_name')->from('sh_online_exam_details ed')->join('sh_classes c','c.id = ed.class_id')->join('sh_batches b','find_in_set(b.id , ed.batch_id)')->join('sh_subjects s','s.id = ed.subject_id')->where('ed.class_id', $class_id)->where('ed.deleted_at is null')->group_by('ed.id')->get()->result();
        foreach($papers as $p){
            $p->added_questions = $this->db->from('sh_online_questions')->where('paper_id', $p->id)->where('deleted_at is null')->count_all_results();
            $p->ready = false;
            if($p->number_of_questions == $p->added_questions && $p->number_of_questions != 0){
                $p->ready = true;
            }
        }
        $data["papers"] = $papers;
        echo json_encode($data);
    }

    function publishPaper(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;
        $status = $request->status;
        $this->db->set('published', $status)->where('id', $id)->update('sh_online_exam_details');
        if($status == "no"){
            $msg = "Paper unpublished successfully!";
        }else{
            $msg = "Paper published successfully!";
        }
        $data["message"] = $msg;
        echo json_encode($data);
    }
}