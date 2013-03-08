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
		
		// Adds an array of data to a spreadsheet row, starting at specified cell.
		public function AddRowData(&$worksheet, $data_array, $start_cell) {
			$col = substr($start_cell, 0, 1);
			$row = substr($start_cell, 1);
			foreach ($data_array as $data) {
				echo 'addrowdata:' . $col.$row . ',' .$data .'|';
				$worksheet->setCellValue($col.$row, $data);
				echo 'done|';
				$col++;
			}
		}
		
		// Removes any zeros from the report.
		public function StripZeros(&$report) {
			foreach ($report as &$row)
				foreach ($row as &$item)
					// If it equals 0, in type as well as value..
					if ($item === 0 || $item == '0%')
						$item = '';
		}
		
		// Returns a 2-dimensional array that holds the spreadsheet's data.
		// Formulas fields will get calculated, and the calculated value returned.
		public function SpreadsheetToCalculatedArray(&$worksheet) {
			$highCol = $worksheet->getHighestColumn();
			$highRow = $worksheet->getHighestRow();
			
			$data_array = array();
			// This lets us search to the last column.
			// Otherwise, != $highCol in the for would
			//   only go to $highCol - 1.
			$highCol++;
			for ($row = 1; $row <= $highRow; $row++) {
				$row_array = array();
				for ($col = 'A'; $col != $highCol; $col++) {
					$row_array[] = $worksheet->getCell($col.$row)->getCalculatedValue();
				}
				$data_array[] = $row_array;
			}
			
			return $data_array;
		}
		
		// Creates a PHPExcel worksheet that'll hold the data and do the calculations of the report.
		// Headers and footers are 2D arrays that hold 1 or more headers and footers.
		// Should be ran for each individual report on a page.
		public function CreateWorksheet($query, $report_id, $ending_year, $headers, $footers) {
			require_once 'domcms/libraries/PHPExcel.php';
			require_once 'domcms/libraries/PHPExcel/IOFactory.php';
			
			$query_result = $query->result();
			
			$objPHPExcel = CreateExcelWorkbook('report');
			$worksheet = CreateWorksheet($objPHPExcel);

			// Go through each row in the query and build the data array.
			// $data is an array of arrays (rows).
			$data_body = array ();
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$month_columns = array('Jan'=>'C','Feb'=>'D','Mar'=>'E','Apr'=>'F','May'=>'G','Jun'=>'H','Jul'=>'I','Aug'=>'J','Sep'=>'K','Oct'=>'L','Nov'=>'M','Dec'=>'N');
			// Generate empty array.
			// Each item is an array consisting of data, what report the data applies to, and its visual style.
			$empty_item = array('report' => $report_id, 'style' => '', 'data' => '');
			$empty_row = array();
				$empty_row['Name'] = $empty_item;
				foreach($months as $month)
					$empty_row[$month] = $empty_item;
				$empty_row['YTD'] = $empty_item;
				
			$row_count = $query->num_rows();
			if ($row_count > 0) {
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
				// Adjust for header rows. Affects formulas.
				$current_row = 1 + count($headers);
				$first_row = true;
				$write_row = false;
				$write_provider = false;
				for ($qi = 0; $qi <= $row_count; $qi++) {
					if ($qi == $row_count) {
						// Past the end of the query. Close out.
						$write_row = true;
						$write_provider = true;
					} else {
						// Still processing query rows.
						$row = $query_result[$qi];
					
						// Provider changed.
						if ($current['provider'] != $row->PName) {
							$write_row = true;
							$write_provider = true;
							// Set new provider.
							$current['provider'] = $row->PName;
						}
						
						// Year changed.
						if ($current['year'] != $row->Year) {
							$write_row = true;
							// Set new year.
							$current['year'] = $row->Year;
						}
						
						// Service changed.
						if ($current['service'] != $row->SName) {
							$write_row = true;
							// Set new service.
							$current['service'] = $row->SName;
						}
					}
					
					if ($write_row) {
						if (!$first_row) {
							// Write row data to body and advance.
							// Add YTD formula to row, and style it the same as the Name.
							$data_row['YTD']['style'] = $data_row['Name']['style'];
							$data_row['YTD']['data'] = '=SUM(' . $month_columns['Jan'] . $current_row . ':' . $month_columns['Dec'] . $current_row .')';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							// Name new row.
							$data_row['Name']['style'] = ($current['year'] == $ending_year) ? 'ThisYear' : 'Year';
							$data_row['Name']['data'] = $current['year'] . ' ' . $current['service'];
						}
						
						$write_row = false;
					}
					
					if ($write_provider) {
						if (!$first_row) {
							// This will be the horizontal rule seperator. Use a blank line for now.
							$data_row['Name']['style'] = 'Seperator';
							$data_row['Name']['data'] = ' ';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							
							// Add end-of-provider totals lines.
							$data_row['Name']['style'] = 'Total';
							$data_row['Name'] = $current['year'] . ' Total';
							foreach ($months as $month) {
								$data_row[$month]['format'] = 'Total';
								$data_row[$month]['data'] = '=SUM(' . $month_columns[$month] . '2:' . $month_columns[$month] . ($current_row-1) . ')';
							}
							$data_row['YTD']['style'] = 'Total';
							$data_row['YTD']['data'] = '=SUM(' . $month_columns['Jan'] . $current_row . ':' . $month_columns['Dec'] . $current_row .')';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							
							$data_row['Name']['style'] = 'Total';
							$data_row['Name']['data'] = $current['year'] . ' Conversion Ratio';
							$data_row['YTD']['style'] = 'Total';
							$data_row['YTD']['data'] = '=SUM(' . $month_columns['Jan'] . $current_row . ':' . $month_columns['Dec'] . $current_row .')';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							
							$data_row['Name']['style'] = 'Total';
							$data_row['Name']['data'] = $current['year'] . ' Cost';
							$data_row['YTD']['style'] = 'Total';
							$data_row['YTD']['data'] = '=SUM(' . $month_columns['Jan'] . $current_row . ':' . $month_columns['Dec'] . $current_row .')';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							
							$data_row['Name']['style'] = 'Total';
							$data_row['Name']['data'] = $current['year'] . ' Cost per lead';
							$data_row['YTD']['style'] = 'Total';
							$data_row['YTD']['data'] = '=SUM(' . $month_columns['Jan'] . $current_row . ':' . $month_columns['Dec'] . $current_row .')';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							
							// Actual blank line.
							$data_row['Name']['style'] = 'Blank';
							$data_row['Name']['data'] = '&nbsp;';
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
						}
						
						if ($qi != $row_count) {
							// Add special provider header line.
							$data_row['Name']['style'] = 'Name';
							$data_row['Name']['data'] = $row->PName;
							$data_body[] = $data_row;
							$current_row++;
							$data_row = $empty_row;
							// Name new row.
							$data_row['Name']['style'] = ($current['year'] == $ending_year) ? 'ThisYear' : 'Year';
							$data_row['Name']['data'] = $current['year'] . ' ' . $current['service'];
						}
						
						$write_provider = false;
					}
					
					if ($qi != $row_count) {
						// Set this query row's style to the same as it's Name.
						$data_row[$months[($row->Month)-1]]['style'] = $data_row['Name']['style'];
						// Get this query row's monthly value.
						$data_row[$months[($row->Month)-1]]['data'] = $row->Value;
					}

					if ($first_row)
						$first_row = false;
				}
			}
			
			// Write data to worksheet.
			$coli = 'A';
			$rowi = 1;
			foreach ($headers as $data) {
				$this->AddRowData($worksheet, $data, $coli.$rowi);
				$rowi++;
			}
			foreach ($data_body as $data) {
				$this->AddRowData($worksheet, $data, $coli.$rowi);
				$rowi++;
			}
			foreach ($footers as $data) {
				$this->AddRowData($worksheet, $data, $coli.$rowi);
				$rowi++;
			}
			
			return $worksheet;
		}
		
		public function getDPRReportData($client_id, $beginning_year, $ending_year) {
			// The first element in each row array indicates the type of data the row represents.
			// This will be used for formatting.
			
			$headers = array(
				array(
					'Format' => 'Header',
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
			$footers = array(array());
			
			// Get initial date to start report with.
			$begin_date = (string)($beginning_year) . '-01-01';
			$end_date = (string)($ending_year) . '-12-31';
			// Pull data from last n years.
			$qu_report = "SELECT p.PROVIDER_Name AS PName, YEAR(r.REPORT_Date) as Year, s.SERVICE_Name AS SName, s.SERVICE_Type AS SType, MONTH(r.REPORT_Date) as Month, r.REPORT_Value AS Value, r.REPORT_Date as Date " .
			             "FROM DPRReports AS r, DPRProviders AS p, DPRReportServices AS s " .
						 "WHERE r.CLIENT_ID = " . $client_id .
						 "  AND r.REPORT_Date >= '" . $begin_date . "' AND r.REPORT_Date <= '" . $end_date . "'" .
						 "  AND r.REPORT_Provider = p.PROVIDER_ID" .
						 "  AND r.REPORT_Service = s.SERVICE_ID" .
						 "  AND (s.SERVICE_Type = 1 OR s.SERVICE_Type = 2) " .
						 "ORDER BY p.PROVIDER_Name, Year, s.SERVICE_Type, s.SERVICE_Name ASC, Month";
			$query = $this->db->query($qu_report);
			
			// Generate report from query data.
			$worksheet = $this->CreateWorksheet($query, 'leads', $ending_year, $headers, $footers);
			$report = $this->SpreadsheetToCalculatedArray($worksheet);
			$this->StripZeros($report);
			print_object($report);
			return $report;
		}
		
	}
