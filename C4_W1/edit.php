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

if ( isset($_POST['save']) ) {
    echo $_POST['summary'] ; 
    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||strlen($_POST['email']) < 1 ||strlen($_POST['headline']) < 1 ||strlen($_POST['summary']) < 1) {
        $_SESSION['error_missingfield_edit'] = 'All fields are required';
        header("Location: edit.php");
        return;
    }


     elseif(strpos($_POST['email'], '@')== false) 
    {
        $_SESSION['signError'] = "Email must have an at-sign (@)" ;
        header('Location: edit.php');
            return ;
    }    
    else{
        $sql = "UPDATE Profile SET first_name = :fn,
                last_name = :ln, email = :em , headline=:hl
                ,summary =:sm
                WHERE profile_id = :pid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':hl' => $_POST['headline'],
            'sm'  => $_POST['summary'],
            ':pid' => $_GET['profile_id']));
        $_SESSION['success'] = 'profile updated';
        header( 'Location: index.php' ) ;
        return;
    }
}


if ( isset($_SESSION['error_missingfield_edit']) ) {
    echo '<p style="color:red">'.$_SESSION['error_missingfield_edit']."</p>\n";
    unset($_SESSION['error_missingfield_edit']);
}

if ( isset($_SESSION['signError']) ) {
    echo '<p style="color:red">'.$_SESSION['signError']."</p>\n";
    unset($_SESSION['signError']);
}


// Guardian: Make sure that user_id is present
if (  !isset($_SESSION['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bada email address';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sm = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
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
    <p>First Name:
    <input type="text" name="first_name" value="<?= $fn?>" size="60"/></p>
    <p>Last Name:
    <input type="text" name="last_name" value="<?= $ln?>" size="60"/></p>
    <p>Email:
    <input type="text" name="email" value="<?= $em?>" size="30"/></p>
    <p>Headline:<br/>
    <input type="text" name="headline" value="<?= $hl?>" size="80"/></p>
    <p>Summary:<br/>
    <textarea name="summary" rows="8"  cols="80"><?= $sm?></textarea>
    <p>
    <input type="submit" value="Save" name ="ssve">
    <input type="submit" name="cancel" value="Cancel">
    </p>
</form>