<?php

require_once("../../includes/constants.php");
require_once(REQUIRE_LOCATION . '/text_filter.php');
require_once(REQUIRE_LOCATION . '/global_functions.php');
require_once(REQUIRE_LOCATION . '/db_commands.php');

// fixes cors issue
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
$json = file_get_contents("php://input");

//convert the string of data to an array
$data = json_decode($json, true);

$secretCheck1 = isset($data['auth']['secret']) ? sha1($data['auth']['secret']) : '';
$secretCheck2 = isset($data['auth']['secret2']) ? sha1($data['auth']['secret2']) : '';

$apiTag = [];

for ($i = 0; $i < count($request); $i++) {
  if ($request[$i] == 'inventory') {
    @array_push($apiTag, $request[$i+1]);
    @array_push($apiTag, $request[$i+2]); // the last tag after it
    break;
  }

}


// Doesn't need authentication to work
if ($apiTag[1] == 'search') {
  $search = sanitize($apiTag[0]);
  
  if (!empty(SelectSearch($search))) {
	echo SelectSearch($search);
  }
  else {
	echo json_encode(['status' => 'Nothing Found']); 
  }
	  
  
  return ;
}

// EVERYTHING UNDER HERE MUST BE VALIDATED
if (sha1(SECRET_CHECK_1) !== $secretCheck1 || sha1(SECRET_CHECK_2) !== $secretCheck2) {
	echo json_encode(['status' => 'Not Authenticated']);
	return;
}



if ($apiTag[0] == 'update') {
  
  //output the array in the response of the curl request
  $id 			= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['id']);
  $title 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['title']);
  $image 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['imageFile']);
  $price 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['price']);
  $description 	= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['description']);
  $url 			= urlencode($data['tshirt']['url']);
  $tags 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['tags']);
  $location 	= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['location']);
  
  UpdateShirt($id, $title, $image, $price, $description, $url, $tags, $location);
 
  echo json_encode(['status' => 'Update Success']);
  return;
}
else if ($apiTag[0] == 'create') {
  
 // get file name
  $file = $data['tshirt']['imageFile'];
  $file = str_replace( "\\", '/', $file );
  $file = basename( $file );
  
  $title 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['title']);
  $image 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$file);
  $price 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['price']);
  $description 	= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['description']);
  $url 			= urlencode($data['tshirt']['url']);
  $tags 		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['tags']);
  $location 	= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['location']);
  
  
  
  CreateShirt($title, $image, $price, $description, $url, $tags, $location);
 
  echo json_encode(['status' => 'Creation Success']);
  return;
}
else if ($apiTag[0] == 'delete') {
  
  $id 			= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['id']);
  $fileName		= preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$data['tshirt']['imageFile']);
  
  DeleteShirt($id);
  
  $uploaddir = '../../img';
	
  // Delete the file if it exists
  @unlink($uploaddir."/".$fileName.".temporary");
 
  echo json_encode(['status' => 'Delete Success']);
  return;
}


/*
v1/invetory/{tag}/search
v1/invetory/{id}/delete
v1/invetory/update
v1/invetory/create
v1/image/{name}
*/
 ?>
