<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Itemselect extends DOM_Controller {
		//All we need is the construct and all controllers will pass through this on every page.
		public function __construct() {
			parent::__construct();
		}
		
		public function Index() {
			$this->load->model('administration');
	
			$qu_contacts = $this->administration->getContacts($this->user['DropdownDefault']->SelectedClient);
			
			$contacts = array();
			foreach ($qu_contacts as $qu_contact) {
				$name = $qu_contact['FirstName'] . ' ' . $qu_contact['LastName'];
				if ($qu_contact['JobTitle'] != '')
					$name = '(' . $qu_contact['JobTitle'] . ')';
				$contacts[] = $name;
			}
			
			if ($contacts)
				//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
				$this->load->view($this->theme_settings['ThemeDir'] . '/forms/form_editpasswords', $contacts);
			else
				//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
				echo 0;
		}
	}
