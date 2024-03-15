<?php

class Knowledge_base extends CI_Controller{
	
	public function index(){

		$lang= $this->session->userdata('site_lang');

		if($lang=='urdu'){
			$data['categories'] = $this->article_model->get_urdu_categories();
		}
		else{
			$data['categories'] = $this->article_model->get_english_categories();
		}

		

		$i=0;

		foreach ($data['categories'] as $category) {

			$data['categories'][$i] = $this->article_model->get_articles($category['id']);

			$i++;
			
		}

		
		$this->load->view('landing_page/knowledge_base', $data);
		
	}

	public function view($slug){

		$lang= $this->session->userdata('site_lang');

		if($lang=='urdu'){
			$data['article'] = $this->article_model->get_urdu_article($slug);
		}
		else{
			$data['article'] = $this->article_model->get_english_article($slug);
		}

		
		$this->load->view('landing_page/knowledge_base_details', $data);
		
	}

	public function create(){

		$data['categories']=$this->article_model->get_categories();
		$this->form_validation->set_rules('title','Title','required');
		$this->form_validation->set_rules('content','Content','required');
		

		if($this->form_validation->run()===FALSE){
			
			$this->load->view('create',$data);
			
		}
		else{

			
			$this->article_model->create_article();
			redirect('knowledge_base');
		}
		

	}
}


?>