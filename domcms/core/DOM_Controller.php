<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
		This is our main controller
		This controller checks the user credentials.
		If the users credentials do not pass it sends them to the login page.
	 */
	session_start();
	class DOM_Controller extends CI_Controller {
		//All we need is the construct and all controllers will pass through this on every page.
		
		public $user;
		public $LevelView;
		public $man_nav;
		public $user_nav;
		public $avatar;
		public $selected_agency;
		public $selected_group;
		public $selected_client;
		public $theme_settings;
		public $validser;
		
		public function __construct() {
			parent::__construct();
			$this->load->helper('template');
			$this->load->library('gravatar');
			$this->load->helper('template');
			$this->load->model('mods');
			
			$this->theme_settings = array(
				'ThemeDir' 	=> $this->config->item('ThemeDir'),
				'GlobalDir' 	=> $this->config->item('GlobalDir'),
				'Title'			=> $this->config->item('Title'),
				'CompanyName' 	=> $this->config->item('CompanyName'),
				'AppName' 		=> $this->config->item('AppName'),
				'Logo'			=> '<img src="' . base_url() . $this->config->item('GLobalDir') . '/' . $this->config->item('Logo') . '" alt="' . $this->config->item('CompanyName') . '" />',
				'AppVersion' 	=> $this->config->item('AppVersion'),
				'GapiEmail' 	=> $this->config->item('GapiEmail'),
				'GapiPass'		=> $this->config->item('GapiPass'),
				'GoogleFonts' 	=> $this->config->item('GoogleFonts'),
				'DocType' 		=> $this->config->item('DocType'),
				'HTML'			=> $this->config->item('HTML'),
				'MetaTags'  	=> $this->config->item('MetaTags'),
				'Files'			=> $this->config->item('Files'),
				'Breadcrums'	=> $this->config->item('Breadcrumbs')
			);
						
			//Active button sets the highlighted icon on the view
			$active_button = $this->router->fetch_class();
			$current_subnav_button = $this->uri->rsegment(2); // The Function 
			
			define('ACTIVE_BUTTON',$active_button);
			define('SUBNAV_BUTTON','/' . $active_button  . '/' . $current_subnav_button);
			
			$this->user   = $this->session->userdata('valid_user');
			$this->avatar = $this->gravatar->get_gravatar((($this->user['Gravatar']) ? $this->user['Gravatar'] : $this->user['Username']));
						
			//This checks the user validation
			$this->validUser = ($this->session->userdata('valid_user')) ? TRUE : FALSE;
			if(!$this->validUser) redirect('login');
			
			$this->selected_agency = (($this->user['DropdownDefault']->SelectedAgency) ? $this->user['DropdownDefault']->SelectedAgency : $this->user['AgencyID']);
			$this->selected_group = (($this->user['DropdownDefault']->SelectedGroup) ? $this->user['DropdownDefault']->SelectedGroup : $this->user['GroupID']);
			$this->selected_client = (($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : $this->user['ClientID']);
			
			$this->load->model('nav');
			$this->main_nav = $this->nav->main($this->user['DropdownDefault']->PermLevel);
			$this->user_nav = $this->nav->user($this->user['AccessLevel']);
			
			//print_object($this->user['DropdownDefault']);
		}
		
		public function LoadTemplate($filepath,$data = false, $header_data = false, $nav_data = false, $footer_data = false) {
			
			$nav = array(
				'nav' => $this->main_nav,
			);
			
			$user_nav = array(
				'nav' => $this->user_nav,
				'user' => $this->user,
				'avatar' => $this->avatar,
			);
			$this->load->view($this->theme_settings['GlobalDir'] . '/incl/header', $this->theme_settings);
			$this->load->view($this->theme_settings['GlobalDir'] . '/incl/user_nav', $user_nav);
			$this->load->view($this->theme_settings['ThemeDir']  . '/incl/header',($header_data) ? $header_data : array());
			$this->load->view($this->theme_settings['GlobalDir'] . '/incl/nav', ($nav) ? $nav : array());
			$this->load->view($this->theme_settings['ThemeDir'] . '/' . $filepath, ($data) ? $data : array());
			$this->load->view($this->theme_settings['ThemeDir'] . '/incl/footer',($footer_data) ? $footer_data : array());
			$this->load->view($this->theme_settings['GlobalDir'] . '/incl/footer');
		}
		
		public function generateLevelName($level) {
			switch($level) {
				case 'g':
					return "<h2>Group Name:</h2>";
				break;
				case 'c':
					return "<h2>Client Name:</h2>";
				break;
				
				case 'a':
					return "<h2>Agency Name:</h2>";
				break;
				default:
					return "<h2>Agency Name:</h2>";
				break;	
			};			
		}
		
		//Gets the access level of the user.
		public function generateLevelNumber($level) {
			$loggedInUserLevel = $this->user['AccessLevel'];
			if($loggedInUserLevel >= 600000) {
				switch($level) {
					case 'g' :
						return 400000;
					break;
					case 'c' :
						return 300000;
					break;
					case 2 :
						return 400000;
					break;
					case 3 :
						return 300000;
					break;
					default :
						return 600000;
					break;
				}
			}else if($loggedInUserLevel < 600000 AND $loggedInUserLevel >= 500000) {
				switch($level) {
					case 'g' :
						return 400000;
					break;
					case 'c' :
						return 300000;
					break;
					case 2:
						return 400000;
					break;
					case 3:
						return 300000;
					break;
					default :
						return 500000;
					break;	
				}
			}else if($loggedInUserLevel < 500000 AND $loggedInUserLevel >= 400000) {
				switch($level) {
					case 'c':
						return 300000;
					break;
					case 3:
						return 300000;
					break;
					default :
						return 400000;
					break;	
				}
			}else if($loggedInUserLevel < 400000 AND $loggedInUserLevel >= 300000) {
				switch($level) {
					default:
						return 300000;
					break;	
				}
			}else if($loggedInUserLevel < 300000 AND $loggedInUserLevel >= 200000) {
				switch($level) {
					default:
						return 200000;
					break;	
				}
			}else {
				return 100000;	
			}
		}
		
		//This checks to see if the user has permissions to the specific module
		public function CheckModule($module_name = false) {
			$this->load->model('mods');
			$user_level = $this->user['DropdownDefault']->PermLevel;
			$permission = $this->mods->getModLevelByName($module_name);
			if(!$permission) {
				return FALSE;	
			}else {
				if($user_level >= $permission->Level) {
					return TRUE;	
				}else {
					return FALSE;	
				}
			}
		}
		
		//custom 404 page
		public function Page_Not_Found() {
			$this->LoadTemplate('pages/404');
		}
		
		//Access Denied
		public function AccessDenied() {
			$this->LoadTemplate('pages/access_denied');
		}
		
		//This tells me what level the user is currently logged in as.
		public function DisplaySettings() {
			$display_session = $this->user['DropdownDefault'];
			$level = substr($display_session->SelectedID, 0, 1);
			//Readable way to tell what level were on.
			if($level == 'a') {
				$this->LevelView = 'Agency';	
			}elseif($level == 'g') {
				$this->LevelView = 'Group';	
			}elseif($level == 'c') {
				$this->LevelView = 'Client';	
			}else {
				//if super admin
				$this->LevelView = 'Agency';
				//if group admin group level
			}
		}
		
		public function Form_processor($page, $which) {
			switch($page) :
				case "agency":
					switch($which):
						case "add":
							//create array from port post elements
							$form = $this->input->post();
							$add = $this->administration->addAgencies($form);
							if($add) {
								redirect('/admin/agency/listing/success','location');
							}else {
								redirect('/admin/agency/add/error', 'location');	
							}
						break;
						case "edit":
							$form = $this->input->post();
							$edit = $this->administration->editAgency($form);
							if($edit) {
								redirect('/admin/agency/listing/success','location');	
							}else {
								redirect('/admin/agency/edit/error','location');	
							}
						break;
						case "disable":
							//disable
						break;
					endswitch;
				break;
				case "users":
					switch($which) :
						case "add":
							//add users
							$form = $this->input->post();
							print_object($form); 
							/*
							$add = $this->administration->addAgencies($form);
							if($add) {
								redirect('/admin/users/listing/success','location');	
							}else {
								redirect('/admin/users/add/error','location');
							}*/
						break;
					endswitch;
				break;
			
			endswitch;
		}
	}
