<?php

    function email_reset_msg($pass) {
        return '<p>Your password at dealeronlinemarketing.com has been reset to ' . $pass . ', which will only be active for one login session. When you log in using this temporary password, you will be prompted to select a new personal password.</p><p>When choosing a new personal password, please select one that includes both letters and numbers with at least eight but no more than 30 characters. Your new password requires at least one special character and at least one capital letter.</p><p>Follow the link below to login in with your temporary password. After that, you’ll be prompted to change your password.</p>';
    }
    
    function error_msg($msg = false) {
		if(!$msg) :
       		return '<div class="nNote nFailure hideit"><p><strong>Error:</strong> Something went wrong. Please try again or contact your admin.</p></div>';
		else :
			return  '<div class="nNote nFailure hideit"><p><strong>Error:</strong> ' . $msg . '</p></div>';
		endif;
    }
    
    function success_msg($msg) {
        return '<div class="nNote nSuccess hideit"><p><strong>SUCCESS:</strong> ' . $msg . '</p></div>';
    }
	
	function failed_form_submit() {
		return '<div class="nNote">The form your trying to submit failed. Please try again.</div>';	
	}
	
	function login_failed() {
		return '<div class="nNote nFailure"><p><strong>Error:</strong>The username and/or password are incorrect. Please try again.</p></div>';	
	}
	
	function exceeded_attempts() {
		return '<div class="nNote nFailure"><p><strong>Error:</strong>The username has exceeded 3 login attempts. The password has automatically been reset and emailed to the users email address on file. Check your email for a new password and the system will ask you to reset your password after a successfull login attempt.</p></div>';	
	}

?>