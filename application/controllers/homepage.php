<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends CI_Controller
{
	
	public function index(){
		 
		$sql = "SELECT logo, name, country from sh_school where url IN  ('pesc','nngos','gup','bfs','mysch','sgis','ism','genius','isma','sfis','ismg','sisb','kca','ksl','ckis','mals','nw','nav','rris','ryt','soh','skis','tfs','tis','tiss','mf','hgs','rris','eaa','ars','sois','hilal','sis','sks','lbs','mfb','sas','mah') ORDER By url ASC ";
		$data["requests"] = $this->admin_model->dbQuery($sql);
        
		$this->load->view('landing_page/landingpage', $data);
		
	}

	//



	public function demo(){
		$this->load->view('uv-landing/index');
	}
}