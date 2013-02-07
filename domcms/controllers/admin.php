<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends DOM_Controller {

    public function __construct() {
        parent::__construct();
        //loading the member model here makes it available for any member of the dashboard controller.
        $this->load->model('administration');
        $this->DisplaySettings();
        //print_object($this->user['DropdownDefault']);
    }

    public function index() {

        /*
          | Load the template in anyway you like, my personal preference with an indexed array to be able to label what which template peice is
          |	- All you have to do is call the template otherwise
          |	- Once template is loaded the code has already ran so always load the template last.
          |   - The second paramater of the template load is the data you want to pass to the template peice.
         */
        $this->LoadTemplate('pages/dashboard');
    }

    public function Users($page = false, $msg = false) {
        $this->load->model('administration');
        $this->load->helper('html');
        $this->load->helper('template');
        $this->load->library('table');
        $this->load->model('utilities');
        $this->load->helper('msg');
        $level = $this->user['DropdownDefault']->LevelType;
        //echo $level;
        switch ($page) {
            case 'add':
                $html = '';
                if($msg AND $msg == 'success') {
                    $html .= success_msg('Added user successfully!');
                }elseif($msg AND $msg == 'error') {
                    $html .= error_msg();
                }
                $data = array(
                  'html' => $html  
                );
                $this->LoadTemplate('forms/form_adduser',$data);
                break;
            case 'edit':
                
                $html = '';

                if($msg AND $msg == 'error') {
                   $html .= error_msg();
                }elseif($msg AND $msg == 'success') {
                   $html .= success_msg('You have successfully added a new Agency! <a href="' . base_url() . 'admin/agency">Agency dashboard</a>');
                }

                $user_id = $this->input->post('user_id');
                $user = $this->administration->getUsers($user_id);
                                
                $data = array(
                    'user' => $user,
                );
                
                $this->LoadTemplate('forms/form_edituser', $data);
                
            break;
            default:
				$html = '';
			//print_object($this->user['DropdownDefault']);
				$client_id = (($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : 1);
				//echo $client_id;
                $users = $this->administration->getUsersByUserInfo($client_id);
                //Creating the table headings (th)
				$this->table->set_heading('Avatar', 'Email Address', 'Name', 'Status', 'Member Since','Actions');
				if($users AND count($users) > 1) {
					foreach ($users as $user) {
						$form_cred = array(
							'name' => 'edit_user',
							'id' => 'user_' . $user->USER_ID
						);
						//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
						$button = array(
							'name' => 'submit',
							'id' => 'user_id_' . $user->USER_ID,
							'class' => 'basicBtn',
							'value' => 'Edit'
						);
	
						$tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
						$this->table->set_template($tmpl);
						
						//edit button
						$edit_form = (($this->CheckModule('User_Edit')) ? form_open('/admin/users/edit', $form_cred) . form_hidden('user_id', $user->USER_ID) . form_submit($button) . form_close() : '');
						$this->table->add_row('<div align="center"><img style="width:25px;" src="' . $this->gravatar->get_gravatar($user->USER_GravatarEmail) . '" /></div>',$user->USER_Name, $user->DIRECTORY_LastName . ', ' . $user->DIRECTORY_FirstName, (($user->USER_Active == 1) ? 'Active' : 'Disabled'), date('n-j-Y', strtotime($user->USER_Created)),$edit_form);
						
					}
				}elseif($users AND count($users) == 1) {
						$form_cred = array(
							'name' => 'edit_user',
							'id' => 'user_' . $users->USER_ID
						);
						//EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
						$button = array(
							'name' => 'submit',
							'id' => 'user_id_' . $users->USER_ID,
							'class' => 'basicBtn',
							'value' => 'Edit'
						);
	
						$tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
						$this->table->set_template($tmpl);
						
						//edit button
						$edit_form = (($this->CheckModule('User_Edit')) ? form_open('/admin/users/edit', $form_cred) . form_hidden('user_id', $users->USER_ID) . form_submit($button) . form_close() : '');
						$this->table->add_row('<div align="center"><img style="width:25px;" src="' . $this->gravatar->get_gravatar($users->USER_GravatarEmail) . '" /></div>',$users->USER_Name, $users->DIRECTORY_LastName . ', ' . $users->DIRECTORY_FirstName, (($users->USER_Active == 1) ? 'Active' : 'Disabled'), date('n-j-Y', strtotime($users->USER_Created)),$edit_form);
				}else {
					$error_msg = '<p style="text-align:center">No users are associated for this client. Please use the Dealer Dropdown to select another client, or add users by clicking the "Add New User" button below.</p>';	
				}

                
                //If there is a message to the user, present it as it should be.
                if ($msg AND $msg != 'error') {
                    //The success message after a group was added or edited.
                    $html .= success_msg('The User was added/edited successfully!');
                } elseif($msg AND $msg != 'success') {
                    //The error message after a group was added, or edited.
                    $html .= error_msg();
                }


                //BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
                $html .= '<div class="title">';
                $html .= "\t" . heading('Users', 5);
                $html .= '</div>';
                $html .=  ((count($users) > 0) ? $this->table->generate() : $error_msg);

                if ($this->CheckModule('User_Add')) {
                    $html .= anchor(base_url() . 'admin/users/add', 'Add New User', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_user_btn"');
                }

                //Prepare data for template in $data array; when in template, you call the keys like vars: example => $html;
                $data = array(
                    'page_html' => $html
                );

                
                $this->LoadTemplate('pages/listings', $data);
                break;
        }
    }

    public function Agency($page = false, $msg = false) {
        $this->load->helper('form');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');
        switch ($page) :
            /* THE ADD AGENCY PAGE */
            case 'add' :
                //MODULE PERMISSIONS
                $permissions = $this->CheckModule('Agency_Add');
                if (!$permissions) {
                    $this->AccessDenied();
                } else {
                    //print_object($this->user);
                    //PREPARE THE DATA FOR PAGE

                    $html = '';

                    if($msg AND $msg == 'error') {
                       $html .= error_msg();
                    }elseif($msg AND $msg == 'success') {
                       $html .= success_msg('You have successfully added a new Agency! <a href="' . base_url() . 'admin/agency">Agency dashboard</a>');
                    }

                    $data = array(
                        'html' => $html
                    );
                    //THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
                    $this->LoadTemplate('forms/form_addagency', $data);
                }
                break;
            /* THE EDIT AGENCY PAGE */
            case 'edit':
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
                        'msg' => (($msg) ? 'There was an error editing your agencies information. Please try again' : '')
                    );
                    //THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
                    $this->LoadTemplate('forms/form_editagency', $data);
                }
                break;
            /* THE DEFAULT LISTING PAGE */
            default:
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

                    if(isset($agencies) AND (count($agencies) > 1)) {

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
                                $form .= form_open('/admin/agency/edit', $form_attr) . form_hidden('agency_id', $agency->ID) . form_submit($button) . form_close();
                            }

                            //BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
                            //USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
                            $this->table->add_row($agency->Name, $agency->Description, (($agency->Status) ? 'Active' : 'Disabled'), $form);
                        endforeach;
                    }else {
                        if(isset($agencies)) {
                            $form_attr = array(
                              'name' => 'edit_' . $agencies->ID,
                              'id' => 'edit_form_' . $agencies->ID,
                            );

                            $button = array(
                                'name' => 'submit',
                                'id' => 'agency_id_' . $agencies->ID,
                                'class' => 'basicBtn',
                                'value' => 'Edit'
                            );

                            $tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
                            $this->table->set_template($tmpl);

                            $form = '';
                            if($this->CheckModule('Agency_Edit')) {
                                $form .= form_open('/admin/agency/edit',$form_attr) . form_hidden('agency_id',$agencies->ID) . form_submit($button) . form_close();
                            }

                            $this->table->add_row($agencies->Name,$agencies->Description,(($agencies->Status) ? 'Active' : 'Disabled'), $form);

                       };
                    }

                    //If there is a message to the user, present it as it should be.
                    if ($msg AND $msg != 'error') {
                        //The success message after a group was added or edited.
                        $html .= success_msg('The Agency was added successfully!');
                    } elseif($msg AND $msg != 'success') {
                        //The error message after a group was added, or edited.
                        $html .= error_msg();
                    }


                    //BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
                    $html .= '<div class="title">';
                    $html .= "\t" . heading('Agencies', 5);
                    $html .= '</div>';
                    $html .= $this->table->generate();

                    if ($this->CheckModule('Agency_Add')) {
                        $html .= anchor(base_url() . 'admin/agency/add', 'Add New Agency', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_agency_btn"');
                    }

                    //Prepare data for template in $data array; when in template, you call the keys like vars: example => $html;
                    $data = array(
                        'page_id' => 'agency',
                        'page_html' => $html
                    );

                    $this->LoadTemplate('pages/listings', $data);
                }
                break;
        //END THE SWITCH
        endswitch;
    }

    public function Groups($page = false, $msg = false) {
        $this->load->helper('form');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');

        $agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $group_id = $this->user['DropdownDefault']->SelectedGroup;
        $level = $this->user['DropdownDefault']->LevelType;

        switch ($page) :
            case 'add' :
			     $html = '';
				
				if($msg AND $msg != 'error') {
					$html .= success_msg('Your group was successfully added!');
				}elseif($msg AND $msg == 'error') {
					$html .= error_msg();
				}
				
				$data = array(
					'html' => $html
				);

                $this->LoadTemplate('forms/form_addgroups',$data);
                break;
            case 'edit' :
                
                    $html = '';
                    
                    if($msg AND $msg != 'error') {
                        $html .= success_msg('Your group was successfully edited!');
                    }elseif($msg AND $msg == 'error') {
                        $html .= error_msg();
                    }
                
                     //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
                    $group_id = $this->input->post('group_id');
                    $this->load->model('administration');
                    $group = $this->administration->getSelectedGroup($group_id);
                    
                    //print_object($group);
                    //PREPARE THE VIEW FOR THE FORM
                    $data = array(
                        'group' => $group,
                        'html' => $html
                    );
                    //THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.

                $this->LoadTemplate('forms/form_editgroups',$data);
                break;
            default:
                //This is the listings page.
                //Check the permissions for this page, if the user has permission to view it, this will be 1, else 0;
                $permissions = $this->CheckModule('Group_List');

                if (!$permissions) {
                    //If the user doesnt have permission to view the page, include access denied page.
                    $this->AccessDenied();
                } else {
					
					$tmpl = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';

                    $groups = (($level != 'a') ? $this->administration->getSelectedGroupResults($group_id) : $this->administration->getAllGroupsInAgencyResults($agency_id));
					
					$tmpl .= '<thead>';
					$tmpl .= 	'<tr>';
					$tmpl .= 		'<th>Tags</th>';
					$tmpl .= 		'<th style="text-align:left;">Name</th>';
					$tmpl .= 		'<th style="text-align:center;">Member Of</th>';
					$tmpl .= 		'<th style="text-align:center;">Status</th>';
					$tmpl .= 		'<th>Actions</th>';
					$tmpl .= 	'</tr>';
					$tmpl .= '</thead>';
					$tmpl .= '<tbody>';
					
                    //This is the template for the dataTables table. This is used to automatically format with dataTables.
					$form = '';
					
					foreach($groups as $group) :
					
						//Instead of including the group id in the url, we post the GroupID to the form processor.
						//This is the attributes for the form open tag.
						$form_attr = array(
							'name' => 'edit_' . $group->GroupID,
							'id' => 'edit_form_' . $group->GroupID,
						);
						
						$tmpl .= '<tr class="tagElement ' . $group->ClassName . '">';
						
						//this is the edit button for each row in the table.
						//each button has different attribute values.
						//the form also has a hidden element, this is the group id being posted to the form processor.
						$button = array(
							'name'  => 'submit',
							'id' 	=> 'group_id_' . $group->GroupID,
							'class' => 'basicBtn',
							'value' => 'Edit'
						);

						if ($this->CheckModule('Group_Edit')) {
							$form = form_open('/admin/groups/edit', $form_attr) . form_hidden('group_id', $group->GroupID) . form_submit($button) . form_close();
						}
						
						$tmpl .= '<td class="tags"><div class="' . $group->ClassName . '">&nbsp;</div></td>';
						$tmpl .= '<td class="client_name">' . $group->Name . '</td>';
						$tmpl .= '<td class="agency" style="width:155px;text-align:center;">' . $group->AgencyName . '</td>';
						$tmpl .= '<td class="status" style="width:75px;text-align:center;">' . (($group->Status) ? 'Active' : 'Disabled') . '</td>';
						$tmpl .= '<td class="editButtons" style="width:55px;">' . $form . '</td>';
						
					endforeach;
					
					$tmpl .= '</tbody>';
					$tmpl .= '</table>';
                    //Starts the html for the page
                    $html = '';

                    //If there is a message to the user, present it as it should be.
                    if ($msg AND $msg != 'error') {
                        //The success message after a group was added or edited.
                        $html .= success_msg('The Group was added/edited successfully!');
                    } elseif($msg AND $msg != 'success') {
                        //The error message after a group was added, or edited.
                        $html .= error_msg();
                    }
					
                    //This builds the pages html out. We do this here so all our listing pages can have the same template view.
                    $html .= '<div class="title">' . "\n";
                    $html .= "\t" . heading('Groups', 5);
                    $html .= '</div>' . "\n";
                    $html .= $tmpl . "\n";
                    $html .= '<div class="fix"></div>';

                    //If the user has permission to add a new group, then show a button to do so.
                    if ($this->CheckModule('Group_Add')) {
                        $html .= anchor(base_url() . 'admin/groups/add', 'Add New Group', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_group_btn"');
                    }

                    $data = array(
                        'page_id' => 'groups',
                        'page_html' => $html
                    );
                    $this->LoadTemplate('pages/listings', $data);
					
                }
                break;
        endswitch;
    }

    public function Contacts($page = false, $msg = false) {
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('administration');
        $this->load->helper('string_parser');
        $this->load->helper('msg');

        switch ($page) {
            case 'add':
                $html = '';

                //If there is a message to the user, present it as it should be.
                if ($msg AND $msg != 'error') {
                    //The success message after a group was added or edited.
                    $html .= success_msg('The Client was added successfully!');
                } elseif($msg AND $msg != 'success') {
                    //The error message after a group was added, or edited.
                    $html .= error_msg();
                }
                $data = array(
                    'html' => $html
                );
                $this->LoadTemplate('forms/form_addcontacts',$data);
            break;
            case 'edit':
			        $html = '';
                    
                    if($msg AND $msg != 'error') {
                        $html .= success_msg('Your client was successfully edited!');
                    }elseif($msg AND $msg == 'error') {
                        $html .= error_msg();
                    }
                
                     //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
                    $contact_id = $this->input->post('contact_id');
                    $this->load->model('administration');
                    $contact = $this->administration->getContact($contact_id);
                    
                    if($contact) {
                        $contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
                        $contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone) : false;
						$contact->Type = substr($contact->Type,0,3);
						$contact->Email = mod_parser($contact->Email);
                    }
                    
                    //PREPARE THE VIEW FOR THE FORM
                    $data = array(
                        'contact' => $contact,
                        'html' => $html
                    );
                    //THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
					
                $this->LoadTemplate('forms/form_editcontacts',$data);
                break;
            default:
				$level = $this->user['DropdownDefault']->LevelType;
				$client_id = (($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : false);			
                $contacts = $this->administration->getContacts($client_id);
                //table heading
                $this->table->set_heading('Title', 'Name', 'Email', 'Phone','Actions');
                $html = '';

                //If there is a message to the user, present it as it should be.
                if ($msg AND $msg != 'error') {
                    //The success message after a group was added or edited.
                    $html .= success_msg('The Client was edited successfully!');
                } elseif($msg AND $msg != 'success') {
                    //The error message after a group was added, or edited.
                    $html .= error_msg();
                }
                
					foreach($contacts as $contact) {
						$edit_button = '';
						$edit_button .= form_open('/admin/contacts/edit',array('name'=>'EditForm','id'=>'contact_' . $contact->ContactID));
						$edit_button .= form_hidden('contact_id',$contact->ContactID);
						$edit_button .= form_submit('editContact','Edit Contact','class="basicBtn"');
						$edit_button .= form_close();

						
						
						$contact->Name 		= $contact->FirstName . ' ' . $contact->LastName;
						$contact->Address 	= mod_parser($contact->Address);
						$contact->Email 	= mod_parser($contact->Email);
						$contact->Phone 	= mod_parser($contact->Phone);
						$contact->Title 	= $this->administration->getContactTitle($contact->Title);
						$this->table->add_row(
							$contact->Title,
							$contact->Name,
							'<span style="font-weight:bold;">Personal Email</span><br /><a href="mailto:' . $contact->Email["home"] . '">' . $contact->Email['home'] . '</a></span>' .
							((isset($contact->Email['work'])) ? '<br /><span style="font-weight:bold;">Work Email</span><br /><a href="mailto:' . $contact->Email["work"] . '">' . $contact->Email['work'] . '</a></span>' : ''),
							'<span style="font-weight:bold;">Direct</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['main'] . '">' . $contact->Phone['main'] . '</a></span>' .
							((isset($contact->Phone['mobile'])) ? 
								'<br /><span style="font-weight:bold;">Mobile</span><br />
								 <span style="white-space:nowrap;">
									<a href="tel:' . $contact->Phone['mobile'] . '">' . $contact->Phone['mobile'] . '</a>
								</span>' : '') . 
							((isset($contact->Phone['fax'])) ? '<br /><span style="font-weight:bold;">Fax</span><br /><span style="white-space:nowrap;"><a href="tel:' . $contact->Phone['fax'] . '">' . $contact->Phone['fax'] . '</a></span>' : ''),
							$edit_button
						);
					}
                
                $tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
                $this->table->set_template($tmpl);

                //This builds the pages html out. We do this here so all our listing pages can have the same template view.
                $html .= '<div class="title">' . "\n";
                $html .= "\t" . heading('Contacts', 5);
                $html .= '</div>' . "\n";
                $html .= ((count($contacts) > 0) ? $this->table->generate() . "\n":$error_msg);
                $html .= '<div class="fix"></div>';
                
                //If the user has permission to add a new group, then show a button to do so.
                if ($this->CheckModule('Client_Add')) {
                    $html .= anchor(base_url() . 'admin/contacts/add', 'Add New Contact', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_contact_btn"');
                }

                $data = array(
                    'page_id' => 'clients',
                    'page_html' => $html,
                );
                //LOAD THE TEMPLATE
                $this->LoadTemplate('pages/listings', $data);
                
                break;
        }
    }

    public function Clients($page = false, $msg = false) {
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');
        $this->load->helper('template');
        $this->load->helper('string_parser');

        $agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $group_id = $this->user['DropdownDefault']->SelectedGroup;

        switch ($page) :
            case 'add' :
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
                break;

            case 'edit' :
                    $html = '';
                    
                    if($msg AND $msg != 'error') {
                        $html .= success_msg('Your client was successfully edited!');
                    }elseif($msg AND $msg == 'error') {
                        $html .= error_msg();
                    }
                
                     //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
                    $client_id = $this->input->post('client_id');
                    $this->load->model('administration');
                    $client = $this->administration->getSelectedClient($client_id);
                    
                    if($client) {
                        $client->Address = (isset($client->Address)) ? mod_parser($client->Address) : false;
                        $client->Phone = (isset($client->Phone)) ? mod_parser($client->Phone) : false;
                    }
                    
                    //print_object($client);
                    
                    //print_object($group);
                    //PREPARE THE VIEW FOR THE FORM
                    $data = array(
                        'client' => $client,
                        'html' => $html
                    );
                    //THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.

                $this->LoadTemplate('forms/form_editclients',$data);
                
                break;

            default:
                $permissions = $this->CheckModule('Client_List');
				$tmpl['table_open'] = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
				
                if (!$permissions) {
                    $this->AccessDenied();
                } else {
                    $level = $this->user['DropdownDefault']->LevelType;
                    $clients = (($level != 'c') ? $this->administration->getAllClientsInGroup($group_id) : $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient));
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
										(($this->CheckModule('Client_Edit')) ? form_open('/admin/clients/edit', $form_attr) . form_hidden('client_id', $client->ClientID) . form_submit($button) . form_close() : ''));
				
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
										(($this->CheckModule('Client_Edit')) ? form_open('/admin/clients/edit', $form_attr) . form_hidden('client_id', $group->ClientID) . form_submit($button) . form_close() : ''));
							
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
                    $html .= '<div class="title">' . "\n";
                    $html .= "\t" . heading('Clients', 5);
                    $html .= '</div>' . "\n";
                    $html .= $this->table->generate() . "\n";
                    $html .= '<div class="fix"></div>';

                    //If the user has permission to add a new group, then show a button to do so.
                    if ($this->CheckModule('Group_Add')) {
                        $html .= anchor(base_url() . 'admin/clients/add', 'Add Client', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_client_btn"');
                    }

                    $data = array(
                        'page_id' => 'clients',
                        'page_html' => $html,
                    );
                    //LOAD THE TEMPLATE
                    $this->LoadTemplate('pages/listings', $data);
                }
                break;
        endswitch;
    }

    public function Websites($page = false, $msg = false) {
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');

        switch ($page) :

            case 'add':
                $this->LoadTemplate('forms/form_addwebsites');
                break;

            case 'edit':
                $this->LoadTemplate('forms/form_editwebsites');
                break;
            default:
                //$this->LoadTemplate('forms/stillworking');
                break;
        endswitch;
    }
	
	public function Tags($page = false, $msg = false) {
		
		$this->LoadTemplate('forms/form_addtags');	
	}

}