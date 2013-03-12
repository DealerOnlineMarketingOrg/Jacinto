<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Password extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('members');
		$this->load->helper('pass');
		$this->load->helper('msg');	
	}

	public function Reset() {
		
	}
	
	public function Change() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');
	}
	
	public function processResetPass() {
		$email = $this->input->post('email');
		$reset = $this->members->reset_password($email);
		echo $reset; 
	}
	
	public function checkIfPasswordIsGenerated() {
		$email = $this->input->post('email');
		$check = $this->members->checkPasswordGeneration($email);
		echo $check;
	}
	
	public function loadChangePasswordForm() {
		$email = $this->input->post('email');
		$data = array(
			'email' => $email
		);
		$this->load->view('themes/itsbrain/forms/form_changepassword',$data);	
	}
	
	public function loadResetPasswordForm() {
		$this->load->view('themes/itsbrain/forms/form_resetpassword');	
	}
	
	public function processChangePassword() {
		$email = $this->input->post('email');
		$oldpass = $this->input->post('oldPass');
		$newpass = $this->input->post('newPass');
		
		$check_password = checkPasswordCharacters($newpass);
		
		if($check_password) {
			$user = $this->members->change_password($email, $oldpass, $newpass);
			if($user) {
				echo '1';	
			}
		}else {
			echo 'The Password does not contain at least 1 upper case letter, 1 lower case letter, 1 number or special character and/or is not 8 characters long. Please Try Again.';	
		}
	}	

}