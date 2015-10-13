<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    //START YEAR/SEM LOOKUP.
    $semest = 1;
    $year = 2015;
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\r\n";
        exit(1);
    }
    
    $sqlsem = "SELECT year, semester FROM current_year_semester";
    $result = $conn->query ($sqlsem);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $year = $row["year"];
            $semest = $row["semester"];
        }
    } else {
        echo "Could not get current year/semester from database, defaulting to semester 1 2015.\r\n";
    }
    //END YEAR/SEM LOOKUP.


    //Take input for which cohort we are modifying.
    //Should be $_POST["cohort"]
    //Take inputs, mark_1, mark_1_name, mark_2, etc...
    //Should be $_POST["mark1"], $_POST["mark1name"], etc...
    //UPDATE FOR THE CURRENT SEMESTER.
    $mark1 = $_POST["mark1"]/100;
    $mark2 = $_POST["mark2"]/100;
    $mark3 = $_POST["mark3"]/100;
    $mark4 = $_POST["mark4"]/100;
    $mark5 = $_POST["mark5"]/100;
    $mark6 = $_POST["mark6"]/100;
    $mark7 = $_POST["mark7"]/100;
    $mark8 = $_POST["mark8"]/100;
    $mark9 = $_POST["mark9"]/100;
    $mark10 = $_POST["mark10"]/100;

    $mark1name = $_POST["mark1name"];
    $mark2name = $_POST["mark2name"];
    $mark3name = $_POST["mark3name"];
    $mark4name = $_POST["mark4name"];
    $mark5name = $_POST["mark5name"];
    $mark6name = $_POST["mark6name"];
    $mark7name = $_POST["mark7name"];
    $mark8name = $_POST["mark8name"];
    $mark9name = $_POST["mark9name"];
    $mark10name = $_POST["mark10name"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        $sql = "UPDATE weighting_" .$_POST["cohort"] ." SET 
mark_1='$mark1', mark_1_name='$mark1name', 
mark_2='$mark2', mark_2_name='$mark2name', 
mark_3='$mark3', mark_3_name='$mark3name', 
mark_4='$mark4', mark_4_name='$mark4name', 
mark_5='$mark5', mark_5_name='$mark5name', 
mark_6='$mark6', mark_6_name='$mark6name', 
mark_7='$mark7', mark_7_name='$mark7name', 
mark_8='$mark8', mark_8_name='$mark8name', 
mark_9='$mark9', mark_9_name='$mark9name', 
mark_10='$mark10', mark_10_name='$mark10name' 
WHERE year='$year' AND semester='$semest'";
        echo $sql;
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully\r\n";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error ."\r\n";
        }
        $conn->close();
?>
