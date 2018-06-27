
<?php  

if(! isset($_GET['term']))// $_GET['term'] is sending by jquery 
{
	die('Missing requierd parameter');
}

require_once "pdo.php";
header("Content-type:application/json;charset=utf-8 ");
	$stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
	$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
	$retval = array();
	while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
	  $retval[] = $row['name'];
	}

	echo(json_encode($retval, JSON_PRETTY_PRINT));

?>