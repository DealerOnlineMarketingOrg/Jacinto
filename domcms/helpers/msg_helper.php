<?php

    function email_reset_msg($pass) {
        return '<p>Your password at dealeronlinemarketing.com has been reset to ' . $pass . ', which will only be active for one login session. When you log in using this temporary password, you will be prompted to select a new personal password.</p><p>When choosing a new personal password, please select one that includes both letters and numbers with at least six but no more than 30 characters. Your new password may not include special characters or punctuation.</p><p>Follow the link below to login in with your temporary password. After that, you’ll be prompted to change your password.</p>';
    }
    
    function error_msg() {
        return '<div class="nNote nFailure hideit"><p><strong>Error:</strong> Something went wrong. Please try again or contact your admin.</p></div><div class="fix"></div>';
    }
    
    function success_msg($msg) {
        return '<div class="nNote nSuccess hideit"><p><strong>SUCCESS:</strong> ' . $msg . '</p></div><div class="fix"></div>';
    }

?>