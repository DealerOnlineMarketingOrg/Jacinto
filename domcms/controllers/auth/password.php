<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Password extends DOM_Controller {

    public function __construct() {
        parent::__construct();
    }
	
	public function Reset() {
		$this->load->model('members');
		$this->load->helper('pass');
		$generated_password = createRandomString(10,'ALPHANUMSYM');
		$email = $this->input->post('email');
		
		$reset = $this->members->reset_password($email,$generated_password);
	}

}