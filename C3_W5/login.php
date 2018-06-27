<?php
require_once "pdo.php";

	// p' OR '1' = '1
	session_start();

	if ( isset($_POST['cancel'] ) ) {
	    // Redirect the browser to game.php
	    header("Location: index.php");
	    return;
	}

	elseif(isset($_POST['login'])){
		//unset($_SESSION['email']);
		//unset($_SESSION['pass']);
		
		if( $_POST['pass']=="" || $_POST['email']=="")
		{
			//echo ("not set");
			$_SESSION['fieldReq'] = "Email and password are required"; 
			header('Location: login.php') ; 
			return ;
		}
		
		else//if(isset($_POST['pass'])&& isset($_POST['email']))
		{
		
			if(strpos($_POST['email'], '@')==false) 
			{
				$_SESSION['signError'] = "Email must have an at-sign (@)" ;
				header('Location: login.php');
			   		return ;
		  		//echo "Email must have an at-sign (@)"; 
		      //return ;
		    }
		    else{
		    	unset($_SESSION['email']);
			    $sql = "SELECT email FROM users 
			        WHERE password = :pw";

			    //echo "<p>$sql</p>\n";

			    $stmt = $pdo->prepare($sql);
			    $stmt->execute(array(
			        /*':em' => $_POST['email'],*/ 
			        ':pw' => $_POST['pass']));
			    $row = $stmt->fetch(PDO::FETCH_ASSOC);

			    //var_dump($row);
			   if ( $row === FALSE ) {
			      error_log("Login fail ".$_POST['email']);
			      //echo "<h1>Incorrect password</h1>\n";
			   		$_SESSION['error'] = "Incorrect password";
			   		header('Location: login.php');
			   		return ;
			    } else { 
			      error_log("Login success ".$_POST['email']);
			      
			      $_SESSION['email']=$_POST['email'];
			      $_SESSION['success']= "Logged in.";
			      ///echo($_SESSION['email']);
			      header('Location: index.php');
			      return ;
			    }

			}
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
<h1>Please Log In</h1>
	<?php 
		if(isset($_SESSION['error'])){
			echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        	unset($_SESSION["error"]);
		}
		elseif(isset($_SESSION['signError'])){
			echo('<p style="color: red;">'.htmlentities($_SESSION['signError'])."</p>\n");
        	unset($_SESSION["signError"]);	
		}
		elseif(isset($_SESSION['fieldReq'])){
			echo('<p style="color: red;">'.htmlentities($_SESSION['fieldReq'])."</p>\n");
        	unset($_SESSION["fieldReq"]);	
		}

	?>

<form method="POST" action="login.php">
	<label for="em">User Name</label>
	<input type="text" name="email" id="em"><br/>
	<label for="id_1723">Password</label>
	<input type="text" name="pass" id="id_1723"><br/>
	<input type="submit" value="Log In" name = "login">
	<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find an account and password hint
in the HTML comments.
<!-- Hint:
The account is csev@umich.edu
The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>
</div>
</body>

</html>




