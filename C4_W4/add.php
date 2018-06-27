<?php
require_once "pdo.php";
require_once "helper.php";
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

 $sql = "SELECT user_id from users WHERE email = '$_SESSION[email]' ";
 $stmt = $pdo->query($sql) ;  
 $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
 //print_r($row) ; 

 $_SESSION['user_id']=$row[0]['user_id'];
 //print $_SESSION['user_id'];


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
    
    $_SESSION['validatePos'] = validatePos() ; 
    $_SESSION['validateEdu'] = validateEdu() ;
    if($_SESSION['validatePos'] === true)
    {
        unset($_SESSION['validatePos']);
    }
    else{
        header('Location: add.php');
        return ;
    }    

    if($_SESSION['validateEdu'] === true)
    {
        unset($_SESSION['validateEdu']);
    }
    else{
        header('Location: add.php');
        return ;
    }   

    $stmt = $pdo->prepare('INSERT INTO Profile
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


$profile_id = $pdo->lastInsertId();

$rank = 1;
for($i=1; $i<=9; $i++) {
  if ( ! isset($_POST['year'.$i]) ) continue;
  if ( ! isset($_POST['desc'.$i]) ) continue;

  $year = $_POST['year'.$i];
  $desc = $_POST['desc'.$i];
  $stmt = $pdo->prepare('INSERT INTO Position
    (profile_id, rank, year, description)
    VALUES ( :pid, :rank, :year, :desc)');

  $stmt->execute(array(
  ':pid' => $profile_id,//$_REQUEST['profile_id'],
  ':rank' => $rank,
  ':year' => $year,
  ':desc' => $desc)
  );

  $rank++;

}
  
    insertEdu($profile_id,$pdo);

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
if ( isset($_SESSION['validatePos']) ) {
    echo '<p style="color:red">'.$_SESSION['validatePos']."</p>\n";
    unset($_SESSION['validatePos']);
    unset($_SESSION['validateEdu']);
}
if ( isset($_SESSION['validateEdu']) ) {
    echo '<p style="color:red">'.$_SESSION['validateEdu']."</p>\n";
    unset($_SESSION['validateEdu']);
    unset($_SESSION['validatePos']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Khaled Badawy</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>


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
        Education: <input type="submit" id="addEdu" value="+">
        <div id="edu_fields">
            
        </div>

        Position: <input type="submit" id="addPos" value="+">
        <div id="position_fields">
            
        </div>
    </p>
    <p>
    <input type="submit" value="Add" name ="add">
    <input type="submit" name="cancel" value="Cancel">
    </p>
</form>

    <script type="text/javascript">
        countpos = 0 ; 
        countEdu = 0;
        $(document).ready(function(){
            window.console&& console.log('Document ready called') ; 
            -
            $('#addPos').click(function(event){
                event.preventDefault();
                if(countpos>=9){
                    alert("maximaum of nine position entries execded") ;
                    return ; 
                }
                countpos++ ; 
                window.console&&console.log("adding Position "+countpos);
                $('#position_fields').append(
                    '<div id="position'+countpos+'">\
                    <p>Year: <input type="text" name="year'+countpos+'">\
                    <input type="button" value="-"\
                    onclick="$(\'#position'+countpos+'\').remove();return false;"> </p>\
                    <textarea name="desc'+countpos+'" rows="8" cols="80"></textarea>  </div>'
                    )
            });

            //////////////////////////////////

            $('#addEdu').click(function(event){
                event.preventDefault();
                  if ( countEdu >= 9 ) {
                 alert("Maximum of nine education entries exceeded");
                 return;
                }
                countEdu++;
                window.console && console.log("Adding education "+countEdu);

            $('#edu_fields').append(
                '<div id="edu'+countEdu+'"> \
                <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
                <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
                <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
                </p></div>'
            );

            $('.school').autocomplete({
                source: "school.php"
            });

        });
    });

    </script>

   
    
</div>
</body>
</html>
