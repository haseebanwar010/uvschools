<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends CI_Model {

	function __construct() {
		parent::__construct();


	}

	function insertBank($bankdata){

		$this->db->insert('sh_banks',$bankdata);
		return $this->db->insert_id();

	}

	function getUserBankDetail($user_id){
		$query = $this->db->get_where('sh_banks',array('user_id'=>$user_id,'deleted_at'=>0));

		return $query->result();
	}

	function deleteOtherPrimary($user_id){
		$this->db->where('user_id',$user_id)->where('is_primary',"Y")->update('sh_banks',array('is_primary'=>"N"));
	}

	function getLastInsertedBank($insert_id){
		$query = $this->db->get_where('sh_banks',array('id'=>$insert_id));
		return $query->row_array();
	}

	function getBankDetails($id){
		$query = $this->db->get_where('sh_banks',array('id'=>$id));
		return $query->row_array();
	}

	function deleteBank($id){
		$row = $this->getBankDetails($id);
		$this->db->where('id',$id)->update('sh_banks',array('deleted_at'=>1,'is_primary'=>"N"));
		if(($row['is_primary']=="Y")){

			$this->makeFirstRowPrimary($id);
		}

		return true;
	}

	

	function updateBank($bankdata,$id){
		$this->db->where('id',$id)->update('sh_banks',$bankdata);
	}

	function makeFirstRowPrimary($id){
		$row = $this->getBankDetails($id);

		$query = $this->db->where('deleted_at',0)->where('user_id',$row['user_id'])->limit(1)->get('sh_banks');

		if($query->num_rows()>0){
			$new_primary_id = $query->row_array();

			$this->setNewPrimary($new_primary_id['id']);
		}



		
	}

	function setNewPrimary($id){
		$this->db->where('id',$id)->update('sh_banks',array('is_primary'=>"Y"));
	}

	function isPrimary($id){
		$query = $this->db->get_where('sh_banks',array('id'=>$id));
		$row = $query->row_array();
		return $row['is_primary'];
	}
        function getSchoolIdOfUser($id){
            $query = $this->db->get_where('sh_users',array('id'=>$id));
            $row = $query ->row_array();
            return $row['school_id'];
        }
        
        function getUserIdBank($id){
            $query = $this->db->get_where('sh_banks',array('id'=>$id));
            $row = $query ->row_array();
            return $row['user_id'];
        }

}