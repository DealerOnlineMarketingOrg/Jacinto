<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Rep extends DOM_Model {
		
		function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
			$this->load->helper('converter_helper');
		}

		// Returns the provider ID if exists, else FALSE.
		public function providerID($provider) {
			$sql = 'SELECT PROVIDER_ID as ID FROM DPRProviders WHERE PROVIDER_Name = "' . $provider . '"';
			$query = $this->db->query($sql);
			$result = $query->result();
			return ($query->num_rows) ? $result[0]->ID : FALSE;
		}
		
		public function getProviders($order_col) {
			$sql = 'SELECT PROVIDER_ID as ID,PROVIDER_Name as Name,PROVIDER_URL as URL FROM DPRProviders ORDER BY ' . $order_col . ' ASC;';
			return query_results($this,$sql);
		}
		
		// Adds a Provider to the DPR db and returns the new ID.
		public function addProvider($provider_data) {
			$data = array(
				'PROVIDER_Name' => $provider_data['provider'],
				'PROVIDER_URL' => $provider_data['providerURL']
			);
			// Insert data into table.
			$this->db->insert('DPRProviders',$data);
			// Get ID of new value added into table.
			return ($this->providerID($provider_data['provider']));
		}
		
		// Returns the service ID if exists, else FALSE.
		public function serviceID($service) {
			$sql = 'SELECT SERVICE_ID as ID FROM DPRReportServices WHERE SERVICE_Name = "' . $service . '"';
			$query = $this->db->query($sql);
			$result = $query->result();
			return ($query->num_rows) ? $result[0]->ID : FALSE;
		}
		
		// Get an array of all services.
		public function getServices($order_col) {
			$sql = 'SELECT SERVICE_ID as ID,SERVICE_Name as Name FROM DPRReportServices ORDER BY ' . $order_col . ' ASC;';
			return query_results($this,$sql);
		}
		
		// Adds a Agency to the DPR db and returns the new ID.
		public function addService($service_data) {
			$data = array(
				'SERVICE_Name' => $service_data['service']
			);
			// Insert data into table. If success..
			$this->db->insert('DPRReportServices',$data);
			// Get ID of new value added into table.
			return $this->serviceID($service_data['service']);
		}
		
		// Adds a lead total to the dpr report table.
		public function addLeadTotal($lead_data) {
			$data = array(
				'REPORT_Provider' => $lead_data['providerID'],
				'REPORT_Service'  => $lead_data['serviceID'],
				'REPORT_Date'     => $lead_data['date'],
				'REPORT_Value'    => $lead_data['total'],
				'CLIENT_ID'       => $lead_data['clientID'],
				);
			$this->db->insert('DPRReports', $data);
		}
		
		// Gets the year from a database date.
		function yearFromDBDate($date) {
			$std_date = DateTime::createFromFormat('Y-m-d', $date);
			return date("Y", $std_date->getTimestamp());
		}
		
		// Gets the month number (1-12) from a database date.		
		function monthFromDBDate($date) {
			$std_date = DateTime::createFromFormat('Y-m-d', $date);
			return date("n", $std_date->getTimestamp());
		}
		
		// Returns all providers in an array.
		public function getRepProviders($db_result) {
			$data = array();
			foreach ($db_result as $row) {
				if (!in_array($row->PName, $data)) {
					$data[] = $row->PName;
				}
			}
			return $data;
		}
		
		// Returns all years in an array.
		// array = { Year1, Year2, .. }
		public function getYears($db_result, $provider) {
			$data = array();
			foreach ($db_result as $row) {
				$year = $this->yearFromDBDate($row->Date);
				if ($row->PName == $provider && !in_array($year, $data)) {
					$data[] = $year;
				}
			}
			return $data;
		}
		
		// Returns all services for a particular year in an array.
		// array = { Service1, Type1, Service2, Type2, .. }
		public function getYearlyServices($db_result, $provider, $year) {
			$data = array();
			foreach ($db_result as $row) {
				if ($row->PName == $provider && $this->yearFromDBDate($row->Date) == $year && !in_array($row->SName, $data)) {
					$data[] = $row->SName;
					$data[] = $row->SType;
				}
			}
			return $data;
		}
		
		// Returns all data for a particular year and service.
		// array = { JanData, FebData, .., DecData }
		public function getYearlyData($db_result, $provider, $year, $service) {
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$data = array(); foreach($months as $month) $data[$month] = 0;
			foreach ($db_result as $row) {
				if ($row->PName == $provider && $this->yearFromDBDate($row->Date) == $year && $row->SName == $service) {
					$month = $months[$this->monthFromDBDate($row->Date)-1];
					$data[$month] = $row->Value;
				}
			}
			return $data;
		}
		
		// Returns lead totals for a particular year.
		// array = { JanTotal, FebTotal, .., DecTotal }
		// type = 1: visitors, 2: leads
		public function getTotalsFromYear($db_result, $provider, $year, $type) {
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$data = array(); foreach($months as $month) $data[$month] = 0;
			foreach ($db_result as $row) {
				if ($row->PName == $provider && $this->yearFromDBDate($row->Date) == $year && $row->SType == $type) {
					$month = $months[$this->monthFromDBDate($row->Date)-1];
					$data[$month] = $row->Value;
				}
			}
			return $data;
		}
		
		// $ytd_func can be 'total', 'average' or 'percentage'. Defaults to 'total'.
		public function GenerateYearRow(&$report_data, $monthly_data, $ytd_func) {
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			// Get monthly data.
			$ytd = 0;
			for ($m = 0; $m < 12; $m++) {
				$report_data[$months[$m]] = $monthly_data[$months[$m]];
				$ytd += $monthly_data[$months[$m]];
			}
			// Include YTD.
			switch ($ytd_func) {
				case 'total': $report_data['YTD'] = $ytd; break;
				case 'average': $report_data['YTD'] = round((float)$ytd / (float)12, 2); break;
				case 'percentage': $report_data['YTD'] = round((float)$ytd / (float)12 * 100, 2); break;
				default: $report_data['YTD'] = $ytd; break;
			}
		}
		
		public function StripZeros(&$report) {
			foreach ($report as &$row)
				foreach ($row as &$item)
					// If it equals 0, in type as well as value..
					if ($item === 0 || $item == '0%')
						$item = '';
		}
		
		// Adds an array of data to a spreadsheet row, starting at specified cell.
		public function AddRowData(&$worksheet, $data_array, $start_cell) {
			$col = substr($start_cell, 0, 1);
			$row = substr($start_cell, 1);
			foreach ($data_array as $data) {
				$worksheet->setCellValue($col.$row, $data);
				$col++;
			}
		}
		
		// Returns a 2-dimensional array that holds the spreadsheet's data.
		// Formulas fields will get calculated, and the calculated value returned.
		public function SpreadsheetToCalculatedArray(&$worksheet) {
			$highCol = $worksheet->getHighestColumn();
			$highRow = $worksheet->getHighestRow();
			
			$data_array = array();
			for ($col = 'A'; $col != $highCol; $col++) {
				$row_array = array();
				for ($row = 1; $row < $highRow; $row++) {
					$row_array[] = $worksheet->getCell($col.$row)->getCalculatedValue();
				}
				$data_array[] = $row_array;
			}
			
			return $data_array;
		}
		
		// Creates a spreadsheet that'll hold the data and do the calculations of the report.
		public function CreateSpreadsheet($query) {
			require_once 'domcms/libraries/PHPExcel.php';
			require_once 'domcms/libraries/PHPExcel/IOFactory.php';
			
			$query_result = $query->result();
			
			$objPHPExcel = CreateExcelWorkbook('report');
			$worksheet = CreateWorksheet($objPHPExcel);

			// Go through each row in the query and build the data array.
			// $data is an array of arrays (rows).
			$data_body = array ();
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			// Generate empty array.
			$empty_row = array();
				// The first element in each row array indicates the type of data the row represents.
				// This will be used for formatting.
				$blank_array['Type'] = '';
				$empty_row['Name'] = '';
				foreach($months as $month)
					$empty_row[$month] = '';
				$empty_row['YTD'] = '';
				
			if ($query->num_rows() > 0) {
				// Leave blank at first to force routine to initialize.
				$current = array (
					'provider' => '',
					'year' => '',
					'service' => '');
				
				$data_row = $empty_row;
				// Set true to close the current excel row.
				$closeRow = false;
				// Set true to close the current row as a header.
				$isHeaderRow = false;
				// States for the routine.
				// advance: moves to the next query row.
				// process: processes the current row.
				// closeRow: closes the current row and advances.
				// closeNoAdvance: closes the current row without further processing, but doesn't advance.
				//   Used when more processing is needed.
				// closeHeader: closes the current row without further processing, and advances.
				$states = array( 'advance' => 1, 'process' => 2, 'closeDataRow' => 3, 'closeNoAdvance' => 4, 'closeHeaderRow' => 5 );
				$state = $states->advance;
				$advance = false;
				$current_row = 1;
				foreach ($query_result as $row) {
					$state = $states->process;
					while ($state != $states->advance) {
						if ($current['provider'] != $row->PName) {
							// If we're processing a line, close out..
							if ($state == $states->process)
								$state = $states->closeNoAdvance;
							// ..else if we're done with the first close..
							else if ($state == $states) {
								// Add special provider header line.
								$data_row['Name'] = $row->PName;
								$state = $states->closeHeaderRow;
								// Update current provider.
								$current['provider'] = $row->PName;
							}
						}
						
						if ($current['year'] != $row->Year) {
							// Flag for row closure.
							$state = $states->closeDataRow;
							$current['year'] = $row->Year;
						}
						
						if ($current['service'] != $row->SName) {
							// Flag for row closure.
							$state = $states->closeDataRow;
							$current['service'] = $row->SName;
						}
						
						switch ($state) {
							case $states->closeDataRow:
								// Add YTD formula to row.
								$data_row['YTD'] = '=SUM(B' . $current_row . ':M' . $current_row .')';
								// Add row to body and go to next data_body row.
								$data_body[] = $data_row;
								$data_row = $empty_row;
								// Advance row pointer and continue processing.
								$current_row++;
								$state = $states->advance;
								break;
							case $states->closeNoAdvance:
								// Add row to body.
								$data_body[] = $data_row;
								$data_row = $empty_row;
								break;
							case $states->closeHeaderRow:
								// Add row to body and go to next data_body row.
								$data_body[] = $data_row;
								$data_row = $empty_row;
								// Advance row pointer and continue processing.
								$current_row++;
								$state = $states->advance;
								break;
							default:
								// Get this query row's monthly value.
								$data_row[$months[($row->Month)-1]] = $row->Value;
						}
					}
				}
			}

			// Create header and footer rows.
			$data_header = array ( array (
				'Lead Provider','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Total YTD' ) );
			$data_footer = array ( array (
				) );
			
			// Write data to worksheet.
			$coli = 'A';
			$rowi = 1;
			foreach ($data_header as $data) {
				$this->AddRowData($worksheet, $data, $coli.$rowi);
				$rowi++;
			}
			foreach ($data_body as $data) {
				$this->AddRowData($worksheet, $data, $coli.$rowi);
				$rowi++;
			}
			foreach ($data_footer as $data) {
				$this->AddRowData($worksheet, $data, $coli.$rowi);
				$rowi++;
			}
			//ProcessHTML($objPHPExcel, $dom);
			// set to first worksheet before close
			$objPHPExcel->setActiveSheetIndex(0);
			
			$test = $this->SpreadsheetToCalculatedArray($worksheet);
			$file_name = 'domcms/cache/dprTest_' . date('m-d-Y') . '.xlsx';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($file_name);
			print_object($data_body);
		}
		
		public function getDPRReportData($client_id, $beginning_year, $ending_year) {
			// The first element in each row array indicates the type of data the row represents.
			// This will be used for formatting.
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			// Generate blank array.
			$blank_array = array();
			$blank_array['Type'] = '';
			$blank_array['Name'] = '';
			foreach($months as $month) $blank_array[$month] = '';
			$blank_array['YTD'] = '';
			// Generate report with header.
			$report = array(
				array(
					'Type' => 'Header',
					'Name' => 'Lead Provider',
					'Jan'  => 'Jan',
					'Feb'  => 'Feb',
					'Mar'  => 'Mar',
					'Apr'  => 'Apr',
					'May'  => 'May',
					'Jun'  => 'Jun',
					'Jul'  => 'Jul',
					'Aug'  => 'Aug',
					'Sep'  => 'Sep',
					'Oct'  => 'Oct',
					'Nov'  => 'Nov',
					'Dec'  => 'Dec',
					'YTD'  => 'Total YTD'
				)
			);
			// Get initial date to start report with.
			$begin_date = (string)($beginning_year) . '-01-01';
			$end_date = (string)($ending_year) . '-12-31';
			// Pull data from last n years.
			$columns = array ('ProviderName', 'Date', 'ServiceType', 'ServiceName', 'Value');
			$qu_report = "SELECT p.PROVIDER_Name AS PName, YEAR(r.REPORT_Date) as Year, s.SERVICE_Name AS SName, s.SERVICE_Type AS SType, MONTH(r.REPORT_Date) as Month, r.REPORT_Value AS Value, r.REPORT_Date as Date " .
			             "FROM DPRReports AS r, DPRProviders AS p, DPRReportServices AS s " .
						 "WHERE r.CLIENT_ID = " . $client_id .
						 "  AND r.REPORT_Date >= '" . $begin_date . "' AND r.REPORT_Date <= '" . $end_date . "'" .
						 "  AND r.REPORT_Provider = p.PROVIDER_ID" .
						 "  AND r.REPORT_Service = s.SERVICE_ID" .
						 "  AND (s.SERVICE_Type = 1 OR s.SERVICE_Type = 2) " .
						 "ORDER BY p.PROVIDER_Name, Year, s.SERVICE_Type, s.SERVICE_Name ASC, Month";
			$query = $this->db->query($qu_report);
			$result = $query->result();
			$this->CreateSpreadsheet($query);
			
			$providers = $this->getRepProviders($result);
			$last = end($providers);
			foreach ($providers as $provider) {
				// Add provider name to report.
				$report_data = $blank_array;
				$report_data['Type'] = 'Name';
				$report_data['Name'] = $provider;
				$report[] = $report_data;
				// Go through each year for the provider.
				$years = $this->getYears($result, $provider);
				foreach ($years as $year) {
					$report_data = $blank_array;
					if ($year == $ending_year)
						$report_data['Type'] = 'ThisYear';
					else
						$report_data['Type'] = 'Year';
					$services = $this->getYearlyServices($result, $provider, $year);
					for ($s = 0; $s < count($services); $s += 2) {
						if ($report_data['Type'] != 'ThisYear' && $services[$s+1] == 2) {
							// If Year is other then Ending Year, do totals on leads.
							$data = getLeadTotalsFromYear($result, $provider, $year);
							// Get year/service label.
							$report_data['Name'] = $year . ' Total Leads';
						} else {
							// else just get the yearly data.
							$data = $this->getYearlyData($result, $provider, $year, $services[$s]);
							// Get year/service label.
							$report_data['Name'] = $year . ' ' . $services[$s];
						}
						// Add year/service data to the report.
						$this->GenerateYearRow($report_data, $data, 'total');
						$report[] = $report_data;
					}
				}
				// Seperator line goes here.
				$report_data = $blank_array;
				$report_data['Type'] = 'Seperator';
				$report[] = $report_data;
				
				// Totals lines.
				$report_data = $blank_array;
				$report_data['Type'] = 'Total';
				$report_data['Name'] = $ending_year . ' Total';
				$lead_data = $this->getTotalsFromYear($result, $provider, $ending_year, 2);
				$this->GenerateYearRow($report_data, $lead_data, 'total');
				$report[] = $report_data;

				$report_data = $blank_array;
				$report_data['Type'] = 'Total';
				$report_data['Name'] = $ending_year . ' Conversion Ratio';
				$visitor_data = $this->getTotalsFromYear($result, $provider, $ending_year, 1);
				for ($d = 0; $d < 12; $d++)
					if ($visitor_data[$months[$d]] == 0)
						$conv_data[$months[$d]] = '';
					else
						$conv_data[$months[$d]] = round($lead_data[$months[$d]] / $visitor_data[$months[$d]] * 100, 2);
				$this->GenerateYearRow($report_data, $conv_data, 'average');
				// Convert to percentage.
				for ($m = 0; $m < count($months); $m++)
					if ($report_data[$months[$m]] != '')
						$report_data[$months[$m]] .= '%';
				$report_data['YTD'] .= '%';
				$report[] = $report_data;
				
				$report_data = $blank_array;
				$report_data['Type'] = 'Total';
				$report_data['Name'] = $ending_year . ' Cost';
				$report[] = $report_data;
				
				$report_data = $blank_array;
				$report_data['Type'] = 'Total';
				$report_data['Name'] = $ending_year . ' Cost per lead';
				$report[] = $report_data;
				
				if ($provider != $last) {
					$report_data = $blank_array;
					$report_data['Type'] = 'Blank';
					$report_data['Name'] = '&nbsp;';
					$report[] = $report_data;
				}
			}
			
			$this->StripZeros($report);
			return $report;
		}
		
	}
