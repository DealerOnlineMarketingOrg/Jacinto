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

        switch ($page) {
            case 'add':
                $this->LoadTemplate('forms/form_addusers');
                break;
            case 'edit':
                $user_id = $this->input->post('user_id');
                $user = $this->administration->getUsers($user_id);
                $data = array(
                    'user' => $user,
                    'states' => $this->utilities->getStates(),
                    'levels' => $this->utilities->getAccessLevels(),
                );
                $this->LoadTemplate('forms/form_editusers', $data);
                break;
            default:
                $users = $this->administration->getUsers();
                //Creating the table headings (th)
                $this->table->set_heading('Email Address', 'Name', 'Status', 'Member Since', '');
                foreach ($users as $user) {
                    $form_cred = array(
                        'name' => 'edit_user',
                        'id' => 'user_' . $user->ID
                    );
                    $form_submit = array(
                        'name' => 'submit',
                        'id' => 'usr_id_' . $user->ID,
                        'class' => 'button blue',
                        'value' => 'Edit'
                    );

                    $tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
                    $this->table->set_template($tmpl);
                    //edit button
                    $edit_form = (($this->CheckModule('User_Edit')) ? form_open('/admin/users/edit', $form_cred) . form_hidden('user_id', $user->ID) . form_submit($form_submit) . form_close() : '');
                    $this->table->add_row($user->EmailAddress, $user->LastName . ', ' . $user->FirstName, (($user->Status == 1) ? 'Active' : 'Disabled'), date('n-j-Y', strtotime($user->JoinDate)), $edit_form);
                }

                //BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
                $page_html = '<div class="title">' . heading('Users', 5) . '</div>' . $this->table->generate() . (($this->CheckModule('User_Add')) ? '<div class="one-fourth right">' . anchor(base_url() . 'admin/users/add', 'Add New User', 'class="button green float_right" id="add_user_button"') . '</div>' : '');

                $data = array(
                    'page_id' => 'users',
                    'page_html' => $page_html,
                    'msg' => $msg
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
                    print_object($this->user);
                    //PREPARE THE DATA FOR PAGE
                    $data = array(
                        'form' => AgencyAddForm(),
                        'page_id' => 'add_agency',
                        'formName' => 'Add New Agency',
                        //THE VIEW LOOKS FOR A MESSAGE, IF THE MESSAGE EXISTS THIS IS THE MESSAGE THE PAGE EXPECTS TO SEE
                        'msg' => (($msg) ? 'There was an error adding your agency to the system. Please try again' : '')
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
                    $this->table->set_heading('Name', 'Description', 'Status', '');
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
                                'class' => 'button blue',
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
                $this->LoadTemplate('forms/form_addgroups');
                break;
            case 'edit' :
                $this->LoadTemplate('forms/form_editgroups');
                break;
            default:

                //This is the listings page.
                //Check the permissions for this page, if the user has permission to view it, this will be 1, else 0;
                $permissions = $this->CheckModule('Group_List');

                if (!$permissions) {
                    //If the user doesnt have permission to view the page, include access denied page.
                    $this->AccessDenied();
                } else {

                    //Get the agencies into an array/

                    /*
                     * 1. Determine what level where on. Agent, Group or Client.
                     * 2. If on Agent level, get all groups in the agency.
                     * 3. If on Group level, get only the group were on.
                     * 4. If on client level, Show only the group the client belongs to.
                     */

                    
                    
                    $groups = (($level != 'a') ? $this->administration->getSelectedGroup($group_id) : $this->administration->getAllGroupsInAgency($agency_id));

                    //print_object($groups);
                    //create html table with codeigniter library. This is awesome btw.
                    $this->table->set_heading('Name', 'Member Of', 'Status', 'Actions');

                    //This is the template for the dataTables table. This is used to automatically format with dataTables. 
                    $tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');

                    //Set the template for the table so codeigniter recongnizes it.
                    $this->table->set_template($tmpl);

                    $form = '';

                    if (count($groups) > 1) :
                        //LOOP THROUGH EACH AGENCY AND CREATE A FORM BUTTON "EDIT" AND ROW FOR IT.
                        foreach ($groups as $group) :


                            //Instead of including the group id in the url, we post the GroupID to the form processor.
                            //This is the attributes for the form open tag.
                            $form_attr = array(
                                'name' => 'edit_' . $group->GroupID,
                                'id' => 'edit_form_' . $group->GroupID,
                            );

                            //this is the edit button for each row in the table.
                            //each button has different attribute values.
                            //the form also has a hidden element, this is the group id being posted to the form processor.
                            $button = array(
                                'name' => 'submit',
                                'id' => 'group_id_' . $group->GroupID,
                                'class' => 'basicBtn',
                                'value' => 'Edit Group'
                            );

                            if ($this->CheckModule('Group_Edit')) {
                                $form = form_open('/admin/groups/edit', $form_attr) . form_hidden('group_id', $group->GroupID) . form_submit($button) . form_close();
                            }

                            //add a row to the table for each group.
                            $this->table->add_row($group->Name, $group->AgencyName, (($group->Status) ? 'Active' : 'Disabled'), $form);
                        endforeach;
                    else :

                        $form_attr = array(
                            'name' => 'edit_' . $groups->GroupID,
                            'id' => 'edit_form_' . $groups->GroupID
                        );

                        $button = array(
                            'name' => 'submit',
                            'id' => 'group_id_' . $groups->GroupID,
                            'class' => 'basicBtn',
                            'value' => 'Edit Group'
                        );

                        if ($this->CheckModule('Group_Edit')) {
                            $form = form_open('/admin/groups/edit', $form_attr) . form_hidden('group_id', $groups->GroupID) . form_submit($button) . form_close();
                        }

                        //add a row to the table for each group.
                        $this->table->add_row($groups->Name, $groups->AgencyName, (($groups->Status) ? 'Active' : 'Disabled'), $form);

                    endif;

                    //Starts the html for the page
                    $html = '';


                    //If there is a message to the user, present it as it should be.
                    if ($msg != 'error') {
                        //The success message after a group was added or edited.
                        $html .= success_msg('The Group was added successfully!');
                    } else {
                        //The error message after a group was added, or edited.
                        $html .= error_msg();
                    }

                    //This builds the pages html out. We do this here so all our listing pages can have the same template view.
                    $html = '<div class="title">' . "\n";
                    $html .= "\t" . heading('Groups', 5);
                    $html .= '</div>' . "\n";
                    $html .= $this->table->generate() . "\n";
                    $html .= '<div class="fix"></div>';

                    //If the user has permission to add a new group, then show a button to do so.
                    if ($this->CheckModule('Group_Add')) {
                        $html .= anchor(base_url() . 'admin/groups/add', 'Add New Group', 'class="greenBtn floatRight button" style="margin-top:10px;" id="add_group_btn"');
                    }

                    $data = array(
                        'page_id' => 'groups',
                        'page_html' => $html,
                        'msg' => $msg
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

        switch ($page) {
            case 'add':
                $this->LoadTemplate('forms/form_addcontacts');
                break;
            case 'edit':
                $this->LoadTemplate('forms/form_editcontacts');
                break;
            default:

                break;
        }
    }

    public function Clients($page = false, $msg = false) {
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');

        $agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $group_id = $this->user['DropdownDefault']->SelectedGroup;

        switch ($page) :
            case 'add' :
                $this->LoadTEmplate('forms/form_addclients');
                break;

            case 'edit' :
                $this->LoadTemplate('forms/form_editclients');
                break;

            default:
                $permissions = $this->CheckModule('Client_List');
                if (!$permissions) {
                    $this->AccessDenied();
                } else {
                    
                    $level = $this->user['DropdownDefault']->LevelType;
                    $clients = (($level != 'a') ? $this->administration->getAllClientsInGroup($group_id) : $this->administration->getAllClientsInAgency($agency_id));
                    
                    //create html table with codeigniter library. This is awesome btw.
                    $this->table->set_heading('Code' , 'Name', 'Member Of', 'Status', 'Actions');
                    
                    $html = '';
                    
                    //If there is a message to the user, present it as it should be.
                    if ($msg != 'error') {
                        //The success message after a group was added or edited.
                        $html .= success_msg('The Client was added/edited successfully!');
                    } else {
                        //The error message after a group was added, or edited.
                        $html .= error_msg();
                    }

                    
                    if(isset($clients) AND (count($clients) > 1)) {
                        //LOOP THROUGH EACH AGENCY AND CREATE A FORM BUTTON "EDIT" AND ROW FOR IT.
                        //print_object($clients);
                        foreach ($clients as $client) :
                            
                            if(count($client) > 1) :
                               foreach($client as $dealer) :
                                    //EACH FORM IS NAMED BY THE EDIT_{AGENCY_ID}, SAME CONCEPT WITH THE ID
                                    $form_attr = array(
                                        'name' => 'edit_' . $dealer->ClientID,
                                        'id' => 'edit_form_' . $dealer->ClientID,
                                    );
                                    //EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
                                    $button = array(
                                        'name' => 'submit',
                                        'id' => 'client_id_' . $dealer->ClientID,
                                        'class' => 'basicBtn',
                                        'value' => 'Edit'
                                    );
                                    //BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
                                    //USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
                                    $this->table->add_row($dealer->ClientCode,
                                            $dealer->Name, $dealer->GroupName, (($dealer->Status) ? 'Active' : 'Disabled'),
                                            //IF THE USER HAS TO HAVE THE CORRECT PERMISSIONS TO VIEW A FEATURE
                                            (($this->CheckModule('Client_Edit')) ? form_open('/admin/clients/edit', $form_attr) . form_hidden('client_id', $dealer->ClientID) . form_submit($button) . form_close() : ''));

                                endforeach;
                             else :

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
                                //BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
                                //USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
                                $this->table->add_row($client->ClientCode,
                                        $client->Name, $client->GroupName, (($client->Status) ? 'Active' : 'Disabled'),
                                        //IF THE USER HAS TO HAVE THE CORRECT PERMISSIONS TO VIEW A FEATURE
                                        (($this->CheckModule('Client_Edit')) ? form_open('/admin/clients/edit', $form_attr) . form_hidden('client_id', $client->ClientID) . form_submit($button) . form_close() : ''));
                            endif;
                        endforeach;
                    }else {
                            //EACH FORM IS NAMED BY THE EDIT_{AGENCY_ID}, SAME CONCEPT WITH THE ID
                            $form_attr = array(
                                'name' => 'edit_' . $clients->ClientID,
                                'id' => 'edit_form_' . $clients->ClientID,
                            );
                            //EACH FORM HAS THE SAME NAME BUT DIFFERENT ID
                            $button = array(
                                'name' => 'submit',
                                'id' => 'client_id_' . $clients->ClientID,
                                'class' => 'basicBtn',
                                'value' => 'Edit'
                            );
                            //BUILD THE FORM ROW IN THE TABLE WITH NAME,DESCRIPTION,STATUS, and EDIT BUTTON, THE FORM ALSO HAS A HIDDEN ELEMENT WITH THE AGENCY ID, THIS IS WHAT WE
                            //USE TO POST TO THE EDIT PAGE TO GRAB THE CORRECT AGENCY FROM THE DB.
                            $this->table->add_row($clients->ClientCode,
                                    $clients->Name, $clients->GroupName, (($clients->Status) ? 'Active' : 'Disabled'),
                                    //IF THE USER HAS TO HAVE THE CORRECT PERMISSIONS TO VIEW A FEATURE
                                    (($this->CheckModule('Client_Edit')) ? form_open('/admin/clients/edit', $form_attr) . form_hidden('client_id', $clients->ClientID) . form_submit($button) . form_close() : ''));
                        
                    }
                    //THE ADD AGENCY BUTTON
                    $add_button = array(
                        'class' => 'button green float_right',
                        'id' => 'add_client_btn',
                        'href' => 'javascript:void(0)',
                    );

                    $tmpl = array('table_open' => '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">');
                    $this->table->set_template($tmpl);

                    //BUILD THE HTML FOR THE PAGE HERE IN A STRING. THE VIEW JUST ECHOS THIS OUT.
                    $page_html = ' <div class="title">' . heading('Clients', 5) . '</div>' . $this->table->generate() . (($this->CheckModule('Client_Add')) ? anchor(base_url() . 'admin/clients/add', '+', 'class="button green float_right" id="add_group_btn"') : '');

                    $data = array(
                        'page_id' => 'clients',
                        'page_html' => $page_html,
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

                break;
        endswitch;
    }

}