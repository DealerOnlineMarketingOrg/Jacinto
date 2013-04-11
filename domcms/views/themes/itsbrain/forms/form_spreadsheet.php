<?php
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
	
	$startDate = '1/1/2013';
	$endDate = '12/1/2013';
	
	$colNames = getMonthYearRange($startDate, $endDate);
	$rowNames = array('A','B','C','D','E','F','G');
?>
	
<div id="mutableTable">
    <!-- Generate a table in a scrolling div based on number of columns and rows. -->
    <!-- Table will be editable. -->
    <!-- Rows and column names will be scrollable, but frozen. -->
    
    <div style="border:solid 1px black; overflow:auto;">
        <!-- placemarker for upper left -->
        <div style="border:none; width:15px; height:15px; position:relative; float:left"></div>
        <!-- Column bar -->
        <div style="border:solid 1px grey; width:50px; height:15px; position:relative; float:left">
            <table>
                <tr>
                	<?php foreach ($colNames as $colName)
						echo '<td>' . $colName . '</td>';
					?>
                </tr>
            </table>
        </div>
        <div style="clear:both"></div>
        <!-- Row bar -->
        <div style="border:solid 1px grey; width:15px; height:50px; position:relative; float:left">
        	<table>
                <?php foreach ($rowNames as $rowName)
					echo '<td><td>' . $rowName . '</td></tr>';
				?>
            </table>
        </div>
        <!-- Body block -->
        <div style="border:solid 1px grey; width:15px; height:50px; position:relative; float:left">
        	<table>
            	<?php foreach ($rowNames as $r) {
					echo '<tr>';
					foreach ($colNames as $c)
						echo '<td>&nbsp;</td>';
					echo '</tr>';
				} ?>
            </table>
        </div>
    </div>
    
</div>