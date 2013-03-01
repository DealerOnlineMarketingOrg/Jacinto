<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends DOM_Controller {

	public $agency_id;
	public $group_id;

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');
        $this->load->helper('template');
        $this->load->helper('string_parser');
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->activeNav = 'admin';
    }

    public function index($msg=false) {
		$permissions = $this->CheckModule('Client_List');
		$tmpl['table_open'] = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
		
		if (!$permissions) {
			$this->AccessDenied();
		} else {
			$level = $this->user['DropdownDefault']->LevelType;
			$clients = (($level != 'c') ? $this->administration->getAllClientsInGroup($this->group_id) : $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient));
			//print_object($clients);
			//create html table with codeigniter library. This is awesome btw.
			$this->table->set_heading('Tag', 'Code' , 'Name', 'Group Name', 'Status', 'Actions');
	
			$html = '';
	
			//If there is a message to the user, present it as it should be.
			if ($msg AND $msg != 'error') {
				//The success message after a group was added or edited.
				$html .= success_msg('The Client was edited successfully!');
			} elseif($msg AND $msg != 'success') {
				//The error message after a group was added, or edited.
				$html .= error_msg();
			}
			
			//LOOP THROUGH EACH AGENCY AND CREATE A FORM BUTTON "EDIT" AND ROW FOR IT.
			foreach ($clients as $group) :
				if(count($group) > 1) {
					foreach($group as $client) {
						
						//EACH FORM IS NAMED BY THE EDIT_{AGENCY_ID}, SAME CONCEPT WITH THE ID
						$form_attr = array(
							'name' => 'edit_' . $client->ClientID,
							'id' => 'edit_form_' . $client->ClientID,
						);
						
						//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
						$button = array(
							'name' => 'submit',
							'id' => 'client_id_' . $client->ClientID,
							'class' => 'basicBtn',
							'value' => 'Edit'
						);
						
						$client->ClassName = explode(',',$client->ClassName);
						$client_class = '';
						foreach($client->ClassName as $class) {
							$client_class .= '<div class="' . $class . '">&nbsp;</div>';
						}
						
						//BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
						//USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
						$this->table->add_row($client_class,$client->ClientCode,
								$client->Name, $client->GroupName, (($client->Status) ? 'Active' : 'Disabled'),
								//IF THE USER HAS TO HAVE THE CORRECT PERMISSIONS TO VIEW A FEATURE
								(($this->CheckModule('Client_Edit')) ? form_open('clients/edit', $form_attr) . form_hidden('client_id', $client->ClientID) . form_submit($button) . form_close() : ''));
		
					}
				}else {
						//EACH FORM IS NAMED BY THE EDIT_{AGENCY_ID}, SAME CONCEPT WITH THE ID
						$form_attr = array(
							'name' => 'edit_' . $group->ClientID,
							'id' => 'edit_form_' . $group->ClientID,
						);
						
						//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
						$button = array(
							'name' => 'submit',
							'id' => 'client_id_' . $group->ClientID,
							'class' => 'basicBtn',
							'value' => 'Edit'
						);
						
						$group->ClassName = explode(',',$group->ClassName);
						$client_class = '';
						foreach($group->ClassName as $class) {
							$client_class .= '<div class="' . $class . '">&nbsp;</div>';
						}
						
						//BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
						//USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
						$this->table->add_row($client_class,$group->ClientCode,
								$group->Name, $group->GroupName, (($group->Status) ? 'Active' : 'Disabled'),
								//IF THE USER HAS TO HAVE THE CORRECT PERMISSIONS TO VIEW A FEATURE
								(($this->CheckModule('Client_Edit')) ? form_open('/clients/edit', $form_attr) . form_hidden('client_id', $group->ClientID) . form_submit($button) . form_close() : ''));
					
				}
				
			endforeach;
			
			//THE ADD AGENCY BUTTON
			$add_button = array(
				'class' => 'greenBtn floatRight button',
				'id' => 'add_client_btn',
				'href' => 'javascript:void(0)',
			);
	
			$this->table->set_template($tmpl);
	
			//This builds the pages html out. We do this here so all our listing pages can have the same template view.
			$html .= $this->table->generate() . "\n";
			$html .= '<div class="fix"></div>';
	
			//If the user has permission to add a new group, then show a button to do so.
			if ($this->CheckModule('Group_Add')) {
				$html .= anchor(base_url() . 'clients/add', 'Add Client', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_client_btn"');
			}
	
			$data = array(
				'page_id' => 'Clients',
				'page_html' => $html,
			);
			//LOAD THE TEMPLATE
			$this->LoadTemplate('pages/listings', $data);
		}
    }
	
	public function Add($msg=false) {
		$html = '';
		$tags = $this->administration->getAllTags();    
		if($msg AND $msg != 'error') {
			$html .= success_msg('Your client was successfully edited!');
		}elseif($msg AND $msg == 'error') {
			$html .= error_msg();
		}
		
		$data = array(
			'html' => $html,
			'tags' => $tags
		);

		$this->LoadTemplate('forms/form_addclients',$data);
	}
	
	public function Edit($msg=false) {
		$level = $this->user['DropdownDefault']->LevelType;
		
		if($level == 'g') {
			redirect('/groups/edit','refresh');	
		}
		
		if($level == 'a') {
			redirect('/agencies/edit','refresh');	
		}
	
		$html = '';
		
		if($msg AND $msg != 'error') {
			$html .= success_msg('Your client was successfully edited!');
		}elseif($msg AND $msg == 'error') {
			$html .= error_msg();
		}
	
		 //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		$client_id = ($this->input->post('client_id'))?$this->input->post('client_id'):$this->user['DropdownDefault']->SelectedClient;
		$this->load->model('administration');
		$client = $this->administration->getSelectedClient($client_id);
		$tags = $this->administration->getAllTags();    
		if($client) {
			$client->Address = (isset($client->Address)) ? mod_parser($client->Address) : false;
			$client->Phone = (isset($client->Phone)) ? mod_parser($client->Phone) : false;
			$client->Reviews = array(
				'Google'   => $this->administration->getSelectedClientsReviews($client_id,1)->URL,
				'GoogleID' => $this->administration->getSelectedClientsReviews($client_id,1)->ID,
				'Yelp'     => $this->administration->getSelectedClientsReviews($client_id,2)->URL,
				'YelpID'   => $this->administration->getSelectedClientsReviews($client_id,2)->ID,
				'Yahoo'    => $this->administration->getSelectedClientsReviews($client_id,3)->URL,
				'YahooID'  => $this->administration->getSelectedClientsReviews($client_id,3)->ID
			);
		}
		
		//print_object($this->administration->getSelectedClientsReviews($client_id,1));
		
		//print_object($group);
		//PREPARE THE VIEW FOR THE FORM
		$data = array(
			'client' => $client,
			'html' => $html,'tags'=>$tags
		);
		//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
	
		$this->LoadTemplate('forms/form_editclients',$data);
	}
	
	public function Delete($msg=false) {
		
	}

}
