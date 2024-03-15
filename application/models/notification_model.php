<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function save($msg_key, $recipient, $url, $app_url, $app_id, $data, $r_id, $otherInfo) {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $uSchool_id = $this->session->userdata("userdata")["sh_id"];
        
        // echo 'hsy '.$app_id;die;
        if($app_id=='' || $app_id==' ')
        {
            $app_id=null;
        }
       

        $query = array('msg_key' => $msg_key, 'url' => $url, 'app_url' => $app_url, 'app_id' => $app_id, 'data' => $data, 'sender_id' => $user_id, 'school_id' => $uSchool_id);
        $this->db->insert('sh_notifications', $query);
        $n_id = $this->db->insert_id();

        $this->db->set('notification_id', $n_id)->where('id', $r_id)->update('sh_request_log');
        $notificationId = $n_id;
        if($notificationId){
            $length = count($recipient);
            for ($i = 0; $i < $length; $i++) {
                $rec = $recipient[$i];
                if(empty($otherInfo) || count($otherInfo) == 0) {
                    $recDetail = array('notification_id' => $notificationId, 'receiver_id' => $rec);
                } else {
                    $recDetail = array('notification_id' => $notificationId, 'receiver_id' => $rec, "class_id" => $otherInfo["class_id"], "batch_id" => $otherInfo["batch_id"], "subject_id" => isset($otherInfo["subject_id"])?$otherInfo["subject_id"]:null);
                }   
                $this->db->insert('sh_notification_details', $recDetail);
            }
            return true;
        }else{
            return false;
        }

        
    }

    public function getNotId() {
        $this->db->select('id');
        $this->db->from('sh_notifications');
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->row();
        return $result->id;
    }

    public function getReceiverId($req) {

        $user_id = $this->session->userdata("userdata")["user_id"];

        $uSchool_id = $this->session->userdata("userdata")["sh_id"];

        $ids = $req->recipient->id;

        foreach ($ids as $id) {

            $this->db->select('role_id');
            $this->db->from('sh_users');
            $this->db->where('School_id', $uSchool_id);
            $this->db->where('id', $id);
            $this->db->where('id !=', $user_id);

            $query = $this->db->get();
            $result[] = $query->result();
        }
        return $result;
    }

    public function show($id) {
        $user_id = $this->session->userdata('userdata')['user_id'];
        $url = $this->db->select('url')->from('sh_notifications')->join('sh_notification_details', 'sh_notifications.id = sh_notification_details.notification_id')->where('sh_notification_details.id', $id)->get()->row()->url;
        $query = "Update sh_notification_details
        INNER JOIN sh_notifications ON sh_notification_details.notification_id = sh_notifications.id
        SET is_read=1
        Where sh_notification_details.id = '" . $id . "'";
        $this->db->query($query);
        //print_r($this->db->last_query());
        return true;
    }

    public function allNotifications($user_id) {
        //$user_id = $this->session->userdata("userdata")["user_id"];
        $role_id = $this->session->userdata("userdata")["role_id"];
        $this->db->select('d.id,n.msg_key, n.data, n.created_at as dateTime, d.is_read as is_read, u.name as sender,u.avatar as user_img, n.url as notiUrl, u.email');
        $this->db->from('sh_notifications n');
        $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'right');
        $this->db->join('sh_users u', 'n.sender_id = u.id', 'right');
        $this->db->where('d.receiver_id', $user_id);
        $this->db->where('d.is_read', 0);
        if($role_id != 1){
            $this->db->where('n.sender_id !=', $user_id);
        }

        $this->db->where('d.deleted_at', NULL);
        $this->db->order_by('n.created_at', 'DESC');
        $query = $this->db->get();
        $res = $query->result();
        foreach ($res as $v) {
            $message = lang($v->msg_key);
            $msg_key = $v->msg_key;
            $msg_data = json_decode($v->data, TRUE);
            if($msg_data != NULL){
                foreach ($msg_data as $d => $vv) {
                    $message = str_replace("{{" . $d . "}}", '' . $vv . '', $message);
                }
            } 
            $v->message = $message;
            $v->data = $msg_data;
        }

        return $res;
    }

//    public function allInbox($user_id){
//        
//        $this->db->select('count(*) as count_all');
//        $this->db->from('sh_notifications n');
//        $this->db->join('sh_notification_details d', 'd.notification_id = n.id', 'right');
//        $this->db->join('sh_users u', 'n.sender_id = u.id', 'right');
//        $this->db->where('d.receiver_id', $user_id);
//        $this->db->where('n.sender_id !=', $user_id);
//        //$this->db->where('n.receiver_role', $role_id);
//        $this->db->where('d.deleted_at', NULL);
//        $this->db->order_by('n.created_at', 'DESC');
//        $query = $this->db->get();
//        
//        return $query->row_array();
//    }

    public function countNotification() {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $role_id = $this->session->userdata("userdata")["role_id"];

        $this->db->select('*');
        $this->db->from('sh_notification_details d');
        $this->db->join('sh_notifications n', 'n.id = d.notification_id');
        if($role_id == 1){
            $this->db->where(array('d.receiver_id' => $user_id, 'd.is_read' => 0, 'd.deleted_at' => NULL));
        }else{
            $this->db->where(array('d.receiver_id' => $user_id, 'd.is_read' => 0, 'n.sender_id !=' => $user_id, 'd.deleted_at' => NULL));
        }
        
        $query = $this->db->get();

        return $query->result();
    }

    public function appNotification($r_id) {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $this->db->set('is_read', '1')->where('notification_id', $r_id)->where('receiver_id', $user_id)->update('sh_notification_details');
    }

}
