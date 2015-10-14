<?php
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

    $cohort = $_POST["cohort"];
    $data = $_POST["data"];
    // echo $data[0];
    $val = 0;
    // echo $data[0];
    $sql = "select * from weighting_".$cohort." where year=".$year." && semester=".$semest.";";
    $result = $conn->query($sql);
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $elements = (count(array_filter($row))/2)-1;
        $multipliers =[];
        for ($i=1; $i <= $elements ; $i++) {
            // echo $row['mark_'.$i.'_name'];
            // echo $row['mark_2_name'];
            if($row['mark_'.$i.'_name'] != 'null') {
                $val = $val + $row['mark_'.$i]*$data[$i-1];
            }
            else break;
        }

    }else {
        echo "No results found.\r\n";
    }
    echo $val;

?>
