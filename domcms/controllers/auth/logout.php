<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends DOM_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
		$this->session->unset_userdata('valid_user');
		//create cookie
		setcookie('oAuth_token','',time()-360000,'/','');
		setcookie('oAuth_email','',time()-360000,'/','');

		redirect('/','refresh');
	}
}