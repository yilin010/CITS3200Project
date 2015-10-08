<?php
    $username = $_POST["user"];
    $conn = new mysqli("127.0.0.1", "root");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        echo "dead";
    }
    $conn->select_db("user");
    $stmt = $conn->prepare("SELECT * FROM login WHERE username=?");
    if (!$stmt->bind_param("s", $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$stmt->execute()) {
        echo "failed";
    }
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_array(MYSQLI_NUM);
        if( password_verify($_POST["password"],$row[2]) ){
            session_start();
            $_SESSION['auth'] = "1";
            echo "verified";
        }
        else{
            session_start();
            $_SESSION['auth'] = "";
            echo "bad pass";
        }
    }
?>
