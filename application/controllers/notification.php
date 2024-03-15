<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login/index"));
        }
    }

    function index() {

        $user_id = $this->session->userdata("userdata")["user_id"];

        //$data['allInbox'] = $this->notification_model->allInbox($user_id);
        $data['allNotifications'] = $this->notification_model->allNotifications($user_id);
        $this->load->view("notifications/index", $data);
    }

    function show() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $id = $request->id;

        $res = $this->notification_model->show($id);

        if ($res) {
            $this->load->helper('url');
            //redirect($url);
            $data = array("status" => "success", "message" => lang('notification_show'));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang('notification_not'));
            echo json_encode($data);
        }
    }

    function sendNotificationViaPusher() {
        require_once 'vendor/autoload.php';
        $msg_key = $this->input->post('msg_key');
        $recipient = $this->input->post('recipient');
        $url = $this->input->post('url');
        $app_url = $this->input->post('app_url');
        $app_id = $this->input->post('app_id');
        $dataVal = $this->input->post('data');
        $r_id = $this->input->post('r_id');
        

        //--------Added by Yasir--------//
        $otherInfo = array();
        if($this->input->post("other") != null){
            $otherInfo = $this->input->post("other");
        }
        //------------------------------//
        
        foreach ($dataVal as $key => $val) {
            $data[$key] = $val;
        }
        $dataJson = json_encode($data);
        $res = $this->notification_model->save($msg_key, $recipient, $url, $app_url, $app_id, $dataJson, $r_id, $otherInfo);

        if ($res) {
            sendNotificationViaPusher($msg_key, $recipient, $url, $dataJson);
            $data = array("status" => "success", "message" => lang('notification_sent'));
            echo json_encode($data);
        } else {
            $data = array("status" => "error", "message" => lang('notification_not_sent'));
            echo json_encode($data);
        }
        return true;
    }

    function allNotifications() {
        $user_id = $this->session->userdata("userdata")["user_id"];
        //$data['allInbox'] = $this->notification_model->allInbox($user_id);
        $data['allNotifications'] = $this->notification_model->allNotifications($user_id);
        echo json_encode($data);
    }

    function countNotification() {
        $res = $this->notification_model->countNotification();

        $count = count($res);

        echo json_decode($count);
    }

    function appNotification() {
        $postdata   = file_get_contents("php://input");
        $request    = json_decode($postdata);
        $user_id = $this->session->userdata("userdata")["user_id"];
        
        $response     = $request->reason;
        $notification_id = $request->notification_id;
        $status     = $request->status;
        $id         = $request->id;
        $type       = $request->type;
        $date       = $request->date;
            
        $this->common_model->update_where("request_log", array("id" => $id), array("status" => $status, 'response_by'=> $user_id));

            if ($type == 'syllabus') {
                if ($status == 'approved') {
                    $data['message'] = "lbl_response_syllabus_approved";
                    $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                    $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    
                } else if($status == 'draft'){
                    $data['message'] = "lbl_response_syllabus_approved";
                    $this->common_model->update_where("request_log", array("id" => $id), array("status" => "draft", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                    $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                } else if ($status == 'not-approved') {
                    $data['message'] = "lbl_response_syllabus_not_approved";
                    $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response, "marked" => "Y"));
                    $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    
                }
            } else if ($type == 'attendance') {
                $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
                $result = $this->admin_model->dbQuery($sql);
                if (count($result) > 0) {
                    if ($status == 'approved') {
                        $data['message'] = "lbl_response_std_attendance_approved";
                        $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response , "marked" => "Y"));
                        $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    } else if ($status == 'not-approved') {
                        $data['message'] = "lbl_response_std_attendance_not_approved";
                       $response = $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response , "marked" => "Y"));
                       $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    }
                }
            } else if ($type == 'emp_attendance') {
                $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
                $result = $this->admin_model->dbQuery($sql);
                if (count($result) > 0) {
                    if ($status == 'approved') {
                        $data['message'] = "lbl_response_emp_attendance_approved";
                        $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                        $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    } else if ($status == 'not-approved') {
                        $data['message'] = "lbl_response_emp_attendance_not_approved";
                       $response = $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response, "marked" => "Y"));
                       $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    }
                }
            }else if ($type == 'mark_sheet') {
                $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
                $result = $this->admin_model->dbQuery($sql);
                if (count($result) > 0) {
                    if ($status == 'approved') {
                        $data['message'] = "lbl_response_marksheet_approved";
                        $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                        $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    } else if ($status == 'not-approved') {
                        $data['message'] = "lbl_response_marksheet_not_approved";
                       $response = $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response, "marked" => "Y"));
                       $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    }
                }
            }else if($type == 'online_exam_edit'){
                $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
                $result = $this->admin_model->dbQuery($sql);
                if (count($result) > 0) {
                    if ($status == 'approved') {
                        $data['message'] = "lbl_response_online_exam_edit_approved";
                        $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                        $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    } else if ($status == 'not-approved') {
                        $data['message'] = "lbl_response_online_exam_edit_rejected";
                       $response = $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response, "marked" => "Y"));
                       $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    }
                }
            }else if($type == 'online_exam_retake'){
                $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
                $result = $this->admin_model->dbQuery($sql);
                if (count($result) > 0) {
                    if ($status == 'approved') {
                        $student_id = $result[0]->student_id;
                        $paper_id = $result[0]->online_exam_detail_id;
                        $where = array("student_id" => $student_id, "paper_id" => $paper_id);
                        $this->common_model->update_where("sh_online_exam_answers",$where, array("deleted_at" => date("Y-m-d h:i:s")));
                        $data['message'] = "lbl_response_online_exam_retake_approved";
                        $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                        $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $request_row['status'] = "not-approved";
                        $this->db->insert('sh_request_log',$request_row);
                    } else if ($status == 'not-approved') {
                        $data['message'] = "lbl_response_online_exam_retake_rejected";
                       $response = $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response, "marked" => "Y"));
                       $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        unset($request_row['id']);
                        $request_row['marked'] = "N";
                        $request_row['edit_reason'] = null;
                        $this->db->insert('sh_request_log',$request_row);
                    }
                }
            }else if($type == 'fee_exemption'){
                $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
                $result = $this->admin_model->dbQuery($sql);
                if (count($result) > 0) {
                    if ($status == 'approved') {
                        $data['message'] = "lbl_response_fee_exemption";
                        $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit", "response" => $response, "marked" => "Y"));
                        // $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                        // unset($request_row['id']);
                        // $request_row['marked'] = "N";
                        // $request_row['edit_reason'] = null;
                        // $this->db->insert('sh_request_log',$request_row);
                    } else if ($status == 'not-approved') {
                        $data['message'] = "lbl_response_reject_fee_exemption";
                       $response = $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit", "response" => $response, "marked" => "Y"));
                       // $request_row = $this->db->select('*')->from('sh_request_log')->where('id',$id)->get()->row_array();
                       //  unset($request_row['id']);
                       //  $request_row['marked'] = "N";
                       //  $request_row['edit_reason'] = null;
                       //  $this->db->insert('sh_request_log',$request_row);
                    }
                }
            }
            
        $this->notification_model->appNotification($notification_id);

        $ids = $this->admin_model->dbSelect("user_id","request_log", " id=$id");
        $data['new_ids'] = array();
        if($ids){
           foreach($ids as $id){
               array_push($data['new_ids'], $id->user_id);
           }
           $data['sender'] = $this->session->userdata("userdata")["name"];
       }
        echo json_encode($data);
    }

    function activeAcademicYear(){
        $academic_year_id = $this->session->userdata("userdata")['academic_year'];

        $data = $this->db->select('start_date, end_date')->from('sh_academic_years')->where('id', $academic_year_id)->get()->row();

        echo json_encode($data);
    }
}
