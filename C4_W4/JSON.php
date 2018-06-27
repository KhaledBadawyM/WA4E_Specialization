<?php
	require_once "pdo.php"; 
	session_start();

	$profile_id = $_SESSION['profile_id'];//file_get_contents("file.json");
	header('Content-Type: application/json; charset=utf-8');

	$education=[] ;
    $sql = "SELECT year , name FROM Education JOIN Institution ON Education.institution_id=Institution.institution_id WHERE profile_id ='$profile_id' ORDER BY rank";
    $stmt = $pdo->query($sql);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $education[] = $row ; 
    }

	echo(json_encode($education));
	//file_put_contents('file.json', "");
	
 ?>