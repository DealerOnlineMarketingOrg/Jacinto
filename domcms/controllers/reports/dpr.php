<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dpr extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();	
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('getdpr');
		}
		
		public function Index() {
			$this->Load('add');
		}
		
		public function reports() {
			$this->Load('reports');
		}
		
		public function Load($page, $err = FALSE) {
			// Processing for dpr entry page.
			if ($page == 'add') {
				$prov_list = $this->getdpr->get('Provider');
				$prov_options = $this->getdpr->output_as_options($prov_list);
				$agency_list = $this->getdpr->get('Agency');
				$agency_options = $this->getdpr->output_as_options($agency_list);
				
				$data = array(
					'html' => '',
					// Success level: -1 for error, 0 for none, 1 for success.
					'err_level' => ($err) ? $err['level'] : 0,
					'err_msg' => ($err) ? $err['msg'] : '',
					'providers' => $prov_options,
					'agencies' => $agency_options
				);
			}
			// Processing for dpr report page.
			if ($page == 'reports') {
				$report_element_i = 1;
				$prov_list = '';
				$report_data = $this->rep->getDPRReportData(1, 2010, 2012);
				$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$prov_list .= "<div id='reportBlock'>";
				$id = "reportID_" . $report_element_i; $report_element_i++;
				$prov_list .= "<table id='" . $id . "' border='0' width='100%' style='color:black'>";
				// Report element counter. Gives each element in the report a unique id.
				foreach ($report_data as $data_row) {
					$style = ''; $ytd_style = '';
					switch ($data_row['Type']) {
						case 'Header': $style = 'font-weight:bold'; $ytd_style = $style; break;
						case 'Name': $style = 'font-weight:bold; font-size:150%'; break;
						case 'Year': $style = 'background-color:yellow'; $ytd_style = $style; break;
						case 'ThisYear': $ytd_style = 'font-weight:bold'; break;
						case 'Total': $style = 'font-weight:bold'; $ytd_style = $style; break;
						case 'Seperator': $style = 'height:0px; border-bottom:solid 1px black'; $ytd_style = $style; break;
					}
					$id = "reportID_" . $report_element_i; $report_element_i++;
					$prov_list .= "<tr id='" . $id . "'>";
					// The name (description) of the row.
					$id = "reportID_" . $report_element_i; $report_element_i++;
					$prov_list .= "<td id='" . $id . "' style='" . $style . "'>" . $data_row['Name'] . "</td>";
					// Each month in the row.
					for ($m = 0; $m < count($months); $m++) {
						$id = "reportID_" . $report_element_i; $report_element_i++;
						$prov_list .= "<td id='" . $id . "' style='" . $style . "'>" . $data_row[$months[$m]] . "</td>";
					}
					// The YTD field.
					$id = "reportID_" . $report_element_i; $report_element_i++;
					$prov_list .= "<td id='" . $id . "' style='" . $ytd_style . "'>" . $data_row['YTD'] . "</td>";
					$id = "reportID_" . $report_element_i; $report_element_i++;
					$prov_list .= "</tr>";
				}
				$prov_list .= "</table>";
				$prov_list .= "</div>";
				
				$data = array(
					'html' => '',
					// Success level: -1 for error, 0 for none, 1 for success.
					'err_level' => ($err) ? $err['level'] : 0,
					'err_msg' => ($err) ? $err['msg'] : '',
					'providers' => $prov_list
				);
			}

			switch ($page) {
				case 'add':		$this->LoadTemplate('forms/form_addDpr',$data); break;
				case 'reports': $this->LoadTemplate('forms/form_reportDpr',$data); break;
			}	
		}
		
		public function Success() {
			$err = array(
				'level' => 1,
				'msg' => 'Data saved!'
				);
			$this->Load('add', $err);
		}
		
		public function Failure() {
			$err = array(
				'level' => -1,
				'msg' => 'Something went wrong!'
				);
			$this->Load('add', $err);
		}
	
	}