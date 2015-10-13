<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";
    
    $username = $_POST["user"];
    //echo $username;
    $hash = password_hash($_POST["password"],PASSWORD_BCRYPT);
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->select_db($dbname);

    $stmt = $conn->prepare("INSERT INTO login(username,password) VALUES(?,?)");
    if (!$stmt->bind_param("ss", $username,$hash)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        echo "\r\n";
    }
    if ($stmt->execute()) {
        echo 1;
    }else echo 0;
?>
