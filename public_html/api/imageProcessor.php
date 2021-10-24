<?PHP

	require_once("../../includes/constants.php");

	// Show output as json
	header('Content-Type: application/json; charset=utf-8'); 
	
	// fixes cors issue
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: PUT, GET, POST");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	
	$secretCheck1 = isset($_GET['secret']) ? sha1($_GET['secret']) : '';
	$secretCheck2 = isset($_GET['secret2']) ? sha1($_GET['secret2']) : '';
	
	// VALIDATE MUST PASS OR NOTHING WILL HAPPEN
	if (sha1(SECRET_CHECK_1) !== $secretCheck1 || sha1(SECRET_CHECK_2) !== $secretCheck2) {
		echo json_encode(['status' => 'not authenticated']);
		return;
	}
	
	if (preg_match("/\./i", $_FILES['tshirtImage']['name'])) {
		//echo 'true';

		$fileNameNoExtension = substr($_FILES['tshirtImage']['name'], 0, strlen($_FILES['tshirtImage']['name'])-4);
		//echo $fileNameNoExtension;
	}
	else {
		$fileNameNoExtension = $_FILES['tshirtImage']['name'];
	}
	
	$fileNameNoExtension = preg_replace('/[^a-zA-Z0-9_ -.$!?\&,;\']/s','',$fileNameNoExtension);
	// this just renames the file
	function tempnam_sfx($path, $suffix, $fileName){
		$f = $fileName;
		do {
            $file = $path."/".$f.$suffix;
            $fp = @fopen($file, 'x');
        }
        while(!$fp);

        fclose($fp);
        return $file;
    }

	if ($_FILES['tshirtImage']['type'] !== 'image/png' && $_FILES['tshirtImage']['type'] !== 'image/jpeg') {
		echo  json_encode(['status' => 'snow']);
		return;
	}
	else if ($_FILES['tshirtImage']['error'] != 0) {
		echo  json_encode(['status' => 'white']);
		return;
	}
	
	$uploaddir = '../../img';
	
	// Delete the file if it exists
	// This will create bugs if the file has the same name as the one in the folder
	@unlink($uploaddir."/".$fileNameNoExtension.".temporary");
	
	$uploadfile = tempnam_sfx($uploaddir, ".temporary", $fileNameNoExtension);
	
	 if (move_uploaded_file($_FILES['tshirtImage']['tmp_name'], $uploadfile)) {
		 echo json_encode(['status' => 'done']);
	 }
  
