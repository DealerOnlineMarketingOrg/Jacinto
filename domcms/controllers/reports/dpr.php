<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dpr extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('getdpr');
			$this->load->helper('charts_helper');
		}
		
		public function Index() {
			$this->Load('add');
		}
		
		public function reports() {
			$this->Load('reports');
		}
				
		public function Load($page) {
			
			// Processing for dpr entry page.
			if ($page == 'add') {
				$report_data = $this->getdpr->get('Provider');
				$prov_options = $this->getdpr->output_as_options($report_data);
				$agency_list = $this->getdpr->get('Agency');
				$agency_options = $this->getdpr->output_as_options($agency_list);
				
				$data = array(
					'html' => '',
					'providers' => $prov_options,
					'agencies' => $agency_options,
					'report_leads' => '',
					'report_lineChart' => '',
					'report_lineChart_script' => '{}'
				);
			}
			
			// Processing for dpr report page.
			if ($page == 'reports') {
				if ($this->user['DropdownDefault']->SelectedClient <= 1) {
					
				$report = $this->rep->getDPRReport($this->user['DropdownDefault']->SelectedClient, 2010, 2012);
				// Wrap each chart in a div to keep them seperate.
				$report_element_start = 1;
				
				$report_lineChart_script = '';
				$report_html = generateLineChart($report, 'lineChart', $report_element_start, $report_lineChart_script);
				$report_lineChart = "<div id='reportBlock_lineChart' class='report'>" . $report_html . "</div>";
				
				$report_pieChart_script = '';
				$report_html = generatePieChart($report, 'pieChart', $report_element_start, $report_pieChart_script);
				$report_pieChart = "<div id='reportBlock_pieChart' class='report'>" . $report_html . "</div>";
				
				$report_html = generateTableChart($report, 'leads', $report_element_start);
				$report_leads = "<div id='reportBlock_tableChart' class='report'>" . $report_html . "</div>";

				$data = array(
					'html' => '',
					'providers' => '',
					'agencies' => '',
					'report_leads' => $report_leads,
					'report_lineChart' => $report_lineChart,
					'report_lineChart_script' => $report_lineChart_script,
					'report_pieChart' => $report_pieChart,
					'report_pieChart_script' => $report_pieChart_script
				);
			}

			switch ($page) {
				case 'add':		$this->LoadTemplate('forms/form_addDpr',$data); break;
				case 'reports': $this->LoadTemplate('forms/form_reportDpr',$data); break;
			}	
			
		}
	
	}