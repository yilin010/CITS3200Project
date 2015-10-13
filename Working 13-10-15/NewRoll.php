<?php
    $servername = "localhost:3307";
    $username = "root";
    $password = "sonic7";
    $dbname = "marks";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    #Temporary weighting tables.
    $sql = "CREATE TABLE `temp_weighting_proposal` (
`year` int(4) NOT NULL,
`semester` int(1) NOT NULL,
`mark_1_name` varchar(45),
`mark_1` FLOAT,
`mark_2_name` varchar(45),
`mark_2` FLOAT,
`mark_3_name` varchar(45),
`mark_3` FLOAT,
`mark_4_name` varchar(45),
`mark_4` FLOAT,
`mark_5_name` varchar(45),
`mark_5` FLOAT,
`mark_6_name` varchar(45),
`mark_6` FLOAT,
`mark_7_name` varchar(45),
`mark_7` FLOAT,
`mark_8_name` varchar(45),
`mark_8` FLOAT,
`mark_9_name` varchar(45),
`mark_9` FLOAT,
`mark_10_name` varchar(45),
`mark_10` FLOAT)";
    $conn->query($sql);

    #Temporary weighting tables.
    $sql = " create table temp_weighting_final ( 
        `year` int(4) NOT NULL,
`semester` int(1) NOT NULL,
`mark_1_name` varchar(45),
`mark_1` FLOAT,
`mark_2_name` varchar(45),
`mark_2` FLOAT,
`mark_3_name` varchar(45),
`mark_3` FLOAT,
`mark_4_name` varchar(45),
`mark_4` FLOAT,
`mark_5_name` varchar(45),
`mark_5` FLOAT,
`mark_6_name` varchar(45),
`mark_6` FLOAT,
`mark_7_name` varchar(45),
`mark_7` FLOAT,
`mark_8_name` varchar(45),
`mark_8` FLOAT,
`mark_9_name` varchar(45),
`mark_9` FLOAT,
`mark_10_name` varchar(45),
`mark_10` FLOAT)";
    $conn->query($sql);

    #Temporary student table.
    $sql ="create table temp(
    title varchar(45) not null,
    student_no int not null,
    first_name varchar(45) not null,
    last_name varchar(45) not null,
    year int null,
    semester int null,
    supervisor_1 varchar(45) null,
    supervisor_2 varchar(45) null,
    supervisor_3 varchar(45) null,
    supervisor_4 varchar(45) null
    )";
    $conn->query($sql);

    #finds latest year
    $sql = "select year from current_year_semester ORDER BY year DESC LIMIT 1";
    $years = $conn->query($sql);
    $row = $years->fetch_assoc();
    $year = $row["year"];
    #from latest year takes highest semester
    $sql = "select semester from current_year_semester where year =" . $year . " order by semester desc limit 1";
    $semesters = $conn->query($sql);
    $row = $semesters->fetch_assoc();#converting from table tuple to number value
    $semester = $row["semester"];
		
		
	
    $sql = "insert into temp_weighting_final (year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name)
(select year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name
from  weighting_final where year = " .$year." and semester =".$semester. ")";
    $conn->query($sql);

    $sql = "insert into temp_weighting_proposal (year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name)
(select year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7, mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name
from  weighting_proposal where year = " .$year." and semester =".$semester. ")";
    $conn->query($sql);
	#getting old semester's students  
    $sql = "insert into temp (title, student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4) (select title,  student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4 from student_proposal where year = '" .$year."' and semester ='".$semester." ')";
	$conn-> query($sql);
    if ($semester == 2){
        $year++;
        $semester = 1;
    }
    else{
        $semester++;
    }#Iterates time

    #updating year/semester
    $sql = "update temp_weighting_final set year = '".$year ."' , semester = '" .$semester ."' where year is not null";
    $conn-> query($sql);
    $sql = "update temp_weighting_proposal set year = '".$year ."' , semester = '" .$semester ."' where year is not null";
    $conn-> query($sql);
	
	$sql = "update temp set year = '".$year ."' , semester = '" .$semester ."' where year is not null";
	$conn-> query($sql);
	$sql = "update current_year_semester set year = '".$year ."' , semester = '" .$semester ."' where year is not null";
	$conn-> query($sql);
    $sql = "insert into weighting_final (year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name)
(select year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name
from  temp_weighting_final)";
    $conn->query($sql);

    $sql = "insert into weighting_proposal (year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name)
(select year, semester, mark_1, mark_1_name, mark_2, mark_2_name, mark_3, mark_3_name, mark_4,mark_4_name, mark_5, mark_5_name, mark_6,mark_6_name, mark_7,mark_7_name, mark_8,mark_8_name, mark_9,mark_9_name, mark_10, mark_10_name
from  temp_weighting_proposal)";
    $conn->query($sql);
    $sql = "insert into student_final(title, student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4)
			(select title,  student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4
			from temp)";
    $conn-> query($sql);#inserts iterated time values where year and semester are null

    #cleaning up.
	$sql = "Drop Table if exists temp_weighting_final";
	$conn-> query($sql);
	
	$sql = "Drop Table if exists temp_weighting_proposal";
	$conn-> query($sql);
	
	$sql = "Drop Table if exists temp";
	$conn-> query($sql);
	$conn->close();
	?>
