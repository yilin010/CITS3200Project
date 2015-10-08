<?php
    $username = $_POST["user"];
    $hash = password_hash($_POST["password"],PASSWORD_BCRYPT);
    $conn = new mysqli("127.0.0.1", "root");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->select_db("user");

    $stmt = $conn->prepare("INSERT INTO login(username,password) VALUES(?,?)");
    if (!$stmt->bind_param("ss", $username,$hash)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if ($stmt->execute()) {
        echo 1;
    }else echo 0;
