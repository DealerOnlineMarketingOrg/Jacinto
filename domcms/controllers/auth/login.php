<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends DOM_Controller {
	public $google_token;
	public $google_email;
	public $google_avatar;
    public function __construct() {
        parent::__construct();
		$this->load->model('members');
		$this->load->model('login_attempts','attempts');
		$this->load->helper('formwriter');
		$this->load->helper('pass');
		$this->load->helper('msg');
		$this->load->library('form_validation');
		
		if(isset($_SESSION['google'])) {
			$token = explode('"',$_SESSION['google']['token']);
			$this->google_token = $token[3];	
			$this->google_email = $_SESSION['google']['email'];
			$this->google_avatar = $_SESSION['google']['image'];
		}
		
		if(isset($this->google_email)) {
			$this->log_google_info();
		}
		
    }
	
	private function log_google_info() {
		$log = $this->members->logGoogleToken($this->google_email,$this->google_token);
		if($log) {
			$valid_user = $this->members->validate($this->google_email,false,$this->google_token);
			if($valid_user) {
				redirect('/','refresh');
			}
			/*
			$this->login_user();
			 */
		}else {
			echo 'The google user was not found on our system.';
		}			
	}
	
	public function google_connect() {
		require_once 'domcms/libraries/googleapi/Google_Client.php';
		require_once 'domcms/libraries/googleapi/contrib/Google_Oauth2Service.php';

		 // session_start();
                  
		$client = new Google_Client();
		$client->setApplicationName('DomCMS');
		$client->setClientID(GoogleClientID);
		$client->setClientSecret(GoogleClientSecret);
		$client->setRedirectUri(base_url() . 'auth/google/connect');
		$client->setDeveloperKey(GoogleAPIKey);
		
		$oauth2 = new Google_Oauth2Service($client);
		
		//print_object($oauth2);
		
		if (isset($_GET['code'])) {
			$client->authenticate($_GET['code']);
			$_SESSION['google'] = array('token' => $client->getAccessToken(), 'email' => '', 'image' => '');
			$redirect = base_url();
			header('Location: ' . base_url());
			return;
		}        
		
		if (isset($_SESSION['google']['token'])) {
			$client->setAccessToken($_SESSION['google']['token']);
		}
		
		if (isset($_REQUEST['logout'])) {
			unset($_SESSION['google']);
			$client->revokeToken();
		}
		
		if ($client->getAccessToken()) {
			$user = $oauth2->userinfo->get();
			
			//print_object($user);
			//
			// These fields are currently filtered through the PHP sanitize filters.
			// See http://www.php.net/manual/en/filter.filters.sanitize.php
			$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);			
			$img   = filter_var($user['picture'], FILTER_VALIDATE_URL);
			
			// The access token may have been updated lazily.
			$_SESSION['google']['token'] = $client->getAccessToken();
			$_SESSION['google']['email'] = $email;
			$_SESSION['google']['image'] = $img;
			
			//print_object($_SESSION['google']);
			
			//redirect(base_url(),'refresh');
		} else {
			$authUrl = $client->createAuthUrl();
		
                  
	  ?>

			<?php if(isset($personMarkup)): ?>
            <?php 	print $personMarkup ?>
            <?php endif ?>
            <?php
            if(isset($authUrl)) {
                print '<a style="display:block;text-align:center;margin-top:10px;" class="login" href="' . $authUrl . '"><img style="border:1px solid #d5d5d5;" src="' . base_url() . THEMEIMGS . 'google.png" alt="Connect to Google" /></a>';
            } else {
                print '<a class="logout" href="' . base_url() . 'auth/google/connect?logout">Logout</a>';
            }
              
		}
	}	
	

    public function index($msg = false) {
		
		/*if(isset($_SESSION['google'])) {
			//print_object($_SESSION['google']);
			$usr = $this->members->validate($_SESSION['google']['email'],false,$_SESSION['google']['token']);
			//print_r($usr);
			if($usr) {
				$this->user = $this->session->userdata('valid_user');	
			}
		}*/
		
		if(isset($_COOKIE['dom_email'])) {
			$data = array(
				'email' => $_COOKIE['dom_email'],
				'checkBox' => TRUE
			);	
		}else {
			$data = array(
				'email' => '',
				'checkBox' => FALSE
			);	
		}
		
		if(isset($_SESSION['google'])) {
			$this->log_google_info();
		}
		if(!$this->user) {
			$this->LoadTemplate('pages/login',$data);
		}else {
			redirect('/','refresh');	
		}
    }	
	
	public function login_user() {
		$this->load->helper('cookie');
		$remember_me = (($this->input->post('remember')) ? 1 : FALSE);
		if($remember_me == 1) {
			setcookie('dom_email',$this->input->post('email'),time()+360000,'/','');
		}else {
			if(isset($_COOKIE['dom_email'])) {
				setcookie('dom_email','',time()-360000,'/','');
			}
		}
		$this->load->helper('pass');
		
		if(isset($_SESSION['google'])){
			$email = $this->google_email;
			$google_token = $this->google_token;
			$password = FALSE;
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$valid_user = $this->members->validate($email,$password,$google_token);
		}else {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$ip_address = $this->input->ip_address();
			$valid_user = $this->members->validate($email,$password);
		}
		
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