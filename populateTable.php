<?php
    $conn = new mysqli("127.0.0.1", "root");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db("seminarmarking");
    $stmt = $conn->prepare("SELECT * FROM mark_proposal WHERE student_no=?");
    $sNumber = $_POST[num];
    if (!$stmt->bind_param("s", $sNumber)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $result = $stmt->get_result();
    // var_dump($result);
    if($result->num_rows > 0)
    {
        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            for($i=0;$i<7;$i++){
                echo $row[$i].",";
            }
        }
    }

 ?>
