<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendors extends DOM_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('administration'));
        $this->load->helper(array('msg','html'));
		$this->activeNav = 'admin';
    }
	
	public function index() {
		$this->LoadTemplate('pages/vendor_listing');
	}
	
	public function Load_table() {
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/vendor_listing_table');	
	}
	
	public function add() {
		//were loading this page on a popup
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addeditviewvendors');
	}
	
	public function edit() {
		//this is a popup that receives a post/get so we need to set the id
		$vid = $_GET['vid'];
		$vendor = $this->administration->getVendor($vid);
		$data = array(
			'vendor' => $vendor,
			'contacts'=>true,
			'websites'=>true
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addeditviewvendors',$data);
	}
	
	public function view() {
		$vid = $_GET['vid'];
		$vendor = $this->administration->getVendor($vid);
		$data = array(
			'vendor'=>$vendor,
			'contacts'=>true,
			'websites'=>true,
			'view'=>true
		);	
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_addeditviewvendors',$data);
	}
	
	public function remove($which) {
		$vid = $this->input->post('vid');
		$disable = $this->administration->disableVendor($vid,$which);
		if($disable) :
			return TRUE;
		else :
			return FALSE;
		endif;
	}
	
	public function form() {
		$form = $this->input->post();

		$address = 'street:' . $this->security->xss_clean($form['street']) . ',city:' . $this->security->xss_clean($form['city']) . ',state:' . $this->security->xss_clean($form['state']) . ',zipcode:' . $this->security->xss_clean($form['zipcode']);
		$phone   = $form['phone'];
		 
		$data = array(
			'VENDOR_Name' 		=> $form['name'],
			'VENDOR_Address' 	=> $address,
			'VENDOR_Phone' 		=> $phone,
			'VENDOR_Notes' 		=> $form['notes'],
			'VENDOR_Active' 	=> $form['status'],
			'VENDOR_ActiveTS' 	=> date('Y-m-d H:i:s'),
			'VENDOR_Created' 	=> date('Y-m-d H:i:s')
		);
		
		$vid = isset($form['ID']) ? $form['ID'] : FALSE;
		
		$process = $this->administration->vendorForm($vid);
		
		if($process) {
			echo '1';
		}else {
			echo '0';
		}
	}
	
}

