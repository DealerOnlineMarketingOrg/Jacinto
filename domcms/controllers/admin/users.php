<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends DOM_Controller {
	
	public $level;
	public $user_id;
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('members','administration','utilities'));
        $this->load->helper(array('template','msg','html'));
        $this->level = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
		
		if(isset($_GET['uid'])) {
			$this->user_id = $_GET['uid'];	
		}else {
			$this->user_id = FALSE;	
		}
    }

	public function Load_table($return = false) {
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/user_listing_table');
	}

    public function Index() {
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
		$user = $this->administration->getMyUser($this->user_id);
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Emails = mod_parser($user->Emails);
		$user->Phones = mod_parser($user->Phones);
		$avatar = $this->members->get_user_avatar($user->ID);
		$data = array(
			'user'=>$user,
			'avatar'=>$avatar
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddviewuser',$data);
	}
	
	public function Edit_details_form() {
		//gonna use the clients from the masterlist function because it auto filters based on the dealer dropdown
		$this->load->model('mlist');
		$dealerships = $this->mlist->getClients();
		$user = $this->administration->getMyUser($this->user_id);
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Emails = mod_parser($user->Emails);
		$user->Phones = mod_parser($user->Phones);
		$data = array(
			'user'=>$user,
			'dealerships'=>$dealerships
		);	
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_userinfoedit',$data);
	}
	
	public function View_popup() {
		$user = $this->administration->getMyUser($this->user_id);
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Emails = mod_parser($user->Emails);
		$user->Phones = mod_parser($user->Phones);
		$avatar = $this->members->get_user_avatar($user->ID);
		$data = array(
			'user'=>$user,
			'view'=>TRUE,
			'avatar'=>$avatar
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editaddviewuser',$data);
	}
	
	public function Change_pass_form() {
		$user = $this->administration->getMyUser($this->user_id);
		$data = array(
			'user'=>$user
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_changepasswordpopup',$data);
	}
	
	/*This will change the users password to whatever the user wants it to be*/
	public function Change_user_password() {
		$this->load->helper('pass');
		$user = $this->administration->getMyUser($this->user_id);
		//password
		$old_pass = $this->input->post('oldPassword');
		$newPass = $this->input->post('newPassword');
		$confirmNewPass = $this->input->post('confirmNewPassword');
		if($newPass == $confirmNewPass) {
			//check if old password is corrent
			$verify = $this->members->validateUser($user->Username,$old_pass);
			if($verify) {
				//validate if the password is in the correct format
				$valid_new_pass = checkPasswordCharacters($newPass);
				if($valid_new_pass) {
					$change_pass = $this->members->simple_pass_change($user->ID,$newPass);
					if($change_pass) {
						echo '1';
					}else {
						echo '6';	
					}
				}else {
					echo '7';	
				}
			}else {
				echo '8';	
			}
		}else {
			echo '9';	
		}
	}
	
	public function Reset_user_password() {
		$this->load->helper('pass');
		$user = $this->administration->getMyUser($this->user_id);
		//set the users primary email address
		$user->Emails = mod_parser($user->Emails);
		$primaryEmail = $user->Emails[$user->PrimaryEmail];
		//generate a new password
		$newPass = createRandomString();
		//this will also email the user their email address, this is sent to the users PRIMARY email, which could differ from the email they are logging in with.
		$reset = $this->members->simple_reset_pass($user->Username,$user->ID,$newPass);
		if($reset) {
			echo $newPass;	
		}else {
			echo '0';	
		}
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