<?php
	if (isset($contact)) {
		echo ContactInfoListingTable($contact,$type);
	} else
		echo ContactInfoListingTable();
?>