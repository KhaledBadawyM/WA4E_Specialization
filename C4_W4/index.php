

<?php
require_once "pdo.php";
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

</head><body>
    <div id="loginDiv"></div>
<?php
 
$sql = "SELECT user_id from users WHERE email = '$_SESSION[email]' ";
 $stmt = $pdo->query($sql) ;  
 $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
 //print_r($row) ; 

 $_SESSION['user_id']=$row[0]['user_id'];


$sql = "SELECT profile_id from Profile WHERE user_id = '$_SESSION[user_id]' ";
 $stmt = $pdo->query($sql) ;  
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
 //print_r($row) ; 
//print $_SESSION['email'];
 //$stmt = $pdo->query($sql) ;  
 $_SESSION['profile_id']=$row['profile_id'];



if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
$stmt = $pdo->query("SELECT first_name ,last_name,headline,profile_id FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($rows===false)
    echo "No rows found" ;
//print $_SESSION['profile_id'];

echo('<table border="1">'."\n");

//print_r($rows);
 
foreach ($rows as $row) {
 
     # code...
    //print_r($row) ;echo "<br>";
    echo "<tr><td>";
    echo( '<a href="view.php?profile_id='.$row['profile_id'].'">'.$row['first_name']." ".$row['last_name'].'</a>');
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
}
?>
</table>

<div id="addDiv"></div>
<!--
<a href="add.php">Add New Entry</a><br>
<a href="logout.php">Logout</a>-->


<script type="text/javascript">
    
    var mailSession = "<?php print isset($_SESSION['email']) ;?>";
    console.log(mailSession);
    if (mailSession==0 ) {
        var aTag = document.createElement("a");
        aTag.setAttribute('href',"login.php");
        aTag.innerHTML = "Please log in";
        var aDiv = document.getElementById("loginDiv");
        aDiv.appendChild(aTag);
    }

    if(mailSession==1)
    {
        var aTag = document.createElement("a");
        aTag.setAttribute('href',"add.php");
        aTag.innerHTML = "Add New Entry";
        var aDiv = document.getElementById("addDiv");
        aDiv.appendChild(aTag);
            /////////////

        var aTag = document.createElement("a");
        aTag.setAttribute('href',"logout.php");
        aTag.innerHTML = "logout";
        var aDiv = document.getElementById("loginDiv");
        aDiv.appendChild(aTag);    


    }

 

</script>
</body>
</html>