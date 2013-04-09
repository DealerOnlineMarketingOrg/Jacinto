<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//
	class Dpr extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('getdpr');
			$this->load->helper('charts_helper');
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