<?php

//Connect to the database 
$servername = 'localhost';
$user = 'CITS3200';
$pass = 'Samsung15';
$db = 'marks_database';
$db = new mysqli($servername, $user, $pass, $db) or die("Unable to connect");

//Get the file in, read it, close it, then delete it.
$target_dir = "";
$target_file = $target_dir . basename("tempfile.txt"/*$_FILES["fileUpload"]["name"]*/);

if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileUpload"]["name"]). " has been uploaded.\r\n";
} else {
    echo "Sorry, there was an error uploading your file.\r\n";
}

$myfile = fopen("tempfile.txt", "r") or die("Unable to open file!\r\n");
$content = fread($myfile,filesize("tempfile.txt"));
fclose($myfile);
unlink("tempfile.txt");

//include "connect.php";
/*$filename = './Sample.txt';
	
$content = file_get_contents($filename, FILE_USE_INCLUDE_PATH);	//need to pass txt file*/

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
 
	$query = "INSERT INTO student_proposal(student_no, last_name, title, first_name, year, semester) VALUES('".$student_no."', '".$last_name."', '".$title."', '".	$first_name."', '".$year."', '".$semester."')";
 
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

