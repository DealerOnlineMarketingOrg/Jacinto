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
		
		// Creates an HTML table containing the report specified by $report_id.
		// $report_element_start is where the id numbering (int) should start for this report.
		// $report_element_start will return with the next available id.
		// Returns a string containing the table code.
		private function generateTableReport($report, $report_id, &$report_element_start) {
			$report_element_i = $report_element_start;
			$report_html = '';
			$style = '';
			$id = "reportID_" . $report_element_i; $report_element_i++;
			$report_html .= "<table id='" . $id . "' border='0' width='100%' style='color:black'>";
			// Report element counter. Gives each element in the report a unique id.
			foreach ($report as $report_row) {
				$first = reset($report_row);
				if ($first['reportID'] == $report_id) {
					// Create row.
					$id = "reportID_" . $report_element_i; $report_element_i++;
					$report_html .= "<tr id='" . $id . "'>";
					foreach ($report_row as $item) {
						$style = '';
						switch ($item['format']['class']) {
							case 'Header': $style = 'font-weight:bold'; break;
							case 'Name': $style = 'font-weight:bold; font-size:150%'; break;
							case 'Year': $style = 'background-color:yellow'; break;
							case 'ThisYear': $style = ''; break;
							case 'Total': $style = 'font-weight:bold'; break;
							case 'Seperator': $style = 'height:0px; border-bottom:solid 1px black'; break;
						}
						$format = '';
						if ($item['data'] == '' || $item['data'] === 0 || $item['data'] == '#DIV/0!')
							$data = '';
						else
							$data = $this->rep->formatData($item['data'], $item['format']['functions']);
						// Create item on row and set style.
						$id = "reportID_" . $report_element_i; $report_element_i++;
						// Convert data to floating-point string to force output of needed precision.
						if (substr($data, strlen($data), 1) == '%')
							$data = numberToString($data, 2);
						else
							$data = numberToString($data, 2, TRUE);
						$report_html .= "<td id='" . $id . "' style='" . $style . "'>" . $data . "</td>";
					}
				}
				$report_html .= "</tr>";
			}
			$report_html .= "</table>";
			
			$report_element_start = $report_element_i + 1;
			return $report_html;
		}
		
		public function Load($page, $err = FALSE) {
			
			// Processing for dpr entry page.
			if ($page == 'add') {
				$report_data = $this->getdpr->get('Provider');
				$prov_options = $this->getdpr->output_as_options($report_data);
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
				$report = $this->rep->getDPRReport(1, 2010, 2012);
				// Wrap our table report in a div for logical and report conversion seperation.
				$reports['leads'] = "<div id='reportID_1'>";
				$report_element_start = 2;
				$report_html = $this->generateTableReport($report, 'leads', $report_element_start);
				// Wrap our table report in a div for logical and report conversion seperation.
				$reports['leads'] .= $report_html . "</div>";
				
				$data = array(
					'html' => '',
					// Success level: -1 for error, 0 for none, 1 for success.
					'err_level' => ($err) ? $err['level'] : 0,
					'err_msg' => ($err) ? $err['msg'] : '',
					'report_leads' => $reports['leads']
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