<?php // line added to turn on color syntax highlight
	require_once "pdo.php" ;
	session_start();
	if ( ! isset($_SESSION['email']) ) {
	  die('Not logged in');
	}

	if(isset($_POST['logout']))
	{
		header('Location: logout.php');
		return ; 
	}

	if(isset($_POST['addnew']))
	{
		header('Location: add.php');
		return ;
	}


?>

<!DOCTYPE html>
<html>
<head>
<title>Khaled Badawy</title>

</head>
<body>
<div class="container">
<h1>Tracking Autos for  <?php echo($_SESSION['email']) ?></h1>
<?php 
if(isset($_SESSION['inserted']))
	{
		echo ('<p style="color:green;">'.htmlentities($_SESSION["inserted"])."</p>\n" );
		unset($_SESSION['inserted']);
	}
?>
<h2>Automobiles</h2>
	<ul>
		<?php 

			$sql = "SELECT * FROM autos WHERE email= '$_SESSION[email]'";
			$stmt2 = $pdo->prepare($sql) ;
			$stmt2->execute();
			$data_row = $stmt2->fetchAll(PDO::FETCH_ASSOC);

			foreach ($data_row as $r) {

				echo "<li>".$r['year']." ".$r['make']." / ".$r['mileage']."<br></li>"; 
			 }

		 ?>
	</ul>

	<a href="add.php">Add New</a> |
    <a href="logout.php">Logout</a>

</div>
</body>
</html>