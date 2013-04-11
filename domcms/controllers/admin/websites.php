<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends DOM_Controller {
	
	//should always have this
	public $client_id;
	
	//when working with a vendor, we fill this
	public $vendor_id;
	
	//if were editing we should always have this
	public $website_id;
	
    public function __construct() {
        parent::__construct();
		//load the admin model
		$this->load->model('administration');
		
		if(isset($_POST['cid'])) {
			$this->client_id = $_POST['cid'];
		}elseif(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
		}
		if(isset($_POST['wid'])) {
			$this->website_id = $_POST['wid'];
		}elseif(isset($_GET['wid'])) {
			$this->website_id = $_GET['wid'];
		}
	}
	
	public function Load_table() {
		$this->load->helper('template');
		$table = load_client_websites($this->client_id);
		print $table;
	}
	
	public function add() {
		$form = $this->input->post();
		if(isset($_GET['cid'])) {
			//var_dump($form);
			$add = $this->administration->addWebsiteInfo($form);
			if($add) {
				print 1;
			}else {
				print 0;
			}
		}elseif(isset($_GET['vid'])) {
			$add = $this->administration->addKnownVendorWebsite($form,$_GET['vid']);
			if($add) {
				print 1;	
			}else {
				print 0;
			}
		}else {
			print 0;
		}
	}
	
	public function edit() {
		$form = $this->input->post();
		if(isset($_GET['wid'])) {
			$add = $this->administration->editWebsiteInfo($form);
			if($add) {
				print 1;
			}else {
				print 0;
			}
		}elseif(isset($_GET['vid'])) {
		
		}else {
			print 0;
		}
	}
	
	public function disable() {
		if(isset($_GET['wid'])) {
			$disable = $this->administration->disableWebsite($this->website_id);
			if($disable) {
				print $disable;	
			}else {
				print 0;	
			}
		}else {
			print 0;	
		}
	}
	
	public function enable() {
		if(isset($_GET['wid'])) {
			$enable = $this->administration->enableWebsite($this->website_id);
			if($enable) {
				print $enable;	
			}else {
				print 0;	
			}
		}else {
			print 0;	
		}
	}
	
	public function form() {
		if(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
			//get the right client info
			$client = $this->administration->getClient($_GET['cid']);
			//vendors are not associated per client
			$vendors = $this->administration->getAllVendors();
			//prepare the array
			$data = array(
				'client'=>$client,
				'vendors'=>$vendors,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE),
			);
			//print_object($this->administration->getWebsite($_GET['wid']));
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addwebsites',$data);
		}elseif(isset($_GET['vid'])) {
			$this->vendor_id = $_GET['vid'];
			$vendor = $this->administration->getVendor($_GET['vid']);
			$data = array(
				'selectedVendor'=>$vendor->ID,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE)
				
			);
		}
	}
	
}
	