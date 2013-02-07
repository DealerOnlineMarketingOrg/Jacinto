<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_welcome_message() {
    $ci = & get_instance();
    return 'Welcome, ' . $ci->session->userdata['valid_user']['FirstName'] . ' ' . $ci->session->userdata['valid_user']['LastName'];
}

function breadcrumb() {
    $ci = &get_instance();
    $i = 1;
    $uri = $ci->uri->segment($i);
    $link = '<div class="container' . ((FLUIDLAYOUT) ? '-fluid' : '') . '">';
    $link .= "\t" . '<ul class="breadcrumbs">';
    $link .= "\t\t" . '<li><a href="' . base_url() . '"><i class="icon-home"></i></a></li>';
    while ($uri != '') {
        $prep_link = '';
        for ($j = 1; $j <= $i; $j++) {
            $prep_link .= $ci->uri->segment($j) . '/';
        }
        if ($ci->uri->segment($i + 1) == '') {
            $link .= "\t\t" . '<li><a href="' . site_url($prep_link) . '">' . $ci->uri->segment($i) . '</a></li>';
        } else {
            $link .= "\t\t" . '<li><a href="' . site_url($prep_link) . '">' . $ci->uri->segment($i) . '</a></li>';
        }
        $i++;
        $uri = $ci->uri->segment($i);
    }
    $link .= "\t" . '</ul>';
    $link .= '</div>';
    return $link;
}

function showStates($selected = false) {
    $ci =& get_instance();
    $ci->load->model('utilities');
    $states = $ci->utilities->getStates();

    $options = '<select data-placeholder="Choose a State..." class="chzn-select" style="width:350px;" name="state">';
	$options .= '<option value=""></option>';
    foreach ($states as $state) {
        $options .= '<option ' . (($selected AND $selected == $state->Abbrev) ? 'selected="selected"' : '') . ' value="' . $state->Abbrev . '">' . $state->Name . '</option>';
    }
    $options .= '</select>';

    return $options;
}

function dealer_selector() {
	$ci =& get_instance();
	$ci->load->model('utilities');
	$dropdown = $ci->utilities->clientList();
	return $dropdown;	
}

function levelSelect() {
    $ci =& get_instance();
    $ci->load->library('dropdowngen');
    $ci->load->helper('string_parser');
    $dropdown = $ci->dropdowngen->drivedrop();

    $options = '<select id="levelSelector" data-placeholder="Select a Level" class="chzn-select" style="width:300px;">' . dropdown_parser($dropdown) . '</select>';
    $options .= '<script type="text/javascript">
                    jQuery("#levelSelector option").each(function() {
                        var option = jQuery(this);
                        if(option.prev().attr("data-level") == option.attr("data-level")) {
                            if(!option.hasClass("agency")) {
                                option.prev().remove();
                                option.removeClass("double-indent").addClass("single-indent");
                            }
                        }
                    });
                </script>';
    return $options;

}

function permission_selector() {
    $ci =& get_instance();
    $ci->load->model('administration');
    $permissions = $ci->administration->getPermissionsList($ci->user['AccessLevel']);

    $options = '';
    $options .= '<select id="perms" placeholder="User Permissions" class="chzn-select validate[required]" style="width:30%;" name="user_level" tabindex="10">';
    foreach ($permissions as $permission) {
        $options .= '<option value="' . $permission->ID . '" data-access-level="' . $permission->Level . '" data-modules="' . $permission->Modules . '">' . $permission->Name . '</option>';
    }
    $options .= '</select>';
    return $options;
}

function member_of_selector() {
    $ci = & get_instance();
    $ci->load->library('dropdowngen');
    $ci->load->helper('string_parser');
    $dropdown = $ci->dropdowngen->drivedrop();

    $options = '<select id="member_of" data-placeholder="Member Of Dropdown" class="chzn-select" style="width:30%" name="member_of">' . dropdown_parser($dropdown) . '</select>';
    return $options;
}

function tag_selector() {
    $ci = & get_instance();
    $ci->load->library('tagdropgen');
    //print_r(client_tag_parser($ci->tagdropgen->drivetagdrop()));
    $ValidUser = $ci->session->userdata('valid_user');
    $DropdownDefault = $ValidUser['DropdownDefault'];
    $tag_id = $DropdownDefault->SelectedTag;
    return client_tag_parser($ci->tagdropgen->drivetagdrop(), $tag_id);
}

function get_client_type() {
    $ci = & get_instance();
    $level_type = $ci->session->userdata['valid_user']['DropdownDefault']->LevelType;
    //get client type from session
    if ($level_type == 'a' OR $level_type == 1) :
        $name = 'Agency Name:';
    elseif ($level_type == 'g' OR $level_type == 2) :
        $name = 'Group Name:';
    elseif ($level_type == 'c' OR $level_type == 3) :
        $name = 'Client Name:';
    else :
        $name = '';
    endif;

    return $name;
}

function get_client_name() {
    $ci = & get_instance();
    $ci->load->model('dropdown');
    $dropdown = $ci->session->userdata['valid_user']['DropdownDefault'];
    //print_object($dropdown);
    $level_type = $dropdown->LevelType;
    $level_id = $dropdown->LevelID;
    if ($level_type == 1 OR $level_type == 'a') :
        $results = $ci->dropdown->AgenciesQuery($level_id, true);
        $name = $results->AGENCY_Name;
    elseif ($level_type == 2 OR $level_type == 'g') :
        $results = $ci->dropdown->GroupsQuery($level_id, false, true);
        $name = $results->GROUP_Name;
    elseif ($level_type == 3 OR $level_type == 'c') :
        $results = $ci->dropdown->ClientQuery($level_id, false, true);
        $name = ((isset($results->CLIENT_Name)) ? $results->CLIENT_Name : '');
    else :
        $results = NULL;
        $name = '';
    endif;
    return $name;
}

function get_user_modules($level) {
   switch($level) {
       case 1 :
           return '1:1,2:1,3:1,4:1,5:1,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 2 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 3 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 4 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       case 5 :
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   
       default:
           return '1:0,2:0,3:0,4:0,5:0,6:1,7:1,8:1,9:1,10:1,11:1,12:1,13:1,14:1,15:1,16:1,17:1,18:1,19:1,20:1,21:1,22:1,23:1,24:1,25:1,26:1,27:1,28:1,29:1,30:1,31:1,32:1,33:1,34:1,35:1,36:1,37:1,38:1,39:1,40:1,41:1,42:1,43:1,44:1,45:1';
       break;
   }
}