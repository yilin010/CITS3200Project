<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db($dbname);
    $cohort = $_POST[cohort];
    $sNumber = $_POST[num];
    
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

    // for ($i=1; $i< ; $i++) {
    //     # code...
    // }
    $htmltable = "<form method=\"POST\">";
    $htmltable .= "<table class = \"table table-bordered\" id=\"markerTable\" style=\"margin-top: 30px\">";
    $htmltable .="<thead><tr>";
    $htmltable .="<th style=\"text-align: center;vertical-align: middle\">Selected</th>";
    $htmltable .="<th style=\"text-align: center\">Marker Name</th>";

    $sql = "select * from weighting_".$cohort." where year=".$year." && semester=".$semest.";";
    $result = $conn->query($sql);
    $columns = 0;
    $headers = [];
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $elements = (count(array_filter($row))/2)-1;

        $multipliers =[];
        for ($i=1; $i <= $elements ; $i++) {
            // echo $row['mark_'.$i.'_name'];
            // echo $row['mark_2_name'];
            if($row['mark_'.$i.'_name'] != 'null') {
                $multipliers[$i] = $row['mark_'.$i];
                // $htmltable .="<th colspan=\"2\" style=\"text-align: center\">Delivery Mark</th>";
                $htmltable .="<th style=\"text-align: center\">".$row['mark_'.$i.'_name']."</th>";
                // $row['mark_'.$i.'_name'];
                $columns = $i;
                $headers[$i] = $row['mark_'.$i.'_name'];
            }
            else break;
        }

        $htmltable .="<th style=\"text-align: center\">Final Mark</th></tr></thead><tbody>";
    } else {
        echo "No results found.\r\n";
    }





    $sql = "select * from mark_".$cohort." where student_no=".$sNumber.";";
    // echo $sql;
    $result = $conn->query($sql);
    // var_dump($result);
    $rowNum = 1;
    if(mysqli_num_rows($result) > 0) {
        // echo "HELLO?";
        // $htmltable = "<form method=\"POST\">";
        // $htmltable .= "<table class = \"table table-bordered\" id=\"markerTable\" style=\"margin-top: 30px\">";
        // $htmltable .="<thead><tr>";
        // $htmltable .="<th style=\"text-align: center;vertical-align: middle\">Selected</th>";
        // $htmltable .="<th style=\"text-align: center\">Marker Name</th>";
        // $htmltable .="<th colspan=\"2\" style=\"text-align: center\">Delivery Mark</th>";
        // $htmltable .="<th style=\"text-align: center\">Content Mark</th>";
        // $htmltable .="<th style=\"text-align: center\">Final Mark</th></tr></thead><tbody>";
        while($row = mysqli_fetch_assoc($result)){
        $numMarks = count(array_filter($row))-2;
        // echo $numMarks;
        // echo s$row['mark_'.$colNum];
        $htmltable .="<tr><td style=\"text-align: center;vertical-align: middle;width: 78px\"><input type=\"checkbox\" name=\"selected\"></td>";
        $htmltable .="<td style=\"width: 78px\"><input type=\"text\" style=\"border: 0px solid;text-align: center\" name=\"marker\" placeholder=\"Enter A Marker Name\" value=\" ".$row['marker']."\"></td>";
        // $htmltable .="<td>".$row['marker']."</td>";
        $val = 0;
        for ($i=1; $i<=count($headers); $i++) {
            // $str = $i;
            // echo $str;
            // echo $row['mark_'.$i];
            $val += $multipliers[$i]*$row['mark_'.$i];
            // echo $val;echo "\n";
            $htmltable .= "<td style=\"width: 78px\"><input type=\"text\" style=\"border: 0px solid;text-align: center\" id=\"".$headers[$i].$rowNum."\" name=\"".$rowNum."\" placeholder=\"".$headers[$i]."\" value=\"".$row['mark_'.$i]."\" onchange=\"calc(".$rowNum.")\"></td>";
        }
        // $valSql = ""
        $htmltable .="<td style=\"width: 78px\"><input type=\"text\" style=\"border: 0px solid;text-align: center\" name=\"final".$rowNum."\" placeholder=\"Final Mark\" onChange=\"calc();\" value=\"".$val."\"></td>";
        $rowNum++;
    }
    // echo "hello?!?!";
    $htmltable .="</tr></tbody></table></form>";

        // $colNum = $row['count(*)'];
            //echo "Student number: ".$snumber.".\r\n";
            echo $htmltable;

    } else {
        $htmltable .="<tr><td style=\"text-align: center;vertical-align: middle;width: 78px\"><input type=\"checkbox\" name=\"selected\"></td>";
        $htmltable .="<td style=\"width: 78px\"><input type=\"text\" style=\"border: 0px solid;text-align: center\" name=\"marker\" placeholder=\"Enter A Marker Name\" value=\"\"></td>";
        for ($i=1; $i<=count($headers); $i++) {
            // $str = $i;
            // echo $str;
            // echo $row['mark_'.$i];
            $val += $multipliers[$i]*$row['mark_'.$i];
            // echo $val;echo "\n";
            $htmltable .= "<td style=\"width: 78px\"><input type=\"text\" style=\"border: 0px solid;text-align: center\" id=\"".$headers[$i].$rowNum."\" name=\"".$rowNum."\" placeholder=\"".$headers[$i]."\" value=\"\" onchange=\"calc(".$rowNum.")\"></td>";
        }
        $htmltable .="<td style=\"width: 78px\"><input type=\"text\" style=\"border: 0px solid;text-align: center\" name=\"final".$rowNum."\" placeholder=\"Final Mark\" onChange=\"calc();\" value=\"\"></td>";
        $rowNum++;

        echo $htmltable;

    }
    // $sql = "";


    $stmt = $conn->prepare("SELECT * FROM mark_".$cohort." WHERE student_no=?");
    if (!$stmt->bind_param("s", $sNumber)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $result = $stmt->get_result();
    if($result->num_rows > 0)
    {
        while ($row = $result->fetch_array(MYSQLI_NUM))
        {
            for($i=1;$i<$colNum;$i++){
                //  echo $row[$i].",";
            }
            // echo "\n";
        }
    }

 ?>
