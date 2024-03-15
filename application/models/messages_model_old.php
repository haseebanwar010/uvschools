<?php

class Messages_model extends CI_Model{
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getConversations($user_id){
		$query = $this->db->select('sh_conversations.*,sh_participants.is_read,sh_users.role_id')
		->select('date_format(max(sh_messages.created_at),"%b %d") as last_time',false)
		->from('sh_conversations')
		->join('sh_participants','sh_conversations.id = sh_participants.conversation_id','left')
		->where('sh_participants.user_id',$user_id)
		->where('sh_participants.delete_status','0')
		->join('sh_messages','sh_conversations.id = sh_messages.conversation_id','left')
		->join('sh_users','sh_conversations.creator_id = sh_users.id')
		->group_by('sh_messages.conversation_id')
		->order_by('max(sh_messages.created_at)','DESC')
		->get();

						// print_r($this->db->last_query());
						// die();
		return $query->result_array();
	}

	public function getSent($user_id){
		$query = $this->db->select('sh_conversations.*,sh_participants.is_read,sh_users.role_id')
		->select('date_format(max(sh_messages.created_at),"%b %d") as last_time',false)
		->from('sh_conversations')
		->join('sh_participants','sh_conversations.id = sh_participants.conversation_id','left')
		->where('sh_conversations.creator_id',$user_id)
		->where('sh_participants.user_id',$user_id)
		->where('sh_participants.delete_status','0')
		->join('sh_messages','sh_conversations.id = sh_messages.conversation_id','left')
		->join('sh_users','sh_conversations.creator_id = sh_users.id')
		->group_by('sh_messages.conversation_id')
		->order_by('max(sh_messages.created_at)','DESC')
		->get();

						// print_r($this->db->last_query());
						// die();
		return $query->result_array();
	}

	public function getTrashConversations($user_id){
		$query = $this->db->select('sh_conversations.*,sh_participants.is_read,sh_users.role_id')
		->select('date_format(max(sh_messages.created_at),"%b %d") as last_time',false)
		->from('sh_conversations')
		->join('sh_participants','sh_conversations.id = sh_participants.conversation_id','left')
		->where('sh_participants.user_id',$user_id)
		->where('sh_participants.delete_status','1')
		->join('sh_messages','sh_conversations.id = sh_messages.conversation_id','left')
		->join('sh_users','sh_conversations.creator_id = sh_users.id')
		->group_by('sh_messages.conversation_id')
		->order_by('max(sh_messages.created_at)','DESC')
		->get();

						// print_r($this->db->last_query());
						// die();
		return $query->result_array();
	}

	public function deleteConversation($con_id,$user_id){
		$query = $this->db->set('delete_status','1')->where('user_id',$user_id)->where('conversation_id',$con_id)
		->update('sh_participants');

	}

	public function restoreConversation($con_id,$user_id){
		$query = $this->db->set('delete_status','0')->where('user_id',$user_id)->where('conversation_id',$con_id)
		->update('sh_participants');
	}

	public function countUnread($user_id){
		$query = $this->db->select('count(*) as unread')->from('sh_participants')->where('sh_participants.user_id',$user_id)
		->where('sh_participants.is_read','0')->get();

		return $query->row_array();
	}

	public function countTrash($user_id){
		$query = $this->db->select('count(*) as trash_count')->from('sh_participants')->where('sh_participants.user_id',$user_id)
		->where('sh_participants.delete_status','1')->get();

		return $query->row_array();		
	}

	public function getMessages($con_id){
		$query = $this->db->select('sh_messages.message_body,sh_messages.attachments,sh_users.name,sh_users.avatar')
		->select('date_format(sh_messages.created_at,"%b %d, %Y %h:%i %p") as created_at',false)
		->from('sh_messages')
		->where('sh_messages.conversation_id',$con_id)
		->join('sh_users','sh_messages.sender_id = sh_users.id')
		->get();
		return $query->result_array();
	}

	public function newMessage($con_id,$message,$user_id,$files){

		$data = array(
			'conversation_id' => $con_id,
			'message_body' => $message,
			'sender_id' => $user_id,
			'attachments' => $files
		);

		$query = $this->db->insert('sh_messages',$data);
		$this->makeReadDefault($con_id,$user_id);
		$to = $this->getParticipantsId($con_id,$this->session->userdata('userdata')['user_id']);
		
		$from = $this->session->userdata('userdata')['name'];

		$temp = array($from,$con_id,$to);
		return $temp;
	}

	public function getParticipantsId($id, $user_id){
		$creator_id = $this->getCreatorId($id);
		$query = $this->db->select('sh_users.id')->where('sh_participants.conversation_id',$id)
		->from('sh_participants')->join('sh_users','sh_participants.user_id = sh_users.id')
		->where_not_in('sh_participants.user_id',array($user_id))
		->get();
		$temp = $query->result_array();
		$arr = array_map (function($value){
			return $value['id'];
		} , $temp);
		return $arr;
	}

	public function getRecipients($q, $sh_id,$user_id,$role_id){
		$query = $this->db->
		select('sh_users.id, sh_users.role_id, sh_users.name as user_name, sh_users.avatar, sh_classes.name as class_name,sh_batches.name as batch_name, sh_role_categories.category,sh_departments.name as department')
		->like('sh_users.name',$q)
		->where('sh_users.deleted_at',0)
		->where('sh_users.school_id',$sh_id)
		->where('sh_users.role_id',$role_id)
		->where_not_in('sh_users.id',array($user_id))
		->from('sh_users')
		->join('sh_classes','class_id = sh_classes.id','left')
		->join('sh_batches','batch_id = sh_batches.id','left')
		->join('sh_role_categories','role_category_id = sh_role_categories.id', 'left')
		->join('sh_departments','sh_users.department_id = sh_departments.id','left')
		->get();

		$result = $query->result_array();

							//echo $this->db->last_query();


							//die();

		// 		$query = $this->db->
		// select('sh_users.id, sh_users.role_id, sh_users.name as username, sh_users.avatar')
		// 					->like('sh_users.name',$q)
		// 					->where('sh_users.deleted_at',0)
		// 					->from('sh_users')			
		// 					->get();				
		return $result;
	}


	public function startConversation($from,$to,$subject,$message,$files){
		$con_data = array(
			'subject' => $subject,
			'creator_id' => $from
		);
		$this->db->insert('sh_conversations',$con_data);
		$con_id = $this->db->insert_id();
		
		$message_data = array(
			'conversation_id' => $con_id,
			'message_body' => $message,
			'sender_id' => $from,
			'attachments' => $files
		);

		$this->db->insert('sh_messages',$message_data);
		

		$creator_data = array(
			'conversation_id' => $con_id,
			'user_id' => $from
		);

		$this->db->insert('sh_participants',$creator_data);
		$this->db->set('is_read','1')->where('user_id',$from)->update('sh_participants');

		foreach ($to as $user) {
			$this->db->insert('sh_participants',array('conversation_id'=>$con_id,'user_id'=>$user));
		}

		$from = $this->session->userdata('userdata')["name"];
		

		$temp = array($from,$con_id,$to);
		return $temp;


	}

	public function con_info($id){

		$query = $this->db->select('sh_conversations.id ,sh_conversations.subject,sh_conversations.created_at,sh_users.name')
		->where('sh_conversations.id',$id)
		->from('sh_conversations')
		->join('sh_users','sh_conversations.creator_id = sh_users.id')
		->get();
		
		return $query->row();
	}

	public function getParticipants($id,$user_id){
		$creator_id = $this->getCreatorId($id);
		$query = $this->db->select('sh_users.name')->where('sh_participants.conversation_id',$id)
		->from('sh_participants')->join('sh_users','sh_participants.user_id = sh_users.id')
		->where_not_in('sh_participants.user_id',array($user_id))
		->get();
		return $query->result();
	}

	public function getCreatorId($id){
		$query = $this->db->select('creator_id')->where('id',$id)->from('sh_conversations')->get()->row();
		return $query->creator_id;
	}

	public function makeReadDefault($con_id,$user_id){
		$this->db->set('is_read','0')
		->set('delete_status','0')
		->where('conversation_id',$con_id)
		->where_not_in('user_id',array($user_id))
		->update('sh_participants');
	}

	public function updateReadStatus($id,$user_id){
		$this->db->set('is_read','1')
		->where('conversation_id',$id)
		->where('user_id',$user_id)
		->update('sh_participants');
	}
}
?>