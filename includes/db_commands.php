<?PHP

require_once ('constants.php');
require_once (REQUIRE_LOCATION . '/global_functions.php');

header('Content-Type: application/json; charset=utf-8');
// There isn't much sterilization going on. Apparently, prepared statements are more than enough to protect against SQL injection
// Use this to search for something in the database using its id
function SelectSearch ($Keyword)
{

	$count = 0;
	$array = array();

	try
	{
		// First Connect to the database
		$pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=". DB_NAME . ";charset=utf8" , DB_USER, DB_PASS);

		$pdo->exec("set names utf8");		// Added for security

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sth = $pdo->prepare("SELECT * from inventory 
								WHERE tags LIKE :Keyword OR title LIKE :Keyword 
								ORDER BY id DESC
								LIMIT 100"
							);
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute(array(
					':Keyword' =>  '%'.$Keyword.'%'
					));
		$pdo = null; // Kill the Database connection

	 	$result = $sth->fetchAll();

		// Put everything you find in an array
		if(count($result) > 0)
		{
			foreach($result as $r)
			{

				$array[$count] = array();
				$array[$count]['id'] 				= $r['id'];
				$array[$count]['title'] 			= $r['title'];
				$array[$count]['image'] 			= $r['image'];
				$array[$count]['price']				= $r['price'];
				$array[$count]['description'] 		= '';//$r['description']; //to save space on localstorage (frontend)
				$array[$count]['tags'] 				= $r['tags'];
				$array[$count]['url'] 				= $r['url'];
				$array[$count]['location'] 			= $r['location'];

				$count++;
			}
		}
		if ($array == null)
		{
			$pdo = null; // Kill the Database connection
			return null;
		}
		return json_encode($array);	// Everything in the array gets pushed to this return value
	}
	catch (PDOException $e)
	{
		//echo "Error Connecting to Source:" . $e->getMessage();
		$pdo = null; // Kill the Database connection
		die();
	}
	$pdo = null; // Kill the Database connection
	return null;
}

// This is used for SELECT, INSERT, UPDATE, DELETE operations
function CreateShirt($title, $image, $price, $description, $url, $tags, $location)
{
	// First Connect to the database
	$pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=". DB_NAME . ";charset=utf8" , DB_USER, DB_PASS);

	$pdo->exec("set names utf8");		// Added for security

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	try
	{
		// Insert into the Blog Database		
		$sth = $pdo->prepare("INSERT INTO inventory 
									   (title, image, description, url, price, tags, location) 
								VALUES (:Title, :Image, :Description, :Url, :Price, :Tags, :Location)");

		// The values
		$sth->execute(array(
				":Title" => $title,
				":Image" => $image,
				":Description" => $description,
				":Url" => $url,
				":Price" => $price,
				":Tags" => $tags,
				":Location" => $location
				));

	}
	catch(PDOException $e)
	{
		// Report an error
		echo $e->getMessage();
		die();
	}

	// Clear everything
	$pdo = null;
	$sth = null;
}

function UpdateShirt ($id, $title, $image, $price, $description, $url, $tags, $location)
{
	// First Connect to the database
	$pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=". DB_NAME . ";charset=utf8" , DB_USER, DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	try
	{
		$sth = $pdo->prepare("Update inventory SET title = :Title,
												image = :Image,
												description = :Description,
												url = :Url,
												price = :Price,
												image = :Image,
												tags = :Tags,
												location = :Location
							WHERE id = :Id") ;

		$sth->execute(array(

				"Id" => $id,
				"Title" => $title,
				"Image" => $image,
				"Description" => $description,
				"Url" => $url,
				"Price" => $price,
				"Image" => $image,
				"Tags" => $tags,
				"Location" => $location
				));
	}
	catch(PDOException $e)
	{
		// Report an error
		echo $e->getMessage();
		$pdo = null;
		$sth = null;

		die();
	}

	// Clear Everything
	$pdo = null;
	$sth = null;
}

function DeleteShirt ($Id)
{
	// First Connect to the database
	$pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=". DB_NAME . ";charset=utf8" , DB_USER, DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	try
	{
		$sth = $pdo->prepare("DELETE FROM inventory
							WHERE id = :Id") ;

		$sth->execute(array(

				"Id" => $Id

				));
	}
	catch(PDOException $e)
	{
		// Report an error
		echo $e->getMessage();
		die();
	}

	// Clear Everything
	$pdo = null;
	$sth = null;
}

?>
