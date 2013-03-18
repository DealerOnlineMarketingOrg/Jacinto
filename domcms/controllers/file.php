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
			
			$fwriter = fopen($form['destPath'], 'w');
			$img = str_replace('image/png;base64,', '', $form['data']);
			$img = urldecode($img);
			if (!fwrite($fwriter, base64_decode($img)))
				echo false;
			else
				echo true;
			fclose($fwriter);
		}
		
	}