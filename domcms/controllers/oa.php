<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Oa extends CI_Controller {
		public $openid;
		public function __construct() {
			parent::__construct();
			$this->openid = $this->load->library('LightOpenID');
		}
		
		public function index() {
			try {
				# Change 'localhost' to your domain name.
				if(!$this->openid->mode) {
					$login = $this->input->get('login');
					if(isset($login)) {
						$this->openid->identity = 'https://www.google.com/accounts/o8/id';
						refresh($this->opeinid->authUrl(),'location');
					}
					
					$html = '';
					$html .= form_open('?login',array('name'=>'login','id'=>'mainForm','class'=>'validate'));
					$html .= form_submit(array('name'=>'loginGoogle','id'=>'loginButton','class'=>'blueBtn'));
					$html .= form_close();
					echo $html;
				} elseif($this->openid->mode == 'cancel') {
					echo 'User has canceled authentication!';
				} else {
					echo 'User ' . ($this->openid->validate() ? $this->openid->identity . ' has ' : 'has not ') . 'logged in.';
				}
			} catch(ErrorException $e) {
				echo $e->getMessage();
			}
		}
		
		public function A() {
		}
}