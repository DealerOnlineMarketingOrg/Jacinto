<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Switch templates by changing these parameters.
$config['ThemeName'] = 'ItsBrain';
/*
	Since were using codeigniter the css and html need to be split up into different areas.
	All images, css and jscript files are located in the Assets/themes folders
	All html files are located in the DomCMS/views/themes folder.
	To include files accross all templates, you can use the Global folder to do this.
*/
$config['ThemeDir']  	= 'themes/itsbrain';
$config['GlobalDir'] 	= 'themes/global';

//Default <title> tag
$config['Title'] 		= 'Dealer Online Marketing | Jacinto';
$config['CompanyName'] 	= 'Dealer Online Marketing';
$config['AppName'] 		= 'Jacinto';
$config['Logo'] 		= 'imgs/login_logo.png';
$config['AppVersion'] 	= 'Beta 0.2';
$config['GapiEmail'] 	= '';
$config['GapiPass'] 	= '';
$config['GoogleFonts'] 	= array('Cuprum','Open+Sans+Condensed:300','Open+Sans');

/* Enabled features */
$config['Breadcrumbs'] = true;

/*
	These are some tags that could differ from theme to theme in the header.
		DocType can be changed to reference HTML5 or 4, etc.
		There are some meta tags incase you want to give some keywords and descriptions.
*/

$config['DocType'] 	= '<!doctype html>';
$config['HTML'] 	= '<html>';
