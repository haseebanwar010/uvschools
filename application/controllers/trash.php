<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
include_once FCPATH."quickstart.php";
class Trash extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function index() {
        $data["view_type"] = isset($_GET["type"]) ? $_GET["type"]:"list"; 
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $data["academic_years"] = $this->admin_model->dbSelect("id,name,deleted_at","academic_years"," school_id='$school_id' AND deleted_at IS NOT NULL ");
        $data["class_levels"] = $this->admin_model->dbSelect("id,level_name as name, deleted_at","class_levels"," school_id='$school_id' AND deleted_at IS NOT NULL ");
        $data["classes"] = $this->admin_model->dbSelect("id,name, deleted_at","classes"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $data["batches"] = $this->admin_model->dbSelect("id,name, deleted_at","batches"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $data["subjects"] = $this->admin_model->dbSelect("id,name, deleted_at","subjects"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $data["subject_groups"] = $this->admin_model->dbSelect("id,group_name as name, deleted_at","subject_groups"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $data["periods"] = $this->admin_model->dbSelect("id,title as name, deleted_at","periods"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $data["grades"] = $this->admin_model->dbSelect("id,name","grades"," school_id='$school_id' AND deleted_at IS NOT NULL ");
        $data["fee_types"] = $this->admin_model->dbSelect("id,name,deleted_at","fee_types"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $data["fee_discount"] = $this->admin_model->dbSelect("id,name,deleted_at","fee_discount"," school_id='$school_id' AND deleted_at IS NOT NULL ");
        $data["employees"] = $this->admin_model->dbSelect("id,name,updated_at as deleted_at","users"," school_id='$school_id' AND role_id=".EMPLOYEE_ROLE_ID." AND deleted_at=1 ");
        
        // $data["students"] = $this->admin_model->dbSelect("id,name,updated_at as deleted_at","users"," school_id='$school_id' AND role_id=".STUDENT_ROLE_ID." AND deleted_at=1 ");
        
        $data["students"] = $this->db->select('cr.student_id as id,u.name, u.updated_at as deleted_at')->from('sh_users u')->join('sh_student_class_relation cr','cr.student_id = u.id')->where('cr.academic_year_id', $academic_year_id)->where('u.school_id', $school_id)->where("cr.deleted_at != 'NULL'")->get()->result();
        
        $data["guardians"] = $this->admin_model->dbSelect("id,name,updated_at as deleted_at","users"," school_id='$school_id' AND role_id=".PARENT_ROLE_ID." AND deleted_at=1 ");
        
        $data["study_materials"] = $this->admin_model->dbSelect("id,title as name","study_material"," school_id='$school_id' AND delete_status=1 ");
        $data["book_shops"] = $this->admin_model->dbSelect("id,title as name,deleted_at","book_shop"," school_id='$school_id' AND deleted_at IS NOT NULL ");
        $sql = "SELECT fc.*, ft.name as feetype_name, c.name as collector_name, s.name as student_name, cls.name as class_name, b.name as batch_name FROM sh_fee_collection fc INNER JOIN sh_fee_types ft ON fc.feetype_id=ft.id INNER JOIN sh_users c ON fc.collector_id=c.id INNER JOIN sh_users s ON fc.student_id=s.id INNER JOIN sh_classes cls ON fc.class_id=cls.id INNER JOIN sh_batches b ON fc.batch_id=b.id WHERE fc.school_id='$school_id' AND fc.deleted_at IS NOT NULL ";
        $data["fee_collections"] = $this->admin_model->dbQuery($sql);
        $data["form_categories"] = $this->admin_model->dbSelect("id,name,deleted_at","form_categories"," school_id='$school_id' AND deleted_at IS NOT NULL ");
        $sql1 = "SELECT t.*, fcat.name as form_category_name FROM sh_templates t INNER JOIN sh_form_categories fcat ON t.form_category_id=fcat.id WHERE t.school_id='$school_id' AND t.deleted_at IS NOT NULL ";
        $data["forms"] = $this->admin_model->dbQuery($sql1);
        $data["exams"] = $this->admin_model->dbSelect("id,title as name,deleted_at","exams"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $sql3 = "SELECT ed.*,e.title as exam_name, c.name as class_name, b.name as batch_name, s.name as subject_name FROM sh_exam_details ed INNER JOIN sh_exams e ON ed.exam_id=e.id INNER JOIN sh_classes c ON ed.class_id=c.id INNER JOIN sh_batches b ON ed.batch_id=b.id INNER JOIN sh_subjects s ON ed.subject_id=s.id WHERE ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.deleted_at IS NOT NULL ";
        $data["exam_details"] = $this->admin_model->dbQuery($sql3);
        $sql4 = "SELECT r.*, e.title as exam_name, c.name as class_name, b.name as batch_name FROM sh_passing_rules r INNER JOIN sh_exams e ON r.exam_id=e.id INNER JOIN sh_classes c ON r.class_id=c.id INNER JOIN sh_batches b ON r.batch_id=b.id WHERE r.school_id='$school_id' AND r.academic_year_id='$academic_year_id' AND r.deleted_at IS NOT NULL";
        $data["passing_rules"] = $this->admin_model->dbQuery($sql4);
        $sql5 = "SELECT syl.*, c.name as class_name, b.name as batch_name, s.name as subject_name FROM sh_syllabus_weeks syl INNER JOIN sh_classes c ON syl.class_id=c.id INNER JOIN sh_batches b ON syl.batch_id=b.id INNER JOIN sh_subjects s ON syl.subject_id=s.id WHERE syl.school_id='$school_id' AND syl.academic_year_id='$academic_year_id' AND syl.deleted_at IS NOT NULL ";
        $data["syllabus_weeks"] = $this->admin_model->dbQuery($sql5);
        $sql6 = "SELECT syld.*, syl.name as week_name, c.name as class_name, b.name as batch_name, s.name as subject_name FROM sh_syllabus_week_details syld INNER JOIN sh_syllabus_weeks syl ON syld.syllabus_week_id=syl.id INNER JOIN sh_classes c ON syl.class_id=c.id INNER JOIN sh_batches b ON syl.batch_id=b.id INNER JOIN sh_subjects s ON syl.subject_id=s.id WHERE syld.school_id='$school_id' AND syld.deleted_at IS NOT NULL ";
        $data["syllabus_week_details"] = $this->admin_model->dbQuery($sql6);
        //$data["evaluations"] = $this->admin_model->dbSelect("id,evaluation_name as name","fee_types"," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id=$academic_year_id ");
        $this->load->view("trash.php", $data);
    }
    
    public function recover(){
        $table = $this->input->post("table");
        $id = $this->input->post("id");
       
        if($table == "study_material")
        {
            $this->common_model->update_where("sh_".$table, array("id"=>$id), array("delete_status"=>0));
        } 
        else if($table == "student_class_relation")
        {
              //print_r($this->input->post("table"));die();
            $this->common_model->update_where("sh_".$table, array("student_id"=>$id), array("deleted_at"=>NULL));
            $this->common_model->update_where("sh_users", array("id" => $id), array("deleted_at"=>0));
        }
        else
        {
            $this->common_model->update_where("sh_".$table, array("id"=>$id), array("deleted_at"=>NULL));
        }        
        echo "success";
        //$this->session->set_flashdata("recover_message","Data recover successfully!");
        //redirect($_SERVER['HTTP_REFERER'], "refresh");
    }

    public function recover_all(){
        $table = $this->input->post("table");
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        
        $data = array();
        if($table == 'academic_years' || $table == 'grades' || $table == 'fee_discount'
            || $table == 'book_shop' || $table == 'form_categories' || $table == 'templates'
            || $table == 'syllabus_week_details') {
                $data = $this->admin_model->dbSelect("*",$table," school_id='$school_id' AND deleted_at IS NOT NULL ");
                foreach($data as $r){
                    $this->common_model->update_where("sh_".$table, array("id"=>$r->id), array("deleted_at"=>NULL));
                }
        } else {
            if($table == 'students'){
                // $data = $this->admin_model->dbSelect("*","users"," school_id='$school_id' AND deleted_at=1 AND role_id=".STUDENT_ROLE_ID." ");
                $data = $this->db->select('u.id')->from('sh_users u')->join('sh_student_class_relation cr','cr.student_id = u.id')->where('cr.academic_year_id', $academic_year_id)->where('u.school_id', $school_id)->where('u.deleted_at' , 1)->get()->result();
                foreach($data as $r1){
                    $this->common_model->update_where("sh_users", array("id"=>$r1->id), array("deleted_at"=>0));
                }
            } else if($table == 'employees'){
                $data = $this->admin_model->dbSelect("*","users"," school_id='$school_id' AND deleted_at=1 AND role_id=".EMPLOYEE_ROLE_ID." ");
                foreach($data as $r2){
                    $this->common_model->update_where("sh_users", array("id"=>$r2->id), array("deleted_at"=>0));
                }
            } else if($table == 'guardians'){
                $data = $this->admin_model->dbSelect("*","users"," school_id='$school_id' AND deleted_at=1 AND role_id=".PARENT_ROLE_ID." ");
                foreach($data as $r3){
                    $this->common_model->update_where("sh_users", array("id"=>$r3->id), array("deleted_at"=>0));
                }
            } else if($table == 'study_material') {
                $data = $this->admin_model->dbSelect("*","study_material"," school_id='$school_id' AND delete_status=1 ");
                foreach($data as $r4){
                    $this->common_model->update_where("sh_".$table, array("id"=>$r4->id), array("delete_status"=>0));
                }
            } else if($table == 'fee_collection') {
                $sql = "SELECT fc.*, ft.name as feetype_name, c.name as collector_name, s.name as student_name, cls.name as class_name, b.name as batch_name FROM sh_fee_collection fc INNER JOIN sh_fee_types ft ON fc.feetype_id=ft.id INNER JOIN sh_users c ON fc.collector_id=c.id INNER JOIN sh_users s ON fc.student_id=s.id INNER JOIN sh_classes cls ON fc.class_id=cls.id INNER JOIN sh_batches b ON fc.batch_id=b.id WHERE fc.school_id='$school_id' AND ft.academic_year_id='$academic_year_id' AND fc.deleted_at IS NOT NULL ";
                $data = $this->admin_model->dbQuery($sql);
                foreach($data as $r5){
                    $this->common_model->update_where("sh_".$table, array("id"=>$r5->id), array("deleted_at"=>NULL));
                }
            }else {
                $data = $this->admin_model->dbSelect("*",$table," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id='$academic_year_id' ");    
                foreach($data as $r6){
                    $this->common_model->update_where("sh_".$table, array("id"=>$r6->id), array("deleted_at"=>NULL));
                }
            }
        }
        echo "success";
    }

    public function delete(){
        $table = $this->input->post("table");
        $id = $this->input->post("id");
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        if($table=='study_material')
        {
            $result=$this->study_model->getdeletedfilesId($id);
            if($result->storage_type==2)
            {
                $sch_id=$this->session->userdata("userdata");

                $sch_id=$sch_id['sh_id'];
                $gd_tokenfile='token_'.$sch_id.'.json';
                $client = getClient($sch_id,$gd_tokenfile);
    
                if(file_exists($gd_tokenfile))
                {
                    $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                    $client->setAccessToken($accessToken);
                    $service = new Google_Service_Drive($client);
    
                    $Ids=explode(',',$result->fileids);
                    
                    if($Ids[0]!='')
                    {
                        foreach($Ids as $s_id)
                        {
                            $service->files->delete($s_id);
                        }
                    }
                }
            }
            elseif($result->storage_type==1)
            {
                $nameof_files=explode(',',$result->files);
                if(sizeof($nameof_files) > 0)
                {
                    foreach($nameof_files as $deleting_file)
                    {
                        if($deleting_file)
                        {
                            $filepath='uploads/study_material/'.$deleting_file;
                            unlink($filepath);
                        }
                    }
                }
            }
            
            $res = $this->common_model->delete("id",$id,"sh_".$table);        
            if($res)
            {
                echo "success";
            } 
            else
            {
                echo "error";
            }
        }
        else
        {   
            $role = $this->db->query("SELECT role_id FROM sh_users WHERE id='$id' AND deleted_at=1")->result();
            if (count($role) > 0 && $role[0]->role_id == 3) {

                $std_cls_id = $this->db->query("SELECT id FROM sh_student_class_relation WHERE student_id='$id' AND academic_year_id='$academic_year_id' AND deleted_at is NOT NULL")->result();

                if (count($std_cls_id) > 0) {
                    $std_cls_id = $std_cls_id[0]->id;
                    $res = $this->common_model->delete("id",$std_cls_id,"sh_student_class_relation");

                    $res = $this->common_model->update_where("sh_".$table, array("id"=>$id), array("parmanent_delete"=>'1'));
   
                } else {

                    $res = $this->common_model->update_where("sh_".$table, array("id"=>$id), array("parmanent_delete"=>'1'));

                }

                if($res){
                    echo "success";
                } else {
                    echo "error";
                }

            }
            else {
                $res = $this->common_model->delete("id",$id,"sh_".$table);        
                if($res){
                    echo "success";
                } else {
                    echo "error";
                }
            } 
        }
        
        
        
    }

    public function delete_all(){
        $table = $this->input->post("id");
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        
        $res=null;
        if($table == 'academic_years' || $table == 'grades' || $table == 'fee_discount'
            || $table == 'book_shop' || $table == 'form_categories' || $table == 'templates'
            || $table == 'syllabus_week_details') {
                $data = $this->admin_model->dbSelect("*",$table," school_id='$school_id' AND deleted_at IS NOT NULL ");
                foreach($data as $r){
                    $res = $this->common_model->delete("id",$r->id,"sh_".$table);
                }
        } else {
            if($table == 'students'){
                $data = $this->db->select('id')->from('sh_users')->where('school_id', $school_id)->where('deleted_at' , 1)->get()->result();
                foreach($data as $r1){

                    $std_cls_id = $this->db->query("SELECT id FROM sh_student_class_relation WHERE student_id='$r1->id' AND academic_year_id='$academic_year_id' AND deleted_at is NOT NULL")->result();

                    if (count($std_cls_id) > 0) {
                        $std_cls_id = $std_cls_id[0]->id;
                        $res = $this->common_model->delete("id",$std_cls_id,"sh_student_class_relation");
                        $res = $this->common_model->update_where("sh_users", array("id"=>$r1->id), array("parmanent_delete"=>'1'));   
                    } else {

                        $res = $this->common_model->update_where("sh_users", array("id"=>$r1->id), array("parmanent_delete"=>'1'));

                    }

                    // $res = $this->common_model->delete("id",$r1->id,"sh_users");
                }
            } else if($table == 'employees'){
                $data = $this->admin_model->dbSelect("*","users"," school_id='$school_id' AND deleted_at=1 AND role_id=".EMPLOYEE_ROLE_ID." ");
                foreach($data as $r2){
                    $res = $this->common_model->delete("id",$r2->id,"sh_users");
                }
            } else if($table == 'guardians'){
                $data = $this->admin_model->dbSelect("*","users"," school_id='$school_id' AND deleted_at=1 AND role_id=".PARENT_ROLE_ID." ");
                foreach($data as $r3){
                    $res = $this->common_model->delete("id",$r3->id,"sh_users");
                }
            } else if($table == 'study_material') {
                $data = $this->admin_model->dbSelect("*","study_material"," school_id='$school_id' AND delete_status=1 ");
                foreach($data as $r4)
                {
                    // $result=$this->study_model->getdeletedfilesId($r4->id);
                    if($r4->storage_type==2)
                    {
                        $sch_id=$this->session->userdata("userdata");
        
                        $sch_id=$sch_id['sh_id'];
                        $gd_tokenfile='token_'.$sch_id.'.json';
                        $client = getClient($sch_id,$gd_tokenfile);
            
                        if(file_exists($gd_tokenfile))
                        {
                            $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                            $client->setAccessToken($accessToken);
                            $service = new Google_Service_Drive($client);
            
                            $Ids=explode(',',$r4->fileids);
                            
                            if($Ids[0]!='')
                            {
                                foreach($Ids as $s_id)
                                {
                                    $service->files->delete($s_id);
                                }
                            }
                        }
                    }
                    elseif($r4->storage_type==1)
                    {
                        $nameof_files=explode(',',$r4->files);
                        if(sizeof($nameof_files) > 0)
                        {
                            foreach($nameof_files as $deleting_file)
                            {
                                if($deleting_file)
                                {
                                    $filepath='uploads/study_material/'.$deleting_file;
                                    unlink($filepath);
                                }
                            }
                        }
                    }
                    
                    $res = $this->common_model->delete("id",$r4->id,"sh_".$table);
                }
            } else if($table == 'fee_collection') {
                $sql = "SELECT fc.*, ft.name as feetype_name, c.name as collector_name, s.name as student_name, cls.name as class_name, b.name as batch_name FROM sh_fee_collection fc INNER JOIN sh_fee_types ft ON fc.feetype_id=ft.id INNER JOIN sh_users c ON fc.collector_id=c.id INNER JOIN sh_users s ON fc.student_id=s.id INNER JOIN sh_classes cls ON fc.class_id=cls.id INNER JOIN sh_batches b ON fc.batch_id=b.id WHERE fc.school_id='$school_id' AND ft.academic_year_id='$academic_year_id' AND fc.deleted_at IS NOT NULL ";
                $data = $this->admin_model->dbQuery($sql);
                foreach($data as $r5){
                    $res = $this->common_model->delete("id",$r5->id,"sh_".$table);
                }
            }else {
                $data = $this->admin_model->dbSelect("*",$table," school_id='$school_id' AND deleted_at IS NOT NULL AND academic_year_id='$academic_year_id' ");    
                foreach($data as $r6){
                    $res = $this->common_model->delete("id",$r6->id,"sh_".$table);
                }
            }
        }

        if($res){
            echo "success";
        } else {
            echo "error";
        }
        
    }
    
}