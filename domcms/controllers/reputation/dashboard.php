<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dashboard extends DOM_Controller { //reputation/social section
		
		public function __construct() {
			parent::__construct();	
		}
	
		public function index() {
			$this->LoadTemplate('pages/dashboard');
		}
		
		public function Yelp() {
			$this->LoadTemplate('pages/reputation');
		}
		
	}
