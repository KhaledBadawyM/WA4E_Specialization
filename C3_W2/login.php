<p>Please Login</p>
<?php
require_once "pdo.php";

// p' OR '1' = '1
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

if (isset($_POST['login']) /*isset($_POST['email']) && isset($_POST['password']) */ ) {
    
    if(strpos($_POST['who'], '@')==false) {
      echo "Email must have an at-sign (@)"; 
      //return ;
    }
    else{
    $sql = "SELECT email FROM users 
        WHERE pass = :pw";

    //echo "<p>$sql</p>\n";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        /*':em' => $_POST['email'],*/ 
        ':pw' => $_POST['pass']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    var_dump($row);
   if ( $row === FALSE ) {
      error_log("Login fail ".$_POST['who']);
      echo "<h1>Incorrect password</h1>\n";
   } else { 
      error_log("Login success ".$_POST['who']);
      header("Location:autos.php?name=".urlencode($_POST['who']));
   }
 }
}
?>

<html>
<head>
  <title>Khaled Badawy </title>
</head>
<body>

  <body>
<div class="container">
<h1>Please Log In</h1>

<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In" name="login">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the 
programming language used in this class (all lower case) 
followed by 123. -->
</p>
</div>
</body>


<!--
  <form method="post">
  <p>Email:
  <input type="text" size="40" name="email" required></p>
  <p>Password:
  <input type="text" size="40" name="password" required></p>
  <p><input type="submit" value="Log In"/>
  <input type="submit" name="cancel" value="Cancel"></p>
</form>
</body>
</html>

