<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends DOM_Controller {
	
	public $level;
	
    public function __construct() {
        parent::__construct();
        $this->load->model('administration');
        $this->load->helper('html');
        $this->load->helper('template');
        $this->load->library('table');
        $this->load->model('utilities');
        $this->load->helper('msg');
        $this->level = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

    public function index() {
		$html = '';
		if($this->level == 1 OR $this->level == 'a') {
			$client_id = $this->user['DropdownDefault']->SelectedAgency;	
		}elseif($this->level == 2 OR $this->level == 'g') {
			$client_id = $this->user['DropdownDefault']->SelectedGroup;	
		}elseif($this->level == 3 OR $this->level == 'c') {
			$client_id = $this->user['DropdownDefault']->SelectedClient;	
		}
		//echo $client_id;
		$users = $this->administration->getUsersByUserInfo($client_id);
		//Creating the table headings (th)
		$this->table->set_heading('Avatar', 'Email Address', 'Name', 'Status', 'Member Since','Edit Details','View Info');
		if($users AND count($users) > 1) {
			foreach ($users as $user) {
				$form_cred = array(
					'name' => 'edit_user',
					'id' => 'user_' . $user->USER_ID
				);
				//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
				$button = array(
					'name' => 'submit',
					'id' => 'user_id_' . $user->USER_ID,
					'class' => 'redBtn',
					'value' => 'Edit'
				);
				
				$view_button = array(
					'name'=>'view',
					'id'=>'view_user_'.$user->USER_ID,
					'class'=>'blueBtn',
					'value'=>'View'
				);

				$tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
				$this->table->set_template($tmpl);
				
				//edit button
				$edit_form = (($this->CheckModule('User_Edit')) ? form_open('users/edit', $form_cred) . form_hidden('user_id', $user->USER_ID) . form_submit($button) . form_close() : '');
				$view_form = form_open('users/view',array('name'=>'view_user')) . form_hidden('user_id',$user->USER_ID) . form_submit($view_button) . form_close();
				
				$this->table->add_row('<div align="center"><img style="width:25px;" src="' . $this->gravatar->get_gravatar($user->USER_GravatarEmail) . '" /></div>',$user->USER_Name, $user->DIRECTORY_LastName . ', ' . $user->DIRECTORY_FirstName, (($user->USER_Active == 1) ? 'Active' : 'Disabled'), date('n-j-Y', strtotime($user->USER_Created)),'<div align="center">' . $edit_form . '</div>','<div align="center">' . $view_form . '</div>');
				
			}
		}elseif($users AND count($users) == 1) {
				$form_cred = array(
					'name' => 'edit_user',
					'id' => 'user_' . $users->USER_ID
				);
				//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
				$button = array(
					'name' => 'submit',
					'id' => 'user_id_' . $users->USER_ID,
					'class' => 'basicBtn',
					'value' => 'Edit'
				);
				
				$view_button = array(
					'name'=>'view',
					'id'=>'view_user_'.$users->USER_ID,
					'class'=>'greenBtn',
					'value'=>'View'
				);

				$tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
				$this->table->set_template($tmpl);
				
				//edit button
				$edit_form = (($this->CheckModule('User_Edit')) ? form_open('users/edit', $form_cred) . form_hidden('user_id', $users->USER_ID) . form_submit($button) . form_close() : '');
				$view_form = form_open('users/view',array('name'=>'view_user')) . form_hidden('user_id',$users->USER_ID) . form_submit($view_button) . form_close();
				$this->table->add_row('<div align="center"><img style="width:25px;" src="' . $this->gravatar->get_gravatar($users->USER_GravatarEmail) . '" /></div>',$users->USER_Name, $users->DIRECTORY_LastName . ', ' . $users->DIRECTORY_FirstName, (($users->USER_Active == 1) ? 'Active' : 'Disabled'), date('n-j-Y', strtotime($users->USER_Created)),$edit_form,$view_form);
		}else {
			$error_msg = '<p style="text-align:center">No users are associated for this client. Please use the Dealer Dropdown to select another client, or add users by clicking the "Add New User" button below.</p>';	
		}


		//BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
		$html .=  ((count($users) > 0) ? $this->table->generate() : $error_msg);

		if ($this->CheckModule('User_Add')) {
			$html .= anchor(base_url() . 'users/add', 'Add New User', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_user_btn"');
		}

		//Prepare data for template in $data array; when in template, you call the keys like vars: example => $html;
		$data = array(
			'page_id' => 'Users',
			'page_html' => $html
		);

		
		$this->LoadTemplate('pages/listings', $data);
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
	
	public function Delete() {
		
	}
	
	public function View() {
		
		//the user posted to the view.
		$user_id = $this->input->post('user_id');
		
		//get the users information
		$user = $this->administration->getUsers($this->input->post('user_id'));
		
		
		$user->Name = $user->FirstName . ' ' . $user->LastName;
		$user->Address = (isset($user->Address)) ? mod_parser($user->Address) : false;
		$user->Phone = (isset($user->Phone)) ? mod_parser($user->Phone) : false;
		$user->Email = mod_parser($contact->Email);
		$data = array(
			'display'=>$user
		);
		$this->LoadTemplate('pages/details_user',$data);
	}

}
