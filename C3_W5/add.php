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

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year'])&& isset($_POST['mileage'])) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION['error_missing'] = 'All fields are required';
        header("Location: add.php");
        return;
    }

    if ( !(is_numeric($_POST['year'])  )) {
        $_SESSION['error_nonYear'] = 'Year must be numeric';
        header("Location: add.php");
        return;
    }

    if ( !(is_numeric($_POST['mileage'])  )) {
        $_SESSION['error_nonMileage'] = 'mileage must be numeric';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO autos (make,model, year,mileage)
              VALUES (:make, :model, :year , :mileage)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));
    $_SESSION['success'] = 'Record Added';
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
?>

<!DOCTYPE html>
<html>
<head>
<title>Khaled Badawy</title>


</head>
<body>
<div class="container">
<h1>Tracking Automobiles for <?php echo($_SESSION['email']) ?></h1>
<form method="post">
<p>Make:

<input type="text" name="make" size="40"/></p>
<p>Model:

<input type="text" name="model" size="40"/></p>
<p>Year:

<input type="text" name="year" size="10"/></p>
<p>Mileage:

<input type="text" name="mileage" size="10"/></p>
<input type="submit" name='add' value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
</div>
</body>
</html>
