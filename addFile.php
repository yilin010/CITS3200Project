<?php

//Connect to the database 
$servername = 'localhost';
$user = 'CITS3200';
$pass = 'Samsung15';
$db = 'marks_database';
$db = new mysqli($servername, $user, $pass, $db) or die("Unable to connect");


//include "connect.php";



$filename = './Sample.txt';
	
$content = file_get_contents($filename, FILE_USE_INCLUDE_PATH);	//need to pass txt file

$line = explode("\n", $content);



for($i = 1; $i<count($line)-1; $i++) {	//first line is column descriptions	
	
	$row = explode("\t", $line[$i]);
	$student_no = trim($row[0]);
	$last_name = trim($row[1]);
	$title = trim($row[2]); 
	$first_name = trim($row[3]);
	$teachperiod = explode(" ", $row[6]);
	$semester = trim($teachperiod[0]);
	$year = trim($teachperiod[1]);
 
	$query = "INSERT INTO student_proposal(student_no, last_name, title, first_name, year, 

semester) 	
	VALUES('".$student_no."', '".$last_name."', '".$title."', '".	$first_name."', '".

$year."', '".$semester."') ";
 
echo $query;
	if(mysqli_query($db, $query))
		{
		echo "Insert success";
		}
	else
		{
		echo "Insert failure";
		}

}

echo "Success!!"

?>

