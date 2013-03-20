<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google extends CI_Controller {

    public $user;
    public $token;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('members');
    }
	
	public function connect() {
		require_once 'domcms/libraries/googleapi/Google_Client.php';
		require_once 'domcms/libraries/googleapi/contrib/Google_PlusService.php';
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
			$_SESSION['token'] = $client->getAccessToken();
			$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			//header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
			return;
		}        
		
		if (isset($_SESSION['token'])) {
			$client->setAccessToken($_SESSION['token']);
		}
		
		if (isset($_REQUEST['logout'])) {
			unset($_SESSION['token']);
			$client->revokeToken();
		}
		
		if ($client->getAccessToken()) {
			$user = $oauth2->userinfo->get();
		
			// These fields are currently filtered through the PHP sanitize filters.
			// See http://www.php.net/manual/en/filter.filters.sanitize.php
			$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);			
			$img = filter_var($user['picture'], FILTER_VALIDATE_URL);
			
			$token = $client->getAccessToken();
			
			//print_object($client->getAccessToken());
			
			$log = $this->members->logGoogleToken($email,$client->getAccessToken());
			
			if($log) {
				$user = $this->members->validate($email,false,$client->getAccessToken());
				if($user) {
					$avatar = $this->members->plus_avatar($email,$img);
				}
			}
			
			$personMarkup = "$email<div><img src='$img?sz=200'></div>";
			print $personMarkup;
			// The access token may have been updated lazily.
			$_SESSION['token'] = $client->getAccessToken();
		} else {
			$authUrl = $client->createAuthUrl();
		
                  
	  ?>

			<?php if(isset($personMarkup)): ?>
            <?php print $personMarkup ?>
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
	
}
