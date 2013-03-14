<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agency extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->helper('form');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');
		$this->activeNav = 'admin';
    }
	
	public function Edit() {
		$permissions = $this->CheckModule('Agency_Edit');
		if (!$permissions) {
			$this->AccessDenied();
		} else {
			//WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
			$agency_id = $this->input->post('agency_id');
			$this->load->model('administration');
			$agency = $this->administration->getAgencyByID($agency_id);
			//PREPARE THE VIEW FOR THE FORM
			$data = array(
				'agency' => $agency,
				'html' => ''
			);
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
			$this->LoadTemplate('forms/form_editagency', $data);
		}
	}
	
	public function Add() {
		//MODULE PERMISSIONS
		$permissions = $this->CheckModule('Agency_Add');
		if (!$permissions) {
			$this->AccessDenied();
		} else {
			//print_object($this->user);
			//PREPARE THE DATA FOR PAGE

			$html = '';

			$data = array(
				'html' => $html
			);
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
			$this->LoadTemplate('forms/form_addagency', $data);
		}
	}

    public function index() {
		$permissions = $this->CheckModule('Agency_List');
		if (!$permissions) {
			$this->AccessDenied();
		} else {

			$level = $this->user['DropdownDefault']->LevelType;
			$html = '';

			//create html table with codeigniter library. This is awesome btw.
			$this->table->set_heading('Name', 'Description', 'Status', 'Action');
			$agency_id = (($level != 'a') ? $this->user['DropdownDefault']->SelectedAgency : false);
			$agencies = $this->administration->getAgencies($agency_id);

			if($agencies) {
				//LOOP THROUGH EACH AGENCY AND CREATE A FORM BUTTON "EDIT" AND ROW FOR IT.
				foreach ($agencies as $agency) :

					//EACH FORM IS NAMED BY THE EDIT_{AGENCY_ID}, SAME CONCEPT WITH THE ID
					$form_attr = array(
						'name' => 'edit_' . $agency->ID,
						'id' => 'edit_form_' . $agency->ID,
					);

					//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
					$button = array(
						'name' => 'submit',
						'id' => 'agency_id_' . $agency->ID,
						'class' => 'basicBtn',
						'value' => 'Edit'
					);

					$tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
					$this->table->set_template($tmpl);

					$form = '';
					if ($this->CheckModule('Agency_Edit')) {
						$form .= form_open('agency/edit', $form_attr) . form_hidden('agency_id', $agency->ID) . form_submit($button) . form_close();
					}

					//BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
					//USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
					$this->table->add_row($agency->Name, $agency->Description, (($agency->Status) ? 'Active' : 'Disabled'), $form);
				endforeach;
			}else {
			}

			//BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
			$html .= $this->table->generate();

			if ($this->CheckModule('Agency_Add')) {
				$html .= anchor(base_url() . 'agency/add', 'Add New Agency', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_agency_btn"');
			}

			//Prepare data for template in $data array; when in template, you call the keys like vars: example => $html;
			$data = array(
				'page_id' => 'Agencies',
				'page_html' => $html
			);

			$this->LoadTemplate('pages/listings', $data);
		}

    }

}
