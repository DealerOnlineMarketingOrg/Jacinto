<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('members');
		$this->load->model('login_attempts','attempts');
		$this->load->helper('formwriter');
		$this->load->helper('pass');
		$this->load->helper('msg');
		$this->load->library('form_validation');
    }

    public function index() {
		$this->load->helper('cookie');
		$cookie = ((isset($_COOKIE['dom_email'])) ? $_COOKIE['dom_email'] : FALSE);
		
		if($cookie) {
			$data = array(
				'email' => $cookie,
				'checkBox' => TRUE
			);	
		}else {
			$data = array(
				'email' => '',
				'checkBox' => FALSE
			);	
		}
		$this->LoadTemplate('pages/login',$data);
    }	
	
	public function login_user() {
		$this->load->helper('cookie');
		$remember_me = (float)$this->input->post('remember');
		if($remember_me == 1) {
			setcookie('dom_email',$this->input->post('email'),time()+3600,'/','');
		}else {
			if(isset($_COOKIE['dom_email'])) {
				setcookie('dom_email','',time()-3600,'/','');
			}
		}
		$this->load->helper('pass');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$ip_address = $this->input->ip_address();
		
		$valid_user = $this->members->validate($email,$password);
		if($valid_user) :
			$count = $this->attempts->get_count($ip_address,$email);
			
			if($count) {
				$this->attempts->clear($ip_address,$email);
			}
						
			echo '1';
		else :
			echo '0';
		endif;
	}
	
	public function log_failed_attempt() {
		$ip_address = $this->input->ip_address();
		$email = $this->input->post('email');
		//add a failed login attempt
		$log = $this->attempts->add($ip_address,$email);
		echo $log;
	}
	
	public function breach_warning() {
		$this->load->helper('email');
		$username = $this->input->post('email');
		$password = $this->input->post('password');
		$ip_address = $this->input->ip_address();
		
		$user = $this->members->validate($email,$password);
		if(!$user) {
			send_email('jwdavis@dealeronlinemarketing.com','Breach Warning','Someone is having difficulties logging in with username: ' . $this->input->post('email') . ' from the ip address: ' . $this->input->ip_address() . '. The system has already locked them out, but you should look into this because the reset pass isn\'t working correctly for them.');
			echo '0';
		}elseif($user) {
			echo '1';	
		}else {
			echo '1';	
		}
		
	}

}
