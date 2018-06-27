<?php 
	//require_once "pdo.php"; 
	//session_start();
	
	function validatePos()
	{
	    for($i=1;$i<=9;$i++)
	    {
	        if(!isset($_POST['year'.$i]))continue ; 
	        if(!isset($_POST['desc'.$i]))continue ;

	        $year = $_POST['year'.$i];
	        $desc = $_POST['desc'.$i];

	        if(strlen($year)<1||strlen($desc)<1)
	            return "All fields are required";
	        if(!is_numeric($year))
	            return "Position year must be numeric" ;     
	    }
	    return true ;
	}

	function validateEdu()
	{
	    for($i=1;$i<=9;$i++)
	    {
	        if(!isset($_POST['edu_year'.$i]))continue ; 
	        if(!isset($_POST['edu_school'.$i]))continue ;

	        $year = $_POST['edu_year'.$i];
	        $school = $_POST['edu_school'.$i];

	        if(strlen($year)<1||strlen($school)<1)
	            return "All fields are required";
	        if(!is_numeric($year))
	            return "Education year must be numeric" ;     
	    }
	    return true ;
}

	function deleteEdu($profile_id,$pdo)
	{
  		$stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
    	$stmt->execute(array( ':pid' => $profile_id ));
	}

	function insertEdu($profile_id,$pdo)
	{
		$rank = 1;
		for($i=1; $i<=9; $i++) {
		  if ( ! isset($_POST['edu_year'.$i]) ) continue;
		  if ( ! isset($_POST['edu_school'.$i]) ) continue;
		  $year = $_POST['edu_year'.$i];
		  $school = $_POST['edu_school'.$i];

		  //lookup if the school is there or not
		  $institution_id = false ;
		  $stmt = $pdo->prepare("SELECT institution_id FROM Institution WHERE name=:name ");
		  $stmt->execute(array(':name'=>$school));
		  $row = $stmt->fetch(PDO::FETCH_ASSOC);
		  //echo $school ;
		  //print_r($row);
		  if($row !==false)
		  		$institution_id = $row['institution_id'] ;

		  	// if the school is not there
		  if($institution_id===false)
		  {
		  	$stmt = $pdo->prepare("INSERT INTO Institution (name) VALUES (:name) ");
		  	$stmt->execute(array(':name'=> $school));

		  	$institute_id = $pdo->lastInsertId();

		  	echo $institute_id ;
		  }	


		  $stmt = $pdo->prepare('INSERT INTO Education
		    (profile_id, rank, year, institution_id)
		    VALUES ( :pid, :rank, :year, :institute_id)');

		  $stmt->execute(array(
		  ':pid' => $profile_id,//$_REQUEST['profile_id'],
		  ':rank' => $rank,
		  ':year' => $year,
		  ':institute_id' => $institution_id)
		  );

		  $rank++;

		}
	}

	function loadEdu($pdo , $profile_id)
	{   
		$education=[] ;
	    $sql = "SELECT * FROM Position WHERE profile_id ='$profile_id' ORDER BY rank";
	    $stmt = $pdo->query($sql);
	    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	        $education[] = $row ; 
	    }

	    return $education ; 
    
	}

	
 ?>