<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends CI_Controller {

    

    public function getClassName(){
        $id = $this->input->post("id");
        $name = $this->admin_model->dbSelect("name","classes"," id='$id' ")[0]->name;
        echo json_encode(array("name"=>$name));
    }
    public function getBatchName(){
        $id = $this->input->post("id");
        $name = $this->admin_model->dbSelect("name","batches"," id='$id' ")[0]->name;
        echo json_encode(array("name"=>$name));
    }
    
}