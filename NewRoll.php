
	
<?php
	
		
		$servername = "localhost";
		$username = "root";
		$password = "DaPassword1";#example password

		$conn = new mysqli($servername, $username, $password, "testDB");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		
		
		
		$sql = "CREATE TABLE `temp_weighting_proposal` (
  `year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `mark_1` decimal(10,10) NOT NULL,
  `mark_1_name` varchar(45) NOT NULL,
  `mark_2` decimal(10,10) DEFAULT NULL,
  `mark_2_name` varchar(45) DEFAULT NULL,
  `mark_3` decimal(10,10) DEFAULT NULL,
  `mark_3_name` varchar(45) DEFAULT NULL,
  `mark_4` decimal(10,10) DEFAULT NULL,
  `mark_4_name` varchar(45) DEFAULT NULL,
  `mark_5` decimal(10,10) DEFAULT NULL,
  `mark_5_name` varchar(45) DEFAULT NULL,
  `mark_6` decimal(10,10) DEFAULT NULL,
  `mark_6_name` varchar(45) DEFAULT NULL,
  `mark_7` decimal(10,10) DEFAULT NULL,
  `mark_7_name` varchar(45) DEFAULT NULL,
  `mark_8` decimal(10,10) DEFAULT NULL,
  `mark_8_name` varchar(45) DEFAULT NULL,
  `mark_9` decimal(10,10) DEFAULT NULL,
  `mark_9_name` varchar(45) DEFAULT NULL,
  `mark_10` decimal(10,10) DEFAULT NULL,
  `mark_10_name` varchar(45) DEFAULT NULL)"; 
		$conn->query($sql);
		
		
		
		
		$sql = " create table temp_weighting_final ( 
			`year` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `mark_1` decimal(10,10) NOT NULL,
  `mark_1_name` varchar(45) NOT NULL,
  `mark_2` decimal(10,10) DEFAULT NULL,
  `mark_2_name` varchar(45) DEFAULT NULL,
  `mark_3` decimal(10,10) DEFAULT NULL,
  `mark_3_name` varchar(45) DEFAULT NULL,
  `mark_4` decimal(10,10) DEFAULT NULL,
  `mark_4_name` varchar(45) DEFAULT NULL,
  `mark_5` decimal(10,10) DEFAULT NULL,
  `mark_5_name` varchar(45) DEFAULT NULL,
  `mark_6` decimal(10,10) DEFAULT NULL,
  `mark_6_name` varchar(45) DEFAULT NULL,
  `mark_7` decimal(10,10) DEFAULT NULL,
  `mark_7_name` varchar(45) DEFAULT NULL,
  `mark_8` decimal(10,10) DEFAULT NULL,
  `mark_8_name` varchar(45) DEFAULT NULL,
  `mark_9` decimal(10,10) DEFAULT NULL,
  `mark_9_name` varchar(45) DEFAULT NULL,
  `mark_10` decimal(10,10) DEFAULT NULL,
  `mark_10_name` varchar(45) NOT NULL)";
		$conn->query($sql);
		
$sql ="create table temp(
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
		echo $year;
		echo $semester;
		
	
	

		$sql = "insert into temp (student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4)
			(select student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4
			from student_proposal where year = '" .$year."' and semester ='".$semester." ')";
	$conn-> query($sql);
	
	
		
		if ($semester == 2){
			$year++;
			$semester = 1;
		}
		else{
			$semester++;
		}#Iterates time
		
		
		$sql = "update temp set year = '".$year ."' , semester = '" .$semester ."' where year is not null";
	$conn-> query($sql);
	$sql = "update current_year_semester set year = '".$year ."' , semester = '" .$semester ."' where year is not null";
	$conn-> query($sql);
		$sql = "insert into student_final(student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4)
			(select student_no, first_name, last_name, year, semester, supervisor_1, supervisor_2, supervisor_3 , supervisor_4
			from temp)";
		$conn-> query($sql);#inserts iterated time values where year and semester are null
		
		
	$sql = "Drop Table if exists temp_weighting_final";
	$conn-> query($sql);
	
	$sql = "Drop Table if exists temp_weighting_proposal";
	$conn-> query($sql);
	
	$sql = "Drop Table if exists temp";
	$conn-> query($sql);
	$conn->close();
	
	?>
