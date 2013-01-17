<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Reports extends DOM_Controller {
		
		public function __construct() {
			parent::__construct();	
			//loading the member model here makes it available for any member of the dashboard controller.
		}
		
		public function Index() {
			$this->Dashboard();	
		}
		
		
		public function Analytics() {
			/*
			$this->load->library('gapi');
			$ga = $this->gapi;
			$ga->requestReportData(54919407,array('browser','browserVersion'),array('pageviews','visits'));
			$google = '';
			foreach($ga->getResults() as $result) {
				$google = '<strong>'.$result.'</strong><br />';
				$google .= 'Pageviews: ' . $result->getPageviews() . ' ';
				$google .= 'Visits: ' . $result->getVisits() . '<br />'; 
			}
			$google .= '<p>Total pageviews: ' . $ga->getPageviews() . ' total visits: ' . $ga->getVisits() . '</p>';
			*/
		}
	
		public function Dashboard() {
			$this->load->helper('pass');
			$data = array();
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function DPR() {
			$this->load->model('rep');
			$selected_site_id = $this->user['DropdownDefault']->SelectedID;
			$reports = $this->rep->getDPR($this->user['DropdownDefault']->SelectedID);
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function Reports_query() {
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function Gameday() {
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function Dms_database() {
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function Web() {
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function Leads() {
			$this->LoadTemplate('pages/dashboard');	
		}
		
	}