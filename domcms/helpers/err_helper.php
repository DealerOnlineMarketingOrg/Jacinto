<?php

	// Creates a standard error and saves it to the current session.
    // Requires Error Level, Error Message, and an element list.
	// The element list will need to be structed as follows:
	//   'ELEMENT_NAME1' => 'ELEMENT_NAME1_error_message',
	//   'ELEMENT_NAME2' => 'ELEMENT_NAME2_error_message',
	//   'ELEMENT_NAME3' => 'ELEMENT_NAME3_error_message',
	// Error message may be blank if no specific error message is
	// associated  with it.
	function ThrowError($Lifetime, $Error_Level, $Error_Message, $Element_List) {
		$ci =& get_instance();
		
		// We add 1 to lifetime since the page load after the error
		//  is considered the lifetime starting point.
		$ci->err->Lifetime = $Lifetime + 1;
		$ci->err->Live = TRUE;
		$ci->err->Level = $Error_Level;
		$ci->err->Msg = $Error_Message;
		$ci->err->ElementList = $Element_List;
		
		$ci->session->set_userdata($ci->err);
		$ci->session->sess_write();
	}
	
	// Notifies the user of the error.
	// Function is inline. Uses itsbrain success and failure elements.
	// Outputs nothing if no error or success.
	// Failure and successes are taken care of here.
	// Error still lives after notification.
	function NotifyError() {
		$ci =& get_instance();
		
		if ($ci->err->Live) {
			if ($ci->err->Level < 0) {
				// Notify failure.
				echo '<div class="nNote nFailure" style="display:inline"><p><strong>FAILURE: </strong>' . $ci->err->Msg . '</p></div>';
			} else if ($ci->err->Level > 0) {
				// Notify success.
				echo '<div class="nNote nSuccess" style="display:inline"><p><strong>SUCCESS: </strong>' . $ci->err->Msg . '</p></div>';
			}
		}
	}
	
	// Logs the error in the specified database table.
	function LogError($table) {
		
	}
	
	// Should be called at the end of the page to clear out the page errors.
	function ClearError() {
		$ci =& get_instance();
		
		$ci->err->Lifetime = 0;
		$ci->err->Live = FALSE;
		$ci->err->Level = 0;
		$ci->err->Msg = '';
		$ci->err->ElementList = array();
		
		$ci->session->sess_write();
	}