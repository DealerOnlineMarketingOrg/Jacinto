<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Rep extends DOM_Model {
		
		function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
			$this->load->helper('converter_helper');
			$this->load->helper('phpext_helper');
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
		
		//
		// Start of report generation functions
		//
		
		// Returns a list of functions from format:functions.
		private function getFunctions($functionList) {
			return explode(';', $functionList);
		}
		
		// Returns a list of arguments from a function.
		// The first argument ([0]) is the name of the function.
		private function getArguments($function) {
			$start = strpos($function, '(');
			$end = strpos($function, ')');
			$name = substr($function, 0, $start);
			$args = explode(',', substr($function, $start+1, $start-$end+1));
			$all_args[] = $name;
			$all_args = array_merge_($all_args, $args);
			return $all_args;
		}
		
		// Returns true if the function is in the function list.
		public function hasFunction($functionList, $function) {
			$functions = $this->getFunctions($functionList);
			foreach ($functions as $function) {
				$args = $this->getArguments($function);
				if ($args[0] == $function)
					return true;
			}
			
			return false;
		}
		
		// Processes the list of functions against data.
		// formatList syntax: functionName1(argument1,..); functionName2(..
		// Returns the data formated with the item's functions.\
		// Functions are processed in order in formatList. All functions are
		//   applied. Be careful which functions, in which order, are set.
		public function formatData($data, $formatList) {
			$fData = $data;
			$functions = $this->getFunctions($formatList);
			foreach ($functions as $function) {
				$args = $this->getArguments($function);
				switch ($args[0]) {
					// prepend(string)
					case 'prepend':
						$fData = $args[1] . $fData;
						break;
					// append(string)
					case 'append':
						$fData = $fData . $args[1];
						break;
					// round(digits)
					case 'round':
						if (is_numeric($fData))
							$fData = round($fData, $args[1]);
						break;
				}
			}
			return $fData;
		}
		
		// Creates a PHPExcel worksheet that'll hold do the calculations on the report.
		// Returns the report with the calculations completed.
		// Any current data in the formula fields will be updated.
		// Returns the calculated report.
		public function CalculateData($report) {
			// Create PHPExcel worksheet for doing in-report calculations.
			// Report formulas should be in the same format as Excel.
			require_once 'domcms/libraries/PHPExcel.php';
			require_once 'domcms/libraries/PHPExcel/IOFactory.php';
			
			$objPHPExcel = CreateExcelWorkbook('report');
			$worksheet = CreateWorksheet($objPHPExcel);
			
			// Write data to worksheet.
			$row = 1;
			foreach ($report as $report_row) {
				$col = 'A';
				foreach ($report_row as $item) {
					if ($item['formula'] != '')
						$worksheet->setCellValue($col.$row, $item['formula']);
					else
						$worksheet->setCellValue($col.$row, $item['data']);
					$col++;
				}
				$row++;
			}
			
			$file_name = 'domcms/cache/dprTest_' . date('m-d-Y') . '.xlsx';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($file_name);
			//print_object($report);
			
			// Retrieve data back from worksheet, in calculated form,
			//  and merge data back into report. Data will have the
			//  same structure 
			$highCol = $worksheet->getHighestColumn();
			$highRow = $worksheet->getHighestRow();
			// This lets us search to the last column.
			// Otherwise, != $highCol in the for would
			//   only go to $highCol - 1.
			$highCol++;
			$row = 1;
			foreach ($report as &$report_row) {
				$col = 'A';
				foreach ($report_row as &$item) {
					$item['data'] = $worksheet->getCell($col.$row)->getCalculatedValue();
					$col++;
				}
				$row++;
			}
			
			return $report;
		}
		
		// Returns a report from the query.
		// Data is stored in an array with the following:
		//  report: the id of the report the data belongs to.
		//  style: the visual style of the data.
		//  data: the actual data.
		//  formula: the formula for the data, if any, else an empty string.
		public function generateDPRReport($query, $report_id, $ending_year) {
			$query_result = $query->result();

			// Go through each row in the query and build the data array.
			// $data is an array of arrays (rows).
			$data_body = array ();
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$month_columns = array('Jan'=>'B','Feb'=>'C','Mar'=>'D','Apr'=>'E','May'=>'F','Jun'=>'G','Jul'=>'H','Aug'=>'I','Sep'=>'J','Oct'=>'K','Nov'=>'L','Dec'=>'M');
			// Generate empty array.
			// Each item is an array consisting of data, what report the data applies to, and its format.
			// The format is an array of the following:
			//   class: the class name of the format.
			//   style: the ccs style for the visual format of the data.
			//   dataType: the type of data.
			$empty_item = array('reportID' => $report_id, 'format' => array('class'=>'', 'style'=>'', 'functions'=>''), 'data' => '', 'formula' => '');
			// Empty row contains the structure of the report and the data items for each.
			$empty_row = array();
				$empty_row['Name'] = $empty_item;
				foreach($months as $month)
					$empty_row[$month] = $empty_item;
				$empty_row['YTD'] = $empty_item;
				
			// Headers.
			$header_row = array($empty_row);
			$header_descs = array(
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
			);
			foreach ($header_descs as $key => $desc) {
				$header_row[0][$key]['format']['class'] = 'Header';
				$header_row[0][$key]['data'] = $desc;
			}
			$headers = $header_row;
			
			$data_body = array();
			$row_count = $query->num_rows();
			$calc_block['YTD']['const'] = true;
			$calc_block['YTD']['first'] = $month_columns['Jan'];
			$calc_block['YTD']['last'] = $month_columns['Dec'];
			$calc_block['visitors']['const'] = false;
			$calc_block['visitors']['first'] = 0;
			$calc_block['visitors']['last'] = 0;
			$calc_block['leads']['const'] = false;
			$calc_block['leads']['first'] = 0;
			$calc_block['leads']['last'] = 0;
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
							$data_row['YTD']['format']['class'] = $data_row['Name']['format']['class'];
							$data_row['YTD']['formula'] = '=SUM(' . $calc_block['YTD']['first'].$current_row . ':' . $calc_block['YTD']['last'].$current_row .')';
							$data_body[] = $data_row;
							// Visitors block.
							if ($calc_block['visitors']['first'] == 0 && $data_row['Name']['format']['class'] == 'ThisYear' && $lastRow->SType == 1) {
								$calc_block['visitors']['first'] = $current_row;
							}
							if ($calc_block['visitors']['last'] == 0 && $data_row['Name']['format']['class'] == 'ThisYear' && $lastRow->SType != 1)
								$calc_block['visitors']['last'] = $current_row-1;
							// Leads block.
							if ($calc_block['leads']['first'] == 0 && $data_row['Name']['format']['class'] == 'ThisYear' && $lastRow->SType == 2) {
								$calc_block['leads']['first'] = $current_row;
							}
							
							$current_row++;
							$data_row = $empty_row;
							// Name new row.
							$data_row['Name']['format']['class'] = ($current['year'] == $ending_year) ? 'ThisYear' : 'Year';
							$data_row['Name']['data'] = $current['year'] . ' ' . $current['service'];
						}
						
						$write_row = false;
					}
					
					if ($write_provider) {
						// Bottom calculation block.
						if (!$first_row) {
							// Set calculation block bottom.
							$calc_block['leads']['last'] = $current_row-1;
							
							// This will be the horizontal rule seperator. Use a blank line for now.
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Seperator';
							$data_row['Name']['data'] = ' ';
							$data_body[] = $data_row;
							
							// Leads totals
							$current_row++;
							$data_row = $empty_row;
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Total';
							$data_row['Name']['data'] = $ending_year . ' Total';
							foreach ($months as $month) {
								$data_row[$month]['formula'] = '=SUM(' . $month_columns[$month].$calc_block['leads']['first'] .':' . $month_columns[$month].$calc_block['leads']['last'] . ')';
							}
							$data_row['YTD']['formula'] = '=SUM(' . $calc_block['YTD']['first'].$current_row . ':' . $calc_block['YTD']['last'].$current_row .')';
							$data_body[] = $data_row;
							
							// Conversion ratios
							$current_row++;
							$data_row = $empty_row;
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Total';
							$data_row['Name']['data'] = $ending_year . ' Conversion Ratio';
							foreach ($months as $month) {
								// If we don't have any visitors, we shouldn't calculate this.
								if ($calc_block['visitors']['first'] > 0) {
									$data_row[$month]['formula'] = '=' . $month_columns[$month].($current_row-1) . '/SUM(' . $month_columns[$month].$calc_block['visitors']['first'] . ':' . $month_columns[$month].$calc_block['visitors']['last'] . ') * 100';
									$data_row[$month]['format']['functions'] = 'round(2);append(%)';
								}
							}
							$data_row['YTD']['formula'] = '=AVERAGE(' . $calc_block['YTD']['first'].$current_row . ':' . $calc_block['YTD']['last'].$current_row .')';
							$data_row['YTD']['format']['functions'] = 'round(2);append(%)';
							$data_body[] = $data_row;
							
							// Costs.
							$current_row++;
							$data_row = $empty_row;
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Total';
							$data_row['Name']['data'] = $ending_year . ' Cost';
							$data_row['YTD']['formula'] = '=SUM(' . $calc_block['YTD']['first'].$current_row . ':' . $calc_block['YTD']['last'].$current_row .')';
							$data_body[] = $data_row;
							
							// Costs per lead.
							$current_row++;
							$data_row = $empty_row;
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Total';
							$data_row['Name']['data'] = $ending_year . ' Cost per lead';
							$data_row['YTD']['formula'] = '=SUM(' . $calc_block['YTD']['first'].$current_row . ':' . $calc_block['YTD']['last'].$current_row .')';
							$data_body[] = $data_row;
							
							// Blank line.
							$current_row++;
							$data_row = $empty_row;
							// Actual blank line.
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Blank';
							$data_row['Name']['data'] = '&nbsp;';
							$data_body[] = $data_row;
							
							// Clear calc blocks.
							foreach ($calc_block as &$calc_type)
								if (!$calc_type['const'])
									foreach ($calc_type as $key => &$item)
										if ($key != 'const')
											$item = 0;
							
							// Go to next row.
							$current_row++;
							$data_row = $empty_row;
						}
						
						// Provider header.
						if ($qi != $row_count) {
							// Add special provider header line.
							foreach ($data_row as &$item)
								$item['format']['class'] = 'Name';
							$data_row['Name']['data'] = $row->PName;
							$data_body[] = $data_row;
							$current_row++;
							
							$data_row = $empty_row;
							// Name new row.
							$data_row['Name']['format']['class'] = ($current['year'] == $ending_year) ? 'ThisYear' : 'Year';
							$data_row['Name']['data'] = $current['year'] . ' ' . $current['service'];
						}
						
						$write_provider = false;
					}
					
					if ($qi != $row_count) {
						// Set this query row's style to the same as it's Name.
						$data_row[$months[($row->Month)-1]]['format']['class'] = $data_row['Name']['format']['class'];
						// Get this query row's monthly value.
						$lastRow = $row;
						$data_row[$months[($row->Month)-1]]['data'] = $row->Value;
					}

					if ($first_row)
						$first_row = false;
				}
			}
			
			// Footers.
			$footers = array();
			
			// Build report.
			$report = array();
			$report = array_merge_($report, $headers);
			$report = array_merge_($report, $data_body);
			$report = array_merge_($report, $footers);
			
			// Calculate report.
			$report = $this->CalculateData($report);
			
			return $report;
		}
		
		public function getDPRReport($client_id, $beginning_year, $ending_year) {
			// The first element in each row array indicates the type of data the row represents.
			// This will be used for formatting.
			
			$report_id = 'leads';
			
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
			$report = $this->generateDPRReport($query, $report_id, $ending_year);
			return $report;
		}
		
	}
