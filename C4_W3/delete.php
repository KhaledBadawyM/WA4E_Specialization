<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {

    ///$profile_id = $_GET['profile_id'];//$pdo->lastInsertId();
    $stmt = $pdo->prepare("DELETE FROM Position WHERE profile_id='$_GET[profile_id]' ");
    $stmt->execute();

    $sql = "DELETE FROM Profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT first_name,last_name, profile_id FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: index.php' ) ;
    return;
}

  echo"<h2>Confirm: Deleting</h2>";
  echo ("First Name :".htmlentities($row['first_name'])."<br>"); 
  echo("Last Name :".htmlentities($row['last_name'])."<br>"); 
?>


<form method="post">
  <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
  <input type="submit" value="Delete" name="delete">
  <a href="index.php">Cancel</a>
</form>
