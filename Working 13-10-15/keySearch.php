<?php
    $name = $_POST['name1'].'%';

    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db($dbname);
    
    //START YEAR/SEM LOOKUP.
    $semest = 1;
    $year = 2015;
    
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
    //END YEAR/SEM LOOKUP

    $stmt = $conn->prepare("SELECT * FROM student_".$_POST["cohort"]." WHERE first_name LIKE ? AND year=".$year." AND semester=".$semest.";");

//
//     // $stmt->bind_params("s",$name);
        if (!$stmt->bind_param("s", $name)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
//     // $result = $stmt->execute();
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $result = $stmt->get_result();
    if($result->num_rows > 0)
    {

        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            for($i=0;$i<3;$i++){
                echo $row[$i].",";
            }
        }
    }

?>
