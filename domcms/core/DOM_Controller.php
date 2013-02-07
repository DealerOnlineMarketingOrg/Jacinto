<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
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
	public $TagCss;
	public $TagHTML;

    public function __construct() {
        parent::__construct();
        $this->load->helper('template');
        $this->load->library('gravatar');
        $this->load->helper('template');
        $this->load->model('mods');
		$this->load->model('utilities');
		
		$this->TagCss = $this->utilities->getTagCss();
		$this->TagHTML = $this->utilities->getTagSelect();

        $this->theme_settings = array(
            'ThemeDir' => $this->config->item('ThemeDir'),
            'GlobalDir' => $this->config->item('GlobalDir'),
            'Title' => $this->config->item('Title'),
            'CompanyName' => $this->config->item('CompanyName'),
            'AppName' => $this->config->item('AppName'),
            'Logo' => '<img src="' . base_url() . $this->config->item('GLobalDir') . '/' . $this->config->item('Logo') . '" alt="' . $this->config->item('CompanyName') . '" />',
            'AppVersion' => $this->config->item('AppVersion'),
            'GapiEmail' => $this->config->item('GapiEmail'),
            'GapiPass' => $this->config->item('GapiPass'),
            'GoogleFonts' => $this->config->item('GoogleFonts'),
            'DocType' => $this->config->item('DocType'),
            'HTML' => $this->config->item('HTML'),
            'MetaTags' => $this->config->item('MetaTags'),
            'Files' => $this->config->item('Files'),
            'Breadcrums' => $this->config->item('Breadcrumbs'),
			'TagCss' => $this->TagCss
        );

        //Active button sets the highlighted icon on the view
        $active_button = $this->router->fetch_class();
        $current_subnav_button = $this->uri->rsegment(2); // The Function 
        define('ACTIVE_BUTTON', $active_button);
        define('SUBNAV_BUTTON', '/' . $active_button . '/' . $current_subnav_button);

        $this->user = $this->session->userdata('valid_user');
        $this->avatar = $this->gravatar->get_gravatar((($this->user['Gravatar']) ? $this->user['Gravatar'] : $this->user['Username']));

        //This checks the user validation
        $this->validUser = ($this->session->userdata('valid_user')) ? TRUE : FALSE;
        if (!$this->validUser)
            redirect('login');

        $this->user['DropdownDefault']->SelectedAgency = (($this->user['DropdownDefault']->SelectedAgency) ? $this->user['DropdownDefault']->SelectedAgency : $this->user['AgencyID']);
        $this->user['DropdownDefault']->SelectedGroup = (($this->user['DropdownDefault']->SelectedGroup) ? $this->user['DropdownDefault']->SelectedGroup : $this->user['GroupID']);
        $this->user['DropdownDefault']->SelectedClient = (($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : $this->user['ClientID']);

        $this->session->sess_write();
		
        $this->load->model('nav');
        $this->main_nav = $this->nav->main($this->user['DropdownDefault']->PermLevel);
		//print_object($this->main_nav);
        //$this->user_nav = $this->nav->user($this->user['AccessLevel']);
        //print_object($this->user['DropdownDefault']);
    }

    public function LoadTemplate($filepath, $data = false, $header_data = false, $nav_data = false, $footer_data = false) {
		
        $nav = array(
            'nav' => $this->main_nav,
        );
        $user_nav = array(
            'nav' => $this->user_nav,
            'user' => $this->user,
            'avatar' => $this->avatar,
        );
		
		$header_data = array(
			'tagHtml' => $this->TagHTML
		);
		
        $this->load->view($this->theme_settings['GlobalDir'] . '/incl/header', $this->theme_settings);
        $this->load->view($this->theme_settings['GlobalDir'] . '/incl/user_nav', $user_nav);
        $this->load->view($this->theme_settings['ThemeDir'] . '/incl/header', ($header_data) ? $header_data : array());
        $this->load->view($this->theme_settings['GlobalDir'] . '/incl/nav', $nav);
        $this->load->view($this->theme_settings['ThemeDir'] . '/' . $filepath, ($data) ? $data : array());
		$this->load->view('themes/itsbrain/forms/form_addtags');
        $this->load->view($this->theme_settings['ThemeDir'] . '/incl/footer', ($footer_data) ? $footer_data : array());
        $this->load->view($this->theme_settings['GlobalDir'] . '/incl/footer');
    }

    public function generateLevelName($level) {
        switch ($level) {
            case 'g':
                return '<span class="title">Group Name:</span>';
                break;
            case 'c':
                return '<span class="title">Client Name:</span>';
                break;

            case 'a':
                return '<span class="title">Agency Name:</span>';
                break;
            default:
                return '<span class="title">Agency Name:</span>';
                break;
        };
    }

    //Gets the access level of the user.
    public function generateLevelNumber($level) {
        $loggedInUserLevel = $this->user['AccessLevel'];
        if ($loggedInUserLevel >= 600000) {
            switch ($level) {
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
        } else if ($loggedInUserLevel < 600000 AND $loggedInUserLevel >= 500000) {
            switch ($level) {
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
        } else if ($loggedInUserLevel < 500000 AND $loggedInUserLevel >= 400000) {
            switch ($level) {
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
        } else if ($loggedInUserLevel < 400000 AND $loggedInUserLevel >= 300000) {
            switch ($level) {
                default:
                    return 300000;
                    break;
            }
        } else if ($loggedInUserLevel < 300000 AND $loggedInUserLevel >= 200000) {
            switch ($level) {
                default:
                    return 200000;
                    break;
            }
        } else {
            return 100000;
        }
    }

    //This checks to see if the user has permissions to the specific module
    public function CheckModule($module_name = false) {
        $this->load->model('mods');
        $user_level = $this->user['DropdownDefault']->PermLevel;
        $permission = $this->mods->getModLevelByName($module_name);
        if (!$permission) {
            return FALSE;
        } else {
            if ($user_level >= $permission->Level) {
                return TRUE;
            } else {
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
        if ($level == 'a') {
            $this->LevelView = 'Agency';
        } elseif ($level == 'g') {
            $this->LevelView = 'Group';
        } elseif ($level == 'c') {
            $this->LevelView = 'Client';
        } else {
            //if super admin
            $this->LevelView = 'Agency';
            //if group admin group level
        }
    }

    public function Form_processor($page, $which) {
        $this->load->helper('template');
        $this->load->model('members');
        $this->load->model('administration');
        switch ($page) :
			case "clients":
							
				switch($which) :
					case "add":
					
						$form = $this->input->post();
						$data = array(
							'GROUP_ID' => (($this->user['DropdownDefault']->SelectedGroup) ? $this->user['DropdownDefault']->SelectedGroup : 1),
							'CLIENT_Name' => $form['ClientName'],
							'CLIENT_Address' => 'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zip'],
							'CLIENT_Phone' => 'main:' . $form['phone'],
							'CLIENT_Notes' => $form['Notes'],
							'CLIENT_Code' => $form['ClientCode'],
							'CLIENT_Active' => $form['Status'],
							'CLIENT_Created' => date('Y-m-d H:i:s'),
							'CLIENT_Tag' => $form['tags']
						);
					
						$addClient = $this->administration->addClient($data);
						
						if($addClient) {
							redirect('/admin/clients/add/success','refresh');	
						}else {
							redirect('/admin/clients/add/error','refresh');	
						}
					break;
					case "edit":
						
						$form = $this->input->post();
						$data = array(
							'GROUP_ID' => (($this->user['DropdownDefault']->SelectedGroup) ? $this->user['DropdownDefault']->SelectedGroup : 1),
							'CLIENT_Name' => $form['ClientName'],
							'CLIENT_Address' => 'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zip'],
							'CLIENT_Phone' => 'main:' . $form['phone'],
							'CLIENT_Notes' => $form['Notes'],
							'CLIENT_Code' => $form['ClientCode'],
							'CLIENT_Active' => $form['Status'],
							'CLIENT_Created' => date('Y-m-d H:i:s')
						);
						
						$updateClient = $this->administration->editClient($data, $this->input->post('ClientID'));
						if($updateClient) {
							if($form['Status'] == '0') {
								$this->reset_dd_session('/admin/clients/success');
							}else {
								redirect('/admin/clients/success','refresh');	
							}
						}else {
							redirect('/admin/clients/error','refresh');	
						}
						
					break;
				endswitch;
			break;
			case "groups":
				switch($which):
					case "add":
						$form = $this->input->post();
						$selectedAgencyId = $this->user['DropdownDefault']->SelectedAgency;
						$group_name = $form['GroupName'];
						$group_notes = $form['Description'];
						$group_status = $form['Status'];
						
						$data = array(
							'GROUP_Name' => $group_name,
							'GROUP_Notes' => $group_notes,
							'AGENCY_ID' => $selectedAgencyId,
							'GROUP_Active' => $group_status,
							'GROUP_Created' => date('Y-m-d H:i:s'),
						);
						
						//print_object($data);
						$add = $this->administration->addGroup($data);
						
						if($add) {
							redirect('/admin/groups/add/success','refresh');	
						}else {
							redirect('/admin/groups/add/error','refresh');	
						}
					break;
					case "edit":
						$form = $this->input->post();
						$selectedAgencyId = $this->user['DropdownDefault']->SelectedAgency;
						$group_id = $form['GroupID'];
						$group_name = $form['GroupName'];
						$group_notes = $form['Description'];
						$group_status = $form['Status'];
						
						$data = array(
							'GROUP_ID' => $group_id,
							'GROUP_Name' => $group_name,
							'GROUP_Notes' => $group_notes,
							'AGENCY_ID' => $selectedAgencyId,
							'GROUP_Active' => $group_status,
							'GROUP_Created' => date('Y-m-d H:i:s'),
						);
						
						//print_object($data);
						$edit = $this->administration->editGroup($data);
						
						if($edit) {
							if($group_status == '0') {
								$this->reset_dd_session('/admin/groups/success');
							}else {
								redirect('/admin/groups/success','refresh');
							}
						}else {
							redirect('/admin/groups/error','refresh');	
						}
					break;
				endswitch;
			break;
            case "contacts":
                switch($which) :
                    case "add":
                        $form = $this->input->post();
                        $type = $form['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
                        $firstname = $form['firstname'];
                        $lastname =  $form['lastname'];
                        $address = 'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zip'];
                        $notes = $form['notes'];
						
						$email  = 'home:' . $this->input->post('PersonalEmailAddress') . 
						  (($this->input->post('WorkEmailAddress')) ? 
							',work:' . $this->input->post('WorkEmailAddress') : 
						  '');
						  
						$phone  = 'main:' . $this->input->post('DirectPhone') . (($this->input->post('MobilePhone')) ? ',mobile:' . $this->input->post('MobilePhone') : '') . (($this->input->post('FaxPhone')) ? ',fax:' . $this->input->post('FaxPhone') : '');

                        
                        $data = array(
                            'TITLE_ID' => 12,
                            'DIRECTORY_Type' => $type,
                            'DIRECTORY_FirstName' => $firstname,
                            'DIRECTORY_LastName' => $lastname,
                            'DIRECTORY_Address' => $address,
                            'DIRECTORY_Email' => $email,
                            'DIRECTORY_Phone' => $phone,
                            'DIRECTORY_Notes' => $notes,
                            'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME)
                        );
                        
                        $addContact = $this->administration->addContact($data);
                        
                        if($addContact) {
                            redirect('/admin/contacts/success','refresh');
                        }else {
                            redirect('/admin/contacts/error','refresh');
                        }

                         
                    break;
					case "edit":
                        $form = $this->input->post();

						$email = 'home:' . $this->input->post('PersonalEmailAddress') . (($this->input->post('WorkEmailAddress')) ? ',work:' . $this->input->post('WorkEmailAddress') : '');
						$phone = 'main:' . $this->input->post('DirectPhone') . (($this->input->post('MobilePhone')) ? ',mobile:' . $this->input->post('MobilePhone') : '') . (($this->input->post('FaxPhone')) ? ',fax:' . $this->input->post('FaxPhone') : '');
                        $type = $form['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
                        $firstname = $form['firstname'];
                        $lastname =  $form['lastname'];
                        $address = 'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zip'];
                        $notes = $form['notes'];
                        
                        $data = array(
                            'TITLE_ID' => 12,
                            'DIRECTORY_Type' => $type,
                            'DIRECTORY_FirstName' => $firstname,
                            'DIRECTORY_LastName' => $lastname,
                            'DIRECTORY_Address' => $address,
                            'DIRECTORY_Email' => $email,
                            'DIRECTORY_Phone' => $phone,
                            'DIRECTORY_Notes' => $notes,
                            'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME)
                        );
                        
                        //print_object($data);
                        
                        $addContact = $this->administration->updateContact($data, $this->input->post('contact_id'));
                        
                        if($addContact) {
                            redirect('/admin/contacts/add/success','refresh');
                        }else {
                            redirect('/admin/contacts/add/error','refresh');
                        }

                         
					break;
                endswitch;
            break;
            case "agency":
                switch ($which):
                    case "add":
                        //create array from port post elements
                        $form = $this->input->post();
                        $add = $this->administration->addAgencies($form);

                        //print_object($form);

                        //print_object($add);

                        /*

                          if($add) {
                          redirect('/admin/agency/listing/success','location');
                          }else {
                          redirect('/admin/agency/add/error', 'location');
                          }

                         */
                        break;
                    case "edit":
                        $form = $this->input->post();

                        $id = $form['AGENCY_ID'];
                        $data = array(
                            'AGENCY_Name' => $form['AGENCY_Name'],
                            'AGENCY_Notes' => $form['AGENCY_Notes'],
                            'AGENCY_Active' => $form['AGENCY_Active']
                                //'AGENCY_Created' => $form['AGENCY_Created']
                        );

                        $edit = $this->administration->updateAgencyInformation($id, $data);

                        if (!$edit) {
                            redirect('/admin/agency/listing/success', 'location');
                        } else {
                            redirect('/admin/agency/edit/error', 'location');
                        }
                        break;
                        
                    case "disable":
                        //disable
                        break;
                endswitch;
                break;
            case "users":
                switch ($which) :
                    case "add":
						$email                  = 'home:' . $this->input->post('PersonalEmailAddress') . 
												  (($this->input->post('WorkEmailAddress')) ? 
												  	',work:' . $this->input->post('WorkEmailAddress') : 
												  '');
												  
						$first_name             = $this->input->post('FirstName');
						$last_name              = $this->input->post('LastName');
						$address                = 'street:' . $this->input->post('Street') . ',city:' . $this->input->post('City') . ',state:' . $this->input->post('State') . ",zipcode:" . $this->input->post('ZipCode');
						$username               = $this->input->post('PersonalEmailAddress');
						
						$phone                  = 'main:' . $this->input->post('DirectPhone') . 
												  (($this->input->post('MobilePhone')) ? 
												  	',mobile:' . $this->input->post('MobilePhone') : '') . 
												  (($this->input->post('FaxPhone')) ? 
												  	',fax:' . $this->input->post('FaxPhone') : '');
						
						$accessID               = $this->input->post('AccessLevel');
						$status                 = $this->input->post('Status');
						$generated_password     = createRandomString(8,'ALPHANUMSYM');
				
                        //add users
                        $user_generated = '0';
                        $created = date(FULL_MILITARY_DATETIME);
                        
                        $usersTable = array(
                          'USER_Name'               => $this->input->post('Username')  
                        );
                        
                        $addUser = $this->members->addUsers($usersTable);
                        
                        if($addUser) {
                            
                            if($this->user['DropdownDefault']->LevelType == 'a') {
                                $type = 'AID:' . $this->user['DropdownDefault']->SelectedAgency;
								$selected_id = $this->user['DropdownDefault']->SelectedAgency;
                            }elseif($this->user['DropdownDefault']->LevelType == 'g') {
                                $type = 'GID:' . $this->user['DropdownDefault']->SelectedGroup;
								$selected_id = $this->user['DropdownDefault']->SelectedGroup;
                            }else {
                                $type = 'CID:' . $this->user['DropdownDefault']->SelectedClient;
								$selected_id = $this->user['DropdownDefault']->SelectedClient;
                            }
                            
                            $directoryTable = array(                    
                                'Title_ID'              => $this->input->post('Title'),
                                'DIRECTORY_Type'        => $type,
                                'DIRECTORY_FirstName'   => $first_name,
                                'DIRECTORY_LastName'    => $this->input->post('LastName'),
                                'DIRECTORY_Address'     => $address,
                                'DIRECTORY_Email'       => $email,
                                'DIRECTORY_Phone'       => $phone,
                                'DIRECTORY_Notes'       => $this->input->post('Notes'),
                                'DIRECTORY_Created'     => date(FULL_MILITARY_DATETIME)
                            );
                            
                            $addDirectory = $this->members->addDirectory($directoryTable);
                            
                            if($addDirectory) {
                               // print_object($addUser);
                                $userInfoTable = array(
                                    
                                    'USER_ID'               => $addUser->ID,
                                    'DIRECTORY_ID'          => $addDirectory->ID,
                                    'CLIENT_ID'             => $selected_id,
                                    'ACCESS_ID'             => $accessID,
                                    'USER_Modules'          => get_user_modules($accessID),
                                    'USER_GravatarEmail'    => 'jwdavis@dealeronlinemarketing.com',
                                    'USER_Password'         => encrypt_password($generated_password),
                                    'USER_Active'           => 1,
                                    'USER_ActiveTS'         => date(FULL_MILITARY_DATETIME),
                                    'USER_Generated'        => 1,
                                    'USER_Created'           => date(FULL_MILITARY_DATETIME)
                                );
                                
                                $addUserInfo = $this->members->addUserInfo($userInfoTable);
                                
                                if($addUserInfo) {
                                    $msg   = 'Dear ' . $first_name . ' ' . $last_name . ', <br> You have been added to the DOM CMS. Your password is ' . $generated_password . ' and your username is ' . $username . '. Go here and login <a href="http://content.dealeronlinemarketing.com">Go To Dashboard</a>';
                                    $email = $this->members->email_results($username, 'You\'ve been added to the DOM CMS!', $msg);
                                    redirect('/admin/users/add/success','refresh');
                                }                               
                           }else {
                               redirect('/admin/users/error','refresh');
                           }
                        }else {
                            redirect('/admin/users/error','refresh');
                        }

                    break;
                    case "edit":
                        //add users
                        $form = $this->input->post();
						
						if($this->user['DropdownDefault']->LevelType == 'a') {
							$type = 'AID:' . $this->user['DropdownDefault']->SelectedAgency;
							$selected_id = $this->user['DropdownDefault']->SelectedAgency;
						}elseif($this->user['DropdownDefault']->LevelType == 'g') {
							$type = 'GID:' . $this->user['DropdownDefault']->SelectedGroup;
							$selected_id = $this->user['DropdownDefault']->SelectedGroup;
						}else {
							$type = 'CID:' . $this->user['DropdownDefault']->SelectedClient;
							$selected_id = $this->user['DropdownDefault']->SelectedClient;
						}
						
						$email = 'home:' . $this->input->post('PersonalEmailAddress') . 
						  		 (($this->input->post('WorkEmailAddress')) ? 
							     ',work:' . $this->input->post('WorkEmailAddress') : 
						         '');
								 
						$phone = 'main:' . $this->input->post('DirectPhone') . 
								 (($this->input->post('MobilePhone')) ? 
								 ',mobile:' . $this->input->post('MobilePhone') : '') . 
								 (($this->input->post('FaxPhone')) ? 
								 ',fax:' . $this->input->post('FaxPhone') : '');
						
                        $update = array(
							'Users' => array(
                            	'USER_Name' => $form['username'],
							),
							'Directories' => array(
								'DIRECTORY_ID'			=> $form['directory_id'],
                                'TITLE_ID'              => $form['Title'],
                                'DIRECTORY_Type'        => $type,
                                'DIRECTORY_FirstName'   => $form['firstname'],
                                'DIRECTORY_LastName'    => $form['lastname'],
								
                                'DIRECTORY_Address'     => 'street:'   . $form['street'] . 
														   ',city:'    . $form['city'] . 
														   ',state:'   . $form['state'] . 
														   ',zipcode:' . $form['zip'],
														   
                                'DIRECTORY_Email'       => $email,
                                'DIRECTORY_Phone'       => $phone,
                                'DIRECTORY_Notes'       => $form['notes'],
							),
							'Users_Info' => array(
								'USER_ID'               => $form['user_id'],
								'DIRECTORY_ID'          => $form['directory_id'],
								'CLIENT_ID'             => $selected_id,
								'ACCESS_ID'             => $form['permissionlevel'],
								'USER_Modules'          => get_user_modules($form['permissionlevel']),
								'USER_GravatarEmail'    => 'jwdavis@dealeronlinemarketing.com',
								'USER_Active'           => $form['Status'],
								'USER_ActiveTS'         => date(FULL_MILITARY_DATETIME),
								'USER_Generated'        => 0,
							)
                        );
						
						$update = $this->administration->updateUser($update);
						
						if($update) {
							redirect('/admin/users/success','refresh');
						}else{
							redirect('/admin/users/edit/error','refresh');
						}
                        
                    break;
                endswitch;
           break;
        endswitch;
    }
	public function reset_dd_session($page) {
		$type = $this->user['DropdownDefault']->LevelType;
        switch ($type) {
            case 'a':
				$this->user['DropdownDefault']->LevelType = 'a';
                $this->user['DropdownDefault']->SelectedAgency = 1;
                $this->user['DropdownDefault']->SelectedGroup = NULL;
                $this->user['DropdownDefault']->SelectedClient = NULL;
                break;
            case 'g':
				$this->user['DropdownDefault']->LevelType = 'a';
                $this->user['DropdownDefault']->SelectedGroup  = NULL;
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getGroupID($selected)->AgencyID;
                $this->user['DropdownDefault']->SelectedClient = NULL;
                break;
            case 'c':
				$this->user['DropdownDefault']->LevelType = 'g';
                $this->user['DropdownDefault']->SelectedClient = NULL;
                $this->user['DropdownDefault']->SelectedGroup = $this->administration->getClientID($selected)->GroupID;
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getGroupID($selected)->AgencyID;
                break;
        }
		
		$this->session->sess_write();
		redirect($page,'refresh');
	}

}
