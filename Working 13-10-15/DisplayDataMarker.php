<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    //Get the inputs.
    $name       = $_POST["name"];
    $cohort     = $_POST["cohort"];
    $year       = $_POST["year"];
    $semester   = $_POST["semester"];
    $names      = explode(" ", $name);

    //Start the MySQL connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\r\n";
        exit(1);
    }

    //Get the weigthings for the appropriate time and cohort.
    $mark = array();
    $marknames = array();
    $nmarks = 0;
    $sql = "SELECT * FROM weighting_".$cohort." WHERE year=".$year." AND semester=".$semester;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $mark[] = $row['mark_1'];
            $mark[] = $row['mark_2'];
            $mark[] = $row['mark_3'];
            $mark[] = $row['mark_4'];
            $mark[] = $row['mark_5'];
            $mark[] = $row['mark_6'];
            $mark[] = $row['mark_7'];
            $mark[] = $row['mark_8'];
            $mark[] = $row['mark_9'];
            $mark[] = $row['mark_10'];
            
            //echo "Mark weightings:\r\n";
            foreach($mark as $m){
                if($m!=0) {
                    $nmarks++;
                }
            }
            $mark = array_slice($mark, 0, $nmarks);
            //foreach($mark as $s){
            //    echo $s."\r\n";
            //}
            //echo "Number of marks: ".$nmarks."\r\n";

            $marknames[]= $row['mark_1_name'];
            $marknames[]= $row['mark_2_name'];
            $marknames[]= $row['mark_3_name'];
            $marknames[]= $row['mark_4_name'];
            $marknames[]= $row['mark_5_name'];
            $marknames[]= $row['mark_6_name'];
            $marknames[]= $row['mark_7_name'];
            $marknames[]= $row['mark_8_name'];
            $marknames[]= $row['mark_9_name'];
            $marknames[]= $row['mark_10_name'];
            
            $marknames = array_slice($marknames, 0, $nmarks);
            //echo "Mark names:\r\n";
            //foreach($marknames as $n){
            //    echo $n."\r\n";
            //}
        }
    } else {
        echo "No results found.\r\n";
    }
    
    //Get the marks data for the appropriate student, cohort and time.
    $sql = "SELECT * FROM mark_".$cohort." WHERE marker='".$name."' AND year=".$year." AND semester=".$semester;
    //echo $sql."\r\n";

    $table = array();
    $firstrow = array();
    $firstrow[] = "Student Name";
    foreach($marknames as $mname){
        $firstrow[] = $mname;
    }
    $firstrow[] = "Overall";
    $table[] = $firstrow;

    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $thisrow = array();
            $thisrow[] = $row['student_no'];
            $thisrow[] = $row['mark_1'];
            $thisrow[] = $row['mark_2'];
            $thisrow[] = $row['mark_3'];
            $thisrow[] = $row['mark_4'];
            $thisrow[] = $row['mark_5'];
            $thisrow[] = $row['mark_6'];
            $thisrow[] = $row['mark_7'];
            $thisrow[] = $row['mark_8'];
            $thisrow[] = $row['mark_9'];
            $thisrow[] = $row['mark_10'];
            $thisrow = array_slice($thisrow, 0, $nmarks+1);
            $totalmark = 0;
            for($i=0; $i<$nmarks; $i++){
                $totalmark += $thisrow[$i+1]*$mark[$i];
            }
            $thisrow[] = $totalmark;
            $table[] = $thisrow;
        }
    } else {
        echo "No results found.\r\n";
    }

    $nrows = count($table);

    //Create an arrays of average, minimum and maximum marks for each section.
    $mins = array("MINIMUM",100,100,100,100,100,100,100,100,100,100,100);
    $maxs = array("MAXIMUM",0,0,0,0,0,0,0,0,0,0,0);
    $avgs = array("AVERAGE",0,0,0,0,0,0,0,0,0,0,0);
    $snum = array();
    for($a=1;$a<$nrows;$a++){
        for($b=1;$b<$nmarks+2;$b++){
            if($table[$a][$b]<$mins[$b]){
                $mins[$b] = $table[$a][$b];
            }
            if($table[$a][$b]>$maxs[$b]){
                $maxs[$b] = $table[$a][$b];
            }
            $avgs[$b]+=$table[$a][$b];
        }
        $snum[] = $table[$a][0];
    }
    for($d=1;$d<$nmarks+2;$d++){
        $avgs[$d]=round($avgs[$d]/($nrows-1), 2);
    }
    $mins = array_slice($mins, 0, $nmarks+2);
    $maxs = array_slice($maxs, 0, $nmarks+2);
    $avgs = array_slice($avgs, 0, $nmarks+2);
    $table[] = $mins;
    $table[] = $maxs;
    //echo "Minimums: ";
    //foreach($mins as $min){
    //    echo $min." ";
    //}
    //echo "\r\n";

    //Create an array of range for each section.
    $rans = array("RANGE");
    $mins = array_slice($mins, 1, $nmarks+1);
    $maxs = array_slice($maxs, 1, $nmarks+1);
    for($c=0;$c<$nmarks+1;$c++){
        $rans[] = $maxs[$c]-$mins[$c];
    }
    $table[] = $rans;
    $table[] = $avgs;

    //Get the student number from the appropriate student table for each student marked.
    $sql = "SELECT first_name, last_name FROM student_".$cohort." WHERE (";
    foreach($snum as $num){
        $sql .="student_no=".$num." OR ";
    }
    $sql = substr($sql, 0, -4);
    $sql .=") AND year=".$year." AND semester=".$semester;
    //echo $sql."\r\n";
    $studentname = array();
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $studentname[] = $row['first_name']." ".$row['last_name'];
            //echo "Student number: ".$snumber.".\r\n";
        }
    } else {
        echo "No results found.\r\n";
    }
    $conn->close();

    for($a=1;$a<$nrows;$a++){
        $table[$a][0] = $studentname[$a-1];
    }
    //We now have a table that includes all of the headings and data to display on the page, simply output.

    $htmltable = "<table class = \"table table-striped\" id=\"markersData\" style=\"margin-top: 30px\">";

    //echo "\r\nData for ".$name.":\r\n";
    foreach($table as $t){ 
        $htmltable .= "<tr>";
        
        foreach($t as $d){
            //echo $d." ";
            $htmltable .= "<th>".$d."</th>";
        }
        $htmltable .= "</tr>";
        //echo "\r\n";
    }
    $htmltable .= "</table>";
    echo $htmltable;
    //echo $nrows."\r\n";
?>