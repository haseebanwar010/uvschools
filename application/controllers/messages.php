<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messages extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function show() {
        $this->load->view('messages/inbox');
    }

    public function view($id) {
        $user_id = $this->session->userdata('userdata')['user_id'];
        $row = $this->db->where('user_id',$user_id)->where('conversation_id',$id)->get('sh_participants')->row();
        if(empty($row)){
            redirect(site_url("messages/show"));
        }
        $this->messages_model->updateReadStatus($id, $user_id);
        $data['con_info'] = $this->messages_model->con_info($id);
        $data['participants'] = $this->messages_model->getParticipants($id, $user_id);
        $data['user_image'] = $user_id = $this->session->userdata('userdata')['avatar'];

        $this->load->view('messages/conversation', $data);
    }

    public function newMessage() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $response['success'] = false;
        if (empty($request->text)) {
            $response['message'] = lang('message_empty');
        } else {
            $files = null;
            if (isset($request->files)) {
                $files = $request->files;
            }
            $con_id = $request->id;
            $message = $request->text;
            $user_id = $this->session->userdata('userdata')['user_id'];
            $data = $this->messages_model->newMessage($con_id, $message, $user_id, $files);
            $response['message'] = lang('message_sent');
            $response['success'] = true;
            $response['sender'] = $data[0];
            $response['con_id'] = $data[1];
            $response['part'] = $data[2];
        }

        echo json_encode($response);
    }

    public function deleteConversation() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $con_id = $request->deleteId;
        $user_id = $this->session->userdata('userdata')['user_id'];
        $this->messages_model->deleteConversation($con_id, $user_id);
        $response['success'] = true;
        $response['message'] = lang('archive_alert');
        echo json_encode($response);
    }

    public function restoreConversation() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $con_id = $request->restoreId;
        $user_id = $this->session->userdata('userdata')['user_id'];
        $this->messages_model->restoreConversation($con_id, $user_id);
        $response['success'] = true;
        $response['message'] = lang('restore_alert');
        echo json_encode($response);
    }

    public function getConversations() {
        $user_id = $this->session->userdata('userdata')['user_id'];
        $response['conversations'] = $this->messages_model->getConversations($user_id);
        $response['count'] = $this->messages_model->countUnread($user_id)['unread'];
        $response['trashes'] = $this->messages_model->getTrashConversations($user_id);
        $response['trash_count'] = $this->messages_model->countTrash($user_id)['trash_count'];
        $response['sent'] = $this->messages_model->getSent($user_id);
        echo json_encode($response);
    }

    public function getMessages() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $con_id = $request->id;

        $response['messages'] = $this->messages_model->getMessages($con_id);
        for ($i = 0; $i < count($response['messages']); $i++) {
            $response['messages'][$i]['attachments'] = explode(",", $response['messages'][$i]['attachments']);
        }

        echo json_encode($response);
    }

    public function getRecipients() {

        $q = $_GET['q'];
        $role_id = $_GET['role'];

        $sh_id = $this->session->userdata('userdata')['sh_id'];
        $user_id = $this->session->userdata('userdata')['user_id'];

        $response['rec'] = $this->messages_model->getRecipients($q, $sh_id, $user_id, $role_id);

        echo json_encode($response);
    }

    public function upload_attachments() {

        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $_FILES['file']['name'][$i] = time() . '_' . $_FILES['file']['name'][$i];
        }
        $attach_names = "";
        if (isset($_FILES['file'])) {
            $attach_names = implode(",", $_FILES['file']['name']);
            $uploaddir = './uploads/attachment/';
            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                $uploadfile = $uploaddir . $_FILES['file']['name'][$i];
                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {
                    
                } else {
                    
                }
            }
        }
        echo $attach_names;
    }

    public function startConversation() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $response['success'] = false;
        if (empty($request->to)) {
            $response['message'] = lang('to_required');
        } else if (empty($request->subject)) {
            $response['message'] = lang('subject_required');
        } else if (empty($request->text)) {
            $response['message'] = lang('message_required');
        } else {
            $files = null;
            if (isset($request->files)) {
                $files = $request->files;
            }
            $from = $this->session->userdata('userdata')['user_id'];
            $to = $request->to;
            $subject = $request->subject;
            $message = $request->text;



            $data = $this->messages_model->startConversation($from, $to, $subject, $message, $files);
            $response['success'] = true;
            $response['message'] = lang('new_message_alert');
            $response['sender'] = $data[0];
            $response['con_id'] = $data[1];
            $response['part'] = $data[2];
        }

        echo json_encode($response);
    }

    public function getStudentsForMessages(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->batch;
        $school_id = $this->session->userdata('userdata')["sh_id"];

        $data = $this->db->query("SELECT * FROM sh_users u LEFT JOIN sh_student_class_relation cr ON cr.student_id=u.id WHERE u.school_id='$school_id' AND cr.batch_id='$batch_id' AND cr.class_id=$class_id AND cr.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND cr.deleted_at is NULL")->result();
        
        echo json_encode($data);
        
    }

}

?>