<?php
    //Need to fill in appropriate details here
    $servername = "localhost";
    $username = "root";
    $password = "DaPassword1";
    $dbname = "testDB";
	echo "HERE!";
    //First argument passed should be Marker or Student, denoting whether we are working with Markers or Students.
    //Second argument passed should be Add or Remove, denoting whether we are adding or removing from the database.
    //If working with Markers, third and fourth arguments should be first_name and last_name respecitively.
    //If working with Students, third argument should be Proposal or Final.
    //If working with Students, 4th, 5th, 6th and 7th arguments should be first_name, last_name, student_no and semester respectively.

    //echo "hello";
    //START YEAR/SEM LOOKUP.

    $conn = new mysqli($servername, $username, $password, $dbname);
	echo "HERE!";
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\r\n";
        exit(1);
    }
    
    $sqlsem = "SELECT year, semester FROM current_year_semester";
    $result = $conn->query($sqlsem);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $year = $row["year"];
            $semest = $row["semester"];
        }
    } else {
        echo "Could not get current year/semester from database, defaulting to semester 1 2015.\r\n";
    }
    $sql = "select year from current_year_semester ORDER BY year DESC LIMIT 1";
		$years = $conn->query($sql);
		$row = $years->fetch_assoc();
		$year = $row["year"];
		#from latest year takes highest semester
		$sql = "select semester from current_year_semester where year =" . $year . " order by semester desc limit 1";
		$semesters = $conn->query($sql);
		$row = $semesters->fetch_assoc();#converting from table tuple to number value
		$sem = $row["semester"];
		echo $year. "'".$sem;
	
	if($_POST["table"]=="Student" || $_POST["table"]=="student") {
        //Working with Students
        if($_POST["task"]=="Add" || $_POST["task"]=="add"){
            //echo "Adding to Students.\r\n";
            //Add to Students
            if($_POST["sem"]=="Proposal" || $_POST["sem"] == "proposal") {
                //echo "Adding ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." to the student_proposal table.\r\n";
                addSuper("proposal", $_POST["fname"], $_POST["lname"], $_POST["num"], $sem, $year, $servername, $username, $password, $dbname);
            } elseif($_POST["sem"]=="Final" || $_POST["sem"] == "final") {
                //echo "Adding ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." to the student_final table.\r\n";
                addSuper("final", $_POST["fname"], $_POST["lname"], $_POST["num"], $sem, $year, $servername, $username, $password, $dbname);
            } else {
                //echo "Third argument incorrect, please specify Proposal or Final.\r\n";
                exit(1);
            }
        } elseif($_POST["task"]=="Remove" || $_POST["task"]=="remove") {
            //echo "Removing from Students.\r\n";
            //Remove from Students
            if($_POST["sem"]=="Proposal" || $_POST["sem"] == "proposal") {
                //echo "Removing ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." from the student_proposal table.\r\n";
                removeSuper("proposal", $_POST["fname"], $_POST["lname"], $_POST["num"], $sem, $year, $servername, $username, $password, $dbname);
            } elseif($_POST["sem"]=="Final" || $_POST["sem"] == "final") {
                //echo "Removing ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." from the student_final table.\r\n";
                removeSuper("final", $_POST["fname"], $_POST["lname"], $_POST["num"], $sem, $year, $servername, $username, $password, $dbname);
            } else {
                //echo "Third argument incorrect, please specify Proposal or Final.\r\n";
                exit(1);
            }
        } else {
            //echo "Second argument incorrect, please specify Add for adding or Remove for removing.\r\n";
            exit(1);
        }
    } else {
        //echo "First argument incorrect, please specify Marker for Markers or Student for Students.\r\n";
        exit(1);
    }
/*
		
	
	$co = "proposal";
	$FN = "bob";
	$LN = "BOBSON";
	$SN = 1234;
	
	
		
	//removeSuper($co, $FN, $LN, $SN, $sem, $year,$servername, $username, $password, $dbname);
	//addSuper($co, $FN, $LN, $SN, $sem, $year,$servername, $username, $password, $dbname);
	*/
    function addSuper($cohort, $fName, $lName, $sNum, $sem, $year, $servername, $username, $password, $dbname){
		echo "Diagnose";
        $conn = new mysqli($servername, $username, $password, $dbname);
        //echo "hello";
        //$title = $_POST["title"];
		
		$sql = "Select year, supervisor_1, supervisor_2, supervisor_3, supervisor_4 from student_".$cohort." where student_no ='".$sNum."' ORDER BY year DESC LIMIT 1";
		echo $sql;
		$supers = $conn->query($sql);
		$row = $supers->fetch_assoc();
		echo $row["supervisor_1"] .", ".$row["supervisor_2"].", ".$row["supervisor_3"].", ".$row["supervisor_4"]. " EMPTY";
		if($row["supervisor_1"] == null){
			$num =1;
		}
		else if($row["supervisor_2"] == null){
			$num =2;
		}
		else if($row["supervisor_3"] == null){
			$num =3;
		}
		else if($row["supervisor_4"] == null){
			$num =4;
		}
		else{
            echo "Number of supervisors has reached maximum. Remove supervisors before adding more.\r\n";
            exit(1);
        }
		echo "/r/n";
		$NOM = $fName;
		$NOM .=" ".$lName;
		echo $NOM;
        $sql = "update student_" .$cohort ." set supervisor_".$num." = '".$NOM."' where student_no = '".$sNum."'";
        //echo $sql;
		echo $sql;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        //echo "goodbye";
        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully\r\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\r\n";
        }
        $conn->close();
    }
	
    function removeSuper($cohort, $fName, $lName, $sNum, $sem, $year, $servername, $username, $password, $dbname){
	
		echo "Diagnose";
        $conn = new mysqli($servername, $username, $password, $dbname);
        //echo "hello";
        //$title = $_POST["title"];
		
		$sql = "Select year, supervisor_1, supervisor_2, supervisor_3, supervisor_4 from student_".$cohort." where student_no ='".$sNum."' ORDER BY year DESC LIMIT 1";
		echo $sql;
		$NOM = $fName;
		$NOM .=" ".$lName;
		echo $NOM;
		$supers = $conn->query($sql);
		$row = $supers->fetch_assoc();
		
		if($row["supervisor_1"] == $NOM){
			$num =1;
			echo "Found Him!";
		}
		else if($row["supervisor_2"] == $NOM){
			$num =2;
		}
		else if($row["supervisor_3"] == $NOM){
			$num =3;
		}
		else if($row["supervisor_4"] == $NOM){
			$num =4;
		}
		else{
            echo "Supervisor does not exist for designated student.\r\n";
            exit(1);
        }
		echo "/r/n";
		$NOM = $fName;
		$NOM .=" ".$lName;
		echo $NOM;
        $sql = "update student_" .$cohort ." set supervisor_".$num." = null where student_no = '".$sNum."'";
        //echo $sql;
		echo $sql;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        //echo "goodbye";
        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully\r\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\r\n";
        }
        
    }

    
?>
