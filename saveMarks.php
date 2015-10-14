<?php
    $tableData = $_POST["array"];
    $strideLength = $_POST["stride"];
    $sNumber = $_POST["sNumber"];
    // echo $tableData;
    $conn = new mysqli("127.0.0.1", "root");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db("seminarmarking");

    function myFilter($var){
      return ($var !== NULL && $var !== FALSE && $var !== '');
    }
    //
    $conn = new mysqli("127.0.0.1", "root");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db("seminarmarking");
    $array = array_filter($tableData, 'myFilter');
    print_r($array);
    $rows = $_POST["rows"];//count($array)/$strideLength;
    echo "THIS IS ROWS";
    echo $rows;echo "\n";

    for ($j=1; $j < $rows; $j++) {
        $query .= "(";
        for ($i=0; $i < $strideLength ; $i++) {
            if($i == 0){
                $query .= $sNumber.",\"".$array[1+$i+$j*$strideLength]."\",";
            }
            else if ($i < $strideLength-3) {
                $query .= $array[1+$i+$j*$strideLength].",";
            }
            else {
                $query .= $array[1+$i+$j*$strideLength];
            }
            // echo $i+$j*$strideLength;
        }
        if($j < $rows-1) {
            $query .="),";
        }
        else {
            $query .=")";
        }
            // echo $query;
            // $numArr = explode(',',$query);
            // echo $numArr[1];
            // insert into mark_proposal(student_no,marker,mark_1,mark_2,mark_3,overall) values(12345678,"first marker",1,1,1,1) on duplicate key update mark_1=0,mark_2=0,mark_3=0,overall=0;
            // $sql = "INSERT INTO mark_proposal(student_no,marker,mark_1,mark_2,mark_3,overall) VALUES (".$query.") ON DUPLICATE KEY UPDATE mark_1=".$numArr[2].", mark_2=".$numArr[3].", mark_3=".$numArr[4].", overall=".$numArr[5].";";

            // echo $sql;
            // $conn->query($sql);
    }
    $del = "DELETE FROM mark_proposal WHERE student_no=".$sNumber.";";
    // echo $del;
    $conn->query($del);
    // echo $query;
    // echo $strideLength-2;
    echo $strideLength;
    for ($j=1; $j<$strideLength-2; $j++) {
        if($j<($strideLength-3)) $cols .="mark_".$j.",";
        else $cols .="mark_".$j;
    }
    $sql = "INSERT INTO mark_proposal(student_no,marker,".$cols.") values ".$query.";";
    echo $sql;
    $conn->query($sql);
?>
