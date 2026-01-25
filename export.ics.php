<?php
	// Setup globals
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
	// Include all libraries
	foreach (glob('*.inc') as $filename) require_once $filename;
	// Load system core
	$core = new core();
	$location = '';
	if (isset($_POST['location']))
		$location = $_POST['location'];
	else
		if (isset($_GET['location']))
			$location = $_GET['location'];
	$description = '';
	if (isset($_POST['description']))
		$description = $_POST['description'];
	else
		if (isset($_GET['description']))
			$description = $_GET['description'];
	$dtstart = '';
	if (isset($_POST['dtstart']))
		$dtstart = $_POST['dtstart'];
	else
		if (isset($_GET['dtstart']))
			$dtstart = $_GET['dtstart'];
	$dtend = '';
	if (isset($_POST['dtend']))
		$dtend = $_POST['dtend'];
	else
		if (isset($_GET['dtend']))
			$dtend = $_GET['dtend'];
	$summary = '';
	if (isset($_POST['summary']))
		$summary = $_POST['summary'];
	else
		if (isset($_GET['summary']))
			$summary = $_GET['summary'];
	$url = '';
	if (isset($_POST['url']))
		$url = $_POST['url'];
	else
		if (isset($_GET['url']))
			$url = $_GET['url'];
	header('Content-Type: text/calendar; charset=utf-8');
	header('Content-Disposition: attachment; filename=invite.ics');
	$ics = new ics(array('location' => $location, 'description' => $description, 'dtstart' => $dtstart, 'dtend' => $dtend, 'summary' => $summary, 'url' => $url));
	echo $ics->to_string();
?>
