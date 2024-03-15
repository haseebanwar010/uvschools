<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();

        $this->load->model('Calendar_model');
    }


	/*Home page Calendar view  */
	Public function index()
	{
		
		$this->load->view('calendar/home');

	}

	/*Get all Events */

	Public function getEvents()
	{
		$result=$this->Calendar_model->getEvents();
		echo json_encode($result);
	}
	/*Add new event */
	Public function addEvent()
	{
		$result=$this->Calendar_model->addEvent();
		echo $result;
	}
	/*Update Event */
	Public function updateEvent()
	{
		$result=$this->Calendar_model->updateEvent();
		echo $result;
	}
	/*Delete Event*/
	Public function deleteEvent()
	{
		$result=$this->Calendar_model->deleteEvent();
		echo $result;
	}
	Public function dragUpdateEvent()
	{	

		$result=$this->Calendar_model->dragUpdateEvent();
		echo $result;
	}

	



}
