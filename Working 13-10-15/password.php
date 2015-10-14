<?php

    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        echo "Dead\r\n";
    }
    $conn->select_db($dbname);
    $username = $_POST["user"];
    $stmt = $conn->prepare("SELECT * FROM username_password WHERE username=?");
    if (!$stmt->bind_param("s", $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        echo "\r\n";
    }

    if (!$stmt->execute()) {
        echo "Failed\r\n";
    }
    $result = $stmt->get_result();
    
    if($result->num_rows > 0){
        $row = $result->fetch_array(MYSQLI_NUM);
        if( password_verify($_POST["password"],$row[1]) ){
            session_start();
            $_SESSION['auth'] = "1";
            echo "Verified\r\n";
        }
        else{
            session_start();
            $_SESSION['auth'] = "";
            echo "Bad Password\r\n";
        }
    }
?>
