<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Converter extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();	
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('converters');
		}
		
		public function Index() {
			$form = $this->input->post();
			if ($form['type'] == 'excel')
				$this->excel($form['html'], $form['file']);
			if ($form['type'] == 'pdf')
				$this->pdf($form['html'], $form['file']);
		}
		
		// Excel conversion.
		private function excel($html, $file_name) {
			$this->converters->html_to_excel($html, $file_name);
		}
		
		// PDF conversion.
		private function pdf($html, $file_name) {
			$this->converters->html_to_pdf($html, $file_name);
		}
		
	}