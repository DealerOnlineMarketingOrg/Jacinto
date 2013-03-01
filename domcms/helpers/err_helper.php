<?php

	// Creates a standard error and saves it to the current session.
    // Requires Error Level, Error Message, and an element list.
	// The element list will need to be structed as follows:
	//   'ELEMENT_NAME1' => 'ELEMENT_NAME1_error_message',
	//   'ELEMENT_NAME2' => 'ELEMENT_NAME2_error_message',
	//   'ELEMENT_NAME3' => 'ELEMENT_NAME3_error_message',
	// Error message may be blank if no specific error message is
	// associated  with it.
	function SetErr($Error_Level, $Error_Message, $Element_List) {
		$ci =& get_instance();
		
		$ci->err->Level = $Error_Level;
		$ci->err->Msg = $Error_Message;
		$ci->err->ElementList = $Element_List;
		$ci->session->sess_write();
	}
	
	// Should be called at the end of the page to clear out the page errors.
	function ClearErr() {
		$ci =& get_instance();
		
		$ci->err->Level = 0;
		$ci->err->Msg = '';
		$ci->err->ElementList = array();
		
		$ci->session->sess_write();
	}