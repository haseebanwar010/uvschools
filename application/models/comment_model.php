<?php

class comment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function newComment($submit_mat_id,$sender_id,$comment,$files){
    	
    	$data = array(
            'submit_material_id	' => $submit_mat_id,
            'sender_id' => $sender_id,
            'comment_body' => $comment,
            'files' => $files
            
        );

        $this->db->insert('sh_comments', $data);
    }

}

?>