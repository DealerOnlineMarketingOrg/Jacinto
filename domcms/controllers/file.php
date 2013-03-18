<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	class File extends DOM_Controller {
		
		public function __construct() {
			parent::__construct();
		}
		
		// $data is in the form:
		// data [ data, destPath ]
		// Returns: true if successful, false if not.
		public function saveDataURL() {
			$this->load->helper('err_helper');
			$form = $this->input->post();
			
			$img = str_replace('data:image/png;base64,', '', $form['data']);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			if (file_put_contents($form['destPath'], $img))
				echo true;
			else
				echo false;
		}
		
	}