<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends DOM_Controller {

	public $agency_id;
	public $group_id;
	public $client_id;

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
		
		if(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
		}
    }
	
	public function load_table($return = false) {
		$this->load->helper('template');
		$allowence = $this->CheckModule('Client_List');
		$html = '';
		if($allowence) : //the user has permission to view this
			$level = $this->user['DropdownDefault']->LevelType;
			$table = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
			$clients = $this->_getClientsByDropdownLevel($level);		
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
			$html .= '<style type="text/css">a.actions_link{margin-right:5px;td.actionsCol{width:75px !important;text-align:center;}</style>';
			$html .= '<script type="text/javascript" src="' . base_url() . 'assets/themes/itsbrain/js/client_popups.js"></script>';
			
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
			
			
			//THE ADD Clients BUTTON
			$add_button = array(
				'class' => 'greenBtn floatRight button',
				'id' => 'add_client_btn',
				'href' => 'javascript:void(0)',
			);
	
	
			//This builds the pages html out. We do this here so all our listing pages can have the same template view.
			$html .= '<div class="fix"></div>';
			
			//If the user has permission to add a new group, then show a button to do so.
			if ($this->CheckModule('Group_Add')) {
				$html .= '<a href="javascript:addClient();" class="greenBtn floatRight button" style="margin-top:10px;" id="add_client_btn">Add New Client</a>';
				//$html .= anchor(base_url() . 'clients/add', 'Add Client', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_client_btn"');
			}
			//you dont have permission to view this
		endif;
			
		
		if($return) {
			return $html;
		}else {
			echo $html;
		}

	}
	
	private function _getClientsByDropdownLevel($level) {
		switch($level) {
			case 1:
				return $this->administration->getAllClientsInAgency($this->agency_id);
			break;
			case 2:
				return $this->administration->getAllClientsInGroup($this->group_id);
			break;
			case 3:
				return $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient);
			break;
			case 'a':
				return $this->administration->getAllClientsInAgency($this->agency_id);
			break;
			case 'g':
				return $this->administration->getAllClientsInGroup($this->group_id);
			break;
			case 'c':
				return $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient);
			break;	
			default:
				return $this->administration->getAllClientsInAgency($this->agency_id);
			break;
		}
	}

    public function index() {
    	$this->load->helper('template');
		$html = $this->load_table(true);
		$data = array(
			'page_id' => 'Clients',
			'page_html' => $html,
			
		);
		//LOAD THE TEMPLATE
		$this->LoadTemplate('pages/client_listing', $data);
    }
	
	public function form() {
		//build the phone string
		$phone = 'main:' . $this->security->xss_clean($this->input->post('phone'));
		//build the address string
		$address = 'street:' . $this->security->xss_clean($this->input->post('street')) . ',city:' . $this->security->xss_clean($this->input->post('city')) . ',state:' . $this->security->xss_clean($this->input->post('state')) . ',zipcode:' . $this->security->xss_clean($this->input->post('zip'));
		
		$client_data = array(
			'CLIENT_Name'=>$this->security->xss_clean($this->input->post('ClientName')),
			'CLIENT_Address'=>$address,
			'CLIENT_Phone'=>$phone,
			'CLIENT_Notes'=>$this->security->xss_clean($this->input->post('Notes')),
			'CLIENT_Code'=>$this->security->xss_clean($this->input->post('ClientCode')),
			'CLIENT_Tag'=>$this->security->xss_clean($this->input->post('tags')),
			'CLIENT_ActiveTS'=>date(FULL_MILITARY_DATETIME),
		);
		
		$rep_data = array();
		
		if((isset($_POST['GoogleReviewURL'])) AND ($_POST['GoogleReviewURL'] != '')) {
			$google_group = array(
				'ServicesID'=>1,
				'URL'=>$this->security->xss_clean($this->input->post('GoogleReviewURL'))
			);
		}
		
		if((isset($_POST['YelpReviewURL'])) AND ($_POST['YelpReviewURL'] != '')) {
			$yelp_group = array(
				'ServicesID'=>2,
				'URL'=>$this->security->xss_clean($this->input->post('YelpReviewURL'))
			);
		}
		
		if((isset($_POST['YahooReviewURL'])) AND ($_POST['YahooReviewURL'] != '')) {
			$yahoo_group = array(
				'ServicesID'=>3,
				'URL'=>$this->security->xss_clean($This->input->post('YahooReviewURL'))
			);
		}
		
		if(isset($_POST['ClientID'])) { //if this is set, we know its the edit form
			if((isset($_POST['GoogleReviewURL'])) AND ($_POST['GoogleReviewURL'] != '')) {
				$google_group_add = array(
					'ID'=>$this->security->xss_clean($this->input->post('GoogleID')),
					'ClientID'=>$this->security->xss_clean($this->input->post('ClientID')),
				);
				$google_group = $google_group + $google_group_add;
				array_push($rep_data,$google_group);
			}
			
			if((isset($_POST['YelpReviewURL'])) AND ($_POST['YelpReviewURL'] != '')) {
				$yelp_group_add = array(
					'ID'=>$this->security->xss_clean($this->input->post('YelpID')),
					'ClientID'=>$this->security->xss_clean($this->input->post('ClientID')),
				);
				$yelp_group = $yelp_group + $yelp_group_add;
				array_push($rep_data,$yelp_group);
			}
			
			if((isset($_POST['YahooReviewURL'])) AND ($_POST['YahooReviewURL'] != '')) {
				$yahoo_group_add = array(
					'ID'=>$this->security->xss_clean($this->input->post('YahooID')),
					'ClientID'=>$this->security->xss_clean($this->input->post('ClientID')),
				);
				$yahoo_group = $yahoo_group + $yahoo_group_add;
				array_push($rep_data,$yahoo_group);
			}
			
			$update_client = $this->administration->updateClient($this->input->post('ClientID'),$client_data);
			
			if($update_client) {
				if(count($rep_data) > 0) {
					$update_reputations = $this->administration->updateReputations($rep_data);
					if($update_reputations) {
						echo '1';	
					}else {
						echo '0';	
					}
				}else {
					echo '1';	
				}
			}else {
				echo '0';	
			}
			
			
		}elseif(isset($_GET['gid'])) { //were adding new client here
			
			$add_client_data = array(
				'GROUP_ID' => $this->user['DropdownDefault']->SelectedGroup,
				'CLIENT_Active' => $this->security->xss_clean($this->input->post('Status')),
				'CLIENT_Created'=>date(FULL_MILITARY_DATETIME),
			);
			
			//merge add fields to array
			$client_info = $client_data + $add_client_data;
			$client = $this->administration->addClient($client_info);
			if($client) {
				if(isset($google_group) OR isset($yelp_group) OR isset($yahoo_group)) {
					$client_id = $client;
					$rep_push = array(
						'ClientID'=>$client_id
					);
					
					if(isset($google_group)) {
						if(count($google_group) > 0) {
							$google_group = $google_group + $rep_push;
							array_push($rep_data,$google_group);
						}
					}
					
					if(isset($yelp_group)) {
						if(count($yelp_group) > 0) {
							$yelp_group = $yelp_group + $rep_push;
							array_push($rep_data,$yelp_group);
						}
					}
					
					if(isset($yahoo_group)) {
						if(count($yahoo_group) > 0) {
							$yahoo_group = $yahoo_group + $rep_push;
							array_push($rep_data,$yahoo_group);
						}
					}
					
					if(count($rep_data) > 0) {
						$add_rep = $this->administration->addReputation($rep_data);
						if($add_rep) {
							echo '1';	
						}else {
							echo '0';	
						}
					}else {
						echo '1';	
					}
				}else {
					echo '1';	
				}
				
			}else {
				echo '1';	
			}
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
    
    public function add_form() {
      $tags = $this->administration->getAllTags();
      $data = array(
          'client'=>false,
          'tags'=>$tags
      );
      $this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editclients',$data);
    }
	

	
	public function Edit() {
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
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
				'Google'   => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->URL : FALSE,
				'GoogleID' => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->ID  : FALSE,
				'Yelp'     => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->URL : FALSE,
				'YelpID'   => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->ID  : FALSE,
				'Yahoo'    => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->URL : FALSE,
				'YahooID'  => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->ID  : FALSE
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
			print 0;
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
		$this->load->model('administration');
		$client_id = $_GET['cid'];
		$client          = $this->administration->getSelectedClient($_GET['cid']);
		$client->Name    = $client->Name;
		$client->Address = (isset($client->Address)) ? mod_parser($client->Address) : false;
		$client->Phone   = (isset($client->Phone)) ? mod_parser($client->Phone) : false;
		$client->Reviews = array(
			'Google'   => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->URL : FALSE,
			'GoogleID' => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->ID  : FALSE,
			'Yelp'     => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->URL : FALSE,
			'YelpID'   => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->ID  : FALSE,
			'Yahoo'    => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->URL : FALSE,
			'YahooID'  => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->ID  : FALSE
		);
		
		$data = array(
			'client'   => $client,
			'websites' => load_client_websites($this->client_id,false),
			//'contacts' => load_client_contacts($this->client_id),
			//'users'    => load_client_related_users($this->client_id),
		);
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/view_client',$data);
	}

}
