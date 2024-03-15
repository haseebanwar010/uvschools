<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends CI_Controller {
	
    public function getPageSettings(){ 
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $page = $this->admin_model->dbSelect("*","pages"," school_id='$school_id' AND deleted_at IS NULL AND status='enable' ");
        $data = array();

        if(count($page) > 0){
            $page = $page[0];
            $data = $page;
            $data->navigations = array();
            $data->sliders = array();
            $navigations = $this->admin_model->dbSelect("*","navigation"," page_id='$page->id' AND deleted_at IS NULL ");
            $sliders = $this->admin_model->dbSelect("*","slider"," page_id='$page->id' AND deleted_at IS NULL ");
            if(count($navigations) > 0){
                $data->navigations = $navigations;
            }
            if(count($sliders) > 0) {
                $data->navigations = $sliders;   
            }

        } else {
            $data = array(
                "name" => $this->session->userdata("userdata")["sh_url"],
                "status" => 'enable',
                "school_id" => $school_id
            );
            $page_id = $this->common_model->insert("pages",$data);
            $page = $this->admin_model->dbSelect("*","pages"," id='$page_id' ");

            //default navigations
            $home_nav = array("name"=>"Home", "url"=>"javascript:void(0);", "school_id"=>$school_id, "page_id"=>$page_id, "description"=>"Some description");
            $login_nav = array("name"=>"Login", "url"=>"javascript:void(0);", "school_id"=>$school_id, "page_id"=>$page_id, "description"=>"Some description");
            $calender_nav = array("name"=>"Calender", "url"=>"javascript:void(0);", "school_id"=>$school_id, "page_id"=>$page_id, "description"=>"Some description");
            $addmission_nav = array("name"=>"Addmissions", "url"=>"javascript:void(0);", "school_id"=>$school_id, "page_id"=>$page_id, "description"=>"Some description");
            $this->common_model->insert("navigation",$home_nav);
            $this->common_model->insert("navigation",$login_nav);
            $this->common_model->insert("navigation",$calender_nav);
            $this->common_model->insert("navigation",$addmission_nav);

            $navigations = $this->admin_model->dbSelect("*","navigation"," page_id='$page_id' AND deleted_at IS NULL ");
            $sliders = $this->admin_model->dbSelect("*","slider"," page_id='$page_id' AND deleted_at IS NULL ");
            $data = $page[0];
            $data->navigations = array();
            $data->sliders = array();
            if(count($navigations) > 0){
                $data->navigations = $navigations;
            }
            if(count($sliders) > 0) {
                $data->navigations = $sliders;   
            } 
        }
        
        $response = array();
        if(count($data) > 0){
            $response = array("status"=> "success", "message"=> "data found", "data"=> $data);
        } else {
            $response = array("status"=> "error", "message"=> "data not found", "data"=> array());
        }

        echo json_encode($response);
    }
	public function saveNavigation(){
        $postdata = file_get_contents("php://input");
        print_r($postdata);
        die();
        $request = json_decode($postdata);

            $data = array("school_id" => $this->session->userdata("userdata")["sh_id"], "name" => $request->name, "url" => $request->url);
            $res = $this->admin_model->dbInsert("sh_navigations", $data);
            if ($res > 0) {
                echo json_encode(array("status" => "success", "message" => "navigation save successfully"));
            } else {
                echo json_encode(array("status" => "error", "message" => "there is soe error"));
            }
            
        

    }
   

    public function template_save()
    {
         // die();
         $page_setting = '<div id="azeem">' . $_POST['html'] . '</div>';
         $page_update_settings = json_encode($page_setting);
        
         // $page_setting = json_encode($_POST['html']);
         // print_r($_POST['css']);die();
         $page_css = json_encode($_POST['css']);

         $school_id = $this->session->userdata("userdata")["sh_id"];
        // $this->common_model->update_where("sh_pagetemplate", array("school_id" => $school_id), array("page_settings"=> json_encode($_POST["html"])));
        $this->common_model->update_where("sh_pagetemplate", array("school_id" => $school_id), array("page_settings"=> $page_update_settings,"pageStyle"=> $page_css));
    }

   
}