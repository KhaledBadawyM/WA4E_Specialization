<?php
	require_once "pdo.php";
	//startsession
	session_start();
	if ( ! isset($_SESSION['email']) ) {
	  die('Not logged in');
	}

	if(isset($_POST['cancel']))
	{
		header('Location: view.php');
		return ; 
	}

	if(isset($_POST['add']))
	{
		if($_POST['make']==""){
			//echo "Make is required";
			$_SESSION['emptyField']="Make is required" ;
			header('Location: add.php');
			return ;
			
		}
		elseif( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))
		{
			//echo "Mileage and year must be numeric";
			$_SESSION['numericError'] =  "Mileage and year must be numeric";
			header('Location: add.php');
			return ;
		}
		else{
 			
 			$sql = "INSERT INTO autos (make,year,mileage,email) VALUES (:mk ,:y ,:ml,:em)";
			$stmtq = $pdo->prepare($sql);
			$stmtq->execute(array(
				'mk'  =>htmlentities($_POST['make']),
				'y'   =>$_POST['year'],
				'ml'  =>$_POST['mileage'],
				'em'  =>$_SESSION['email'] 
	 		));	

			$_SESSION['inserted'] = "Record inserted";
			header('Location: view.php');
			return ; 
	   }
		
	}
		
?>
<!DOCTYPE html>
<html>
<head>
<title>Khaled Badawy</title>


</head>
<body>
	
<div class="container">
<h1>Tracking Autos for <?php echo $_SESSION['email'] ?> </h1>
	<?php 
		if(isset($_SESSION['emptyField']))
		{
			echo ('<p style="color:red;">'.htmlentities($_SESSION["emptyField"])."</p>\n" );
			unset($_SESSION['emptyField']);
		}

		elseif(isset($_SESSION['numericError']))
		{
			echo ('<p style="color:red;">'.htmlentities($_SESSION["numericError"])."</p>\n" );
			unset($_SESSION['numericError']);
		}

	?>
<form method="post">
	<p>Make:
	<input type="text" name="make" size="60"/></p>
	<p>Year:
	<input type="text" name="year"/></p>
	<p>Mileage:
	<input type="text" name="mileage"/></p>
	<input type="submit" value="Add" name="add">
	<input type="submit" name="cancel" value="Cancel">
</form>


</div>
</body>
</html>