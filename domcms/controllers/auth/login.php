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
		$this->LoadTemplate('pages/login');
    }
	
	public function authenticate() {
		//validation rules
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password','Password','trim|required|alpha_numeric|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="error">','</div>');
	
		//form elements posted to controller function
		$email 		= $this->security->xss_clean($this->input->post('email'));
		$ip_address = $this->input->ip_address();
	
		//If its not a valid IP Address, kill the app.
		if(!$this->input->valid_ip($ip_address)) {
			die('Your system does not have a valid ip address associated with it. We require that you do.');
		};
		
		//check how many times this email address from this IP address has attempted to login unsuccessfully.
		$check_attempts = $this->attempts->get_count($ip_address,$email);
		//$check_attempts = FALSE;
		
		//if the login attempts go above 5 times, we show the reset password page instead.
		if($check_attempts < 5) {
			//run the form validation
			if($this->form_validation->run() == FALSE) {
				//set some custom error messages
				$this->form_validation->set_message('valid_email','The email address provided is the incorrect format. Please try again.');
				$this->form_validation->set_message('required','Some required elements were left unfilled. Please try again.');
				$this->form_validation->set_message('alpha_numeric','The password only allows alpha and numeric values. Please Try again.');
	
				//load the login view and show error messages
				$this->load->view($this->theme_settings['GlobalDir'] . '/incl/header', $this->theme_settings);
				$this->load->view($this->theme_settings['GlobalDir'] . '/incl/user_nav');
				$this->load->view($this->theme_settings['ThemeDir'] . '/pages/forgotpass');
				$this->load->view($this->theme_settings['ThemeDir'] . '/incl/footer');
				$this->load->view($this->theme_settings['GlobalDir'] . '/incl/footer');
	
				//$this->load->view($this->theme_settings['ThemeDir'] . '/pages/forgotpass', $data);
	
				//add a failed login attempt
				$this->attempts->add($ip_address,$email);
			}else {	
	
				//posts the form to the members model and verifys the data
				//should return an object
				$user = $this->members->validate();
	
				//if the user failed to authenticate.
				//set some error messages and increment our login attempts var.
				//show the login form.
				if(!is_object($user) && $user->isGenerated != 1) {
	
					$this->form_validation->set_message('email',	'Your email address or password is incorrect.');
					$this->form_validation->set_message('password', 'Your email address or password is incorrect.');
	
					//increment failed login
					$this->attempts->add($ip_address, $email);
	
					//load form
					$data = array(
						'form' => LoginForm()
					);
					$this->load->view($this->theme_settings['GlobalDir'] . '/incl/header', $this->theme_settings);
					$this->load->view($this->theme_settings['GlobalDir'] . '/incl/user_nav');
					$this->load->view($this->theme_settings['ThemeDir'] . '/pages/login');
					$this->load->view($this->theme_settings['ThemeDir'] . '/incl/footer');
					$this->load->view($this->theme_settings['GlobalDir'] . '/incl/footer');
	
				}else {
					
				}  
			}
		}else {
			//after the 5th failed login, send the user to reset password page. 
			$this->form_validation->set_message('email','You have exceeded 5 login attempts for this username with your current session. This Email address will be locked out of our system for 24 hrs unless you call support. You may reset the password for the user and try to log in with the generated password. Sorry for the inconvienience.');
			$data = array(
			  'form' => ForgotPassForm()  
			);
		$this->load->view($this->theme_settings['GlobalDir'] . '/incl/header', $this->theme_settings);
		$this->load->view($this->theme_settings['GlobalDir'] . '/incl/user_nav');
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/login');
		$this->load->view($this->theme_settings['ThemeDir'] . '/incl/footer');
		$this->load->view($this->theme_settings['GlobalDir'] . '/incl/footer');
		}
	}
	
	
	public function login_user() {
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
