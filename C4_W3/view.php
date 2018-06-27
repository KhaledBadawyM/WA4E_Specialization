<?php 
    require_once "pdo.php"  ; 
    session_start();
    if(!isset($_GET['profile_id']))
    {
        $_SESSION['missing_pr'] = "Missing profile_id" ; 
        header('Location :index.php');
        return ;
    }

    $stmt = $pdo->query("SELECT * FROM Profile WHERE profile_id = '$_GET[profile_id]' ");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

   echo ("First Name : ". $row['first_name']."<br><br>");
   echo ("Last Name : ". $row['last_name']."<br><br>");
   echo ("Email : ". $row['email']."<br><br>");
   echo ("Headline : <br>". $row['headline']."<br><br>");
   echo ("Summay : <br>". $row['summary']."<br><br>");

   


   $stmt = $pdo->query("SELECT * FROM Position WHERE profile_id = '$_GET[profile_id]' ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo"<p>Position</p>";
    echo "<ul>";
    foreach ($rows as $row) {
      echo "<li>".$row['year']." : ".$row['description']."</li>"."<br>"  ;
    }
    echo "</ul>";

    echo '<a href="index.php">Done</a>';
?>

