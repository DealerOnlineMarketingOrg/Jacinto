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

    public function index() {
    	$this->load->helper('template');
		$permissions = $this->CheckModule('Client_List');
		
		$table = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
		
		if (!$permissions) {
			$this->AccessDenied();
		} else {
			$level = $this->user['DropdownDefault']->LevelType;
			$permission_level = $this->user['DropdownDefault']->PermLevel;

			switch($level) {
				case 1:
					$clients = $this->administration->getAllClientsInAgency($this->agency_id);
				break;
				case 2:
					$clients = $this->administration->getAllClientsInGroup($this->group_id);
				break;
				case 3:
					$clients = $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient);
				break;
				case 'a':
					$clients = $this->administration->getAllClientsInAgency($this->agency_id);
				break;
				case 'g':
					$clients = $this->administration->getAllClientsInGroup($this->group_id);
				break;
				case 'c':
					$clients = $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient);
				break;	
			}
			
			
				$table .= "<thead>" . "\n" . 
							"\t" . '<tr>' . "\n" . 
							"\t\t" . "<th>Tag</th>" . "\n" . 
							"\t\t" . "<th>Code</th>" . "\n" . 
							"\t\t" . "<th>Dealership</th>" . "\n" .
							"\t\t" . "<th>Group</th>" . "\n" .
							"\t\t" . "<th>Status</th>" . "\n" .
							"\t\t" . "<th>Actions</th>" . "\n" .
							"\t" . "</tr>" . "\n" .
						  "</thead>" . "\n";
				$table .= '<tbody>' . "\n";
	
			$html = '';
			$html .= '<style type="text/css">a.actions_link{margin-right:5px;td.actionsCol{width:75px !important;text-align:center;}</style>';
			$html .= '<script type="text/javascript" src="' . base_url() . 'assets/themes/itsbrain/js/client_popups.js"></script>';
			//LOOP THROUGH EACH AGENCY AND CREATE A FORM BUTTON "EDIT" AND ROW FOR IT.
			foreach ($clients as $client) :
				
				//EACH FORM IS NAMED BY THE EDIT_{AGENCY_ID}, SAME CONCEPT WITH THE ID
				$form_attr = array(
					'name' => 'edit_' . $client->ClientID,
					'id' => 'edit_form_' . $client->ClientID,
					'class' => 'actions'
				);
				
				//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
				$button = array(
					'name' => 'submit',
					'id' => 'client_id_' . $client->ClientID,
					'class' => 'basicBtn',
					'value' => 'Edit'
				);
				
				$warning  = '<img src="' . base_url() . 'assets/themes/global/imgs/icons/notifications/error.png"  title="Disabled" alt="Disabled" />';
				$active   = '<img src="' . base_url() . 'assets/themes/global/imgs/icons/notifications/accept.png" title="Active" alt="Active"   />';
				$disabled = '<img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Client Is Disabled" />';
				
				//edit button
				$edit_img = '<img src="' . base_url() . THEMEIMGS . 'icons/dark/pencil.png" alt="Edit Client" />';
				$edit_a = '<a class="actions_link" href="javascript:editClient(\'' . $client->ClientID . '\',\'' . base_url() . '\');" title="Edit Client">' . $edit_img . '</a>';
				//view button
				$view_img = '<img src="' . base_url() . THEMEIMGS . 'icons/color/application.png" alt="View Client Information" />';
				$view_a = '<a class="actions_link" href="javascript:viewClient(\'' . $client->ClientID . '\');" title="View Client Information">' . $view_img . '</a>';
				//disable button
				$disable_img = '<img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable Client" />';
				$disable_a = '<a class="actions_link" href="javascript:disableClient(\'' . $client->ClientID . '\');" title="Disable Client">' . $disable_img . '</a>';
				//enable button
				$enable_img = '<img src="' . base_url() . THEMEIMGS . 'icons/notifications/accept.png" alt="Enable Client" />';
				$enable_a = '<a class="actions_link" href="javascript:enableClient(\'' . $client->ClientID . '\');" title="Enable Client">' . $enable_img . '</a>';
								
				$edit_button = '<input data-client="' . $client->ClientID . '" title="Edit Client Information" type="image" src="' . base_url() . 'assets/themes/global/imgs/icons/color/pencil.png" name="edit_' . $client->ClientID . '" id="client_id_' . $client->ClientID . '" class="imageBtn editBtn" />';
				
				$view_button = '<input data-client="' . $client->ClientID . '" style="margin-left:10px;" title="View Client Information" type="image" src="' . base_url() . 'assets/themes/global/imgs/icons/color/application.png" name="view_' . $client->ClientID . '" id="viewClient_id_' . $client->ClientID . '" class="imageBtn viewBtn" />';
				
				$delete_button = '<input data-client="' . $client->ClientID . '" style="margin-left:10px;" title="Disable Client" type="image" src="' . base_url() . 'assets/themes/global/imgs/icons/color/cross.png" name="disable_' . $client->ClientID . '" id="disableClient_id_' . $client->ClientID . '" class="imageBtn disableBtn" />';
				
				//BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
				//USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.	
				$table .= '<tr class="tagElement ' . $client->ClassName . '">' . "\n";
				$table .= "\t" . '<td class="tags"><div class="' . $client->ClassName . '">&nbsp;</div></td>' . "\n";
				$table .= "\t" . '<td class="clientCode" style="width:45px;">' . $client->ClientCode . '</td>' . "\n";
				$table .= "\t" . '<td>' . $client->Name . '</td>' . "\n";
				$table .= "\t" . '<td>' . $client->GroupName . '</td>' . "\n";
				$table .= "\t" . '<td class="status" style="width:30px;text-align:center">' . (($client->Status) ? $active : $disabled) . '</td>' . "\n";
				$table .= '<td class="actionsCol" style="width:75px;text-align:center">';
				
				//put allowed action buttons in place
				if($this->CheckModule('Client_Edit')) {
					$table .= $edit_a;
				}
				$table .= $view_a;
				if($this->CheckModule('Client_Disable_Enable')) {
					if($client->Status) {
						$table .= $disable_a;
					}else {
						$table .= $enable_a;
					}
				}
				
				
				$table .= '</td>';
				$table .= '</tr>' . "\n";
			endforeach;
			
			$table .= '</tbody></table><div id="editClientInfo"></div><div id="viewClientInfo"></div><div id="addWebsite"></div><div id="editWebsite"></div>' . "\n";
			$html .= $table;
			
			
			//THE ADD AGENCY BUTTON
			$add_button = array(
				'class' => 'greenBtn floatRight button',
				'id' => 'add_client_btn',
				'href' => 'javascript:void(0)',
			);
	
	
			//This builds the pages html out. We do this here so all our listing pages can have the same template view.
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
	
	public function Add() {
		$html = '';
		$tags = $this->administration->getAllTags();  
		
		$data = array(
			'html' => $html,
			'tags' => $tags
		);

		$this->LoadTemplate('forms/form_addclients',$data);
	}
	

	
	public function Edit() {
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
		/*
		if($level == 'g') {
			redirect('/groups/edit','refresh');	
		}
		
		if($level == 'a') {
			redirect('/agencies/edit','refresh');	
		}
		*/
		
		$html = '';
	
		if(isset($_POST['client_id'])) {
			$client_id = $this->input->post('client_id');
		}elseif(isset($_GET['cid'])) {
			$client_id = $this->input->get('cid');
		}else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;
		}
	
		 //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		//$client_id = ($this->input->post('client_id'))?$this->input->post('client_id'):$this->user['DropdownDefault']->SelectedClient;
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
			$data = array(
				'client' => $client,
				'html' => $html,
				'tags'=>$tags,
				'websites'=>load_client_websites($client_id)
			);
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
		
			$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editclients',$data);
			
		}else {
			//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
			echo 0;
		}
	}
	
	public function Disable($cid) {
		if(isset($_GET['cid'])) {
			$client_id = $_GET['cid'];
		}elseif(isset($_POST['cid'])) {
			$client_id = $_POST['cid'];	
		}else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;	
		}
		
		//load administration model
		$this->load->model('administration');
		
		$disable = $this->administration->changeClientStatus($client_id,0);
		
		if($disable) {
			echo 1;	
		}else {
			echo 0;	
		}
	}
	
	public function Enable($cid) {
		if(isset($_GET['cid'])) {
			$client_id = $_GET['cid'];
		}elseif(isset($_POST['cid'])) {
			$client_id = $_POST['cid'];	
		}else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;	
		}
		
		//load administration model
		$this->load->model('administration');
		
		$disable = $this->administration->changeClientStatus($client_id,1);
		
		if($disable) {
			echo 1;	
		}else {
			echo 0;	
		}
	}
	
	public function View() {
		$client = $this->administration->getClient($this->input->post('view_id'));
		
		$client->Name = $client->Name;
		$client->Address = (isset($client->Address)) ? mod_parser($client->Address) : false;
		$client->Phone = (isset($client->Phone)) ? mod_parser($client->Phone) : false;
		$client->Notes = $client->Notes;
		$data = array(
			'display'=>$client
		);
		$this->LoadTemplate('pages/details_client',$data);
	}

}
