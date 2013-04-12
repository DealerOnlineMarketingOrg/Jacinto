<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Masterlist extends DOM_Controller {

	var $myClientID;

    public function __construct() {
        parent::__construct();
		$this->load->model(array('mlist'));
		
		//if we detect the get paramater 'cid' 
		//we know that the system already knows which client we need to work with.
		//set the global var for the class that is available for all methods.
		//if we do not detect the get parameter, then set the var to false
		if(isset($_GET['cid'])) {
			$this->myClientID = $_GET['cid'];
		}else {
			$this->myClientID = FALSE;	
		}
    }

    public function index() {
		$this->LoadTemplate('pages/masterlist_listing');
    }
	
	public function Edit_entry() {
		if($this->myClientID) :
			$client          = $this->mlist->getFormData($this->myClientID);
			$vendorOptions   = $this->mlist->getVendorOptions();
			$crazyEggOptions = $this->mlist->getCrazyEggOptions();
						
			$data = array(
				'client'=>$client,
				'vendorOptions'=>$vendorOptions,
				'crazyEggOptions'=>$crazyEggOptions
			);
			
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editmasterlist',$data);
		else:
			echo 0;
		endif;	
	}
	
	public function form() {
		
	}

}
