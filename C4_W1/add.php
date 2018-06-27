<?php
require_once "pdo.php";
session_start();
if(! isset($_SESSION['email']))
{
    die("ACCESS DENIED");
}

if(isset($_POST['cancel']))
{
    header('Location: index.php');
    return ;
}

/*
 $sql = "SELECT user_id from users WHERE email = '$_SESSION[email]' ";
 $stmt = $pdo->query($sql) ;  
 $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
 //print_r($row) ; 

 $_SESSION['user_id']=$row[0]['user_id'];
 //print $_SESSION['user_id'];
*/

if (isset($_POST['add'])) {

    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||strlen($_POST['email']) < 1 ||strlen($_POST['headline']) < 1 ||strlen($_POST['summary']) < 1) {
        $_SESSION['error_missing'] = 'All fields are required';
        header("Location: add.php");
        return;
    }

    if(strpos($_POST['email'], '@')==false) 
    {
        $_SESSION['signError'] = "Email must have an at-sign (@)" ;
        header('Location: add.php');
            return ;
    }    

    $stmt = $pdo->prepare('INSERT INTO Profileuser_id
    (user_id, first_name, last_name, email, headline, summary)
            VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary'])
    );
    $_SESSION['success'] = 'Profile added';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error_missing']) ) {
    echo '<p style="color:red">'.$_SESSION['error_missing']."</p>\n";
    unset($_SESSION['error_missing']);
}
if ( isset($_SESSION['error_nonyear']) ) {
    echo '<p style="color:red">'.$_SESSION['error_nonyear']."</p>\n";
    unset($_SESSION['error_nonyear']);
}
if ( isset($_SESSION['error_nonMileage']) ) {
    echo '<p style="color:red">'.$_SESSION['error_nonMileage']."</p>\n";
    unset($_SESSION['error_nonMileage']);
}

if ( isset($_SESSION['signError']) ) {
    echo '<p style="color:red">'.$_SESSION['signError']."</p>\n";
    unset($_SESSION['signError']);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Khaled Badawy</title>


</head>
<body>
<div class="container">
<h1>Adding Profile for UMSI</h1>
<form method="post">
    <p>First Name:
    <input type="text" name="first_name" size="60"/></p>
    <p>Last Name:
    <input type="text" name="last_name" size="60"/></p>
    <p>Email:
    <input type="text" name="email" size="30"/></p>
    <p>Headline:<br/>
    <input type="text" name="headline" size="80"/></p>
    <p>Summary:<br/>
    <textarea name="summary" rows="8" cols="80"></textarea>
    <p>
    <input type="submit" value="Add" name ="add">
    <input type="submit" name="cancel" value="Cancel">
    </p>
</form>
</div>
</body>
</html>
