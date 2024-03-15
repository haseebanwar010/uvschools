<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Licensesrenew extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //if (!$this->session->userdata("userdata")) {
        //    redirect(site_url("login/index"));
        //}
    }
    
    public function index(){
        $sql = "SELECT
            sch.id as sid,sch.logo as logo, sch.name as sname,li_tmp.id as id,sch.email as smail,li_tmp.licence_type as type, li_tmp.start_date as start_date, li_tmp.end_date as end_date
            FROM
            sh_school sch
            LEFT JOIN ( 
                SELECT s1.* FROM sh_license as s1 
                LEFT JOIN sh_license AS s2 ON s1.id = s2.id 
                AND s1.end_date < s2.end_date 
                WHERE s2.id IS NULL 
            )
            as li_tmp ON (sch.id = li_tmp.school_id) 
            WHERE sch.deleted_at=0 ORDER BY li_tmp.id";
        
        //$sql = "SELECT distinct(sch.id as id), sch.name as name, li.start_date as start_date, li.end_date as end_date FROM sh_school sch INNER JOIN sh_license li ON sch.id=li.school_id WHERE sch.deleted_at=0 ";
        $data["schools"] = $this->admin_model->dbQuery($sql);
        $this->load->view("licenses/renew", $data);
    }

    public function delete() {
        $this->common_model->update_where("sh_school", array("id" => $this->input->post("id")), array("deleted_at" => 1));
        $this->session->set_flashdata('alert', array("status" => "success", "message" => "School deleted successfully"));
        echo "success";
        //redirect("employee/all","refresh");
    }
    
    public function update(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $this->common_model->update_where("sh_license", array("id" => $request->li), array("end_date" => to_mysql_date($request->end_date)));
        echo json_encode(array("status" => "alert-success", "message" => "License updated successfully"));
    }
    
}
