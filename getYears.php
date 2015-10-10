<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";
    
    //No inputs required

    //Start the MySQL connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\r\n";
        exit(1);
    }

    //Get all the years available in the database.
    $years = array();
    $sql = "SELECT year FROM weighting_final";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $years[] = $row['year'];
        }
    } else {
        echo "No results found.\r\n";
    }
    $conn->close();
    
    //echo "Years: ";
    //foreach($years as $s){
    //    echo $s." ";
    //}
    //echo "\r\n";

    echo json_encode($years);
?>