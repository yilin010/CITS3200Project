<?php
//Set server details here
    $servername = "localhost";
    $username = "CITS3200";
    $password = "Samsung15";
    $dbname = "marks_database";
//Connect to the database
$db = new mysqli($servername, $username, $password, $dbname) or die("Unable to connect");

//Get the file in, read it, close it, then delete it.
$target_file = basename("tempfile.txt");
if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileUpload"]["name"]). " has been uploaded.\r\n";
} else {
    echo "Sorry, there was an error uploading your file.\r\n";
}
$myfile = fopen("tempfile.txt", "r") or die("Unable to open file!\r\n");
$content = fread($myfile,filesize("tempfile.txt"));
fclose($myfile);
unlink("tempfile.txt");
$line = explode("\n", $content);

//check if input file contains column name Grade - for student file
$rowDesc = explode("\t", $line[0]);
if(in_array("Grade", $rowDesc)) {
	//for student file upload
	for($i = 1; $i<count($line)-1; $i++) {//first line is column descriptions	
		$row = explode("\t", $line[$i]);
		$student_no = trim($row[0]);
		$last_name = trim($row[1]);
		$title = trim($row[2]); 
		$first_name = trim($row[3]);
		$teachperiod = explode(" ", $row[6]);
		$semester = trim($teachperiod[0]);
		$year = trim($teachperiod[1]);
		
		$db->select_db($dbname);
		$query = $db->prepare("INSERT INTO student_proposal(student_no, last_name, title, first_name, year, semester) VALUES(?,?,?,?,?,?)");
		
		if(!$query->bind_param("ssssss", $student_no,$last_name, $title, $first_name, $year, $semester)){
        echo "Binding parameters failed: (" . $query->errno . ") " . $query->error;
        echo "\r\n";		
		}
		
		if ($query->execute()) {
        echo 1;
		}else echo 0;
 
	}
} 
//for marker file 
else {
	for($i = 1; $i<count($line)-1; $i++) {//first line is column descriptions	
	$row = explode("\t", $line[$i]);
	$last_name = trim($row[0]);
	$first_name = trim($row[1]); 
	$current = TRUE;

	$db->select_db($dbname);
	$query = $db->prepare("INSERT INTO marker(first_name, last_name, current) VALUES(?,?,?)");
		
	if(!$query->bind_param("sss",$first_name, $last_name, $current)){
    echo "Binding parameters failed: (" . $query->errno . ") " . $query->error;
    echo "\r\n";		
	}
		
	if ($query->execute()) {
    echo 1;
	}else echo 0;
}


}
$db->close();
header("Location: addRemove.php");
?>

