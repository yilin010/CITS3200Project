<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    // Function to calculate square of value - mean
    function sd_square($x, $mean) { return pow($x - $mean,2); }

    // Function to calculate standard deviation (uses sd_square)    
    function sd($array) {
    
    // square root of sum of squares devided by N-1
        return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
    }

    //Get the inputs;
    $cohort     = /*"final";*/          $_POST["cohort"];
    $year       = /*2015; */            $_POST["year"];
    $semester   = /*2;*/                $_POST["semester"];

    $cohorttable = array();
    $studentlist = array();

    $cohorttable[] = array("Student Number","Student Name", "Number of Markers", "Final Mark", "Mark MIN", "Mark MAX", "Mark RANGE", "Mark STD DEV");

    $subav = array();
    $firstline = array();

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\r\n";
        exit(1);
    }
    
    //Get the student numbers from the appropriate student table.
    $sql = "SELECT student_no FROM student_".$cohort." WHERE year=".$year." AND semester=".$semester;
    //echo $sql."\r\n";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $studentlist[] = $row['student_no'];
            //echo "Student number: ".$snumber.".\r\n";
        }
    } else {
        echo "No results found.\r\n";
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
            
            /*foreach($mark as $s){
                echo $s."\r\n";
            }
            echo "Number of marks: ".$nmarks."\r\n";*/

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
            foreach($marknames as $n){
                //echo $n."\r\n";
                $firstline[] = $n." Average";
            }
        }
    } else {
        echo "No results found.\r\n";
    }
    $firstline[] = "Overall";
    $firstline[] = "S.D. for Overall Marks";
    $subav[] = $firstline;
    $subav[] = array();
    $newrow = array();
    $finals = array();
    foreach($studentlist as $sn) {
        $thisnewrow = array();
        $thisnewrow[] = $sn;
        //Start the MySQL connection.
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error . "\r\n";
            exit(1);
        }
        //Get the student name from the appropriate student table.
        $sql = "SELECT first_name, last_name FROM student_".$cohort." WHERE student_no=".$sn." AND year=".$year." AND semester=".$semester;
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $thisnewrow[] = $row['first_name']." ".$row['last_name'];
            }
        } else {
            echo "No results found.\r\n";
        }
        //Get the marks data for the appropriate student, cohort and time.
        $sql = "SELECT * FROM mark_".$cohort." WHERE student_no=".$sn." AND year=".$year." AND semester=".$semester;
        $table = array();
        $firstrow = array();
        $firstrow[] = "Marker Name";
        foreach($marknames as $mname){
            $firstrow[] = $mname;
        }
        $firstrow[] = "Overall";
        $table[] = $firstrow;
        $finalmarks = array();
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $thisrow = array();
                $thisrow[] = $row['marker'];
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
                $finalmarks[] = $totalmark;
                $thisrow[] = $totalmark;
                $table[] = $thisrow;
            }
        } else {
            //echo "No results found.\r\n";
        }
        $conn->close();
        $nrows = count($table);
        $thisnewrow[] = ($nrows-1);
        //Create an arrays of minimum and maximum marks for each section.
        $mins = array("MINIMUM",100,100,100,100,100,100,100,100,100,100,100);
        $maxs = array("MAXIMUM",0,0,0,0,0,0,0,0,0,0,0);
        $avgs = array("AVERAGE",0,0,0,0,0,0,0,0,0,0,0);
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
        }
        //echo "Tots ";
        for($d=1;$d<$nmarks+2;$d++){
            $avgs[$d]=round($avgs[$d]/($nrows-1), 2);
            $newrow[$d] += $avgs[$d];
            //echo " ";
            if($d==$nmarks+1){
                $finals[] += $avgs[$d];
            }
        }
        //echo "\r\n";
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
        $thisnewrow[] = $avgs[count($avgs)-1];
        $thisnewrow[] = $mins[count($mins)-1];
        $thisnewrow[] = $maxs[count($maxs)-1];
        $thisnewrow[] = $rans[count($rans)-1];
        $thisnewrow[] = round(sd($finalmarks),2);
        $cohorttable[] = $thisnewrow;
    }
    $newnewrow = array();
    foreach($newrow as $hg){
        $newnewrow[] = round($hg/(count($cohorttable)-1),2);
        //echo $hg." ";
    }
    /*echo $newrow[0];
    echo "Aves ";
    for($f=0; $f<count($newrow)-1; $f++){
        echo $newrow[f];
        echo " ";
        echo $newrow[f] = round($newrow[f]/(count($cohorttable)-1),2);
        echo " ";
    }*/
    //echo "ns: ".(count($cohorttable)-1);
    //echo sd($finals);
    $newnewrow[] = round(sd($finals),2);
    /*echo "This is newrow2: ";
    foreach($newrow as $hg){
        echo $hg." ";
    }*/
    $subav[] = $newnewrow;
    $htmltable = "<table class = \"table table-bordered\" id=\"averages\" style=\"margin-top: 30px\">";
    //echo "\r\nData for ".$name.":\r\n";
    foreach($subav as $t){ 
        $htmltable .= "<tr>";

        foreach($t as $d){
            //echo $d." ";
            $htmltable .= "<th class = \"info\">".$d."</th>";
        }
        $htmltable .= "</tr>";
        //echo "\r\n";
    }
    $htmltable .= "</table>";

    $htmltable .= "<table class = \"table table-striped\" id=\"cohortData\" style=\"margin-top: 30px\">";
    foreach($cohorttable as $t){ 
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
