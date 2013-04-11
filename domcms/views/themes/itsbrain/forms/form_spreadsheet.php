<?php
	class cell {
		public $name = '';
		public $selected = false;
	}
	
	// Create 2D table array.
	$cells = array();
	foreach ($rows as $row) {
		$cells[] = new cell();
		foreach ($columns as $column) {
			$cells[][] = new cell();
		}
	}
?>

<div id="mutableTable">
    <!-- Generate a table in a scrolling div based on number of columns and rows. -->
    <!-- Table will be editable. -->
    <!-- Rows and column names will be scrollable, but frozen. -->
    <table style="border:solid 1px grey">
        <?php
        $col = '';
        $row = 0;
        foreach ($rowNames as $r) {
            echo '<tr>';
            foreach ($colNames as $colName) {
                if ($row == 0) {
                    if ($col == '')
                        echo '<td style="border:solid 1px grey"></td>';								
                    else
                        echo '<td style="border:solid 1px grey">'.$colName.'</td>';
                } else {
                    if ($col == '')
                        echo '<td style="border:solid 1px grey">'.$row.'</td>';
                    else
                        echo '<td id="'.$col.$row.'" style="border:solid 1px grey">'.$col.$row.'</td>';
                }
                $col = ($col == '') ? 'A' : $col+1;
            }
            echo '</tr>';
            $row++;
            $col = '';
        } ?>
    </table>
</div>