<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	class File extends DOM_Controller {
		
		public function __construct() {
			parent::__construct();

			$this->load->helper('err_helper');
		}
		
		// $data is in the form:
		// data [ data, destPath ]
		// Returns: true if successful, false if not.
		public function saveDataURL() {
			$form = $this->input->post();
			
			$img = str_replace('data:image/png;base64,', '', $form['data']);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			if (file_put_contents($form['destPath'], $img))
				echo true;
			else
				echo false;
		}
		
		// Compresses a list of files and adds them to the specified zip file.
		public function zipFiles() {
			// Get a new zip initialization for each call.
			$this->load->library('zip');

			$form = $this->input->post();
			
			foreach ($form['file_list'] as $file)
				// Store file in zip.
				$this->zip->read_file($file);
				
			// Save zip to drive.
			$this->zip->archive($form['zip_file']);
		}
		
	}