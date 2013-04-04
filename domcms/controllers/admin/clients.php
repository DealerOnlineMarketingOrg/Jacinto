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
		$html = '';
		$level = $this->user['DropdownDefault']->LevelType;
		$clients = $this->_getClientsByDropdownLevel($level);		
		
		$data = array(
			'clients'=>$clients
		);
		
		$this->load->view($this->theme_settings['ThemeDir'] . '/pages/client_listing_table',$data);
	}
	
	private function _getClientsByDropdownLevel($level = false) {
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
		$level = $this->user['DropdownDefault']->LevelType;
		$clients = $this->_getClientsByDropdownLevel($level);		

		$data = array(
			'clients'=>$clients			
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
