<?php

require_once("../../../includes/constants.php");
require_once(REQUIRE_LOCATION . '/text_filter.php');
require_once(REQUIRE_LOCATION . '/global_functions.php');
require_once(REQUIRE_LOCATION . '/db_commands.php');

// fixes cors issue
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	
$request = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
$json = file_get_contents("php://input");

//convert the string of data to an array
$data = json_decode($json, true);


$apiTag = [];
for ($i = 0; $i < count($request); $i++) {
  if ($request[$i] == 'image') {
    array_push($apiTag, $request[$i]);
    array_push($apiTag, $request[$i+1]); // the last tag after it
    break;
  }

}

if (empty($apiTag[1])) {
  echo json_encode(['status' => 'ok']);
  return;
}


if ($apiTag[0] == 'image') {
	
	$uploaddir = '../../../img/';
	$newfile = $apiTag[1];
	
	// append temporary to find the file
	$result['name'] = $apiTag[1] . '.temporary';
	
	// determine mime type
	if (strpos($newfile, 'jpg') !== false) {
		$result['mime_type'] = 'image/jpeg';
	}
	else {
		$result['mime_type'] = 'image/png';
	}
	

	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename='.basename($newfile));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($uploaddir.$result['name']));
	header("Content-Type: " . $result['mime_type']);

	readfile($uploaddir.$result['name']);
}



 ?>
