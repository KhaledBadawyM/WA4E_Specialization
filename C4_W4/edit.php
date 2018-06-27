<?php
require_once "pdo.php";
include "helper.php";
//require_once "add.php";
session_start();
$_SESSION['profile_id'] =  $_GET['profile_id'];


function loadPos($pdo)
{   $position=[] ;
    $sql = "SELECT * FROM Position WHERE profile_id ='$_GET[profile_id]' ORDER BY rank";
    $stmt = $pdo->query($sql);
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $position[] = $row ; 
    }

    return $position ; 
    
}

function insertPos($pdo)
{
    $profile_id = $_GET['profile_id'];//$pdo->lastInsertId();
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_GET['profile_id']));
    //header("Location: edit.php?profile_id=".$profile_id);
       // return;


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
}
$posData = loadPos($pdo);
$eduData = loadEdu($pdo , $_GET['profile_id']);

//echo $validatePos ; 


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
    //echo $_POST['summary'] ; 
     $validatePos = validatePos();
     $validateEdu = validateEdu();
     //header("Location: edit.php?profile_id=".is_string($validatePos)."____".$validatePos);
       // return;

    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||strlen($_POST['email']) < 1 ||strlen($_POST['headline']) < 1 ||strlen($_POST['summary']) < 1) {
        $_SESSION['error_missingfield_edit'] = 'All fields are required';
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
    }


     elseif(strpos($_POST['email'], '@')== false) 
    {
        $_SESSION['signError'] = "Email must have an at-sign (@)" ;
        header('Location: edit.php?profile_id='.$_GET['profile_id']);
            return ;
    } 

    elseif(is_string($validatePos))
    {   //  echo "ejjhewhkewhkehehjktekerrekhrk";
        echo '<p style="color:red">'.$validatePos.'</p>';
        //header('Location: edit.php?profile_id='.$_GET['profile_id']);
        //return ; 
    }
    elseif(is_string($validateEdu))
    {   //  echo "ejjhewhkewhkehehjktekerrekhrk";
        echo '<p style="color:red">'.$validateEdu.'</p>';
         
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

        //file_put_contents('file.json', $_GET['profile_id']);
        
        insertPos($pdo);
        deleteEdu($_GET['profile_id'],$pdo);
        insertEdu($_GET['profile_id'],$pdo);

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
/*
if ( $row === false ) {
    $_SESSION['error'] = 'Bada email address';
    header( 'Location: index.php' ) ;
    return;
}
*/
// Flash pattern

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$hl = htmlentities($row['headline']);
$sm = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
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


<h1>Edit Automobile</h1>
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
<div>
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
        Education:<input type="submit" id="addEdu" value="+">
    </p>    

    <div id= eduHistory>
        
    </div>
    <div id="edu_fields">
        
    </div>    

     <p>
        Position: <input type="submit" id="addPos" value="+">
    </p>
    
    <?php  
       // echo $posData[$i]['year'];
        for ($i=1; $i <=sizeof($posData) ; $i++) { 
            echo '<div id="position'.$i.'">
                    <p>Year:
                    <input type="text"   name="year'.$i.'" value="'.$posData[$i-1]['year'].'"/> 
                    <input type="button" value="-" onclick="$(\'#position'.$i.'\').remove(); return false ;">
                    </p>
                    <textarea name = "desc'.$i.'" rows="8" cols="80">
                    '.$posData[$i-1]['description'].'</textarea>"</div>';
        }

        //onclick="$(#position'.$i.').remove(); return false ;"
    ?>
    <div id="position_fields">
            
    </div>
    <p>
    <input type="submit" value="Save" name ="save">
    <input type="submit" name="cancel" value="Cancel">
    </p>

    

</form>

 
</div>
<script type="text/javascript">
        countpos = <?php echo sizeof($posData)?> ; 
        countEdu = <?php echo sizeof($eduData)?> ;
        $(document).ready(function(){
            window.console&& console.log('Document ready called') ; 
            
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

            ///// for education history 

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

    <script type="text/javascript">
        $(document).ready(function(){
            $.getJSON('JSON.php',function(data){
                for (var i = 1; i <= data.length; i++) {
                    $('#eduHistory').append('<div id="edu'+i+'"> \
                    <p>Year: <input type="text" name="edu_year'+i+'" value="'+data[i-1]['year']+'" /> \
                    <input type="button" value="-" onclick="$(\'#edu'+i+'\').remove();return false;"><br>\
                    <p>School: <input type="text" size="80" name="edu_school'+i+'" class="school" value="'+data[i-1]['name']+'" />\
                    </p></div>')
                }
            });
            $('.school').autocomplete({
                    source: "school.php"
            });
        });
    </script>

</body>
</html>