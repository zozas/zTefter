<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

define('CONFIGURATION_MAIN', 'core.ini.php');

// Methods
// ~constructor ()

class core {
	public function __construct() {
		$GLOBALS['SESSION'] = new session;
		$GLOBALS['EMAIL'] = new email;
		$GLOBALS['CONFIG'] = new ini('');
		$GLOBALS['CONFIG']->open(CONFIGURATION_MAIN);
		$GLOBALS['CONFIG']->read();
		$GLOBALS['DATABASE'] = new database($GLOBALS['CONFIG']->get('DATABASE','HOST'), $GLOBALS['CONFIG']->get('DATABASE','NAME'), $GLOBALS['CONFIG']->get('DATABASE','PASSWORD'), $GLOBALS['CONFIG']->get('DATABASE','USER'));
		$GLOBALS['DATABASE']->connect();
		// Load main template
		$GLOBALS['TEMPLATE'] = new template($GLOBALS['CONFIG']->get('TEMPLATES','PATH').$GLOBALS['CONFIG']->get('TEMPLATES','MAIN'));
		// Load language by session or set default language as current
		$GLOBALS['LANGUAGE'] = new ini('');
		if ($GLOBALS['SESSION']->exist('LANGUAGE')) {
			if ($GLOBALS['LANGUAGE']->open($GLOBALS['CONFIG']->get('LANGUAGE', 'PATH').$GLOBALS['SESSION']->get('LANGUAGE'))) {
				$GLOBALS['LANGUAGE']->read();
				$GLOBALS['SESSION']->set('LANGUAGE', $GLOBALS['SESSION']->get('LANGUAGE'));
			} else {
				$GLOBALS['LANGUAGE']->open($GLOBALS['CONFIG']->get('LANGUAGE', 'PATH').$GLOBALS['CONFIG']->get('LANGUAGE_0', 'FILE'));
				$GLOBALS['LANGUAGE']->read();			
				$GLOBALS['SESSION']->set('LANGUAGE', $GLOBALS['SESSION']->get('LANGUAGE'));
			}
		} else {
			$GLOBALS['LANGUAGE']->open($GLOBALS['CONFIG']->get('LANGUAGE', 'PATH').$GLOBALS['CONFIG']->get('LANGUAGE_0', 'FILE'));
			$GLOBALS['LANGUAGE']->read();
			$GLOBALS['SESSION']->set('LANGUAGE', $GLOBALS['CONFIG']->get('LANGUAGE_0', 'FILE'));
		}
	}	
}

// Methods
// set_id(id)
// get_id()
// set_email(email)
// get_email()
// set_brand(brand)
// get_brand()
// set_tax(tax)
// get_tax()
// set_address(address)
// get_address()
// set_phone(phone)
// get_phone()
// set_cell(cell)
// get_cell()
// set_owner(owner)
// get_owner()
// set_employee(owner)
// get_employee()
// set_duration(owner)
// get_duration()
// set_time_start(owner)
// get_time_start()
// set_time_end(owner)
// get_time_end()
// validate()
// calculate_password()
// create(email)
// recover(email)
// update_password(oldpassword, newpassword)
// update(nickname)
// update_business(brand, tax, address, phone, cell, owner)

class user {
	private $user_email = '';
	private $user_password = '';
	private $user_id = '';
	private $brand = '';
	private $tax = '';
	private $address = '';
	private $phone = '';
	private $cell = '';
	private $owner = '';
	private $employee = 1;
	private $duration = 30;
	private $time_start = '07:00';
	private $time_end = '23:00';
	public function set_id($user_id='') {
		$this->user_id = $user_id;
	}
	public function get_id() {
		return $this->user_id;
	}
	public function set_email($user_email='') {
		$this->user_email = $user_email;
	}
	public function get_email() {
		return $this->user_email;
	}
	public function set_password($user_password='') {
		$this->user_password = $user_password;
	}
	public function set_brand($brand='') {
		$this->brand = $brand;
	}
	public function get_brand() {
		return $this->brand;
	}
	public function set_tax($tax='') {
		$this->tax = $tax;
	}
	public function get_tax() {
		return $this->tax;
	}
	public function set_address($address='') {
		$this->address = $address;
	}
	public function get_address() {
		return $this->address;
	}
	public function set_phone($phone='') {
		$this->phone = $phone;
	}
	public function get_phone() {
		return $this->phone;
	}
	public function set_cell($cell='') {
		$this->cell = $cell;
	}
	public function get_cell() {
		return $this->cell;
	}
	public function set_owner($owner='') {
		$this->owner = $owner;
	}
	public function get_owner() {
		return $this->owner;
	}
	public function set_employee($employee='') {
		$this->employee = $employee;
	}
	public function get_employee() {
		return $this->employee;
	}
	public function set_duration($duration='') {
		$this->duration = $duration;
	}
	public function get_duration() {
		return $this->duration;
	}
	public function set_time_start($time_start='') {
		$this->time_start = $time_start;
	}
	public function get_time_start() {
		return $this->time_start;
	}
	public function set_time_end($time_end='') {
		$this->time_end = $time_end;
	}
	public function get_time_end() {
		return $this->time_end;
	}
	public function validate() {
		Global $DATABASE;
		$output = false;
		$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$this->user_email."' AND `password`=MD5('".$this->user_password."');";
		$result = $DATABASE->get($sql);
		if ($result[0][0] == 1) {
			$sql = "SELECT * FROM `users` WHERE `email`='".$this->user_email."' AND `password`=MD5('".$this->user_password."');";
			$result = $DATABASE->get($sql);
			$this->user_id = $result[0]['id'];
			$this->brand = $result[0]['brand'];
			$this->tax = $result[0]['tax'];
			$this->address = $result[0]['address'];
			$this->phone = $result[0]['phone'];
			$this->cell = $result[0]['cell'];
			$this->owner = $result[0]['owner'];
			$this->employee = $result[0]['employee'];
			$this->duration = $result[0]['duration'];
			$this->time_start = $result[0]['time_start'];
			$this->time_end = $result[0]['time_end'];
			$output = true;
		} else {
			$output = false;
		}
		return $output;
	}
	public function calculate_password() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$random_password = array();
		$alphabet_length = strlen($alphabet) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphabet_length);
			$random_password[] = $alphabet[$n];
		}
		return implode($random_password);
	}
	public function create($register_email) {
		Global $DATABASE;
		Global $EMAIL;
		Global $CONFIG;
		Global $LANGUAGE;
		$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$register_email."';";
		$result = $DATABASE->get($sql);
		$new_password = $this->calculate_password();
		if ($result[0][0] == 0) {
			$sql = "INSERT INTO `users` (`id`, `email`, `password`, `date_registered`, `brand`, `tax`, `address`, `phone`, `cell`, `owner`, `employee`, `duration`, `time_start`, `time_end`) VALUES (NULL, '".nl2br($register_email)."', MD5('".nl2br($new_password)."'), NOW(), NULL, NULL, NULL, NULL, NULL, NULL, 1, 30, '07:00', '23:00');";
			$result = $DATABASE->set($sql);
			$new_user = $DATABASE->get_id();
			$this->user_id = $new_user;
			$this->user_email = $register_email;
			$this->user_password = $new_password;
			$EMAIL->set_author($CONFIG->get('APPLICATION', 'PRODUCT'));
			$EMAIL->set_origin($CONFIG->get('APPLICATION', 'CONTACT'));
			$EMAIL->set_destination($register_email);
			$EMAIL->set_subject($LANGUAGE->get('STRING', 'PASSWORD_TEMPORARY'));
			$EMAIL->set_message($LANGUAGE->get('STRING', 'PASSWORD_NEW').' '.$new_password);
			$EMAIL->send();
			return $new_user;
		} else {
			return -1;
		}
	}
	public function recover($register_email) {
		Global $DATABASE;
		Global $EMAIL;
		Global $CONFIG;
		Global $LANGUAGE;
		$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$register_email."';";
		$result = $DATABASE->get($sql);
		$new_password = $this->calculate_password();
		if ($result[0][0] == 1) {
			$sql = "UPDATE `users` SET `password` = MD5('".nl2br($new_password)."') WHERE `email` = '".$register_email."';";
			$result = $DATABASE->set($sql);
			$this->user_email = $register_email;
			$this->user_password = $new_password;
			$EMAIL->set_author($CONFIG->get('APPLICATION', 'PRODUCT'));
			$EMAIL->set_origin($CONFIG->get('APPLICATION', 'CONTACT'));
			$EMAIL->set_destination($register_email);
			$EMAIL->set_subject($LANGUAGE->get('STRING', 'PASSWORD_TEMPORARY'));
			$EMAIL->set_message($LANGUAGE->get('STRING', 'PASSWORD_NEW').' '.$new_password);
			$EMAIL->send();
			return $new_user;
		} else {
			return -1;
		}
	}
	public function get_registration() {
		Global $DATABASE;
		$sql = "SELECT date_registered FROM `users` WHERE `email`='".$this->user_email."' AND `password`=MD5('".$this->user_password."') LIMIT 0,1;";
		$result = $DATABASE->get($sql);
		return $result[0][0];
	}
	public function update_password($old_password, $new_password) {
		Global $DATABASE;
		$output = false;
		$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$this->user_email."';";
		$result = $DATABASE->get($sql);
		if ($result[0][0] == 1) {
			$output = true;
			$sql = "UPDATE `users` SET `password` = MD5('".nl2br($new_password)."') WHERE `password` = MD5('".nl2br($old_password)."') AND `email` = '".nl2br($this->user_email)."';";
			$result = $DATABASE->set($sql);
		} else {
			$output = false;
		}
		return $output;
	}
	public function update_business($brand, $tax, $address, $phone, $cell, $owner) {
		Global $DATABASE;
		$output = false;
		$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$this->user_email."';";
		$result = $DATABASE->get($sql);
		if ($result[0][0] == 1) {
			$output = true;
			$sql = "UPDATE `users` SET `brand` = '".nl2br($brand)."', `tax` = '".nl2br($tax)."', `address` = '".nl2br($address)."', `phone` = '".nl2br($phone)."', `cell` = '".nl2br($cell)."', `owner` = '".nl2br($owner)."' WHERE `password` = MD5('".$this->user_password."') AND `email` = '".nl2br($this->user_email)."';";
			$result = $DATABASE->set($sql);
		} else {
			$output = false;
		}
		return $output;
	}
	public function update_preferences($employees, $duration, $time_start, $time_end) {
		Global $DATABASE;
		$output = false;
		$sql = "SELECT COUNT(*) FROM `users` WHERE `email`='".$this->user_email."';";
		$result = $DATABASE->get($sql);
		if ($result[0][0] == 1) {
			$output = true;
			$sql = "UPDATE `users` SET `employee` = '".nl2br($employees)."', `duration` = '".nl2br($duration)."', `time_start` = '".nl2br($time_start)."', `time_end` = '".nl2br($time_end)."' WHERE `password` = MD5('".$this->user_password."') AND `email` = '".nl2br($this->user_email)."';";
			$result = $DATABASE->set($sql);
		} else {
			$output = false;
		}
		return $output;
	}
	public function delete() {
		Global $DATABASE;
		$sql = "DELETE FROM `users` WHERE `id` = '".$this->user_id."';";
		$result = $DATABASE->set($sql);
		$sql = "DELETE FROM `events` WHERE `user` = '".$this->user_id."';";
		$result = $DATABASE->set($sql);
	}
}

// Methods
// .set_author (text)
// .set_origin (email)
// .set_destination (email)
// .set_subject (text)
// .set_message (text)
// .send ()

class email {
	private $author;
	private $origin;
	private $destination;
	private $subject;
	private $message;
	public function set_author($author) {
		$this->author;
	}
	public function set_origin($origin) {
		$this->origin = $origin;
	}
	public function set_destination($destination) {
		$this->destination = $destination;
	}
	public function set_subject($subject) {
		$this->subject = $subject;
	}
	public function set_message($message) {
		$this->message = $message;
	}
	public function send() {
		return mail($this->destination, "=?UTF-8?B?".base64_encode($this->subject)."?=", $this->message, "From: "."=?UTF-8?B?".base64_encode($this->author)."?="." ".$this->origin."\r\n"."MIME-Version: 1.0"."\r\n"."Content-type: text/html; charset=UTF-8"."\r\n");
	}
}

// Methods
// ~constructor (file)
// .open (file)
// .set (key, value)
// .get ()

class template {
	protected $file;
	protected $values = array();
	public function __construct($file) {
		$this->file = $file;
		if($file != '') {
			if(is_readable($file)) {
				$this->file = $file;
				return(TRUE);
			} else {
				return(FALSE);
			}
		}
	}
	public function open($file) {
		$this->file = $file;
		if($file != '') {
			if(is_readable($file)) {
				$this->file = $file;
				return(TRUE);
			} else {
				return(FALSE);
			}
		}
	}
	public function set($key, $value) {
		$this->values[$key] = $value;
	}
	public function get() {
		if (!file_exists($this->file)) {
			die($this->file);
		}
		$output = file_get_contents($this->file);
		foreach ($this->values as $key => $value) {
			$tagToReplace = "[@$key]";
			$output = str_replace($tagToReplace, $value, $output);
		}
		return $output;
	}
}

// Methods
// ~constructor (file)
// .open (file)
// .read ()
// .write ()
// .exist (section, key)
// .set (section, key, value)
// .get (section, key)
// .delete (section, key)

class ini {
	private $file = NULL;
	private $data = array();
	public function __construct($file) {
		$this->file = $file;
		if($file != '') {
			if(is_readable($file)) {
				$this->file = $file;
				return(TRUE);
			} else {
				return(FALSE);
			}
		} else {
			return(FALSE);
		}
	}	
	public function open($file) {
		$this->file = $file;
		if($file != '') {
			if(is_readable($file)) {
				$this->file = $file;
				return(TRUE);
			} else {
				return(FALSE);
			}
		} else {
			return(FALSE);
		}
	}
	public function read() {
		$this->data = parse_ini_file(realpath($this->file), TRUE);
		if ($this->data == FALSE) {
			return(FALSE);
		} else {
			return(TRUE);
		}
	}
	public function write() {
		$content = NULL;
		foreach ($this->data as $section => $data) {
			$content = $content.'['.$section.']'.PHP_EOL;
			foreach ($data as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $v) {
						$content = $content.$key.'[] = '.(is_numeric($v) ? $v : '"'.$v.'"').PHP_EOL;
					}
				} elseif (empty($val)) {
					$content = $content.$key.' = '.PHP_EOL;
				} else {
					$content = $content.$key.' = '.(is_numeric($val) ? $val : '"'.$val.'"').PHP_EOL;
				}
			}
			$content = $content.PHP_EOL;
		}
		return (($handle = fopen($this->file, 'w')) && fwrite($handle, ";<?php".PHP_EOL.";die();".PHP_EOL.";/*".PHP_EOL.trim($content).PHP_EOL.";*/".PHP_EOL.";?>".PHP_EOL) && fclose($handle)) ? TRUE : FALSE;
	}
	public function exist($section, $key = NULL) {
		if ($key != NULL ) {
			if (isset($this->data[$section][$key])) {
				return(TRUE);
			} else {
				return(FALSE);
			}
		} else {
			if (isset($this->data[$section])) {
				return(TRUE);
			} else {
				return(FALSE);
			}
		}
	}
	public function get($section, $key) {
		if (isset($this->data[$section][$key])) {
			return $this->data[$section][$key];
		} else {
			return(FALSE);
		}
	}
	public function set($section, $key, $value) {
		if (isset($this->data[$section][$key])) {
			$this->data[$section][$key] = $value;
			return(TRUE);
		} else {
			$this->data[$section][$key] = $value;
			return(FALSE);
		}
	}
	public function delete($section, $key = NULL) {
		if ($key != NULL ) {
			if (isset($this->data[$section][$key])) {
				unset($this->data[$section][$key]);
				return(TRUE);
			} else {
				return(FALSE);
			}
		} else {			
			if (isset($this->data[$section])) {
				unset($this->data[$section]);
				return(TRUE);
			} else {
				return(FALSE);
			}
		}
	}
}

// Methods
// ~constructor (host, name, password, user)
// .connect ()
// .set (query)
// .get (query)
// .count (query)
// .get (key)
// .get_id ()

class database {
	private $db_user;
	private $db_password;
	private $db_host;
	private $db_name;
	private $db_connection;
	function __construct($host, $name, $password, $user) {
		$this->db_host = $host;
		$this->db_name = $name;
		$this->db_password = $password;
		$this->db_user = $user;
		$this->db_connection = "";
	}
	public function connect() {
		$this->db_connection = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_name);
		mysqli_set_charset($this->db_connection, 'utf8');
	}
	public function set($query) {
		mysqli_query($this->db_connection, $query) or die(mysqli_error($this->db_connection));
	}
	public function get($query) {
		$result = mysqli_query($this->db_connection, $query) or die(mysqli_error($this->db_connection));
		$returnarray = array ();
		while ($row = mysqli_fetch_array($result)) {
			$returnarray[] = $row;
		}
		return $returnarray;
	}
	public function count($query) {
		$result = mysqli_query($this->db_connection, $query) or die(mysqli_error($this->db_connection));
		$rows = mysqli_num_rows($result);
		return $rows;
	}
	public function get_id() {
		return mysqli_insert_id($this->db_connection);
	}
}

// Methods
// ~constructor ()
// .destroy ()
// .exist (key)
// .delete ()
// .set (key, value)
// .get (key)

class session {
	public function __construct() {
		if(!isset($_SESSION)) {
			session_start();
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function destroy() {
		setcookie(session_name(), NULL, 0, "/");
		session_destroy();
		session_unset();
	}
	public function set($key, $value) {
		return $_SESSION[$key] = $value;
	}
	public function exist($key) {
		if(isset($_SESSION[$key])) {
			return TRUE;
		}
		return FALSE;
	}
	public function delete($key) {
		unset($_SESSION[$key]);
	}
	public function get($key) {
		if(!isset($_SESSION[$key])) {
			return FALSE;
		}
		return $_SESSION[$key];
	}
}

// Methods
// ~constructor ()
// setDates(year, month)
// get_date()
// get_prevdate()
// get_nextdate()
// getCalendar()

class calendar {
	private $curr_date;
	private $prev_date;
	private $next_date;
	private $first_cal_day, $last_cal_day, $num_weeks, $has_events;
	public function __construct($year, $month) {
		$this->setDates($year, $month);
	}
	public function setDates($year, $month) {
		$this->curr_date = new DateTime();
		$this->curr_date->setDate($year, $month, 1);
		$this->prev_date = new DateTime();
		$this->prev_date->setDate($year, $month, 0);
		$this->next_date = new DateTime();
		$this->next_date->setDate($year, $month + 1, 1);
		$this->first_cal_day = new DateTime();
		$this->first_cal_day->setDate($year, $month, 2)->modify('previous Monday');
		$this->last_cal_day = new DateTime();
		$this->last_cal_day->setDate($year, $month + 1, -1)->modify('first Sunday');
		$diff = $this->first_cal_day->diff($this->last_cal_day);
		$this->num_weeks = ceil ($diff->days / 7);
	}
	public function get_date() {
		return $this->curr_date;
	}
	public function get_prevdate() {
		return $this->prev_date;
	}
	public function get_nextdate() {
		return $this->next_date;
	}
	public function getCalendar() {
		$calendar = [];
		$oneday = new DateInterval('P1D');
		$calday = $this->first_cal_day;
		for ($w = 1; $w <= $this->num_weeks+1; $w++) {
			for ($d = 1; $d < 8; $d++) {
				$day = new stdClass();
				$event_date = $calday->format("Y-m-d");
				$day->date = clone $calday;
				$calendar[] = $day;
				$calday->add($oneday);
			}
		}
		return $calendar;
	}
}

// Methods
// dayevents_exists(user, day, month, year)
// dayevents_get(user, day, month, year)
// event_new(user, year, month, day, time, duration, customer , contact, description)
// event_get(user, id)
// event_set(user, id, year, month, day, time, duration, customer, contact, description)
// event_delete(user, id)
// customers_get(user)
// statistics(user)
// campaign_email(user)
// campaign_phone(user)

class events {
	public function dayevents_exists($user, $day, $month, $year) {
		Global $DATABASE;
		$sql = "SELECT COUNT(*) FROM `events` WHERE `user`='".$user."' AND DAY(`date`) = ".$day." AND MONTH(`date`) = ".$month." AND YEAR(`date`) = ".$year." AND `active` = 1;";
		$result = $DATABASE->get($sql);
		return $result[0][0];
	}
	public function dayevents_get($user, $day, $month, $year) {
		Global $DATABASE;
		$sql = "SELECT * FROM `events` WHERE `user`='".$user."' AND DAY(`date`) = ".$day." AND MONTH(`date`) = ".$month." AND YEAR(`date`) = ".$year."  AND `active` = 1 ORDER BY `time`;";
		$result = $DATABASE->get($sql);
		return $result;
	}
	public function event_new($user, $year, $month, $day, $time, $duration, $customer , $contact, $description) {
		Global $DATABASE;
		$sql = "INSERT INTO `events` (`id`, `user`, `date`, `time`, `duration`, `customer`, `contact`, `description`,  `active`) VALUES (NULL, '".$user."', '".$year."-".$month."-".$day."', '".$time."', '".$duration."', '".$customer."', '".$contact."', '".$description."', 1); ";
		$result = $DATABASE->set($sql);	
	}
	public function event_get($user, $id) {
		Global $DATABASE;
		$sql = "SELECT * FROM `events` WHERE `user`='".$user."' AND `id` = ".$id." AND `active` = 1;";
		$result = $DATABASE->get($sql);
		return $result;
	}
	public function event_set($user, $id, $year, $month, $day, $time, $duration, $customer, $contact, $description) {
		Global $DATABASE;
		$sql = "UPDATE `events` SET `date` = '".$year."-".$month."-".$day."', `time` = '".$time."', `duration` = '".$duration."', `customer` = '".$customer."', `contact` = '".$contact."', `description` = '".$description."' WHERE `id` = '".$id."' AND `user`='".$user."'  AND `active` = 1;";
		$result = $DATABASE->set($sql);
		return $result;
	}
	public function event_delete($user, $id) {
		Global $DATABASE;
		$sql = "UPDATE `events` SET `active` = 0 WHERE `user`='".$user."' AND `id` = ".$id.";";
		$result = $DATABASE->set($sql);
		return $result;
	}
	public function customers_search($user, $order='customer', $order_level='ASC', $search = '') {
		Global $DATABASE;
		if ($search == '') {
			$sql = "SELECT `customer`, `contact`, `description` FROM `events` WHERE `user`='".$user."' ORDER BY `".$order."` ".$order_level.";";
		} else {
			$sql = "SELECT DISTINCT `customer`, `contact`, `description` FROM `events` WHERE `user`='".$user."' AND (`customer` LIKE '%".nl2br($search)."%' OR `contact` LIKE '%".nl2br($search)."%') ORDER BY `".$order."` ".$order_level.";";
		}
		$result = $DATABASE->get($sql);
		return $result;
	}
	public function events_search($user, $search = '', $from = 0, $to = 20) {
		Global $DATABASE;
		if ($search == '') {
			$sql = "SELECT * FROM `events` WHERE `user`='".$user."' LIMIT ".$from.",".$to.";";
		} else {
			$sql = "SELECT * FROM `events` WHERE `user`='".$user."' AND (`customer` LIKE '%".nl2br($search)."%' OR `contact` LIKE '%".nl2br($search)."%') LIMIT ".$from.",".$to.";";
		}
		$result = $DATABASE->get($sql);
		return $result;
	}
	public function stat($user) {
		Global $DATABASE;
		$statistics = [];		
		$sql = "SELECT COUNT(*) FROM `events` WHERE `user`='".$user."';";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['TOTAL'] = $result[0][0];
		$sql = "SELECT COUNT(*) FROM `events` WHERE `user`='".$user."' AND `active` = 1;";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['COMPLETED'] = $result[0][0];
		$statistics['EVENTS']['CANCELLED'] = $statistics['EVENTS']['TOTAL'] - $statistics['EVENTS']['COMPLETED'];
		$sql = "SELECT MIN(`date`) FROM `events` WHERE `user`='".$user."' AND `active` = 1;";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['OLDEST'] = $result[0][0];
		$sql = "SELECT MAX(`date`) FROM `events` WHERE `user`='".$user."' AND `active` = 1;";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['NEWEST'] = $result[0][0];
		$sql = "SELECT MIN(`duration`) FROM `events` WHERE `user`='".$user."' AND `active` = 1;";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['SHORTEST'] = $result[0][0];
		$sql = "SELECT MAX(`duration`) FROM `events` WHERE `user`='".$user."' AND `active` = 1;";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['LONGEST'] = $result[0][0];		
		$sql = "SELECT `description`, COUNT(*) FROM `events` WHERE `user`='".$user."' GROUP BY `description` ORDER BY COUNT(*) DESC LIMIT 5;";
		$result = $DATABASE->get($sql);
		$statistics['EVENTS']['COMMON'] = $result;
		$sql = "SELECT `customer`, `contact`, COUNT(*) FROM `events` WHERE `user`='".$user."' GROUP BY `contact` ORDER BY COUNT(*) ASC LIMIT 5;";
		$result = $DATABASE->get($sql);
		$statistics['CUSTOMER']['LOYAL'] = $result;
		$sql = "SELECT `customer`, `contact`, COUNT(*) FROM `events` WHERE `user`='".$user."' GROUP BY `contact` ORDER BY COUNT(*) DESC LIMIT 5;";
		$result = $DATABASE->get($sql);
		$statistics['CUSTOMER']['DISLOYAL'] = $result;
		$sql = "SELECT table_name , round(((data_length + index_length) / 1024 / 1024), 2) as SIZE_MB FROM information_schema.TABLES WHERE table_schema = DATABASE() ORDER BY SIZE_MB DESC;";
		$result = $DATABASE->get($sql);
		$statistics['DATABASE']['SIZE'] = $result[1][1];
		return $statistics;
	}
	public function campaign_email($user) {
		Global $DATABASE;
		$sql = "SELECT DISTINCT `contact` FROM `events` WHERE `contact` REGEXP '^[^@]+@[^@]+\.[^@]{2,}$' AND `user`='".$user."';";
		$result = $DATABASE->get($sql);
		return $result;
	}
	public function campaign_phone($user) {
		Global $DATABASE;
		$sql = "SELECT DISTINCT `contact` FROM `events` WHERE `contact` REGEXP '69[0-9]{8}' AND `user`='".$user."';";
		$result = $DATABASE->get($sql);
		return $result;
	}
}

// Methods
// ~constructor ()
// set(key, value)
// to_string()
// build_props()
// sanitize_val(val, key)
// format_timestamp(timestamp)
// escape_string(str)

class ics {
	const DT_FORMAT = 'Ymd\THis\Z';
	protected $properties = array();
	private $available_properties = array('description', 'dtend', 'dtstart', 'location', 'summary', 'url');
	public function __construct($props) {
		$this->set($props);
	}
	public function set($key, $val = false) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->set($k, $v);
			}
		} else {
			if (in_array($key, $this->available_properties)) {
				$this->properties[$key] = $this->sanitize_val($val, $key);
			}
		}
	}
	public function to_string() {
		$rows = $this->build_props();
		return implode("\r\n", $rows);
	}
	private function build_props() {
		$ics_props = array('BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//hacksw/handcal//NONSGML v1.0//EN', 'CALSCALE:GREGORIAN', 'BEGIN:VEVENT');
		$props = array();
		foreach($this->properties as $k => $v) {
			$props[strtoupper($k . ($k === 'url' ? ';VALUE=URI' : ''))] = $v;
		}
		$props['DTSTAMP'] = $this->format_timestamp('now');
		$props['UID'] = uniqid();
		foreach ($props as $k => $v) {
			$ics_props[] = "$k:$v";
		}
		$ics_props[] = 'END:VEVENT';
		$ics_props[] = 'END:VCALENDAR';
		return $ics_props;
	}
	private function sanitize_val($val, $key = false) {
		switch($key) {
			case 'dtend':
			case 'dtstamp':
			case 'dtstart':
				$val = $this->format_timestamp($val);
				break;
			default:
				$val = $this->escape_string($val);
		}
		return $val;
	}
	private function format_timestamp($timestamp) {
		$dt = new DateTime($timestamp);
		return $dt->format(self::DT_FORMAT);
	}
	private function escape_string($str) {
		return preg_replace('/([\,;])/','\\\$1', $str);
	}
}
?>
