<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "serverName";

    //First argument passed should be Marker or Student, denoting whether we are working with Markers or Students.
    //Second argument passed should be Add or Remove, denoting whether we are adding or removing from the database.
    //If working with Markers, third and fourth arguments should be first_name and last_name respecitively.
    //If working with Students, third argument should be Proposal or Final.
    //If working with Students, 4th, 5th, 6th and 7th arguments should be first_name, last_name, student_no and semester respectively.


    if($argv[1]=="Marker" || $argv[1]=="marker") {
        //Working with Markers
        if($argv[2]=="Add" || $argv[2]=="add") {
            echo "Adding to Markers.\r\n";
            //Add to Markers
            echo "Adding ".$argv[3]." ".$argv[4]." to the marker table.\r\n";
            marker($argv[3], $argv[4], TRUE);
        } elseif($argv[2]=="Remove" || $argv[2]=="remove") {
            echo "Removing from Markers.\r\n";
            //Remove from Markers
            echo "removing ".$argv[3]." ".$argv[4]." to the marker table.\r\n";
            marker($argv[3], $argv[4], FALSE);
        } else {
            echo "Second argument incorrect, please specify Add for adding or Remove for removing.\r\n";
            exit(1);
        }
    } elseif($argv[1]=="Student" || $argv[1]=="student") {
        //Working with Students
        if($argv[2]=="Add" || $argv[2]=="add"){
            echo "Adding to Students.\r\n";
            //Add to Students
            if($argv[3]=="Proposal" || $argv[3] == "proposal") {
                echo "Adding ".$argv[4]." ".$argv[5]." ".$argv[6]." for semester ".$argv[7]." to the student_proposal table.\r\n";
                addStudent("proposal", $argv[4], $argv[5], $argv[6], $argv[7]);
            } elseif($argv[3]=="Final" || $argv[3] == "final") {
                echo "Adding ".$argv[4]." ".$argv[5]." ".$argv[6]." for semester ".$argv[7]." to the student_final table.\r\n";
                addStudent("final", $argv[4], $argv[5], $argv[6], $argv[7]);
            } else {
                echo "Third argument incorrect, please specify Proposal or Final.\r\n";
                exit(1);
            }
        } elseif($argv[2]=="Remove" || $argv[2]=="remove") {
            echo "Removing from Students.\r\n";
            //Remove from Students
            if($argv[3]=="Proposal" || $argv[3] == "proposal") {
                echo "Removing ".$argv[4]." ".$argv[5]." ".$argv[6]." for semester ".$argv[7]." from the student_proposal table.\r\n";
                removeStudent("proposal", $argv[4], $argv[5], $argv[6], $argv[7]);
            } elseif($argv[3]=="Final" || $argv[3] == "final") {
                echo "Removing ".$argv[4]." ".$argv[5]." ".$argv[6]." for semester ".$argv[7]." from the student_proposal table.\r\n";
                removeStudent("final", $argv[4], $argv[5], $argv[6], $argv[7]);
            } else {
                echo "Third argument incorrect, please specify Proposal or Final.\r\n";
                exit(1);
            }
        } else {
            echo "Second argument incorrect, please specify Add for adding or Remove for removing.\r\n";
            exit(1);
        }
    } else {
        echo "First argument incorrect, please specify Marker for Markers or Student for Students.\r\n";
        exit(1);
    }
    
    function addStudent($cohort, $fName, $lName, $sNum, $sem){  
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        $y=date("Y");
        $sql = "INSERT INTO student_" .$cohort ." (student_no, first_Name, last_Name, year, semester) VALUES ('$sNum', '$fName', '$lName', '$y', '$sem')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully\r\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\r\n";
        }
        $conn->close();
    }
    function removeStudent($cohort, $fName, $lName, $sNum, $sem){  
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        $y=date("Y");
        $sql = "DELETE FROM student_" .$cohort ." WHERE student_no=='$sNum' AND  first_name=='$fName' AND last_name=='$lName' AND year=='$y' AND semester=='$sem')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully\r\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\r\n";
        }
        $conn->close();
    }

    function marker($fName, $lName, $curr){  
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        $sql = "INSERT INTO marker (first_name, last_name, current) VALUES ('$fName', '$lName', $curr)";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully\r\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\r\n";
        }
        $conn->close();
    }
?>  