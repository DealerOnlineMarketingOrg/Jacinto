<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agency extends DOM_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('msg');
		$this->activeNav = 'admin';
		$this->load->model('administration');
    }
	
	public function form() {
		//when the url parameter aid is found, we know its editing a agency.
		if(isset($_GET['aid'])) {
			//heres the agency id
			$agency_id = $_GET['aid'];
		}else {
			$agency_id = FALSE;	
		}
		
		//the form data, cleaned and ready to go
		$agency_data = $this->security->xss_clean($this->input->post());
		$data = array(
			'AGENCY_Name' => $agency_data['name'],
			'AGENCY_Notes' => $agency_data['notes']
		);
		if($agency_id) {
			
			if($agency_data['status'] == 0) {
				//check if there are groups enabled.
				$group_check = $this->administration->getGroups($agency_id);
				if($group_check) {
					echo '3';	
				}
			}else {
				$edit_form = array(
					'AGENCY_Active' => $agency_data['status']
				);
				$data = $data + $edit_form;
				$edit_agency = $this->administration->updateAgencyInformation($agency_id,$data);
				if($edit_agency) {
					echo '1';	
				}else {
					echo '0';	
				}
			}
			
		}else {
			$add_form = array(
				'AGENCY_Active' => 1,
				'AGENCY_Created' => date(FULL_MILITARY_DATETIME),
			);
			$data = $data + $add_form;
			$add_agency = $this->administration->addAgencies($data);
			if($add_agency) {
				echo '1';	
			}else {
				echo '0';	
			}
		}
	}
	
	public function Edit() {
		//WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		$agency_id = $_GET['aid'];
		$this->load->model('administration');
		$agency = $this->administration->getAgencyByID($agency_id);
		//PREPARE THE VIEW FOR THE FORM
		$data = array(
			'agency' => $agency,
			'html' => ''
		);
		//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editagency', $data);
	}
	
	public function Disable() {
		//agencies cant be disabled until all groups and clients have been disabled under them.
	}
	
	public function load_table() {
		$agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$agencies = $this->administration->getAgencies();	
		$data = array(
			'agencies'=>$agencies
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/agency_listing_table',$data);
	}
	
	
	public function Add() {
		$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editagency');
	}

    public function index() {
		$agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$agencies = $this->administration->getAgencies();	
		$data = array(
			'agencies' => $agencies
		);
		$this->LoadTemplate('pages/agency_listing',$data);
    }

}
