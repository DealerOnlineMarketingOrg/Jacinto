<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// All functions in this file:
	// http://phpexcel.codeplex.com/discussions/275807
	// Post index: Nov 8, 2011 at 11:39 AM
	// Author: mamuscia
	// Modified: Phillip Kazda [2/27/13] for Dealer Online Marketing use.
	
	/*	$file_name : The path and name of the excel file being created.
		$isExcel2007 : Set True if an Excel 2007 file is required (.xlsx).
	                   False if Excel 5 (.xls). Defaults to Excel 2007.
	*/
	function CreateExcel($file_name, &$objPHPExcel, $isExcel2007 = TRUE) {
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		
		if ($isExcel2007) {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		} else
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($file_name);
	}
	
	/*	$file_name : The path and name of the PDF file being created.
	*/
	function CreatePDF($file_name, $objPHPExcel) {
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';

		$objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->writeAllSheets();
		$objWriter->save($file_name);
	}
	
	// Sets the style markup on the specified cell.
	function SetStyleMarkup(&$worksheet, $col, $row, $nodeName, $attrName, $attrValue) {
		// Get style markups.
		$styleArray = '';
		switch ($nodeName) {
			case 'table':
				switch ($attrName) {
					case 'background-color':
					case 'height':
					case 'weight':
					case 'font-family':
					case 'font-size':
					case 'font-weight':
				}
				break;
			case 'col':
			case 'colgroup':
			case 'caption':
			case 'thead':
			case 'tbody':
			case 'tfoot':
				// Table elements not yet implemented.
				break;
			case 'tr':
				switch ($attrName) {
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
			case 'th':
				switch ($attrName) {
					case 'colspan':
						break;
					case 'rowspan':
						break;
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
			case 'td':
				switch ($attrName) {
					case 'colspan':
						break;
					case 'rowspan':
						break;
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
		}
		
		// Set style markup.
		$worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($styleArray);
	}
	
	// Sets the markup on the specified cell.
	// 
	function SetMarkup(&$worksheet, $col, $row, $nodeName, $attrName, $attrValue) {
		// Get style markups.
		$styleArray = '';
		switch ($nodeName) {
			case 'table':
				switch ($attrName) {
					case 'defaults':
						SetMarkup($worksheet, $col, $row, $nodeName, 'border', 'none');
						break;
					case 'border':
						$styleArray = array('borders' => array('outline' => array(
							'style' => PHPExcel_Style_Border::BORDER_NONE)));
						break;
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
			case 'col':
			case 'colgroup':
			case 'caption':
			case 'thead':
			case 'tbody':
			case 'tfoot':
				// Table elements not yet implemented.
				break;
			case 'tr':
				switch ($attrName) {
					case 'defaults':
						break;
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
			case 'th':
				switch ($attrName) {
					case 'defaults':
						break;
					case 'colspan':
						break;
					case 'rowspan':
						break;
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
			case 'td':
				switch ($attrName) {
					case 'defaults':
						break;
					case 'colspan':
						break;
					case 'rowspan':
						break;
					case 'dir':
						break;
					case 'style':
						break;
				}
				break;
		}
		
		// Set style markup.
		$worksheet->getStyleByColumnAndRow($col, $row)->applyFromArray($styleArray);
	}
	
	// Helper function that gets the value of a style attribute.
	function getStyleAttr($style, $attr) {
		preg_match('/(^|;) *' . $attr . ':([^;]+) *(;|$)/i', $style, $matches);
		if (count($matches) > 1) {
			return $matches[2];
		} else {
			return '';
		}
	}
	
	// Returns an array of each attribute in style, with the
	//  dom-standard nodeName and nodeValue fields.
	// Extra spaces are stripped.
	function GetStyleAttributes($style) {
		preg_match('/^( *([^ ]+) *: *(.+) *(;|$))+ *$/i', $style, $matches);
		$attr = array( 'nodeName' => '', 'nodeValue' => '' );
		$attrs = array();
		$count = 0;
		while ($count < count($matches)) {
			$attr['nodeName'] = $matches[$count+2];
			$attr['nodeValue'] = $matches[$count+3];
			$attrs[] = (object)$attr;
			$count += 5;
		}
		
		return $attrs;
	}
	
	// Creates a new worksheet for a table.
	function CreateWorksheet(&$objPHPExcel) {
		// Sheets are 0-indexed.
		$sheet = $objPHPExcel->getSheetCount() - 1;
		if ($sheet > 0)
			// Sheet 0 is created by default.
			$objPHPExcel->createSheet($sheet);
		$suf = $sheet + 1;
		$wksheetname = 'Worksheet' . $suf;
		$objPHPExcel->setActiveSheetIndex($sheet);
		// Worksheet tab name.
		$objPHPExcel->getActiveSheet()->setTitle($wksheetname);
		$worksheet = $objPHPExcel->getActiveSheet();

		return $worksheet;
	}
	
	// isMarkup = true, only cell/data markup is applied.
	// isMarkup = false, data is set.
	function ProcessTable(&$objPHPExcel, &$worksheet, &$col, &$row, $node, $isMarkup) {
		// Process the different table tags.
		switch (strtolower($node->nodeName)) {
			case 'table':
				// New table. Create new worksheet from it.
				$worksheet = CreateWorksheet($objPHPExcel);
				break;
			case 'col':
			case 'colgroup':
			case 'caption':
			case 'thead':
			case 'tbody':
			case 'tfoot':
				// Table elements not yet implemented.
				break;
			case 'tr':
				// New row.
				$row++;
				$col = -1;
				break;
			case 'th':
			case 'td':
				// New column.
				$col++;
				break;
			case '#text':
				if (!$isMarkup) {
					// Apply only if not markup.
					// Data node.
					$worksheet->setCellValueByColumnAndRow($col, $row, $node->nodeValue);
				}
				break;
			default:
				// Other node. Ignore.
				break;
		}
		
		if ($isMarkup) {
			// Apply attribute markup, only if specified.
			// Currently implemented: <th> and <td> markup.
			// Inline styles have precedence.
			//
			// Set the default markups first.
			SetMarkup($worksheet, $col, $row, $node->nodeName, 'defaults', '');
			// Set the coded attribute markups.
			if ($node->hasAttributes()) {
				$attrs = $node->attributes;
				foreach ($attrs as $attr) {
					if ($attr->nodeName == 'style') {
						$styles = GetStyleAttributes($attr->nodeValue);
						foreach ($styles as $style)
							SetMarkup($worksheet, $col, $row, $node->nodeName, $style->nodeName, $style->nodeValue);
					} else {
						SetMarkup($worksheet, $col, $row, $node->nodeName, $attr->nodeName, $attr->nodeValue);
					}
				}
			}
		}
		
		// Go through the child nodes.
		if ($node->hasChildNodes()) {
			$children = $node->childNodes;
			foreach ($children as $child)
				ProcessTable($objPHPExcel, $worksheet, $col, $row, $child, $isMarkup);
		}
	}
	
	// Processes the tables in the HTML.
	function ProcessHTML(&$objPHPExcel, $dom) {
		$tables = $dom->getElementsByTagName('table');
		foreach ($tables as $table) {
			// Go through all elements in the tables to
			//  produce cells in the worksheets.
			// Columns start at 0. Rows start at 1.
			// Start off the page so the first new cell
			//  will land on the first worksheet cell.
			$col = -1;
			$row = 0;
			// We'll traverse the table twice.
			// First we'll do the markup, then we'll do the data.
			// We do this because when markup is changed in PHPExcel,
			//  all data is rechecked. This can slow large worksheets down.
			ProcessTable($objPHPExcel, $worksheet, $col, $row, $table, true);
			// Reset column and row.
			$col = -1;
			$row = 0;
			ProcessTable($objPHPExcel, $worksheet, $col, $row, $table, false);
		}
	}
	
	function CreateExcelWorkbook($user_id) {
		//
		// Create new PHPExcel object with default attributes
		//
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
		
		$objPHPExcel->getProperties()->setCreator($user_id)
									 ->setLastModifiedBy($user_id)
									 ->setTitle("Automated Export")
									 ->setSubject("Automated Report Generation")
									 ->setDescription("Automated Report Generation.")
									 ->setKeywords("Report")
									 ->setCompany("Dealer Online Marketing")
									 ->setCategory("Report");
									 
		return $objPHPExcel;
	}
	
	function CreateDom($html) {
		$htmltable = $html;
		// Check table validity.
		if(strlen($htmltable) == strlen(strip_tags($htmltable)) ) {     // anything left after we strip HTML?
		  echo "<br />Invalid HTML Table after Stripping Tags, nothing to Export.";
		  exit;
		}
		
		// Prepare bare html for use with loadHTML.
		$htmltable = strip_tags($htmltable, "<table><tr><th><thead><tbody><tfoot><td><br><b><span>");
		$htmltable = str_replace("<br />", "\n", $htmltable);
		$htmltable = str_replace("<br/>", "\n", $htmltable);
		$htmltable = str_replace("<br>", "\n", $htmltable);
		$htmltable = str_replace("&nbsp;", " ", $htmltable);
		$htmltable = str_replace("\n\n", "\n", $htmltable);
		
		$dom = new domDocument;
		$dom->loadHTML($htmltable);
		if ($dom)
			// remove redundant whitespace
			$dom->preserveWhiteSpace = false;
		return $dom;
		
	}
	
	function HTMLToobjPHPExcel($user_id, $html) {
		$ci =& get_instance();
		$ci->load->helper('html_codes_helper');
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		
		$dom = CreateDom($html);
		if(!$dom) {
		  echo "<br />Invalid HTML DOM, nothing to Export.";
		  exit;
		}
		
		$objPHPExcel = CreateExcelWorkbook($user_id);
		ProcessHTML($objPHPExcel, $dom);
		// set to first worksheet before close
		$objPHPExcel->setActiveSheetIndex(0);
		
		return $objPHPExcel;
	}
	
	/* Generic function which writes out an html table to an excel spreadsheet using PHPExcel.
	   $user_id : The name of the user creating the report.
	   $html : Needs to contain the table(s) which will be converted into spreadsheets.
	   $table_limit : The max number of spreadsheets to be created from $html (each table will
	     become a seperate spreadsheet). 0 means no limit. Default 0.
	   $debug_on : Some basic debug functions. Default false.
	*/
	
	function HTMLToobjPHPExcel_Old($user_id, $html, $table_limit = 0, $debug_on = FALSE) {	
		ini_set("memory_limit", "-1");
		ini_set("set_time_limit", "0");
		set_time_limit(0);
		if(!isset($html)) {
		  echo "<br />No Table Variable Present, nothing to Export.";
		  exit;
		}else{
		  $tablevar = $html;
		}
		if(!isset($table_limit)) {
		  $limit = 0;                            // maximum number of Excel tabs to create, optional
		}else{
		  $limit = $table_limit;
		}
		if(!isset($debug_on)) {             // optional, debug script by writing out to log file
		  $debug = false;
		}else{
		  $debug = $debug_on;
		  if ($debug) {
			  $handle = fopen("domcms/errors/exportdebug_log.txt", "w");
			  fwrite($handle, "\nDebugging On...");
		  }
		}
		$htmltable = $html;
		if(strlen($htmltable) == strlen(strip_tags($htmltable)) ) {     // anything left after we strip HTML?
		  echo "<br />Invalid HTML Table after Stripping Tags, nothing to Export.";
		  exit;
		}
		if($debug) {
		  fwrite($handle, "\n-------------------------------------------");
		  fwrite($handle, "\nHTML before prep: \n".$htmltable);
		  fwrite($handle, "\n-------------------------------------------");
		}
		$htmltable = strip_tags($htmltable, "<table><tr><th><thead><tbody><tfoot><td><br><b><span>");
		$htmltable = str_replace("<br />", "\n", $htmltable);
		$htmltable = str_replace("<br/>", "\n", $htmltable);
		$htmltable = str_replace("<br>", "\n", $htmltable);
		$htmltable = str_replace("&nbsp;", " ", $htmltable);
		$htmltable = str_replace("\n\n", "\n", $htmltable);
		if($debug) {
		  fwrite($handle, "\n-------------------------------------------");
		  fwrite($handle, "\nHTML after prep: \n".$htmltable);
		  fwrite($handle, "\n-------------------------------------------");
		}
		//
		//  Create Document Object Model from HTML table contents
		//
		$dom = new domDocument;
		$dom->loadHTML($htmltable);
		if(!$dom) {
		  echo "<br />Invalid HTML DOM, nothing to Export.";
		  exit;
		}
		$dom->preserveWhiteSpace = false;             // remove redundant whitespace
		$tables = $dom->getElementsByTagName('table');
		if(!is_object($tables)) {
		  echo "<br />Invalid HTML Table DOM, nothing to Export.";
		  exit;
		}
		if($debug) {
		  fwrite($handle, "\nTable Count: ".$tables->length);
		}
		if($tables->length < 1) {
		  echo "<br />DOM Table Count is ".$tables->length.", nothing to Export.";
		  exit;
		}
		$tbcnt = $tables->length - 1;                 // count minus 1 for 0 indexed loop over tables
		if($limit > 0 && $tbcnt > $limit) {
		  $tbcnt = $limit;
		}
		//
		//
		// Create new PHPExcel object with default attributes
		//
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
		
		$objPHPExcel->getProperties()->setCreator($user_id)
									 ->setLastModifiedBy($user_id)
									 ->setTitle("Automated Export")
									 ->setSubject("Automated Report Generation")
									 ->setDescription("Automated Report Generation.")
									 ->setKeywords("Report")
									 ->setCompany("Dealer Online Marketing")
									 ->setCategory("Report");
		//
		// Loop over tables in DOM to create an array, each table becomes a worksheet
		//
		for($z=0;$z<=$tbcnt;$z++) {
		  $maxcols = 0;
		  $totrows = 0;
		  $headrows = array();
		  $bodyrows = array();
		  $r = 0;
		  $h = 0;
		  $rows = $tables->item($z)->getElementsByTagName('tr');
		  $totrows = $rows->length;
		  if($debug) {
			fwrite($handle, "\nTotal Rows: ".$totrows);
		  }
		  //
		  // Get TH values
		  //
		  foreach ($rows as $row) {
			  $ths = $row->getElementsByTagName('th');
			  if(is_object($ths)) {
				if($ths->length > 0) {
				  $headrows[$h]['colcnt'] = $ths->length;
				  if($ths->length > $maxcols) {
					$maxcols = $ths->length;
				  }
				  $nodes = $ths->length - 1;
				  for($x=0;$x<=$nodes;$x++) {
					$thishdg = $ths->item($x)->nodeValue;
					$headrows[$h]['th'][] = $thishdg;
					$headrows[$h]['bold'][] = findBoldText(innerHTML($ths->item($x)));
					if($ths->item($x)->hasAttribute('style')) {
					  $style = $ths->item($x)->getAttribute('style');
					  $stylecolor = findStyleColor($style);
					  if($stylecolor == '') {
						$headrows[$h]['color'][] = findSpanColor(innerHTML($ths->item($x)));
					  }else{
						$headrows[$h]['color'][] = $stylecolor;
					  }
					}else{
					  $headrows[$h]['color'][] = findSpanColor(innerHTML($ths->item($x)));
					}
					if($ths->item($x)->hasAttribute('colspan')) {
					  $headrows[$h]['colspan'][] = $ths->item($x)->getAttribute('colspan');
					}else{
					  $headrows[$h]['colspan'][] = 1;
					}
					if($ths->item($x)->hasAttribute('align')) {
					  $headrows[$h]['align'][] = $ths->item($x)->getAttribute('align');
					}else{
					  $headrows[$h]['align'][] = 'left';
					}
					if($ths->item($x)->hasAttribute('valign')) {
					  $headrows[$h]['valign'][] = $ths->item($x)->getAttribute('valign');
					}else{
					  $headrows[$h]['valign'][] = 'top';
					}
					if($ths->item($x)->hasAttribute('bgcolor')) {
					  $headrows[$h]['bgcolor'][] = str_replace("#", "", $ths->item($x)->getAttribute('bgcolor'));
					}else{
					  $headrows[$h]['bgcolor'][] = 'FFFFFF';
					}
				  }
				  $h++;
				}
			  }
		  }
		  //
		  // Get TD values
		  //
		  foreach ($rows as $row) {
			  $tds = $row->getElementsByTagName('td');
			  if(is_object($tds)) {
				if($tds->length > 0) {
				  $bodyrows[$r]['colcnt'] = $tds->length;
				  if($tds->length > $maxcols) {
					$maxcols = $tds->length;
				  }
				  $nodes = $tds->length - 1;
				  for($x=0;$x<=$nodes;$x++) {
					$thistxt = $tds->item($x)->nodeValue;
					$bodyrows[$r]['td'][] = $thistxt;
					$bodyrows[$r]['bold'][] = findBoldText(innerHTML($tds->item($x)));
					if($tds->item($x)->hasAttribute('style')) {
					  $style = $tds->item($x)->getAttribute('style');
					  $stylecolor = findStyleColor($style);
					  if($stylecolor == '') {
						$bodyrows[$r]['color'][] = findSpanColor(innerHTML($tds->item($x)));
					  }else{
						$bodyrows[$r]['color'][] = $stylecolor;
					  }
					}else{
					  $bodyrows[$r]['color'][] = findSpanColor(innerHTML($tds->item($x)));
					}
					if($tds->item($x)->hasAttribute('colspan')) {
					  $bodyrows[$r]['colspan'][] = $tds->item($x)->getAttribute('colspan');
					}else{
					  $bodyrows[$r]['colspan'][] = 1;
					}
					if($tds->item($x)->hasAttribute('align')) {
					  $bodyrows[$r]['align'][] = $tds->item($x)->getAttribute('align');
					}else{
					  $bodyrows[$r]['align'][] = 'left';
					}
					if($tds->item($x)->hasAttribute('valign')) {
					  $bodyrows[$r]['valign'][] = $tds->item($x)->getAttribute('valign');
					}else{
					  $bodyrows[$r]['valign'][] = 'top';
					}
					if($tds->item($x)->hasAttribute('bgcolor')) {
					  $bodyrows[$r]['bgcolor'][] = str_replace("#", "", $tds->item($x)->getAttribute('bgcolor'));
					}else{
					  $bodyrows[$r]['bgcolor'][] = 'FFFFFF';
					}
				  }
				  $r++;
				}
			  }
		  }
		  if($z > 0) {
			$objPHPExcel->createSheet($z);
		  }
		  $suf = $z + 1;
		  $tableid = 'Worksheet' . $suf;
		  $wksheetname = ucfirst($tableid);
		  $objPHPExcel->setActiveSheetIndex($z);                      // each sheet corresponds to a table in html
		  $objPHPExcel->getActiveSheet()->setTitle($wksheetname);     // tab name
		  $worksheet = $objPHPExcel->getActiveSheet();                // set worksheet we're working on
		  $style_overlay = array('font' =>
							array('color' =>
							  array('rgb' => '000000'),'bold' => false,),
								  'fill' 	=>
									  array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCFF')),
								  'alignment' =>
									  array('wrap' => true, 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP),
								  'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
													 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
													 'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
													 'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),
							   );
		  $xcol = '';
		  $xrow = 1;
		  $usedhdrows = 0;
		  $heightvars = array(1=>'42', 2=>'42', 3=>'48', 4=>'52', 5=>'58', 6=>'64', 7=>'68', 8=>'76', 9=>'82');
		  $mergedcells = false;
		  for($h=0;$h<count($headrows);$h++) {
			$th = $headrows[$h]['th'];
			$colspans = $headrows[$h]['colspan'];
			$aligns = $headrows[$h]['align'];
			$valigns = $headrows[$h]['valign'];
			$bgcolors = $headrows[$h]['bgcolor'];
			$colcnt = $headrows[$h]['colcnt'];
			$colors = $headrows[$h]['color'];
			$bolds = $headrows[$h]['bold'];
			$usedhdrows++;
			$mergedcells = false;
			for($t=0;$t<count($th);$t++) {
			  if($xcol == '') {$xcol = 'A';}else{$xcol++;}
			  $thishdg = $th[$t];
			  $thisalign = $aligns[$t];
			  $thisvalign = $valigns[$t];
			  $thiscolspan = $colspans[$t];
			  $thiscolor = $colors[$t];
			  $thisbg = $bgcolors[$t];
			  $thisbold = $bolds[$t];
			  $strbold = ($thisbold==true) ? 'true' : 'false';
			  if($thisbg == 'FFFFFF') {
				$style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_NONE;
			  }else{
				$style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_SOLID;
			  }
			  $style_overlay['alignment']['vertical'] = $thisvalign;              // set styles for cell
			  $style_overlay['alignment']['horizontal'] = $thisalign;
			  $style_overlay['font']['color']['rgb'] = $thiscolor;
			  $style_overlay['font']['bold'] = $thisbold;
			  $style_overlay['fill']['color']['rgb'] = $thisbg;
			  if($thiscolspan > 1) {                                                // spans more than 1 column
				$mergedcells = true;
				$lastxcol = $xcol;
				for($j=1;$j<$thiscolspan;$j++) {                                    // count to last column in span
				  $lastxcol++;
				}
				$cellRange = $xcol.$xrow.':'.$lastxcol.$xrow;
				if($debug) {
				  fwrite($handle, "\nmergeCells: ".$xcol.":".$xrow." ".$lastxcol.":".$xrow);
				}
				$worksheet->mergeCells($cellRange);                                // merge the columns
				$worksheet->setCellValue($xcol.$xrow, $thishdg);
				$worksheet->getStyle($cellRange)->applyFromArray($style_overlay);
				$worksheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
				$num_newlines = substr_count($thishdg, "\n");                       // count number of newline chars
				if($num_newlines > 1) {
				  $rowheight = $heightvars[1];                                      // default to 42
				  if(array_key_exists($num_newlines, $heightvars)) {                // I couldn't find a PHPExcel method
					$rowheight = $heightvars[$num_newlines];                        // to do this, so I look to see how
				  }else{                                                            // many newlines and just guess at
					$rowheight = 75;                                                // row height
				  }
				  $worksheet->getRowDimension($xrow)->setRowHeight($rowheight);     // adjust heading row height
				  //$worksheet->getRowDimension($xrow)->setRowHeight(-1);           // this doesn't work in PHPExcel
				}
				if($debug) {
				  fwrite($handle, "\n".$cellRange." ColSpan:".$thiscolspan." Color:".$thiscolor." Align:".$thisalign." VAlign:".$thisvalign." BGColor:".$thisbg." Bold:".$strbold." cellValue: ".$thishdg);
				}
				$xcol = $lastxcol;
			  }else{
				$worksheet->setCellValue($xcol.$xrow, $thishdg);
				$worksheet->getStyle($xcol.$xrow)->applyFromArray($style_overlay);
				if($debug) {
				  fwrite($handle, "\n".$xcol.":".$xrow." ColSpan:".$thiscolspan." Color:".$thiscolor." Align:".$thisalign." VAlign:".$thisvalign." BGColor:".$thisbg." Bold:".$strbold." cellValue: ".$thishdg);
				}
			  }
			}
			$xrow++;
			$xcol = '';
		  }
		  //Put an auto filter on last row of heading only if last row was not merged
		  if(!$mergedcells) {
			// == BUG: This line creates a bad worksheet ==
			//$worksheet->setAutoFilter("A$usedhdrows:" . $worksheet->getHighestColumn() . $worksheet->getHighestRow() );
		  }
		  if($debug) {
			//fwrite($handle, "\nautoFilter: A".$usedhdrows.":".$worksheet->getHighestColumn().$worksheet->getHighestRow());
		  }
		  // Freeze heading lines starting after heading lines
		  $usedhdrows++;
		  $worksheet->freezePane("A$usedhdrows");
		  if($debug) {
			fwrite($handle, "\nfreezePane: A".$usedhdrows);
		  }
		  //
		  // Loop thru data rows and write them out
		  //
		  $xcol = '';
		  $xrow = $usedhdrows;
		  for($b=0;$b<count($bodyrows);$b++) {
			$td = $bodyrows[$b]['td'];
			$colcnt = $bodyrows[$b]['colcnt'];
			$colspans = $bodyrows[$b]['colspan'];
			$aligns = $bodyrows[$b]['align'];
			$valigns = $bodyrows[$b]['valign'];
			$bgcolors = $bodyrows[$b]['bgcolor'];
			$colors = $bodyrows[$b]['color'];
			$bolds = $bodyrows[$b]['bold'];
			for($t=0;$t<count($td);$t++) {
			  if($xcol == '') {$xcol = 'A';}else{$xcol++;}
			  $thistext = $td[$t];
			  $thisalign = $aligns[$t];
			  $thisvalign = $valigns[$t];
			  $thiscolspan = $colspans[$t];
			  $thiscolor = $colors[$t];
			  $thisbg = $bgcolors[$t];
			  $thisbold = $bolds[$t];
			  $strbold = ($thisbold==true) ? 'true' : 'false';
			  if($thisbg == 'FFFFFF') {
				$style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_NONE;
			  }else{
				$style_overlay['fill']['type'] = PHPExcel_Style_Fill::FILL_SOLID;
			  }
			  $style_overlay['alignment']['vertical'] = $thisvalign;              // set styles for cell
			  $style_overlay['alignment']['horizontal'] = $thisalign;
			  $style_overlay['font']['color']['rgb'] = $thiscolor;
			  $style_overlay['font']['bold'] = $thisbold;
			  $style_overlay['fill']['color']['rgb'] = $thisbg;
			  if($thiscolspan > 1) {                                              // spans more than 1 column
				$lastxcol = $xcol;
				for($j=1;$j<$thiscolspan;$j++) {                                  // count spanned columns
				  $lastxcol++;
				}
				$cellRange = $xcol.$xrow.':'.$lastxcol.$xrow;
				if($debug) {
				  fwrite($handle, "\nmergeCells: ".$xcol.":".$xrow." ".$lastxcol.":".$xrow);
				}
				$worksheet->mergeCells($cellRange);                               // merge columns in span
				$worksheet->setCellValue($xcol.$xrow, $thistext);
				$worksheet->getStyle($cellRange)->applyFromArray($style_overlay);
				$worksheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
				$num_newlines = substr_count($thistext, "\n");                       // count number of newline chars
				if($num_newlines > 1) {
				  $rowheight = $heightvars[1];                                      // default to 42
				  if(array_key_exists($num_newlines, $heightvars)) {                // I could not find a method in PHPExcel
					$rowheight = $heightvars[$num_newlines];                        // that would set row height automatically
				  }else{                                                            // based on content, so I guess based
					$rowheight = 75;                                                // on number of newlines in the content
				  }
				  $worksheet->getRowDimension($xrow)->setRowHeight($rowheight);     // adjust heading row height
				  //$worksheet->getRowDimension($xrow)->setRowHeight(-1);           // this doesn't work in PHPExcel
				}
				if($debug) {
				  fwrite($handle, "\n".$cellRange." ColSpan:".$thiscolspan." Color:".$thiscolor." Align:".$thisalign." VAlign:".$thisvalign." BGColor:".$thisbg." Bold:".$strbold." cellValue: ".$thistext);
				}
				//$worksheet->getRowDimension($xrow)->setRowHeight(-1);
				$xcol = $lastxcol;
			  }else{
				$worksheet->getColumnDimension($xcol)->setWidth(25);                // default width
				$worksheet->setCellValue($xcol.$xrow, $thistext);
				$worksheet->getStyle($xcol.$xrow)->applyFromArray($style_overlay);
				if($debug) {
				  fwrite($handle, "\n".$xcol.":".$xrow." ColSpan:".$thiscolspan." Color:".$thiscolor." Align:".$thisalign." VAlign:".$thisvalign." BGColor:".$thisbg." Bold:".$strbold." cellValue: ".$thistext);
				}
			  }
			}
			$xrow++;
			$xcol = '';
		  }
		  // autosize columns to fit data
		  $azcol = 'A';
		  for($x=1;$x==$maxcols;$x++) {
			$worksheet->getColumnDimension($azcol)->setAutoSize(true);
			$azcol++;
		  }
		  if($debug) {
			fwrite($handle, "\nHEADROWS: ".print_r($headrows, true));
			fwrite($handle, "\nBODYROWS: ".print_r($bodyrows, true));
		  }
		} // end for over tables
		$objPHPExcel->setActiveSheetIndex(0);                      // set to first worksheet before close
		if($debug) {
			fwrite($handle, "\nTitle: " . $objPHPExcel->getActiveSheet()->getTitle());
			fwrite($handle, "\nNumber of spreadsheet rows: " . $objPHPExcel->getActiveSheet()->getHighestRow());
			
			$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); // e.g. 10
			$highestColumn = $objPHPExcel->getActiveSheet()->getHighestColumn(); // e.g 'F'
			
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
			
			fwrite($handle, "\n\n");
			for ($row = 1; $row <= $highestRow; ++$row) {
			  for ($col = 0; $col <= $highestColumnIndex; ++$col) {
				fwrite($handle, $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->getValue() . ' ');
			  }
			  
			  if ($row < $highestRow)
				  fwrite($handle, "\n");
			}
		}
		
		if($debug) {
		  fclose($handle);
		}
		
		return $objPHPExcel;
	}
	
	/*	$file_name : The path and name of the excel file being created.
		$isExcel2007 : Set True if an Excel 2007 file is required (.xlsx).
	                   False if Excel 5 (.xls). Defaults to Excel 2007.
	*/
	function CreateExcel($file_name, $objPHPExcel, $isExcel2007 = TRUE) {
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		
		if ($isExcel2007) {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		} else
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($file_name);
	}
	
	/*	$file_name : The path and name of the PDF file being created.
	*/
	function CreatePDF($file_name, $objPHPExcel) {
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';

		$objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->writeAllSheets();
		$objWriter->save($file_name);
	}
	
	function innerHTML($node) {
	  $doc = $node->ownerDocument;
	  $frag = $doc->createDocumentFragment();
	  foreach ($node->childNodes as $child) {
		$frag->appendChild($child->cloneNode(TRUE));
	  }
	  return $doc->saveXML($frag);
	}
	
	function findSpanColor($node) {
	  $pos = stripos($node, "color:");       // ie: looking for style='color: #FF0000;'
	  if ($pos === false) {                  //                        12345678911111
		return '000000';                     //                                 01234
	  }
	  $node = substr($node, $pos);           // truncate to color: start
	  $start = "#";                          // looking for html color string
	  $end = ";";                            // should end with semicolon
	  $node = " ".$node;                     // prefix node with blank
		$ini = stripos($node,$start);          // look for #
		if ($ini === false) return "000000";   // not found, return default color of black
		$ini += strlen($start);                // get 1 byte past start string
		$len = stripos($node,$end,$ini) - $ini; // grab substr between start and end positions
		return substr($node,$ini,$len);        // return the RGB color without # sign
	}
	
	function findStyleColor($style) {
	  $pos = stripos($style, "color:");      // ie: looking for style='color: #FF0000;'
	  if ($pos === false) {                  //                        12345678911111
		return '';                           //                                 01234
	  }
	  $style = substr($style, $pos);           // truncate to color: start
	  $start = "#";                          // looking for html color string
	  $end = ";";                            // should end with semicolon
	  $style = " ".$style;                     // prefix node with blank
		$ini = stripos($style,$start);          // look for #
		if ($ini === false) return "";         // not found, return default color of black
		$ini += strlen($start);                // get 1 byte past start string
		$len = stripos($style,$end,$ini) - $ini; // grab substr between start and end positions
		return substr($style,$ini,$len);        // return the RGB color without # sign
	}
	
	function findBoldText($node) {
	  $pos = stripos($node, "<b>");          // ie: looking for bolded text
	  if ($pos === false) {                  //                        12345678911111
		return false;                        //                                 01234
	  }
	  return true;                           // found <b>
	}