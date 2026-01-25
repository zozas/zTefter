<?php
	// Setup globals
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
	// Include all libraries
	foreach (glob('*.inc') as $filename) require_once $filename;
	// Load system core
	$core = new core();
	// System objects:
	//    $SESSION ($USER, $PASSWORD, $LANGUAGE)
	//    $DATABASE
	//    $TEMPLATE
	//    $CONFIG
	//    $LANGUAGE
	//	  $EMAIL
	//	  $USER
	// Prepare template
	$TEMPLATE->set('meta_title', $CONFIG->get('APPLICATION', 'TITLE'));
	$TEMPLATE->set('meta_charset', $LANGUAGE->get('CONFIG', 'CHARSET'));
	$TEMPLATE->set('meta_viewport', $CONFIG->get('APPLICATION', 'VIEWPORT'));
	$TEMPLATE->set('meta_author', $CONFIG->get('APPLICATION', 'AUTHOR'));
	$TEMPLATE->set('meta_contact', $CONFIG->get('APPLICATION', 'CONTACT'));
	$TEMPLATE->set('meta_distribution', $CONFIG->get('APPLICATION', 'DISTRIBUTION'));
	$TEMPLATE->set('meta_google', $CONFIG->get('APPLICATION', 'GOOGLE'));
	$TEMPLATE->set('meta_robots', $CONFIG->get('APPLICATION', 'ROBOTS'));
	$TEMPLATE->set('meta_copyright', $CONFIG->get('APPLICATION', 'COPYRIGHT'));
	$TEMPLATE->set('meta_xua', $CONFIG->get('APPLICATION', 'XUA'));
	$TEMPLATE->set('meta_type', $CONFIG->get('APPLICATION', 'TYPE'));
	$TEMPLATE->set('meta_product', $CONFIG->get('APPLICATION', 'PRODUCT'));
	$TEMPLATE->set('meta_description', $CONFIG->get('APPLICATION', 'DESCRIPTION'));
	$TEMPLATE->set('meta_disclaimer', $CONFIG->get('APPLICATION', 'DISCLAIMER'));
	$TEMPLATE->set('meta_keywords', $CONFIG->get('APPLICATION', 'KEYWORDS'));
	$TEMPLATE->set('meta_version', $CONFIG->get('APPLICATION', 'VERSION'));
	$TEMPLATE->set('meta_logo', $CONFIG->get('APPLICATION', 'LOGO'));
	$TEMPLATE->set('meta_icon', $CONFIG->get('APPLICATION', 'ICON'));
	$TEMPLATE->set('font_url', $CONFIG->get('FONT', 'URL'));
	$TEMPLATE->set('font_name', $CONFIG->get('FONT', 'NAME'));
	// Change language
	$new_language = '';
	if (isset($_POST['language']))
		$new_language = $_POST['language'];
	else
		if (isset($_GET['language']))
			$new_language = $_GET['language'];
	if ($new_language != '') {
		if ($LANGUAGE->open($GLOBALS['CONFIG']->get('LANGUAGE', 'PATH').$new_language)) {
			$LANGUAGE->read();
			$SESSION->set('LANGUAGE', $new_language);
		} else {
			$LANGUAGE->open($CONFIG->get('LANGUAGE', 'PATH').$CONFIG->get('LANGUAGE', 'DEFAULT'));
			$LANGUAGE->read();			
			$SESSION->set('LANGUAGE', $CONFIG->get('LANGUAGE', 'DEFAULT'));
		}
	}
	// Prepare available languages
	$available_language_list = "";
	for($i=0; $i < $CONFIG->get('LANGUAGE', 'INSTALLED'); $i = $i+1) {
		$TEMPLATE_URL = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_URL'));
		$TEMPLATE_URL->set('reference', '?language='.$CONFIG->get('LANGUAGE_'.$i, 'FILE'));
		$TEMPLATE_URL->set('text', $CONFIG->get('LANGUAGE_'.$i, 'NAME'));
		$available_language_list = $available_language_list.'&nbsp;'.$TEMPLATE_URL->get();
	}
	$TEMPLATE->set('language_list', $available_language_list);
	$TEMPLATE->set('label_terms', $LANGUAGE->get('STRING','TERMS'));
	// Verify user
	$USER = new user();
	$login_username = '';
	if (isset($_POST['login_username'])) {
		$login_username = $_POST['login_username'];
		$SESSION->set('USER', $login_username);
	}
	if ($SESSION->exist('USER'))
		$login_username = $SESSION->get('USER');
	$login_password = '';
	if (isset($_POST['login_password'])) {
		$login_password = $_POST['login_password'];
		$SESSION->set('PASSWORD', $login_password);
	}
	if ($SESSION->exist('PASSWORD'))
		$login_password = $SESSION->get('PASSWORD');
	$USER->set_email($login_username);
	$USER->set_password($login_password);
	if (!$USER->validate()) {
		$SESSION->delete('USER');
		$SESSION->delete('PASSWORD');
	}
	// Check user and create menu for verified users & non-users
	$available_menu_list = "";
	if ($SESSION->exist('USER')) {
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','CALENDAR'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU->set('menu_link', "?action=customers");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','CUSTOMERS'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU->set('menu_link', "?action=statistics");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','STATISTICS'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=campaign");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','CAMPAIGNS'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=account");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','ACCOUNT'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=contact");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','CONTACT'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=help");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','HELP'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=logout");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','SIGNOUT'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
	} else {
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','HOMEPAGE'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=signin");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','SIGNIN'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=register");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','REGISTER'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=help");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','HELP'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
		$TEMPLATE_MENU = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_MENU'));
		$TEMPLATE_MENU->set('menu_link', "?action=contact");
		$TEMPLATE_MENU->set('menu_title', $LANGUAGE->get('STRING','CONTACT'));
		$available_menu_list = $available_menu_list.$TEMPLATE_MENU->get();
	}
	// Finish-up menu list
	$TEMPLATE->set('menu_list', $available_menu_list);
	// Initialize actions
	$action = '';
	if (isset($_POST['action']))
		$action = $_POST['action'];
	else
		if (isset($_GET['action']))
			$action = $_GET['action'];
	if ($action=="logout") {
		$SESSION->delete('USER');
		$SESSION->delete('PASSWORD');
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','SIGNOUT'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="signin") {
		$TEMPLATE_SIGNIN = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_SIGNIN'));
		$TEMPLATE_SIGNIN->set('label_login', $LANGUAGE->get('STRING','SIGNIN'));
		$TEMPLATE_SIGNIN->set('label_email', $LANGUAGE->get('STRING','EMAIL'));
		$TEMPLATE_SIGNIN->set('label_password', $LANGUAGE->get('STRING','PASSWORD'));
		$TEMPLATE_SIGNIN->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE_SIGNIN->set('label_recover', $LANGUAGE->get('STRING','RECOVER'));
		$TEMPLATE->set('content', $TEMPLATE_SIGNIN->get());
	} else if ($action=="register") {
		$TEMPLATE_REGISTER = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_REGISTER'));
		$TEMPLATE_REGISTER->set('label_register', $LANGUAGE->get('STRING','REGISTER'));
		$TEMPLATE_REGISTER->set('label_terms', $LANGUAGE->get('STRING','CONSENT'));
		$TEMPLATE_REGISTER->set('label_email', $LANGUAGE->get('STRING','EMAIL'));
		$TEMPLATE_REGISTER->set('label_email_help', $LANGUAGE->get('STRING','EMAIL_REGISTER'));
		$TEMPLATE_REGISTER->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE->set('content', $TEMPLATE_REGISTER->get());
	} else if ($action=="register_activate") {
		$login_username = '';
		if (isset($_POST['login_username']))
			$login_username = $_POST['login_username'];
		else
			if (isset($_GET['login_username']))
				$login_username = $_GET['login_username'];
		$USER->create($login_username);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','REGISTER'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="recover") {
		$TEMPLATE_RECOVER = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_RECOVER'));
		$TEMPLATE_RECOVER->set('label_recover', $LANGUAGE->get('STRING','RECOVER'));
		$TEMPLATE_RECOVER->set('label_email', $LANGUAGE->get('STRING','EMAIL'));
		$TEMPLATE_RECOVER->set('label_email_help', $LANGUAGE->get('STRING','EMAIL_RECOVER'));
		$TEMPLATE_RECOVER->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE->set('content', $TEMPLATE_RECOVER->get());
	} else if ($action=="recover_activate") {
		$login_username = '';
		if (isset($_POST['login_username']))
			$login_username = $_POST['login_username'];
		else
			if (isset($_GET['login_username']))
				$login_username = $_GET['login_username'];
		$USER->recover($login_username);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','RECOVER'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="account" && $SESSION->exist('USER')) {
		$TEMPLATE_ACCOUNT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_ACCOUNT'));
		$TEMPLATE_ACCOUNT->set('label_business', $LANGUAGE->get('STRING','BUSINESS'));
		$TEMPLATE_ACCOUNT->set('label_brand_name', $LANGUAGE->get('STRING','BRAND'));
		$TEMPLATE_ACCOUNT->set('brand_name', $USER->get_brand());
		$TEMPLATE_ACCOUNT->set('label_tax_name', $LANGUAGE->get('STRING','TAX'));
		$TEMPLATE_ACCOUNT->set('tax_name', $USER->get_tax());
		$TEMPLATE_ACCOUNT->set('label_address_name', $LANGUAGE->get('STRING','ADDRESS'));
		$TEMPLATE_ACCOUNT->set('address_name', $USER->get_address());
		$TEMPLATE_ACCOUNT->set('label_phone_name', $LANGUAGE->get('STRING','PHONE'));
		$TEMPLATE_ACCOUNT->set('phone_name', $USER->get_phone());
		$TEMPLATE_ACCOUNT->set('label_cell_name', $LANGUAGE->get('STRING','CELL'));
		$TEMPLATE_ACCOUNT->set('cell_name', $USER->get_cell());
		$TEMPLATE_ACCOUNT->set('label_owner_name', $LANGUAGE->get('STRING','OWNER'));
		$TEMPLATE_ACCOUNT->set('owner_name', $USER->get_owner());
		$TEMPLATE_ACCOUNT->set('label_account', $LANGUAGE->get('STRING','ACCOUNT'));
		$TEMPLATE_ACCOUNT->set('label_email', $LANGUAGE->get('STRING','EMAIL'));
		$TEMPLATE_ACCOUNT->set('user_email', $USER->get_email());
		$TEMPLATE_ACCOUNT->set('label_registration_date', $LANGUAGE->get('STRING','REGISTER_DATE'));
		$TEMPLATE_ACCOUNT->set('user_registration_date', date($LANGUAGE->get('CONFIG','DATE'), strtotime($USER->get_registration())));
		$TEMPLATE_ACCOUNT->set('label_preferences', $LANGUAGE->get('STRING','PREFERENCES'));
		$TEMPLATE_ACCOUNT->set('label_employees', $LANGUAGE->get('STRING','EMPLOYEES'));
		$TEMPLATE_ACCOUNT->set('label_employees_number', $USER->get_employee());
		$TEMPLATE_ACCOUNT->set('label_duration', $LANGUAGE->get('STRING','DURATION'));		
		$TEMPLATE_ACCOUNT->set('label_default_duration', $USER->get_duration());
		$TEMPLATE_ACCOUNT->set('label_time_start', $LANGUAGE->get('STRING','TIME_START'));
		$TEMPLATE_ACCOUNT->set('label_default_time_start', $USER->get_time_start());
		$TEMPLATE_ACCOUNT->set('label_time_end', $LANGUAGE->get('STRING','TIME_END'));
		$TEMPLATE_ACCOUNT->set('label_default_time_end', $USER->get_time_end());
		$TEMPLATE_ACCOUNT->set('label_password_change', $LANGUAGE->get('STRING','PASSWORD_CHANGE'));
		$TEMPLATE_ACCOUNT->set('label_password_old', $LANGUAGE->get('STRING','PASSWORD_OLD'));
		$TEMPLATE_ACCOUNT->set('label_password_new', $LANGUAGE->get('STRING','PASSWORD_NEW'));
		$TEMPLATE_ACCOUNT->set('label_save', $LANGUAGE->get('STRING','SAVE'));
		$TEMPLATE_ACCOUNT->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE_ACCOUNT->set('label_account_delete', $LANGUAGE->get('STRING','ACCOUNT_DELETE'));
		$TEMPLATE_ACCOUNT->set('label_delete', $LANGUAGE->get('STRING','DELETE'));
		$TEMPLATE_ACCOUNT->set('label_password', $LANGUAGE->get('STRING','PASSWORD_VERIFICATION'));
		$TEMPLATE->set('content', $TEMPLATE_ACCOUNT->get());
	} else if ($action=="business_preferences" && $SESSION->exist('USER')) {
		$employees = '';
		if (isset($_POST['employees']))
			$employees = $_POST['employees'];
		else
			if (isset($_GET['employees']))
				$employees = $_GET['employees'];
		$duration = '';
		if (isset($_POST['duration']))
			$duration = $_POST['duration'];
		else
			if (isset($_GET['duration']))
				$duration = $_GET['duration'];
		$time_start = '';
		if (isset($_POST['time_start']))
			$time_start = $_POST['time_start'];
		else
			if (isset($_GET['time_start']))
				$time_start = $_GET['time_start'];
		$time_end = '';
		if (isset($_POST['time_end']))
			$time_end = $_POST['time_end'];
		else
			if (isset($_GET['time_end']))
				$time_end = $_GET['time_end'];
		$USER->update_preferences($employees, $duration, $time_start, $time_end);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','ACCOUNT'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="business_change" && $SESSION->exist('USER')) {
		$brand_name = '';
		if (isset($_POST['brand_name']))
			$brand_name = $_POST['brand_name'];
		else
			if (isset($_GET['brand_name']))
				$brand_name = $_GET['brand_name'];
		$tax_name = '';
		if (isset($_POST['tax_name']))
			$tax_name = $_POST['tax_name'];
		else
			if (isset($_GET['tax_name']))
				$tax_name = $_GET['tax_name'];
		$address_name = '';
		if (isset($_POST['address_name']))
			$address_name = $_POST['address_name'];
		else
			if (isset($_GET['address_name']))
				$address_name = $_GET['address_name'];
		$phone_name = '';
		if (isset($_POST['phone_name']))
			$phone_name = $_POST['phone_name'];
		else
			if (isset($_GET['phone_name']))
				$phone_name = $_GET['phone_name'];
		$cell_name = '';
		if (isset($_POST['cell_name']))
			$cell_name = $_POST['cell_name'];
		else
			if (isset($_GET['cell_name']))
				$cell_name = $_GET['cell_name'];
		$owner_name = '';
		if (isset($_POST['owner_name']))
			$owner_name = $_POST['owner_name'];
		else
			if (isset($_GET['owner_name']))
				$owner_name = $_GET['owner_name'];
		$USER->update_business($brand_name, $tax_name, $address_name, $phone_name, $cell_name, $owner_name);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','ACCOUNT'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="password_change" && $SESSION->exist('USER')) {
		$login_password_old = '';
		if (isset($_POST['login_password_old']))
			$login_password_old = $_POST['login_password_old'];
		else
			if (isset($_GET['login_password_old']))
				$login_password_old = $_GET['login_password_old'];
		$login_password_new = '';
		if (isset($_POST['login_password_new']))
			$login_password_new = $_POST['login_password_new'];
		else
			if (isset($_GET['login_password_new']))
				$login_password_new = $_GET['login_password_new'];
		if ($USER->update_password($login_password_old, $login_password_new)) {
			$SESSION->set('PASSWORD', $login_password_new);
		}
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','PASSWORD_CHANGE'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="account_delete" && $SESSION->exist('USER')) {
		$login_password_verification = '';
		if (isset($_POST['login_password_verification']))
			$login_password_verification = $_POST['login_password_verification'];
		else
			if (isset($_GET['login_password_verification']))
				$login_password_verification = $_GET['login_password_verification'];		
		if ($login_password_verification == $SESSION->get('PASSWORD')) {
			$USER->delete();
			$SESSION->delete('USER');
			$SESSION->delete('PASSWORD');
			$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
			$TEMPLATE_REDIRECT->set('url', '?');
			$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','ACCOUNT_DELETE'));
			$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
			$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
			$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
		} else {
			$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
			$TEMPLATE_REDIRECT->set('url', '?');
			$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','ACCOUNT_DELETE'));
			$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','ERROR'));
			$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
			$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
		}
	} else if ($action=="day_view" && $SESSION->exist('USER')) {
		$year = date('Y');
		if (isset($_POST['year']))
			$year = $_POST['year'];
		else
			if (isset($_GET['year']))
				$year = $_GET['year'];
		$month = date('n');
		if (isset($_POST['month']))
			$month = $_POST['month'];
		else
			if (isset($_GET['month']))
				$month = $_GET['month'];
		$day = date('j');
		if (isset($_POST['day']))
			$day = $_POST['day'];
		else
			if (isset($_GET['day']))
				$day = $_GET['day'];
		$events = new events();
		$total_day_events = $events->dayevents_get($USER->get_id(), $day, $month, $year);
		$current_date = DateTime::createFromFormat('m/d/Y', $month.'/'.$day.'/'.$year);		
		$TEMPLATE_DAYVIEW = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_DAYVIEW'));
		$TEMPLATE_DAYVIEW->set('year_next', $current_date->modify('+1 day')->format('Y'));
		$current_date->modify('-1 day');
		$TEMPLATE_DAYVIEW->set('month_next', $current_date->modify('+1 day')->format('m'));
		$current_date->modify('-1 day');
		$TEMPLATE_DAYVIEW->set('day_next', $current_date->modify('+1 day')->format('d'));
		$current_date->modify('-1 day');
		$TEMPLATE_DAYVIEW->set('year_previous', $current_date->modify('-1 day')->format('Y'));
		$current_date->modify('+1 day');
		$TEMPLATE_DAYVIEW->set('month_previous', $current_date->modify('-1 day')->format('m'));
		$current_date->modify('+1 day');
		$TEMPLATE_DAYVIEW->set('day_previous', $current_date->modify('-1 day')->format('d'));
		$current_date->modify('+1 day');
		$TEMPLATE_DAYVIEW->set('year_week_next', $current_date->modify('+7 day')->format('Y'));
		$current_date->modify('-7 day');
		$TEMPLATE_DAYVIEW->set('month_week_next', $current_date->modify('+7 day')->format('m'));
		$current_date->modify('-7 day');
		$TEMPLATE_DAYVIEW->set('day_week_next', $current_date->modify('+7 day')->format('d'));
		$current_date->modify('-7 day');
		$TEMPLATE_DAYVIEW->set('year_week_previous', $current_date->modify('-7 day')->format('Y'));
		$current_date->modify('+7 day');
		$TEMPLATE_DAYVIEW->set('month_week_previous', $current_date->modify('-7 day')->format('m'));
		$current_date->modify('+7 day');
		$TEMPLATE_DAYVIEW->set('day_week_previous', $current_date->modify('-7 day')->format('d'));
		$current_date->modify('+7 day');
		$TEMPLATE_DAYVIEW->set('date_current', date($LANGUAGE->get('CONFIG','DATE'), strtotime($month.'/'.$day.'/'.$year)));
		$TEMPLATE_DAYVIEW->set('appointments', $LANGUAGE->get('STRING','APPOINTMENTS'));
		$TEMPLATE_DAYVIEW->set('availability', $LANGUAGE->get('STRING','AVAILABILITY'));
		$TEMPLATE_DAYVIEW->set('total_appointments', count($total_day_events));
		$TEMPLATE_DAYVIEW->set('vday_today', $day);
		$TEMPLATE_DAYVIEW->set('month_today', $month);
		$TEMPLATE_DAYVIEW->set('year_today', $year);
		$TEMPLATE_DAYVIEW->set('label_return', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE_DAYVIEW->set('label_add', $LANGUAGE->get('STRING','ADD'));
		$TEMPLATE_DAYVIEW->set('label_week_previous', $LANGUAGE->get('STRING','WEEK_PREVIOUS'));
		$TEMPLATE_DAYVIEW->set('label_day_previous', $LANGUAGE->get('STRING','DAY_PREVIOUS'));
		$TEMPLATE_DAYVIEW->set('label_day_next', $LANGUAGE->get('STRING','DAY_NEXT'));
		$TEMPLATE_DAYVIEW->set('label_week_next', $LANGUAGE->get('STRING','WEEK_NEXT'));
		// Check conflicts
		if (count($total_day_events) > 0) {
			for ($i=0; $i < count($total_day_events); $i++) {
				$total_day_events[$i]['time_end'] = date('H:i', strtotime($total_day_events[$i]['time'].' +'.$total_day_events[$i]['duration'].' minutes'));
				$total_day_events[$i]['overlap'] = 0;
				if ($i > 0) {
					for ($j=0; $j < count($total_day_events); $j++) {
						if ($j > 0) {
							if (isset($total_day_events[$i]['time']) && isset($total_day_events[$j - 1]['time_end'])) {
								if (strtotime($total_day_events[$i]['time']) < strtotime($total_day_events[$j - 1]['time_end'])) {
									$total_day_events[$i]['overlap'] = $total_day_events[$i]['overlap'] + 1;
								}
							}
						}
					}
				}
				if ($total_day_events[$i]['overlap'] > 0) {
					$total_day_events[$i]['overlap'] = $total_day_events[$i]['overlap'] - 1;
				}
			}
		}
		// Check free time slots
		$gaps = [];
		if (!empty($total_day_events)) {  
			$gaps[] = [ 'start' => date('H:i', strtotime($USER->get_time_start())), 'end' => $total_day_events[0]['time'] ];
			$currentEnd = $total_day_events[0]['time_end'];
			for ($i = 1; $i < count($total_day_events); $i++) {
				$nextStart = $total_day_events[$i]['time'];
				$nextEnd = $total_day_events[$i]['time_end'];
				if ($currentEnd < $nextStart) {
					$gaps[] = [ 'start' => $currentEnd, 'end' => $nextStart ];
				}
				if ($nextEnd > $currentEnd) {
					$currentEnd = $nextEnd;
				}				
			}
			$gaps[] = [ 'start' => $total_day_events[(count($total_day_events)-1)]['time_end'], 'end' => date('H:i', strtotime($USER->get_time_end())) ];
		} else {
			$gaps[] = [ 'start' => date('H:i', strtotime($USER->get_time_start())), 'end' => date('H:i', strtotime($USER->get_time_end())) ];
		}
		for ($i = 0; $i < count($gaps); $i = $i + 1) {
			$gaps[$i]['start'] = date('H:i', strtotime($gaps[$i]['start']));
			$gaps[$i]['end'] = date('H:i', strtotime($gaps[$i]['end']));
		}
		// Print event list
		$event_list = '';
		if (count($total_day_events) > 0) {
			for ($i=0; $i < count($total_day_events); $i++) {
				$TEMPLATE_EVENT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_EVENT'));
				$TEMPLATE_EVENT->set('event_time_start', date('H:i', strtotime($total_day_events[$i]['time'])));
				$TEMPLATE_EVENT->set('event_time_end',  date('H:i', strtotime('+'.$total_day_events[$i]['duration'].' minutes', strtotime($total_day_events[$i]['time']))));
				$TEMPLATE_EVENT->set('event_id',  $total_day_events[$i]['id']);
				$TEMPLATE_EVENT->set('event_duration', $total_day_events[$i]['duration']);
				$TEMPLATE_EVENT->set('event_description', $total_day_events[$i]['description']);
				$TEMPLATE_EVENT->set('event_customer', $total_day_events[$i]['customer']);
				if (filter_var($total_day_events[$i]['contact'], FILTER_VALIDATE_EMAIL)) {
					$TEMPLATE_EVENT->set('event_contact', "	&#x2709;&nbsp;<a href='mailto:".$total_day_events[$i]['contact']."' target='_blank'>".$total_day_events[$i]['contact']."</a>");
				} else if (preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", '+12223334444')) {
					$TEMPLATE_EVENT->set('event_contact', "	&#9742;&nbsp;<a href='tel:".$total_day_events[$i]['contact']."' target='_blank'>".$total_day_events[$i]['contact']."</a>");
				} else {
					$TEMPLATE_EVENT->set('event_contact', $total_day_events[$i]['contact']);
				}
				if ($total_day_events[$i]['overlap'] >= $USER->get_employee()) {
					$gaps[] = [ 'start' => date('H:i', strtotime($total_day_events[$i]['time'])), 'end' => date('H:i', strtotime($total_day_events[$i]['time_end'])), 'overlapped' => 1 ];
					$TEMPLATE_EVENT->set('event_warnings', $LANGUAGE->get('STRING','EMPLOYEES_INSUFFICIENT'));
				} else {
					$TEMPLATE_EVENT->set('event_warnings', '');
				}
				$TEMPLATE_EVENT->set('label_event_edit', $LANGUAGE->get('STRING','EDIT'));
				$TEMPLATE_EVENT->set('label_event_delete', $LANGUAGE->get('STRING','DELETE'));
				$TEMPLATE_EVENT->set('label_event_export', $LANGUAGE->get('STRING','EXPORT'));
				// ICS filetype export
				$TEMPLATE_EVENT->set('ics_location', $USER->get_address());
				$TEMPLATE_EVENT->set('ics_description', $total_day_events[$i]['customer']);
				$TEMPLATE_EVENT->set('ics_dtstart', date($LANGUAGE->get('CONFIG','DATE').' H:i', strtotime($month.'/'.$day.'/'.$year.' '.$total_day_events[$i]['time'])));
				$TEMPLATE_EVENT->set('ics_dtend', date($LANGUAGE->get('CONFIG','DATE').' H:i', strtotime('+'.$total_day_events[$i]['duration'].' minutes', strtotime($month.'/'.$day.'/'.$year.' '.$total_day_events[$i]['time']))));
				$TEMPLATE_EVENT->set('ics_summary', $total_day_events[$i]['description']);
				$TEMPLATE_EVENT->set('ics_url', $total_day_events[$i]['contact']);
				$TEMPLATE_DAYVIEW->set('availability', $LANGUAGE->get('STRING','AVAILABILITY'));
				$event_list = $event_list.$TEMPLATE_EVENT->get();
			}
		}
		$gap_list = "";
		for ($i = 0; $i < count($gaps); $i = $i + 1) {
			if (($gaps[$i]['start'] != $gaps[$i]['end']) && ($gaps[$i]['start'] < $gaps[$i]['end'])) {
				$TEMPLATE_GAP = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_GAP'));
				$TEMPLATE_GAP->set('event_time_start', $gaps[$i]['start']);
				$TEMPLATE_GAP->set('event_time_end', $gaps[$i]['end']);
				$TEMPLATE_GAP->set('event_duration', round(abs(strtotime($gaps[$i]['start']) - strtotime($gaps[$i]['end'])) / 60,2));
				$TEMPLATE_GAP->set('vday_today', $day);
				$TEMPLATE_GAP->set('month_today', $month);
				$TEMPLATE_GAP->set('year_today', $year);
				$TEMPLATE_GAP->set('start_time', $gaps[$i]['start']);
				$TEMPLATE_GAP->set('event_add', $LANGUAGE->get('STRING','ADD'));
				if (isset($gaps[$i]['overlapped'])) {
					$TEMPLATE_GAP->set('event_overlapped', $LANGUAGE->get('STRING','OVERLAPPED'));
				} else {
					$TEMPLATE_GAP->set('event_overlapped', '');
				}
				$gap_list = $gap_list.$TEMPLATE_GAP->get();
			}
		}
		$TEMPLATE_DAYVIEW->set('gaps', $gap_list);
		$TEMPLATE_DAYVIEW->set('events', $event_list);
		$TEMPLATE->set('content', $TEMPLATE_DAYVIEW->get());
	} else if ($action=="event_edit" && $SESSION->exist('USER')) {
		$id = 0;
		if (isset($_POST['id']))
			$id = $_POST['id'];
		else
			if (isset($_GET['id']))
				$id = $_GET['id'];
		$events = new events();
		$current_event = $events->event_get($USER->get_id(), $id);
		$year = date('Y', strtotime($current_event[0]['date']));
		$month = date('m', strtotime($current_event[0]['date']));
		$day = date('d', strtotime($current_event[0]['date']));
		$TEMPLATE_APPOINTMENT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_EVENT'));
		$TEMPLATE_APPOINTMENT->set('day_current', $day);
		$TEMPLATE_APPOINTMENT->set('month_current', $month);
		$TEMPLATE_APPOINTMENT->set('year_current', $year);
		$TEMPLATE_APPOINTMENT->set('event_id', $id);
		$TEMPLATE_APPOINTMENT->set('action', 'event_modify');
		$TEMPLATE_APPOINTMENT->set('date_current', date($LANGUAGE->get('CONFIG','DATE'), strtotime($month.'/'.$day.'/'.$year)));
		$TEMPLATE_APPOINTMENT->set('label_appointment', $LANGUAGE->get('STRING','APPOINTMENTS'));
		$TEMPLATE_APPOINTMENT->set('label_appointment_action', $LANGUAGE->get('STRING','EDIT'));
		$TEMPLATE_APPOINTMENT->set('label_save', $LANGUAGE->get('STRING','SAVE'));
		$TEMPLATE_APPOINTMENT->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE_APPOINTMENT->set('label_customer', $LANGUAGE->get('STRING','CUSTOMER'));
		$TEMPLATE_APPOINTMENT->set('customer_value', $current_event[0]['customer']);
		$TEMPLATE_APPOINTMENT->set('label_contact', $LANGUAGE->get('STRING','CONTACT'));
		$TEMPLATE_APPOINTMENT->set('contact_value', $current_event[0]['contact']);
		$TEMPLATE_APPOINTMENT->set('label_description', $LANGUAGE->get('STRING','DESCRIPTION'));
		$TEMPLATE_APPOINTMENT->set('description_value', $current_event[0]['description']);
		$TEMPLATE_APPOINTMENT->set('label_duration', $LANGUAGE->get('STRING','DURATION'));
		$TEMPLATE_APPOINTMENT->set('label_default_duration', $current_event[0]['duration']);
		$TEMPLATE_APPOINTMENT->set('readonly', '');
		$TEMPLATE_APPOINTMENT->set('label_date', $LANGUAGE->get('STRING','DATE'));
		$TEMPLATE_APPOINTMENT->set('label_default_date', date('Y', strtotime($month.'/'.$day.'/'.$year)).'-'.date('m', strtotime($month.'/'.$day.'/'.$year)).'-'.date('d', strtotime($month.'/'.$day.'/'.$year)));
		$TEMPLATE_APPOINTMENT->set('label_default_time_start', date('H:i', strtotime($USER->get_time_start())));
		$TEMPLATE_APPOINTMENT->set('label_default_time_end', date('H:i', strtotime($USER->get_time_end())));
		$TEMPLATE_APPOINTMENT->set('label_default_time', date('H:i', strtotime($USER->get_time_start())));
		$TEMPLATE_APPOINTMENT->set('label_default_time', date("H:i", strtotime($current_event[0]['time'])));
		$TEMPLATE_APPOINTMENT->set('label_time', $LANGUAGE->get('STRING','START'));
		$TEMPLATE->set('content', $TEMPLATE_APPOINTMENT->get());
	} else if ($action=="event_modify" && $SESSION->exist('USER')) {
		$id = 0;
		if (isset($_POST['id']))
			$id = $_POST['id'];
		else
			if (isset($_GET['id']))
				$id = $_GET['id'];
		$year = date('Y');
		if (isset($_POST['year']))
			$year = $_POST['year'];
		else
			if (isset($_GET['year']))
				$year = $_GET['year'];
		$month = date('n');
		if (isset($_POST['month']))
			$month = $_POST['month'];
		else
			if (isset($_GET['month']))
				$month = $_GET['month'];
		$day = date('j');
		if (isset($_POST['day']))
			$day = $_POST['day'];
		else
			if (isset($_GET['day']))
				$day = $_GET['day'];
		$time = '';
		if (isset($_POST['time']))
			$time = $_POST['time'];
		else
			if (isset($_GET['time']))
				$time = $_GET['time'];
		$duration = '';
		if (isset($_POST['duration']))
			$duration = $_POST['duration'];
		else
			if (isset($_GET['duration']))
				$duration = $_GET['duration'];
		$customer = '';
		if (isset($_POST['customer']))
			$customer = $_POST['customer'];
		else
			if (isset($_GET['customer']))
				$customer = $_GET['customer'];
		$contact = '';
		if (isset($_POST['contact']))
			$contact = $_POST['contact'];
		else
			if (isset($_GET['contact']))
				$contact = $_GET['contact'];
		$description = '';
		if (isset($_POST['description']))
			$description = $_POST['description'];
		else
			if (isset($_GET['description']))
				$description = $_GET['description'];
		$date = '';
		if (isset($_POST['date']))
			$date = $_POST['date'];
		else
			if (isset($_GET['date']))
				$date = $_GET['date'];
		if ($date != '') {
			$year = date('Y', strtotime($date));
			$month = date('n', strtotime($date));
			$day = date('j', strtotime($date));
		}
		$events = new events();
		$events->event_set($USER->get_id(), $id, $year, $month, $day, $time, $duration, $customer, $contact, $description);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','EDIT'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="event_remove" && $SESSION->exist('USER')) {
		$id = 0;
		if (isset($_POST['id']))
			$id = $_POST['id'];
		else
			if (isset($_GET['id']))
				$id = $_GET['id'];
		$events = new events();
		$current_event = $events->event_get($USER->get_id(), $id);
		$year = date('Y', strtotime($current_event[0]['date']));
		$month = date('m', strtotime($current_event[0]['date']));
		$day = date('d', strtotime($current_event[0]['date']));
		$TEMPLATE_APPOINTMENT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_EVENT'));
		$TEMPLATE_APPOINTMENT->set('day_current', $day);
		$TEMPLATE_APPOINTMENT->set('month_current', $month);
		$TEMPLATE_APPOINTMENT->set('year_current', $year);
		$TEMPLATE_APPOINTMENT->set('event_id', $id);
		$TEMPLATE_APPOINTMENT->set('action', 'event_delete');
		$TEMPLATE_APPOINTMENT->set('date_current', date($LANGUAGE->get('CONFIG','DATE'), strtotime($month.'/'.$day.'/'.$year)));
		$TEMPLATE_APPOINTMENT->set('label_appointment', $LANGUAGE->get('STRING','APPOINTMENTS'));
		$TEMPLATE_APPOINTMENT->set('label_appointment_action', $LANGUAGE->get('STRING','DELETE'));
		$TEMPLATE_APPOINTMENT->set('label_save', $LANGUAGE->get('STRING','REMOVE'));
		$TEMPLATE_APPOINTMENT->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE_APPOINTMENT->set('label_customer', $LANGUAGE->get('STRING','CUSTOMER'));
		$TEMPLATE_APPOINTMENT->set('customer_value', $current_event[0]['customer']);
		$TEMPLATE_APPOINTMENT->set('label_contact', $LANGUAGE->get('STRING','CONTACT'));
		$TEMPLATE_APPOINTMENT->set('contact_value', $current_event[0]['contact']);
		$TEMPLATE_APPOINTMENT->set('label_description', $LANGUAGE->get('STRING','DESCRIPTION'));
		$TEMPLATE_APPOINTMENT->set('description_value', $current_event[0]['description']);
		$TEMPLATE_APPOINTMENT->set('label_duration', $LANGUAGE->get('STRING','DURATION'));
		$TEMPLATE_APPOINTMENT->set('label_default_duration', $current_event[0]['duration']);
		$TEMPLATE_APPOINTMENT->set('readonly', 'readonly');
		$TEMPLATE_APPOINTMENT->set('label_default_time_start', '');
		$TEMPLATE_APPOINTMENT->set('label_default_time_end', '');
		$TEMPLATE_APPOINTMENT->set('label_default_time', date("H:i", strtotime($current_event[0]['time'])));
		$TEMPLATE_APPOINTMENT->set('label_time', $LANGUAGE->get('STRING','START'));
		$TEMPLATE->set('content', $TEMPLATE_APPOINTMENT->get());
	} else if ($action=="event_delete" && $SESSION->exist('USER')) {
		$id = 0;
		if (isset($_POST['id']))
			$id = $_POST['id'];
		else
			if (isset($_GET['id']))
				$id = $_GET['id'];
		$year = date('Y');
		$events = new events();
		$events->event_delete($USER->get_id(), $id);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','DELETE'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="event_add" && $SESSION->exist('USER')) {
		$year = date('Y');
		if (isset($_POST['year']))
			$year = $_POST['year'];
		else
			if (isset($_GET['year']))
				$year = $_GET['year'];
		$month = date('n');
		if (isset($_POST['month']))
			$month = $_POST['month'];
		else
			if (isset($_GET['month']))
				$month = $_GET['month'];
		$day = date('j');
		if (isset($_POST['day']))
			$day = $_POST['day'];
		else
			if (isset($_GET['day']))
				$day = $_GET['day'];
		$start_time = date('H:i', strtotime($USER->get_time_start()));
		if (isset($_POST['start_time']))
			$start_time = $_POST['start_time'];
		else
			if (isset($_GET['start_time']))
				$start_time = $_GET['start_time'];
		$TEMPLATE_APPOINTMENT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_EVENT'));
		$TEMPLATE_APPOINTMENT->set('day_current', $day);
		$TEMPLATE_APPOINTMENT->set('month_current', $month);
		$TEMPLATE_APPOINTMENT->set('year_current', $year);
		$TEMPLATE_APPOINTMENT->set('event_id', '');
		$TEMPLATE_APPOINTMENT->set('action', 'event_new');
		$TEMPLATE_APPOINTMENT->set('date_current', date($LANGUAGE->get('CONFIG','DATE'), strtotime($month.'/'.$day.'/'.$year)));
		$TEMPLATE_APPOINTMENT->set('label_appointment', $LANGUAGE->get('STRING','APPOINTMENTS'));
		$TEMPLATE_APPOINTMENT->set('label_appointment_action', $LANGUAGE->get('STRING','ADD'));
		$TEMPLATE_APPOINTMENT->set('label_save', $LANGUAGE->get('STRING','SAVE'));
		$TEMPLATE_APPOINTMENT->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$TEMPLATE_APPOINTMENT->set('label_customer', $LANGUAGE->get('STRING','CUSTOMER'));
		$TEMPLATE_APPOINTMENT->set('customer_value', '');
		$TEMPLATE_APPOINTMENT->set('label_contact', $LANGUAGE->get('STRING','CONTACT'));
		$TEMPLATE_APPOINTMENT->set('contact_value', '');
		$TEMPLATE_APPOINTMENT->set('label_description', $LANGUAGE->get('STRING','DESCRIPTION'));
		$TEMPLATE_APPOINTMENT->set('description_value', '');
		$TEMPLATE_APPOINTMENT->set('label_duration', $LANGUAGE->get('STRING','DURATION'));
		$TEMPLATE_APPOINTMENT->set('label_default_duration', $USER->get_duration());
		$TEMPLATE_APPOINTMENT->set('readonly', '');
		$TEMPLATE_APPOINTMENT->set('label_date', $LANGUAGE->get('STRING','DATE'));
		$TEMPLATE_APPOINTMENT->set('label_default_date', date('Y', strtotime($month.'/'.$day.'/'.$year)).'-'.date('m', strtotime($month.'/'.$day.'/'.$year)).'-'.date('d', strtotime($month.'/'.$day.'/'.$year)));
		$TEMPLATE_APPOINTMENT->set('label_default_time_start', $start_time);
		$TEMPLATE_APPOINTMENT->set('label_default_time_end', date('H:i', strtotime($USER->get_time_end())));
		$TEMPLATE_APPOINTMENT->set('label_default_time', $start_time);
		$TEMPLATE_APPOINTMENT->set('label_time', $LANGUAGE->get('STRING','START'));
		$TEMPLATE->set('content', $TEMPLATE_APPOINTMENT->get());
	} else if ($action=="event_new" && $SESSION->exist('USER')) {
		$year = date('Y');
		if (isset($_POST['year']))
			$year = $_POST['year'];
		else
			if (isset($_GET['year']))
				$year = $_GET['year'];
		$month = date('n');
		if (isset($_POST['month']))
			$month = $_POST['month'];
		else
			if (isset($_GET['month']))
				$month = $_GET['month'];
		$day = date('j');
		if (isset($_POST['day']))
			$day = $_POST['day'];
		else
			if (isset($_GET['day']))
				$day = $_GET['day'];
		$time = '';
		if (isset($_POST['time']))
			$time = $_POST['time'];
		else
			if (isset($_GET['time']))
				$time = $_GET['time'];
		$duration = '';
		if (isset($_POST['duration']))
			$duration = $_POST['duration'];
		else
			if (isset($_GET['duration']))
				$duration = $_GET['duration'];
		$customer = '';
		if (isset($_POST['customer']))
			$customer = $_POST['customer'];
		else
			if (isset($_GET['customer']))
				$customer = $_GET['customer'];
		$contact = '';
		if (isset($_POST['contact']))
			$contact = $_POST['contact'];
		else
			if (isset($_GET['contact']))
				$contact = $_GET['contact'];
		$description = '';
		if (isset($_POST['description']))
			$description = $_POST['description'];
		else
			if (isset($_GET['description']))
				$description = $_GET['description'];
		$date = '';
		if (isset($_POST['date']))
			$date = $_POST['date'];
		else
			if (isset($_GET['date']))
				$date = $_GET['date'];
		if ($date != '') {
			$year = date('Y', strtotime($date));
			$month = date('n', strtotime($date));
			$day = date('j', strtotime($date));
		}
		$events = new events();
		$events->event_new($USER->get_id(), $year, $month, $day, $time, $duration, $customer , $contact, $description);
		$TEMPLATE_REDIRECT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_REDIRECT'));
		$TEMPLATE_REDIRECT->set('url', '?');
		$TEMPLATE_REDIRECT->set('label_account', $LANGUAGE->get('STRING','ADD'));
		$TEMPLATE_REDIRECT->set('label_text', $LANGUAGE->get('STRING','SUCCESS'));
		$TEMPLATE_REDIRECT->set('label_button', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE->set('content', $TEMPLATE_REDIRECT->get());
	} else if ($action=="customers" && $SESSION->exist('USER')) {
		$order = 'customer';
		if (isset($_POST['order']))
			$order = $_POST['order'];
		else
			if (isset($_GET['order']))
				$order = $_GET['order'];
		$order_level = 'ASC';
		if (isset($_POST['order_level']))
			$order_level = $_POST['order_level'];
		else
			if (isset($_GET['order_level']))
				$order_level = $_GET['order_level'];
		$search = '';
		if (isset($_POST['search']))
			$search = $_POST['search'];
		else
			if (isset($_GET['search']))
				$search = $_GET['search'];
		$events = new events();
		$customers = $events->customers_search($USER->get_id(), $order, $order_level, $search);
		$TEMPLATE_CUSTOMERS = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_CUSTOMERS'));
		$TEMPLATE_CUSTOMERS->set('label_customers', $LANGUAGE->get('STRING','CUSTOMERS'));
		$TEMPLATE_CUSTOMERS->set('label_entries', $LANGUAGE->get('STRING','ENTRIES'));
		$TEMPLATE_CUSTOMERS->set('label_name', $LANGUAGE->get('STRING','CUSTOMER'));
		$TEMPLATE_CUSTOMERS->set('label_contact', $LANGUAGE->get('STRING','CONTACT'));
		$TEMPLATE_CUSTOMERS->set('label_sort_asc', $LANGUAGE->get('STRING','SORT_ASC'));
		$TEMPLATE_CUSTOMERS->set('label_sort_desc', $LANGUAGE->get('STRING','SORT_DESC'));
		$TEMPLATE_CUSTOMERS->set('label_search', $LANGUAGE->get('STRING','SEARCH'));
		$TEMPLATE_CUSTOMERS->set('label_search_string', $LANGUAGE->get('STRING','SEARCH_STRING'));
		$TEMPLATE_CUSTOMERS->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
		$customer_list = "";
		$total_customers = count($customers);
		$customer_counter = 0;
		for ($i = 0; $i < count($customers); $i = $i + 1) {
			if (isset($customers[$i+1]['contact'])) {
				if ($customers[$i]['contact'] != $customers[$i+1]['contact']) {
					$TEMPLATE_CUSTOMER = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_CUSTOMER'));
					$customer_counter = $customer_counter + 1;
					$TEMPLATE_CUSTOMER->set('label_count', $customer_counter);
					$TEMPLATE_CUSTOMER->set('label_name', $customers[$i]['customer']);
					$TEMPLATE_CUSTOMER->set('label_contact_search', $customers[$i]['contact']);
					if (filter_var($customers[$i]['contact'], FILTER_VALIDATE_EMAIL)) {
						$TEMPLATE_CUSTOMER->set('label_contact', "	&#x2709;&nbsp;<a href='mailto:".$customers[$i]['contact']."' target='_blank'>".$customers[$i]['contact']."</a>");
					} else if (preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", '+12223334444')) {
						$TEMPLATE_CUSTOMER->set('label_contact', "	&#9742;&nbsp;<a href='tel:".$customers[$i]['contact']."' target='_blank'>".$customers[$i]['contact']."</a>");
					} else {
						$TEMPLATE_CUSTOMER->set('label_contact', $total_day_events[$i]['contact']);
					}
					$customer_list = $customer_list.$TEMPLATE_CUSTOMER->get();
				} else {
					$total_customers = $total_customers - 1;
				}
			} else {
				$TEMPLATE_CUSTOMER = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_CUSTOMER'));
				$customer_counter = $customer_counter + 1;
				$TEMPLATE_CUSTOMER->set('label_count', $customer_counter);
				$TEMPLATE_CUSTOMER->set('label_name', $customers[$i]['customer']);
				$TEMPLATE_CUSTOMER->set('label_contact_search', $customers[$i]['contact']);
				if (filter_var($customers[$i]['contact'], FILTER_VALIDATE_EMAIL)) {
					$TEMPLATE_CUSTOMER->set('label_contact', "	&#x2709;&nbsp;<a href='mailto:".$customers[$i]['contact']."' target='_blank'>".$customers[$i]['contact']."</a>");
				} else if (preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", '+12223334444')) {
					$TEMPLATE_CUSTOMER->set('label_contact', "	&#9742;&nbsp;<a href='tel:".$customers[$i]['contact']."' target='_blank'>".$customers[$i]['contact']."</a>");
				} else {
					$TEMPLATE_CUSTOMER->set('label_contact', $total_day_events[$i]['contact']);
				}
				$customer_list = $customer_list.$TEMPLATE_CUSTOMER->get();
			}
		}
		$TEMPLATE_CUSTOMERS->set('label_customer_count', $total_customers);
		$TEMPLATE_CUSTOMERS->set('customer_list', $customer_list);
		$TEMPLATE->set('content', $TEMPLATE_CUSTOMERS->get());
	} else if ($action=="event_search" && $SESSION->exist('USER')) {
		$step = 20;
		$search = '';
		if (isset($_POST['search']))
			$search = $_POST['search'];
		else
			if (isset($_GET['search']))
				$search = $_GET['search'];
		$from = 0;
		if (isset($_POST['from']))
			$from = $_POST['from'];
		else
			if (isset($_GET['from']))
				$from = $_GET['from'];
		$to = $step;
		if (isset($_POST['to']))
			$to = $_POST['to'];
		else
			if (isset($_GET['to']))
				$to = $_GET['to'];
		if ($from > $to) {
			$from = 0;
			$to = $step;
		}
		$previous_from = $from - $step;
		$previous_to = $to - $step;
		if (($previous_from > $previous_to) || ($previous_from < 0)) {
			$from = 0;
			$to = $step;
			$previous_from = 0;
			$previous_to = $step;
		}
		$next_from = $from + $step;
		$next_to = $to + $step;
		$events = new events();
		$total_day_events = $events->events_search($USER->get_id(), $search, $from, $to);
		if (count($total_day_events)==0) {
			$from = 0;
			$to = $step;
			$previous_from = 0;
			$previous_to = $step;
			$next_from = $from + $step;
			$next_to = $to + $step;
		}
		$TEMPLATE_DAYVIEW = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_DAYSEARCH'));
		$TEMPLATE_DAYVIEW->set('label_search', $LANGUAGE->get('STRING','SEARCH_FAST'));
		$TEMPLATE_DAYVIEW->set('label_return', $LANGUAGE->get('STRING','RETURN'));
		$TEMPLATE_DAYVIEW->set('label_next', $LANGUAGE->get('STRING','NEXT'));
		$TEMPLATE_DAYVIEW->set('label_previous', $LANGUAGE->get('STRING','PREVIOUS'));
		$TEMPLATE_DAYVIEW->set('search_string', $search);
		$TEMPLATE_DAYVIEW->set('previous_from', $previous_from);
		$TEMPLATE_DAYVIEW->set('previous_to', $previous_to);
		$TEMPLATE_DAYVIEW->set('next_from', $next_from);
		$TEMPLATE_DAYVIEW->set('next_to', $next_to);
		$TEMPLATE_DAYVIEW->set('label_results', $LANGUAGE->get('STRING','RESULTS'));
		if (count($total_day_events)==0) {
			$TEMPLATE_DAYVIEW->set('from', 0);
			$TEMPLATE_DAYVIEW->set('to', 0);
		} else {
			$TEMPLATE_DAYVIEW->set('from', $from);
			$TEMPLATE_DAYVIEW->set('to', $to);
		}
		// Check end time
		if (count($total_day_events) > 0) {
			for ($i=0; $i < count($total_day_events); $i++) {
				$total_day_events[$i]['time_end'] = date('H:i', strtotime($total_day_events[$i]['time'].' +'.$total_day_events[$i]['duration'].' minutes'));
			}
		}
		// Print event list
		$event_list = '';
		if (count($total_day_events) > 0) {
			for ($i=0; $i < count($total_day_events); $i++) {
				$TEMPLATE_EVENT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_SEARCH'));
				$TEMPLATE_EVENT->set('event_time', date($LANGUAGE->get('CONFIG','DATE'), strtotime($total_day_events[$i]['date'])));
				$TEMPLATE_EVENT->set('event_time_start', date('H:i', strtotime($total_day_events[$i]['time'])));
				$TEMPLATE_EVENT->set('event_time_end',  date('H:i', strtotime('+'.$total_day_events[$i]['duration'].' minutes', strtotime($total_day_events[$i]['time']))));
				$TEMPLATE_EVENT->set('event_duration', $total_day_events[$i]['duration']);
				$TEMPLATE_EVENT->set('event_description', $total_day_events[$i]['description']);
				$TEMPLATE_EVENT->set('event_customer', $total_day_events[$i]['customer']);
				if (filter_var($total_day_events[$i]['contact'], FILTER_VALIDATE_EMAIL)) {
					$TEMPLATE_EVENT->set('event_contact', "	&#x2709;&nbsp;<a href='mailto:".$total_day_events[$i]['contact']."' target='_blank'>".$total_day_events[$i]['contact']."</a>");
				} else if (preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", '+12223334444')) {
					$TEMPLATE_EVENT->set('event_contact', "	&#9742;&nbsp;<a href='tel:".$total_day_events[$i]['contact']."' target='_blank'>".$total_day_events[$i]['contact']."</a>");
				} else {
					$TEMPLATE_EVENT->set('event_contact', $total_day_events[$i]['contact']);
				}
				// ICS filetype export
				$TEMPLATE_EVENT->set('ics_location', $USER->get_address());
				$TEMPLATE_EVENT->set('ics_description', $total_day_events[$i]['customer']);
				$TEMPLATE_EVENT->set('ics_dtstart', date($LANGUAGE->get('CONFIG','DATE').' H:i', strtotime($total_day_events[$i]['date'].' '.$total_day_events[$i]['time'])));
				$TEMPLATE_EVENT->set('ics_dtend', date($LANGUAGE->get('CONFIG','DATE').' H:i', strtotime('+'.$total_day_events[$i]['duration'].' minutes', strtotime($total_day_events[$i]['date'].' '.$total_day_events[$i]['time']))));
				$TEMPLATE_EVENT->set('ics_summary', $total_day_events[$i]['description']);
				$TEMPLATE_EVENT->set('ics_url', $total_day_events[$i]['contact']);
				$event_list = $event_list.$TEMPLATE_EVENT->get();
			}
		}
		$TEMPLATE_DAYVIEW->set('events', $event_list);
		$TEMPLATE->set('content', $TEMPLATE_DAYVIEW->get());
	} else if ($action=="statistics" && $SESSION->exist('USER')) {
		$events = new events();
		$statistics = $events->stat($USER->get_id());
		$TEMPLATE_STATISTICS = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_STATISTICS'));
		$TEMPLATE_STATISTICS->set('label_statistics', $LANGUAGE->get('STRING','STATISTICS'));
		$TEMPLATE_STATISTICS->set('label_appointments', $LANGUAGE->get('STRING','APPOINTMENTS'));
		$TEMPLATE_STATISTICS->set('label_events_total', $LANGUAGE->get('STRING','TOTAL'));
		$TEMPLATE_STATISTICS->set('events_total', $statistics['EVENTS']['TOTAL']);
		$TEMPLATE_STATISTICS->set('label_events_completed', $LANGUAGE->get('STRING','COMPLETED'));
		$TEMPLATE_STATISTICS->set('events_completed', $statistics['EVENTS']['COMPLETED']);
		$TEMPLATE_STATISTICS->set('label_events_cancelled', $LANGUAGE->get('STRING','CANCELLED'));
		$TEMPLATE_STATISTICS->set('events_cancelled', $statistics['EVENTS']['CANCELLED']);
		$TEMPLATE_STATISTICS->set('label_events_oldest', $LANGUAGE->get('STRING','OLDEST'));
		$TEMPLATE_STATISTICS->set('events_oldest', date($LANGUAGE->get('CONFIG','DATE'), strtotime($statistics['EVENTS']['OLDEST'])));
		$TEMPLATE_STATISTICS->set('label_events_newest', $LANGUAGE->get('STRING','NEWEST'));
		$TEMPLATE_STATISTICS->set('events_newest', date($LANGUAGE->get('CONFIG','DATE'), strtotime($statistics['EVENTS']['NEWEST'])));
		$TEMPLATE_STATISTICS->set('label_events_longest', $LANGUAGE->get('STRING','LONGEST'));
		$TEMPLATE_STATISTICS->set('events_longest', $statistics['EVENTS']['LONGEST']);
		$TEMPLATE_STATISTICS->set('label_events_shortest', $LANGUAGE->get('STRING','SHORTEST'));
		$TEMPLATE_STATISTICS->set('events_shortest', $statistics['EVENTS']['SHORTEST']);
		$TEMPLATE_STATISTICS->set('minutes', $LANGUAGE->get('STRING','MINUTES'));
		$TEMPLATE_STATISTICS->set('label_customers', $LANGUAGE->get('STRING','CUSTOMERS'));
		$TEMPLATE_STATISTICS->set('label_customers_loyal', $LANGUAGE->get('STRING','CUSTOMERS_MOST_LOYAL'));
		for ($i = 0; $i < 5; $i = $i + 1) {
			if (isset($statistics['CUSTOMER']['LOYAL'][$i])) {
				if (filter_var($statistics['CUSTOMER']['LOYAL'][$i]['contact'], FILTER_VALIDATE_EMAIL)) {
					$TEMPLATE_STATISTICS->set('customers_loyal'.$i, "	&#x2709;&nbsp;<a href='mailto:".$statistics['CUSTOMER']['LOYAL'][$i]['contact']."' target='_blank'>".$statistics['CUSTOMER']['LOYAL'][$i]['contact']."</a>");
				} else if (preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", '+12223334444')) {
					$TEMPLATE_STATISTICS->set('customers_loyal'.$i, "	&#9742;&nbsp;<a href='tel:".$statistics['CUSTOMER']['LOYAL'][$i]['contact']."' target='_blank'>".$statistics['CUSTOMER']['LOYAL'][$i]['contact']."</a>");
				} else {
					$TEMPLATE_STATISTICS->set('customers_loyal'.$i, $statistics['CUSTOMER']['LOYAL'][$i]['contact']);
				}
				$TEMPLATE_STATISTICS->set('customers_loyal_name'.$i, $statistics['CUSTOMER']['LOYAL'][$i]['customer']);
				$TEMPLATE_STATISTICS->set('customers_loyal_count'.$i, $statistics['CUSTOMER']['LOYAL'][$i][2]);
			} else {
				$TEMPLATE_STATISTICS->set('customers_loyal'.$i, '');
				$TEMPLATE_STATISTICS->set('customers_loyal_name'.$i, '');
				$TEMPLATE_STATISTICS->set('customers_loyal_count'.$i, '');
			}
		}
		$TEMPLATE_STATISTICS->set('label_customers_disloyal', $LANGUAGE->get('STRING','CUSTOMERS_LEAST_LOYAL'));
		for ($i = 0; $i < 5; $i = $i + 1) {
			if (isset($statistics['CUSTOMER']['DISLOYAL'][$i])) {
				if (filter_var($statistics['CUSTOMER']['DISLOYAL'][$i]['contact'], FILTER_VALIDATE_EMAIL)) {
					$TEMPLATE_STATISTICS->set('customers_disloyal'.$i, "	&#x2709;&nbsp;<a href='mailto:".$statistics['CUSTOMER']['DISLOYAL'][$i]['contact']."' target='_blank'>".$statistics['CUSTOMER']['DISLOYAL'][$i]['contact']."</a>");
				} else if (preg_match("/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/", '+12223334444')) {
					$TEMPLATE_STATISTICS->set('customers_disloyal'.$i, "	&#9742;&nbsp;<a href='tel:".$statistics['CUSTOMER']['DISLOYAL'][$i]['contact']."' target='_blank'>".$statistics['CUSTOMER']['DISLOYAL'][$i]['contact']."</a>");
				} else {
					$TEMPLATE_STATISTICS->set('customers_disloyal'.$i, $statistics['CUSTOMER']['DISLOYAL'][$i]['contact']);
				}
				$TEMPLATE_STATISTICS->set('customers_disloyal_name'.$i, $statistics['CUSTOMER']['DISLOYAL'][$i]['customer']);
				$TEMPLATE_STATISTICS->set('customers_disloyal_count'.$i, $statistics['CUSTOMER']['DISLOYAL'][$i][2]);
			} else {
				$TEMPLATE_STATISTICS->set('customers_disloyal'.$i, '');
				$TEMPLATE_STATISTICS->set('customers_disloyal_name'.$i, '');
				$TEMPLATE_STATISTICS->set('customers_disloyal_count'.$i, '');
			}
		}
		$TEMPLATE_STATISTICS->set('label_customers_commons', $LANGUAGE->get('STRING','EVENTS_COMMON'));
		for ($i = 0; $i < 5; $i = $i + 1) {
			if (isset($statistics['EVENTS']['COMMON'][$i])) {
				$TEMPLATE_STATISTICS->set('customers_commons'.$i, $statistics['EVENTS']['COMMON'][$i]['description']);
				$TEMPLATE_STATISTICS->set('customers_commons_count'.$i, $statistics['EVENTS']['COMMON'][$i][1]);
			} else {
				$TEMPLATE_STATISTICS->set('customers_commons'.$i, '');
				$TEMPLATE_STATISTICS->set('customers_commons_count'.$i, '');
			}
		}
		$TEMPLATE_STATISTICS->set('label_application', $LANGUAGE->get('STRING','APPLICATION'));
		$TEMPLATE_STATISTICS->set('label_title', $LANGUAGE->get('STRING','TITLE'));
		$TEMPLATE_STATISTICS->set('title', $CONFIG->get('APPLICATION', 'TITLE'));
		$TEMPLATE_STATISTICS->set('label_product', $LANGUAGE->get('STRING','PRODUCT'));
		$TEMPLATE_STATISTICS->set('product', $CONFIG->get('APPLICATION', 'PRODUCT'));
		$TEMPLATE_STATISTICS->set('label_version', $LANGUAGE->get('STRING','VERSION'));
		$TEMPLATE_STATISTICS->set('version', $CONFIG->get('APPLICATION', 'VERSION'));
		$TEMPLATE_STATISTICS->set('label_server', $LANGUAGE->get('STRING','SERVER'));
		$TEMPLATE_STATISTICS->set('label_server_name', $LANGUAGE->get('STRING','SERVER_NAME'));
		$TEMPLATE_STATISTICS->set('server_name', $_SERVER['SERVER_NAME']);
		$TEMPLATE_STATISTICS->set('label_http_host', $LANGUAGE->get('STRING','SERVER_HOST'));
		$TEMPLATE_STATISTICS->set('http_host', $_SERVER['HTTP_HOST']);
		$TEMPLATE_STATISTICS->set('label_disk_total', $LANGUAGE->get('STRING','DISK_TOTAL'));
		$TEMPLATE_STATISTICS->set('disk_total', round(disk_total_space(".") / 1000000000));
		$TEMPLATE_STATISTICS->set('label_disk_free', $LANGUAGE->get('STRING','DISK_FREE'));
		$TEMPLATE_STATISTICS->set('disk_free', round(disk_free_space(".") / 1000000000));		
		$TEMPLATE_STATISTICS->set('label_disk_used', $LANGUAGE->get('STRING','DISK_USED'));
		$TEMPLATE_STATISTICS->set('disk_used', round(round(disk_total_space(".") / 1000000000)-round(disk_free_space(".") / 1000000000)));
		$TEMPLATE_STATISTICS->set('label_disk_usage', $LANGUAGE->get('STRING','DISK_USAGE'));
		$TEMPLATE_STATISTICS->set('disk_usage', round(round(disk_free_space(".") / 1000000000)/round(disk_total_space(".") / 1000000000)*100));	
		$TEMPLATE_STATISTICS->set('label_http_user_agent', $LANGUAGE->get('STRING','USER_AGENT'));
		$TEMPLATE_STATISTICS->set('http_user_agent', $_SERVER['HTTP_USER_AGENT']);
		$TEMPLATE_STATISTICS->set('label_database_size', $LANGUAGE->get('STRING','DATABASE_SIZE'));
		$TEMPLATE_STATISTICS->set('database_size', $statistics['DATABASE']['SIZE']);
		$TEMPLATE->set('content', $TEMPLATE_STATISTICS->get());
	} else if ($action=="terms") {
		$TEMPLATE_TERMS = new template($CONFIG->get('TEMPLATES','PATH').$LANGUAGE->get('CONFIG','TERMS'));
		$TEMPLATE_TERMS->set('label_terms', $LANGUAGE->get('STRING','TERMS'));
		$TEMPLATE_TERMS->set('label_product', $CONFIG->get('APPLICATION', 'PRODUCT'));
		$TEMPLATE_TERMS->set('label_owner', $CONFIG->get('APPLICATION', 'AUTHOR'));
		$TEMPLATE_TERMS->set('label_url', $CONFIG->get('APPLICATION', 'URL'));
		$TEMPLATE_TERMS->set('label_contact', $CONFIG->get('APPLICATION', 'CONTACT'));
		$TEMPLATE->set('content', $TEMPLATE_TERMS->get());
	} else if ($action=="campaign" && $SESSION->exist('USER')) {
		$TEMPLATE_CAMPAIGN = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_CAMPAIGN'));
		$TEMPLATE_CAMPAIGN->set('label_campaign', $LANGUAGE->get('STRING', 'CAMPAIGNS'));
		$TEMPLATE_CAMPAIGN->set('label_email', $LANGUAGE->get('STRING', 'EMAIL'));
		$events = new events();
		$campaign_email_list = $events->campaign_email($USER->get_id());
		$final_email_list = "";
		for ($i = 0; $i < count($campaign_email_list); $i = $i + 1) {
			$final_email_list = $final_email_list.$campaign_email_list[$i]['contact'].",";
		}
		$final_email_list = rtrim($final_email_list, ",");
		$TEMPLATE_CAMPAIGN->set('email_list', $final_email_list);
		$TEMPLATE_CAMPAIGN->set('label_phone', $LANGUAGE->get('STRING', 'PHONE'));
		$TEMPLATE_CAMPAIGN->set('label_send_email', $LANGUAGE->get('STRING', 'BULK_SEND'));
		$final_phone_list = "";
		$campaign_phone_list = $events->campaign_phone($USER->get_id());
		for ($i = 0; $i < count($campaign_phone_list); $i = $i + 1) {
			$final_phone_list = $final_phone_list.$campaign_phone_list[$i]['contact'].",";
		}
		$final_phone_list = rtrim($final_phone_list, ",");
		$TEMPLATE_CAMPAIGN->set('phone_list', $final_phone_list);
		$TEMPLATE_CAMPAIGN->set('label_send_sms', $LANGUAGE->get('STRING', 'BULK_SEND'));
		$TEMPLATE->set('content', $TEMPLATE_CAMPAIGN->get());
	} else if ($action=="contact") {
		$TEMPLATE_CONTACT = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_CONTACT'));
		$TEMPLATE_CONTACT->set('label_product', $CONFIG->get('APPLICATION', 'PRODUCT'));
		$TEMPLATE_CONTACT->set('label_version', $LANGUAGE->get('STRING', 'VERSION'));
		$TEMPLATE_CONTACT->set('version', $CONFIG->get('APPLICATION', 'VERSION'));
		$TEMPLATE_CONTACT->set('email', $CONFIG->get('APPLICATION', 'CONTACT'));
		$TEMPLATE_CONTACT->set('author', $CONFIG->get('APPLICATION', 'AUTHOR'));
		$TEMPLATE_CONTACT->set('copyright', $CONFIG->get('APPLICATION', 'DISCLAIMER'));
		$TEMPLATE_CONTACT->set('contact', $LANGUAGE->get('STRING', 'CONTACT'));
		$TEMPLATE->set('content', $TEMPLATE_CONTACT->get());
	} else if ($action=="help") {
		$TEMPLATE_TERMS = new template($CONFIG->get('TEMPLATES','PATH').$LANGUAGE->get('CONFIG','HELP'));
		$TEMPLATE_TERMS->set('label_help', $LANGUAGE->get('STRING','HELP'));
		$TEMPLATE_TERMS->set('label_product', $CONFIG->get('APPLICATION', 'PRODUCT'));
		$TEMPLATE_TERMS->set('label_owner', $CONFIG->get('APPLICATION', 'AUTHOR'));
		$TEMPLATE->set('content', $TEMPLATE_TERMS->get());
	} else {
		if ($SESSION->exist('USER')) {
			$year = date('Y');
			if (isset($_POST['year']))
				$year = $_POST['year'];
			else
				if (isset($_GET['year']))
					$year = $_GET['year'];
			$month = date('n');
			if (isset($_POST['month']))
				$month = $_POST['month'];
			else
				if (isset($_GET['month']))
					$month = $_GET['month'];
			$day = date('j');
			if (isset($_POST['day']))
				$day = $_POST['day'];
			else
				if (isset($_GET['day']))
					$day = $_GET['day'];
			$calendar = new calendar($year, $month);
			$events = new events();
			$TEMPLATE_CALENDAR = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','ELEMENT_CALENDAR'));
			$TEMPLATE_CALENDAR->set('month', $month);
			$TEMPLATE_CALENDAR->set('year_previous', ($year-1));
			$TEMPLATE_CALENDAR->set('year_previous_month', $calendar->get_prevdate()->format('Y'));
			$TEMPLATE_CALENDAR->set('month_previous', $calendar->get_prevdate()->format('m'));
			$TEMPLATE_CALENDAR->set('year_next_month', $calendar->get_nextdate()->format('Y'));
			$TEMPLATE_CALENDAR->set('month_next', $calendar->get_nextdate()->format('m'));
			$TEMPLATE_CALENDAR->set('year_next', ($year+1));
			$TEMPLATE_CALENDAR->set('year', $year);
			$TEMPLATE_CALENDAR->set('month_current', $LANGUAGE->get('MONTHS','M'.intval($month)));
			$TEMPLATE_CALENDAR->set('year_current', $year);
			$TEMPLATE_CALENDAR->set('day1', $LANGUAGE->get('DAYS','D1'));
			$TEMPLATE_CALENDAR->set('day2', $LANGUAGE->get('DAYS','D2'));
			$TEMPLATE_CALENDAR->set('day3', $LANGUAGE->get('DAYS','D3'));
			$TEMPLATE_CALENDAR->set('day4', $LANGUAGE->get('DAYS','D4'));
			$TEMPLATE_CALENDAR->set('day5', $LANGUAGE->get('DAYS','D5'));
			$TEMPLATE_CALENDAR->set('day6', $LANGUAGE->get('DAYS','D6'));
			$TEMPLATE_CALENDAR->set('day7', $LANGUAGE->get('DAYS','D7'));
			$TEMPLATE_CALENDAR->set('today', $LANGUAGE->get('STRING','TODAY'));
			$TEMPLATE_CALENDAR->set('vday_today', date('d'));
			$TEMPLATE_CALENDAR->set('month_today', date('m'));
			$TEMPLATE_CALENDAR->set('year_today', date('Y'));
			$TEMPLATE_CALENDAR->set('label_year_previous', $LANGUAGE->get('STRING','YEAR_PREVIOUS'));
			$TEMPLATE_CALENDAR->set('label_year_next', $LANGUAGE->get('STRING','YEAR_NEXT'));
			$TEMPLATE_CALENDAR->set('label_month_previous', $LANGUAGE->get('STRING','MONTH_PREVIOUS'));
			$TEMPLATE_CALENDAR->set('label_month_next', $LANGUAGE->get('STRING','MONTH_NEXT'));
			$TEMPLATE_CALENDAR->set('label_add', $LANGUAGE->get('STRING','ADD'));
			$TEMPLATE_CALENDAR->set('label_today', $LANGUAGE->get('STRING','TODAY'));
			$TEMPLATE_CALENDAR->set('label_view_day', $LANGUAGE->get('STRING','VIEW'));
			$TEMPLATE_CALENDAR->set('label_fast_search_string', $LANGUAGE->get('STRING','SEARCH_FAST'));
			$TEMPLATE_CALENDAR->set('label_employees', $LANGUAGE->get('STRING','EMPLOYEES'));
			$TEMPLATE_CALENDAR->set('label_number_of_employees', $USER->get_employee());
			$TEMPLATE_CALENDAR->set('label_cancel', $LANGUAGE->get('STRING','CANCEL'));
			$TEMPLATE_CALENDAR->set('label_search', $LANGUAGE->get('STRING','SEARCH'));
			$caldays = $calendar->getCalendar();
			for ($i = 0; $i < sizeof($caldays); $i++) {
				$total_day_events = $events->dayevents_exists($USER->get_id(), $caldays[$i]->date->format('d'), $caldays[$i]->date->format('m'), $caldays[$i]->date->format('Y'));
				$TEMPLATE_CALENDAR->set('vday'.($i+1), intval($caldays[$i]->date->format('d')));
				$TEMPLATE_CALENDAR->set('month'.($i+1), intval($caldays[$i]->date->format('m')));
				$TEMPLATE_CALENDAR->set('year'.($i+1), intval($caldays[$i]->date->format('Y')));
				if ($total_day_events > 0) {
					$TEMPLATE_CALENDAR->set('dayview'.($i+1), '<b>'.$caldays[$i]->date->format('d').'</b>');
					$TEMPLATE_CALENDAR->set('dayevent'.($i+1), '+'.$total_day_events);
				} else {
					$TEMPLATE_CALENDAR->set('dayview'.($i+1), $caldays[$i]->date->format('d'));
					$TEMPLATE_CALENDAR->set('dayevent'.($i+1), '');
				}
				if (sizeof($caldays) < 36) {
					for ($j = 35; $j < 42; $j++) {
						$TEMPLATE_CALENDAR->set('dayview'.($j+1), '');
						$TEMPLATE_CALENDAR->set('dayevent'.($j+1), '');
					}
				}
			}
			$calendar_view = $TEMPLATE_CALENDAR->get();
			$TEMPLATE->set('content', $calendar_view);
		} else {
			$TEMPLATE_TERMS = new template($CONFIG->get('TEMPLATES','PATH').$CONFIG->get('TEMPLATES','FORM_FRONT'));
			$TEMPLATE_TERMS->set('label_product', $CONFIG->get('APPLICATION', 'PRODUCT'));
			$TEMPLATE_TERMS->set('label_description', $LANGUAGE->get('CONFIG','DESCRIPTION'));
			$TEMPLATE_TERMS->set('label_S1', $LANGUAGE->get('STARTUP','S1'));
			$TEMPLATE_TERMS->set('label_S2', $LANGUAGE->get('STARTUP','S2'));
			$TEMPLATE_TERMS->set('label_S3', $LANGUAGE->get('STARTUP','S3'));
			$TEMPLATE_TERMS->set('label_S4', $LANGUAGE->get('STARTUP','S4'));
			$TEMPLATE_TERMS->set('label_S5', $LANGUAGE->get('STARTUP','S5'));
			$TEMPLATE_TERMS->set('label_S6', $LANGUAGE->get('STARTUP','S6'));
			$TEMPLATE_TERMS->set('label_S7', $LANGUAGE->get('STARTUP','S7'));
			$TEMPLATE_TERMS->set('label_S8', $LANGUAGE->get('STARTUP','S8'));
			$TEMPLATE_TERMS->set('label_S9', $LANGUAGE->get('STARTUP','S9'));
			$TEMPLATE_TERMS->set('label_S10', $LANGUAGE->get('STARTUP','S10'));
			$TEMPLATE_TERMS->set('label_S11', $LANGUAGE->get('STARTUP','S11'));
			$TEMPLATE_TERMS->set('label_S12', $LANGUAGE->get('STARTUP','S12'));
			$TEMPLATE_TERMS->set('label_S13', $LANGUAGE->get('STARTUP','S13'));
			$TEMPLATE_TERMS->set('label_register', $LANGUAGE->get('STRING','REGISTER'));
			$TEMPLATE_TERMS->set('label_signin', $LANGUAGE->get('STRING','SIGNIN'));
			$TEMPLATE->set('content', $TEMPLATE_TERMS->get());
		}
	}
	echo $TEMPLATE->get();
?>
