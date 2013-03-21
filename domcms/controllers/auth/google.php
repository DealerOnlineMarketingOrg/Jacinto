<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google extends CI_Controller {

    public $user;
    public $token;
    public $code;
    public function __construct() {
        parent::__construct();
        $this->load->model('members');
    }
	
	public function connect() {
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
			
			// These fields are currently filtered through the PHP sanitize filters.
			// See http://www.php.net/manual/en/filter.filters.sanitize.php
			$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);			
			$img   = filter_var($user['picture'], FILTER_VALIDATE_URL);
			
			// The access token may have been updated lazily.
			$_SESSION['google']['token'] = $client->getAccessToken();
			$_SESSION['google']['email'] = $email;
			$_SESSION['google']['image'] = $img;
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
	
	public function gettoken() {
			
	}
	
	public function Do_token_tasks() {
		
		require_once 'domcms/libraries/googleapi/Google_Client.php';
		require_once 'domcms/libraries/googleapi/contrib/Google_Oauth2Service.php';
		
		// Posted array from clientside.
		$access_token = $this->input->post('token');
		
		//create client object to hold oAuth2 information
		$client = new Google_Client();
		$client->setApplicationName('DomCMS');
		$client->setClientID(GoogleClientID);
		$client->setClientSecret(GoogleClientSecret);
		$client->setRedirectUri(base_url() . 'auth/google/connect');
		$client->setDeveloperKey(GoogleAPIKey);
		
		$oauth2 = new Google_Oauth2Service($client);
		
		// Authenticate the user with the token returned
		if (isset($_POST['token'])) {
			//authenticate the user through the api and create an array of userdata
			$client->authenticate($access_token['code']);
			
			$client->setAccessToken($_SESSION['google']['token']);
			
			$valid_user = $oauth2->userinfo->get();
			print_object($valid_user);
			
			//array to be stored in session
			$google_array = array(
				'token' => $client->getAccessToken(),
				'email' => '',
				'pic' => ''
			);
			
			//Create a session so this data is available everywhere
			$_SESSION['google'] = $google_array();
		}
		     
		/*
		//pass the access token from the client side to the api
		if(isset($_SESSION['google']['token'])) {
			$client->setAccessToken($_SESSION['google']['token']);	
		}
		
		//if we post a variable logout to this controller set to true, it will log out the google user.
		if(isset($_REQUEST['logout'])) {
			unset($_SESSION['google']);
			$client->revokeToken();	
		}
		
		// if the access token validates lets create session magic
		if($client->getAccessToken()) {
			$valid_user = $oauth2->userinfo->get();
			
			// These fields are currently filtered through the PHP sanitize filters.
			// See http://www.php.net/manual/en/filter.filters.sanitize.php
			$email = filter_var($valid_user['email'],FILTER_SANITIZE_EMAIL);
			$img   = filter_var($valid_user['picture'],FILTER_VALIDATE_URL);
			
			$_SESSION['google']['token'] = $client->getAcessToken();
			$_SESSION['google']['email'] = $email;
			$_SESSION['google']['pic'] = $img;	
			return $_SESSION['google'];
		}else {
			return FALSE;
		}
		*/
	}
	
}
