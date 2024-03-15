<?php


class article_model extends CI_Model{

	public function get_english_categories(){
		$query = $this->db->get_where('article_categories',"lang = 'en'");

		return $query->result_array();
	}

	public function get_urdu_categories(){
		$query = $this->db->get_where('article_categories',"lang = 'ur'");

		return $query->result_array();
	}

	public function get_articles($id){
		$query = $this->db->join('article_categories','article_categories.id=cat_id')->select('articles.id,title,content,name,slug')->get_where('articles','cat_id = '.$id);

		return $query->result_array();
	}

	public function get_english_article($slug){
		
		$query = $this->db->get_where('articles',array('slug' => $slug, 'lang' => 'en'));


		return $query->row_array();
	}

	public function get_urdu_article($slug){
		
		$query = $this->db->get_where('articles',array('slug' => $slug, 'lang' => 'ur'));


		return $query->row_array();
	}

	public function create_article(){
		$data=array('title'=>$this->input->post('title'),
					'slug'=> url_title($this->input->post('title')),
					'content'=>$this->input->post('content'),
					'cat_id'=>$this->input->post('category'));

		return $this->db->insert('articles',$data);
	}
}