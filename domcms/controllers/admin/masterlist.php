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
	
	public function Load_table() {
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/masterlist_listing_table');	
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
		$form = $this->input->post();
		/* Since we never know how many websites have been edited, we have to do some tricks to keep the right data organized. */
		/* Lets go ahead and prepare the data */
		
		//always just one entry, never duplicated based on different websites
		$doc = $this->security->xss_clean($form['doc']);
		$xsl = $this->security->xss_clean($form['xsl']);
		$crm = $this->security->xss_clean($form['crm']);
		$crm_link = $this->security->xss_clean($form['crm_link']);
		$asset_id = $this->security->xss_clean($form['assets_id']);
		$assets = array(
			'CRM_Vendor_ID'=>$crm,
			'CRM_Vendor_Link'=>$crm_link,
			'DOC_Link'=>$doc,
			'XLS_Link'=>$xsl
		);
		
		$update_assets = $this->mlist->updateDocExcelCRM($asset_id,$assets);
		
		if($update_assets) {
			$cmss = $form['cms'];
			
			foreach($cmss as $key => $value) {
				$update_cms = $this->mlist->updateCms($key,$this->security->xss_clean($value));
				if(!$update_cms) {
					echo '0';	
				}
			}
			
			$ces = $form['crazyegg'];
			
			foreach($ces as $key => $value) {
				$update_crazy_egg = $this->mlist->updateCrazyEgg($key,$this->security->xss_clean($value));	
				if(!$update_crazy_egg) {
					echo '0';	
				}
			}
			
			echo '1';
			
		}else {
			echo '0';	
		}		
	}

}
