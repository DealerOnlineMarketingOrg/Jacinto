<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends DOM_Controller {
	
	public $level;
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('members','administration','utilities'));
        $this->load->helper(array('template','msg','html'));
        $this->level = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

	public function load_table($return = false) {
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/user_listing_table');
	}

    public function index() {
		$this->LoadTemplate('pages/user_listing');
    }
	
	public function Add() {
		$html = '';
		$data = array(
		  'html' => $html  
		);
		$this->LoadTemplate('forms/form_adduser',$data);
	}
	
	public function Edit() {
		$html = '';
		$user_id = $this->input->post('user_id');
		$user = $this->administration->getUsers($user_id);
						
		$data = array(
			'user' => $user,
		);
		
		$this->LoadTemplate('forms/form_edituser', $data);
	}
	
	public function View() {
		//the user posted to the view.
		$user_id = $this->input->post('user_id');
		$user = $this->administration->getUsers($user_id);
		//print_object($user);
		$user->Name = $user->FirstName . ' ' . $user->LastName;
		$users_address = '';
		if($user->Address['street'] != '') {
			foreach($user->Address as $address) {
				$users_address .= $address . ' ';
			}
		}else {
			$users_address = FALSE;	
		}
		$user->Address = $users_address;		
		$user->Notes = (($user->Notes) ? $user->Notes : FALSE);
		$data = array(
			'display' => $user
		);
		$this->LoadTemplate('pages/details_user',$data);
	}

}
