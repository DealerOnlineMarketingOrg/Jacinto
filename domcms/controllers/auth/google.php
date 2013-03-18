<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google extends CI_Controller {

	public $client_id;
	public $client_secret;
	public $api_key;
	public $params;
	public $client;
	public $plus;

    public function __construct() {
        parent::__construct();
    }

	public function form() {
		$this->load->view('themes/itsbrain/forms/form_googleconnect');
	}
	
	public function auth() {
		$this->client_id = $this->config->item('GoogleClientID');
		$this->client_secret = $this->config->item('GoogleClientSecret');
		$this->api_key = $this->config->item('GoogleAPIKey');
		
		$this->client = $this->load->library('googleapi/google_client');
		
		$this->client->setApplicationName('DomCMS');
		$this->client->setClientId($this->client_id);
		$this->client->setClientSecret($this->client_secret);
		$this->client->setRedirectUri('authorizzle');
		$this->client->setDeveloperKey($this->api_key);
		$this->plus = $this->load->library('googleapi/contrib/google_plusservice',$this->client);
		
		if(isset($_GET['code'])) {
			$this->client->authenticate();
			$_SESSION['token'] = $this->client->getAccessToken();
			$redirect = base_url() . 'auth/openid/google_authenticate';
			header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}
		
		if(isset($_SESSION['token'])) {
			$this->client->setAccessToken($_SESSION['token']);
		}
		
		if($this->client->getAccessToken()) {
			$activities = $this->plus->activities->listActivities('me','public');
			print 'Your Activities: <pre>' . print_r($activities, true) . '</pre>';
			
			$_SESSION['token'] = $this->client->getAccessToken();
		}else {
			$authUrl = $this->client->createAuthUrl();
			print '<a href="$authUrl">Connect Me!</a>';
		}

	}
}
