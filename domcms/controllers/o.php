<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class O extends CI_Controller {
		
		public function __construct() {
			$this->load->library('LightOpenID');
		}
		
		public function index() {
			try {
				# Change 'localhost' to your domain name.
				$openid = $this->LightOpenID('http://content.dealeronlinemarketing.com');
				if(!$openid->mode) {
					if(isset($_GET['login'])) {
						$openid->identity = 'https://www.google.com/accounts/o8/id';
						refresh($opeinid->authUrl(),'location');
					}
					
					$html = '';
					$html .= form_open('?login',array('name'=>'login','id'=>'mainForm','class'=>'validate'));
					$html .= form_submit(array('name'=>'loginGoogle','id'=>'loginButton','class'=>'blueBtn'));
					$html .= form_close();
					echo $html;
				} elseif($openid->mode == 'cancel') {
					echo 'User has canceled authentication!';
				} else {
					echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
				}
			} catch(ErrorException $e) {
				echo $e->getMessage();
			}
		}
		
		public function A() {
		}
}