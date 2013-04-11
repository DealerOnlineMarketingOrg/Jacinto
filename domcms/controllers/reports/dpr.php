<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//
	class Dpr extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('getdpr');
			$this->load->helper('charts');
			$this->load->helper('controls');
			$this->load->model('administration');

			$this->activeNav = 'reports';
		}
		
		public function Index() {
			$endMonth = date('m');
			$endYear = date('Y');
			$startMonth = '1';
			$startYear = (string)($endYear - 1);
			$data = array(
				'startMonth' => $startMonth,
				'startYear' => $startYear,
				'endMonth' => $endMonth,
				'endYear' => $endYear
			);
			$this->Load('reports', $data);
		}
		
		public function add() {
			$report_data = $this->getdpr->get('Provider');
			$prov_options = $this->getdpr->output_as_options($report_data);
			$service_list = $this->getdpr->get('Service');
			$service_options = $this->getdpr->output_as_options($service_list);
			
			$data = array(
				'html' => '',
				'page' => 'add',
				'providers' => $prov_options,
				'services' => $service_options,
				'report_leads' => '',
				'report_lineChart' => '',
				'report_lineChart_script' => '{}'
			);
			
			//$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addDpr',$data);
		}
		
		public function import_step1() {
			$report_data = $this->getdpr->get('Provider');
			$prov_options = $this->getdpr->output_as_options($report_data);
			
			$data = array(
				'sources' => $prov_options,
			);
			
			//$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprImport_step1',$data);
		}
		
		public function import_step2() {
			$form = $this->input->post();
			
			$source = $this->getdpr->get('Provider', $form['source']);
			$service_list = $this->getdpr->get('Service');
			$service_options = $this->getdpr->output_as_options($service_list);
				
			$data = array(
				'persistent' => array(
					'source' => $source,
				),
				'source' => $source,
				'metrics' => $service_options,
			);
			//$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprImport_step2',$data);
		}
		
		public function import_step3() {
			$form = $this->input->post();
			
			$provider = $form['provider'];
			// Generate array of metrics.
			$metrics = array();
			$count = $form['metricCount'];
			for ($i = 0; $i < $count; $i++)
				$metrics[] = $this->getdpr->get('Service', $form['metrics_'.$i]);
			
			$data = array(
				'persistent' => array(
					'source' => $form['source'],
					'metrics' => $metrics,
				),
			);
			
			//$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprImport_step3',$data);
		}
		
		// Returns array of (month/year)s in the specified date range.
		function getMonthYearRange($startDate, $endDate) {
			$startMonth = date('n', strtotime($startDate));
			$endMonth = date('n', strtotime($endDate));
			$startYear = date('y', strtotime($startDate));
			$endYear = date('y', strtotime($endDate));
			$range = array();
			for ($y = $startYear; $y <= $endYear; $y++) {
				if ($y == $startYear) {
					$sm = $startMonth;
					$em = ($y != $endYear) ? 12 : $endMonth;
				} else {
					$sm = 1;
					$em = ($y != $endYear) ? 12 : $endMonth;
				}
				for ($m = $sm; $m <= $em; $m++)
					$range[] = $m . '/' . $y;
			}
			return $range;
		}
		
		public function import_step4() {
			$form = $this->input->post();
		
			$startDate = $form['metricsStartMonth'].'/1/'.$form['metricsStartYear'];
			$endDate = $form['metricsEndMonth'].'/1/'.$form['metricsEndYear'];
			
			$colNames = getMonthYearRange($startDate, $endDate);
			$rowNames = $form['metrics'];
			$rowNames[] = 'Cost';
			
			$data = array(
				'columns' => $colNames,
				'rows' => $rowNames,
			);
			
			$spreadsheet = $this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_spreadsheet', $data, true);
			
			$data = array(
				'persistent' => array(
					'source' => $form['source'],
					'metrics' => $form['metrics'],
				),
				'provider' => $provider,
				'metrics' => $metrics,
				'spreadsheet' => $spreadsheet,
			);
			
			//$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->view($this->theme_settings['ThemeDir'] . '/wizards/ReportDprImport_step4',$data);
		}
		
		public function reports() {
			$this->Load('reports');
		}
		
		public function editReport() {
			$this->Load('editReport');	
		}
		
		public function ajaxGetCost() {		
			$form = $this->input->post();
			
			$where = array(
				'PROVIDER_ID' => $form['provider'],
				'PROVIDERDATA_Month' => $form['month'],
				'PROVIDERDATA_Year' => $form['year'],
			);
			$query = $this->db->get_where('DPRProviderData', $where);
			$results = $query->result();
			
			$cost = '';
			if ($query->num_rows > 0) {
				foreach ($results as $row) {
					$cost = $row->PROVIDERDATA_Cost;
					// We only want the first result.
					break;
				}
			}
			
			echo $cost;
		}
		
		public function eMail() {
			$domGroup = 1;
			// Don't grab group contacts if we're already on the dom group.
			if ($this->user['DropdownDefault']->SelectedClient != $domGroup) {
				$group_contacts = $this->administration->getAllContactsInGroup($this->user['DropdownDefault']->SelectedClient);
				$dom_contacts = $this->administration->getAllContactsInGroup($domGroup);
				$all_contacts = array_merge($group_contacts, $dom_contacts);
			} else {
				$all_contacts = $this->administration->getAllContactsInGroup($domGroup);
			}
			
			$contacts = array();
			foreach ($all_contacts as $contact) {
				$name = '[' . $contact->ClientCode . '] ' . $contact->FirstName . ' ' . $contact->LastName;
				if ($contact->JobTitle != '')
					$name .= ' (' . $contact->JobTitle . ')';
				$c['text'] = $name;
				// Get work email from list.
				$emails = explode(',', $contact->Email);
				$c['value'] = '';
				foreach ($emails as $email) {
					$emailParts = explode(':', $email);
					if ($emailParts[0] == 'work')
						$c['value'] = $emailParts[1];
				}
				$contacts[] = $c;
			}
			
			$data = array (
				'items' => $contacts,
				'options' => $this->input->post()
			);
			
			if ($contacts)
				//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_input', $data);
			else
				//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
				echo 0;
		}
		
		public function Load($page, $rdata = FALSE) {
			$form = $this->input->post();
			
			// Processing for dpr report page.
			if ($page == 'reports' || $page == 'editReport') {
				// If function was called posted instead of called, get posted data.
				if (!$rdata) $rdata = $form;
				
				$signature = '';
				if ($this->user['DropdownDefault']->SelectedClient < 1) {
					throwError(newError('Clients Add', -1, 'You must chose a dealership in order to view a DPR report.', 0, ''));
					$runReport = false;
					$report_leads = '';
					$report_lineChart = '';
					$report_lineChart_script = '';
					$report_pieChart = '';
					$report_pieChart_script = '';
					
				} else {
					$report = $this->rep->getDPRReport($this->user['DropdownDefault']->SelectedClient, $rdata['startMonth'], $rdata['startYear'], $rdata['endMonth'], $rdata['endYear']);
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
					
					$runReport = true;
					$signature = $this->administration->getSignatureFragment($this->user['UserID']);
				}
				
				$data = array(
					'runReport' => $runReport,
					'html' => '',
					'providers' => '',
					'services' => '',
					'report_leads' => $report_leads,
					'report_lineChart' => $report_lineChart,
					'report_lineChart_script' => $report_lineChart_script,
					'report_pieChart' => $report_pieChart,
					'report_pieChart_script' => $report_pieChart_script,
					// Send date range data to report form.
					'dateRange' => array (
						'startMonth' => $rdata['startMonth'],
						'startYear' => $rdata['startYear'],
						'endMonth' => $rdata['endMonth'],
						'endYear' => $rdata['endYear']
					),
					'signatureFragment' => ($signature) ? $signature : ''
				);
			}

			switch ($page) {
				case 'reports': $this->LoadTemplate('forms/form_reportDpr',$data); break;
				case 'editReport': $this->LoadTemplate('forms/form_reportDpr',$data); break;
			}	
			
		}
	
	}