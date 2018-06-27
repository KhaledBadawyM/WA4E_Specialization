<?php
require_once "pdo.php";
session_start();
?>
<html>
<head></head><body>
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