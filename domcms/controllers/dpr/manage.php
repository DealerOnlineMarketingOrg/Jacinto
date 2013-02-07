<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Manage extends DOM_Controller {
		
		public function __construct() {
			parent::__construct();	
			//loading the member model here makes it available for any member of the dashboard controller.
		}
		
		public function Index() {
			$html = '';
			$data = array(
				'html' => ''
			);
			$this->LoadTemplate('forms/form_addDpr',$data);
		}

	}