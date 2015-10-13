<?php
    $name = $_POST['name1'].'%';

    $servername = "localhost";
    $username = "root";
    $conn = new mysqli("127.0.0.1:3307", $username, "sonic7");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db("marks");

    $stmt = $conn->prepare("SELECT * FROM student_".$_POST["cohort"]." WHERE first_name LIKE ?");

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
