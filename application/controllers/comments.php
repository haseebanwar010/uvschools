<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
    include_once FCPATH."quickstart.php";
   
class comments extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function SendNewComment(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $submit_mat_id = $request->submit_mat_id;
        $comment = $request->text;
        $sender_id = $this->session->userdata('userdata')['user_id'];
        $files_1 = $request->files;

        $result = $this->db->select('student_id')->from('sh_submit_material')->where('id',$submit_mat_id)->where('deleted_status',0)->get()->result();

        $files = "";
        
        if($files_1){
            $files = array();
            foreach ($files_1 as $value) {
                $files[] = $value->new_name;
            }
            $files = array_unique($files);
            $files=implode(",", $files);
        }
        
        $this->comment_model->newComment($submit_mat_id,$sender_id,$comment,$files);

        $response['sub_mat_id'] = $submit_mat_id;
        $response['part'] = explode(',', $result[0]->student_id);
        $response["message"] = lang('comments_sent');
        $response["sender"] = $this->session->userdata("userdata")["name"];
        echo json_encode($response);   
    }

    public function SendNewCommentStd(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // echo '<pre>';
        // print_r($request);
        // die;
        // echo json_encode('die');die;
        $submit_mat_id = $request->submit_mat_id;
        $comment = $request->text;
        $sender_id = $this->session->userdata('userdata')['user_id'];
        $files_1 = $request->files;

        $result = $this->db->query("SELECT a.uploaded_by FROM sh_submit_material sm INNER JOIN sh_assignments a ON sm.material_id=a.id")->result();
    
        $files = "";
        
        if($files_1){
            $files = array();
            foreach ($files_1 as $value) {
                $files[] = $value->new_name;
            }
            $files = array_unique($files);
            $files=implode(",", $files);
        }
        
        $this->comment_model->newComment($submit_mat_id,$sender_id,$comment,$files);

        $response['sub_mat_id'] = $submit_mat_id;
        $response['part'] = explode(',', $result[0]->uploaded_by);
        $response["message"] = lang('comments_sent');
        $response["sender"] = $this->session->userdata("userdata")["name"];
        echo json_encode($response);   
    }


}