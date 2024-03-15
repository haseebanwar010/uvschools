<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logout extends CI_Controller {

    

    public function index()
    {
        if($this->session->userdata('userdata'))
        {
            $school = $this->session->userdata('userdata')['sh_url'];
            $user_id = $this->session->userdata('userdata')['user_id'];
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $user_timezone=$this->db->select('time_zone')->from('sh_school')->where('id',$school_id)->get()->row();
            if($user_timezone->time_zone!='')
            {
                date_default_timezone_set($user_timezone->time_zone);
            }
                        
            $activity=$this->db->select('*')->from('sh_activities')->where('user_id',$user_id)->order_by('user_id','desc')->limit(1)->get()->row();
            $res=$this->db->where('id', $activity->id)->update('sh_activities', array('logout_datetime' => date("Y-m-d h:i:s"), 'tag'=>'logout'));
            // saveActivity(array("user_id"=>$user_id,"tag"=>"logout","datetime"=>date("Y-m-d h:i:s")));
            $this->session->sess_destroy("userdata");
        }
        redirect(site_url($school.'/login'));
    }
    
}