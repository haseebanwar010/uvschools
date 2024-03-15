<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model {


/*Read the data from DB */
	public function getEvents() {
		$school_id = $this->session->userdata("userdata")["sh_id"];		
		$user_id = $this->session->userdata("userdata")["user_id"];
		$role_id = $this->session->userdata("userdata")["role_id"];

		if (login_user()->user->role_id == ADMIN_ROLE_ID || $role_id == '4') {
			$sql = "SELECT * FROM sh_events WHERE sh_events.start BETWEEN ? AND ? AND sh_events.school_id=? AND sh_events.deleted_at IS NULL ORDER BY sh_events.start ASC";
			return $this->db->query($sql, array($_GET['start'], $_GET['end'], $school_id))->result();
		}
		
		if ($role_id == '2') {
			//$permission = 'public';

			$sql = "SELECT * FROM sh_events WHERE sh_events.start BETWEEN ? AND ? AND sh_events.school_id=? AND sh_events.mode=? AND sh_events.deleted_at IS NULL ORDER BY sh_events.start ASC";
		return $this->db->query($sql, array($_GET['start'], $_GET['end'], $school_id,'public'))->result();
			
		}
	}

	/*Create new events */
	public function addEvent(){
		$school_id = $this->session->userdata("userdata")["sh_id"];		
		$user_id = $this->session->userdata("userdata")["user_id"];
		$mode = $_POST["mode"];

		
		$sql = "INSERT INTO sh_events (title,sh_events.start,sh_events.end,description, color, event_type, holiday_type, mode, school_id, user_id) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$this->db->query($sql, array($_POST['title'], $_POST['start'],$_POST['end'], $_POST['description'], $_POST['color'], $_POST['event_type'], $_POST['holiday_type'], $mode, $school_id, $user_id));
			return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Update  event */
	public function updateEvent(){
		$sql = "UPDATE sh_events SET title = ?, description = ?, color = ?, event_type = ?, holiday_type = ?, mode = ? WHERE id = ?";
		$this->db->query($sql, array($_POST['title'],$_POST['description'], $_POST['color'], $_POST['event_type'], $_POST['holiday_type'], $_POST['mode'], $_POST['id']));
			return ($this->db->affected_rows()!=1)?false:true;
	}


	/*Delete event */
	public function deleteEvent(){
		$current_date_time = date("Y-m-d h:i:s");
		$sql = "UPDATE sh_events SET deleted_at='$current_date_time' WHERE id = ?";
		$this->db->query($sql, array($_GET['id']));
			return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Update  event */
	public function dragUpdateEvent(){
			//$date=date('Y-m-d h:i:s',strtotime($_POST['date']));
			$sql = "UPDATE sh_events SET  sh_events.start = ? ,sh_events.end = ?  WHERE id = ?";
			$this->db->query($sql, array($_POST['start'],$_POST['end'], $_POST['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}
}