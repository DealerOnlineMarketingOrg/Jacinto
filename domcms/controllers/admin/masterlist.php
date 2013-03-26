<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Masterlist extends DOM_Controller {


	public $activeLevel;
	public $accessLevel;
    public function __construct() {
        parent::__construct();
		$this->activeNav = 'admin';
		$this->accessLevel = $this->user['AccessLevel'];
		switch($this->user['DropdownDefault']->LevelType) :
			case 'a': $this->activeLevel = 1; break;
			case 'g': $this->activeLevel = 2; break;
			case 'c': $this->activeLevel = 3; break;
			default : $this->activeLevel = 1; break;
		endswitch;
		$this->load->model('administration','master');
    }

    public function index() {
		$html = '';
		if($this->activeLevel != 1) {
			$html .= '<div class="nNote nFailure"><p>The current page is not available on ' . (($this->activeLevel == 2) ? 'Group' : 'Client') . ' Level. Please use the Dealer Dropdown to change to Agency Level to view the Master List.</p></div>';
		}else {
			if($this->accessLevel >= 500000) {
				$masterlist = $this->master->getMasterlist();
				$table = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
				$table .= '<thead><tr><th>Team</th><th>Code</th><th>Dealership</th><th>Website</th><th>CMS</th><th>CRM</th><th>DOC</th><th>XLS</th><th>Crazy Egg</th></tr></thead>';
				$table .= '<tbody>';
				foreach($masterlist as $client) :
					$table .= '<tr class="tagElement ' . $client->Class . '">';
					$table .= '<td class="' . $client->Class . '"><div class="' . $client->Class . '">&nbsp;</div></td>';
					$table .= '<td>' . $client->Code . '</td>';
					$table .= '<td><a href="' . $client->Xls . '" target="_blank">' . $client->Dealership . '</a></td>';
					$table .= '<td><a href="' . $client->DealershipURL . '" target="_blank">' . $client->DealershipURL . '</a></td>';
					$table .= '<td style="text-align:center"><a href="' . $client->CMSUrl . '" target="_blank">' . $client->CMSLabel . '</a></td>';
					$table .= '<td style="text-align:center"><a href="' . $client->CRMUrl . '" target="_blank">' . $client->CRMLabel . '</a></td>';
					$table .= '<td style="width:23px;"><a href="' . $client->Doc . '" target="_blank"><img src="' . base_url() . 'assets/themes/itsbrain/imgs/icons/middlenav/docs.png" alt="Google Doc" /></a></td>';
					$table .= '<td style="width:23px;"><a href="' . $client->Xls . '" target="_blank"><img src="' . base_url() . 'assets/themes/itsbrain/imgs/icons/middlenav/excelDoc.png" alt="Spreadsheet" /></a></td>';
					$table .= '<td style="text-align:center"><a href="http://www.crazyegg.com/login" target="_blank">' . $client->NewEgg . '</a></td>';
					$table .= '</tr>';
				endforeach;
				$table .= '</tbody>';
				$table .= '</table>';
				$html .= $table;
				//print_object($masterlist);
			}else {
				$html .= '<div class="nNote nFailure"><p>You do not have permission to view this page. Please contact support and request this module be added.</p></div>';	
			}
		}
		
		$data = array(
			'page_id' => '',
			'page_html' => $html
		);
		$this->LoadTemplate('pages/listings',$data);
    }

}
