<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
	
	public function connect() {
		require('domcms/libraries/openid.php');
		try {
		    # Change 'localhost' to your domain name.
		    $openid = new LightOpenID(base_url());
		    if(!$openid->mode) {
		        if(isset($_GET['login'])) {
		            $openid->identity = 'https://www.google.com/accounts/o8/id';
		            redirect($openid->authUrl(),'refresh');
		        }
		?>
		<form action="?login" method="post">
		    <button>Login with Google</button>
		</form>
		<?php
		    } elseif($openid->mode == 'cancel') {
		        echo 'User has canceled authentication!';
		    } else {
		        echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
		    }
		} catch(ErrorException $e) {
		    echo $e->getMessage();
		}
		
	}
	
}
