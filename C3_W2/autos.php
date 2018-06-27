<?php
	require_once "pdo.php";
	if(!isset($_GET['name']))
	{
		die("Name parameter missing");
	}

	if(isset($_POST['logout']))
	{
		header('Location: index.php');
	}

	if(isset($_POST['add']))
	{
		if($_POST['make']==""){
			echo "Make is required";
		}
		elseif( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))
			echo "Mileage and year must be numeric";
		

		else{
			$sql = "INSERT INTO autos (make,year,mileage,email) VALUES (:mk ,:y ,:ml,:em)";
			$stmtq = $pdo->prepare($sql);
			$stmtq->execute(array(
				'mk'  =>htmlentities($_POST['make']),
				'y'   =>$_POST['year'],
				'ml'  =>$_POST['mileage'],
				'em'  =>$_GET['name'] 
	 		));	

			//echo "Record inserted" ;
			echo '<span style="color:green;text-align:center;">Record inserted</span>';  
	   }
		
	}
		
?>
<!DOCTYPE html>
<html>
<head>
<title>Khaled Badawy</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
	
<div class="container">
<h1>Tracking Autos for <?php echo $_GET['name'] ?> </h1>

<form method="post">
	<p>Make:
	<input type="text" name="make" size="60"/></p>
	<p>Year:
	<input type="text" name="year"/></p>
	<p>Mileage:
	<input type="text" name="mileage"/></p>
	<input type="submit" value="Add" name="add">
	<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<ul>
	<?php 

		$sql = "SELECT * FROM autos WHERE email= '$_GET[name]'";
		$stmt2 = $pdo->prepare($sql) ;
		$stmt2->execute();
		$data_row = $stmt2->fetchAll(PDO::FETCH_ASSOC);

		foreach ($data_row as $r) {

			echo "<li>".$r['year']." ".$r['make']." / ".$r['mileage']."<br></li>"; 
		 }

	 ?>
</ul>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/f2bf09f8/cloudflare-static/email-decode.min.js"></script></body>
</html>
