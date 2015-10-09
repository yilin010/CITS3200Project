<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "password";
    $dbname = "serverName";

    //First argument passed should be Marker or Student, denoting whether we are working with Markers or Students.
    //Second argument passed should be Add or Remove, denoting whether we are adding or removing from the database.
    //If working with Markers, third and fourth arguments should be first_name and last_name respecitively.
    //If working with Students, third argument should be Proposal or Final.
    //If working with Students, 4th, 5th, 6th and 7th arguments should be first_name, last_name, student_no and semester respectively.


    //START YEAR/SEM LOOKUP.
    $semest = 1;
    $year = 2015;

    $conn = new mysqli($servername, $username, $password, $dbname);

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
    //END YEAR/SEM LOOKUP.

    if($_POST["table"]=="Marker" || $_POST["table"]=="marker") {
        //Working with Markers
        if($_POST["task"]=="Add" || $_POST["task"]=="add") {
            echo "Adding to Markers.\r\n";
            //Add to Markers
            echo "Adding ".$_POST["fname"]." ".$_POST["lname"]." to the marker table.\r\n";
            marker($_POST["fname"], $_POST["lname"], TRUE);
        } elseif($_POST["task"]=="Remove" || $_POST["task"]=="remove") {
            echo "Removing from Markers.\r\n";
            //Remove from Markers
            echo "removing ".$_POST["fname"]." ".$_POST["lname"]." to the marker table.\r\n";
            marker($_POST["fname"], $_POST["lname"], FALSE);
        } else {
            echo "Second argument incorrect, please specify Add for adding or Remove for removing.\r\n";
            exit(1);
        }
    } elseif($_POST["table"]=="Student" || $_POST["table"]=="student") {
        //Working with Students
        if($_POST["task"]=="Add" || $_POST["task"]=="add"){
            echo "Adding to Students.\r\n";
            //Add to Students
            if($_POST["sem"]=="Proposal" || $_POST["sem"] == "proposal") {
                echo "Adding ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." to the student_proposal table.\r\n";
                addStudent("proposal", $_POST["fname"], $_POST["lname"], $_POST["num"], $semest);
            } elseif($_POST["sem"]=="Final" || $_POST["sem"] == "final") {
                echo "Adding ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." to the student_final table.\r\n";
                addStudent("final", $_POST["fname"], $_POST["lname"], $_POST["num"], $semest);
            } else {
                echo "Third argument incorrect, please specify Proposal or Final.\r\n";
                exit(1);
            }
        } elseif($_POST["task"]=="Remove" || $_POST["task"]=="remove") {
            echo "Removing from Students.\r\n";
            //Remove from Students
            if($_POST["sem"]=="Proposal" || $_POST["sem"] == "proposal") {
                echo "Removing ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." from the student_proposal table.\r\n";
                removeStudent("proposal", $_POST["fname"], $_POST["lname"], $_POST["num"], $semest);
            } elseif($_POST["sem"]=="Final" || $_POST["sem"] == "final") {
                echo "Removing ".$_POST["fname"]." ".$_POST["lname"]." ".$_POST["num"]." for semester ".$semest." from the student_final table.\r\n";
                removeStudent("final", $_POST["fname"], $_POST["lname"], $_POST["num"], $semest);
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
        $title = $_POST["title"];
        $sql = "INSERT INTO student_" .$cohort ." (student_no, first_Name, last_Name, year, semester, title) VALUES ('$sNum', '$fName', '$lName', '$year', '$sem', '$title')";
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
        $sql = "DELETE FROM student_" .$cohort ." WHERE student_no=='$sNum' AND  first_name=='$fName' AND last_name=='$lName' AND year=='$year' AND semester=='$sem'";
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
<<<<<<< HEAD
?>
=======
?>  
>>>>>>> e493e0d5f3c29bd1fd54184ae0193a49627ec962
