<?php
    $conn = new mysqli("127.0.0.1", "root");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db("seminarmarking");
    $cohort = $_POST[cohort];
    $data = $_POST[data];
    // echo $data[0];
    $val = 0;
    // echo $data[0];
    $sql = "select * from weighting_".$cohort." where year=2015 && semester=1".";";
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
