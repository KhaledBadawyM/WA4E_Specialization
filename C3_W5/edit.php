<?php
require_once "pdo.php";
session_start();
if(! isset($_SESSION['email']))
{
    die("ACCESS DENIED");
}

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year'])&& isset($_POST['mileage']) && isset($_POST['auto_id']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;
    }

     if ( !(is_numeric($_POST['year'])  )) {
        $_SESSION['error_nonYear'] = 'Year must be numeric';
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;
    }

    if ( !(is_numeric($_POST['mileage'])  )) {
        $_SESSION['error_nonMileage'] = 'mileage must be numeric';
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;
    }

    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year , mileage=:mileage
            WHERE auto_id = :auto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':auto_id' => $_POST['auto_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['auto_id']) ) {
  $_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern

$mk = htmlentities($row['make']);
$md = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mg = htmlentities($row['mileage']);
$auto_id = $row['auto_id'];
?>
<p>Edit Automobile</p>
    <?php 
        if ( isset($_SESSION['error']) ) {
            echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
             unset($_SESSION['error']);
         }
        elseif ( isset($_SESSION['error_nonYear']) ) {
            echo '<p style="color:red">'.$_SESSION['error_nonYear']."</p>\n";
            unset($_SESSION['error_nonYear']);
        }
        elseif ( isset($_SESSION['error_nonMileage']) ) {
            echo '<p style="color:red">'.$_SESSION['error_nonMileage']."</p>\n";
            unset($_SESSION['error_nonMileage']);    
        }
     
    ?>

<form method="post">
<p>Name:
<input type="text" name="make" value="<?= $mk ?>"></p>
<p>Email:
<input type="text" name="model" value="<?= $md ?>"></p>
<p>year:
<input type="text" name="year" value="<?= $y ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mg ?>"></p>
<input type="hidden" name="auto_id" value="<?= $auto_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
