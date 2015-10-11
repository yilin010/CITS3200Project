<!DOCTYPE html>

<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <link href="css/additionalStyles.css" rel="stylesheet">

    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <script src="export.js" type="text/javascript"></script>

<script>
function selectedOption() {

    var markerName = document.getElementById("markersName").value;
    var selectedCohort = $('#selectCohort option:selected').val();
    var selectedSem = $('#selectSem option:selected').val();
    var selectedYear = $("#yearsSelect option:selected").text();

    //     $name       = /*"James Hercock";*/  $_POST["name"];
    // $cohort     = /*"final";*/          $_POST["cohort"];
    // $year       = 2015;             $_POST["year"];
    // $semester   = /*2;*/                $_POST["semester"];
    // $names      = explode(" ", $name);

    //     var markerName = "Yi Lin Lim";
    // var selectedCohort = "final";
    // var selectedSem = 2;
    // var selectedYear = 2015;


                $.ajax({
                type:"POST",
                url: "DisplayDataMarker.php",
                data: {name:markerName,cohort:selectedCohort,year:selectedYear,semester:selectedSem},
                cache: false,
                     success: function(htmltable) {
                          $('#markersdata').html(htmltable);
                      },
                      error: function() {
                        alert("Error!");
                      }                    // alert('Successfully added '+studentName+' with student number: '+studentNumber+' to '+checkedSeminar+' cohort');
                })

}

$(document).ready(function() {
      
    $('#selectCohort').change(selectedOption );
     selectedOption();
     
    $("#selectCohort").change(function () {
      var country = $('#selectCohort option:selected').val();

        }).change();



        $('#selectSem').change(selectedOption );
     selectedOption();
     
    $("#selectSem").change(function () {
      var country = $('#selectSem option:selected').val();

        }).change();

        $('#yearsSelect').change(selectedOption );
     selectedOption();
     
    $("#yearsSelect").change(function () {
      var country = $('#yearsSelect option:selected').val();

        }).change();





    });


</script>


</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Seminar Marking Database</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="profile.html">Home</a></li>
            <li><a href="#dataEntry">Enter Marks</a></li>
            <li><a href="#contact">Change Algorithm</a></li>
            <li><a href="addRemove.html">Add/Remove</a></li>
            <li><a href="#export"> Export Data </a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div id="navigation">

    <div class="container starter-template">
        <div class="jumbotron text-center ">
              <h1><span class="fa fa-lock"></span> Display Data </h1>

              <a href="DataStudentPage.php" class="btn btn-default"><span class="fa fa-user"></span>Students</a>
              <a href="DataMarkerPage.php" class="btn btn-default"><span class="fa fa-user"></span>Markers</a>
</div>

            <h2> Markers' Statistics </h2>
            <div class="col-md-20">

<input type="text" id="markersName" placeholder="Enter Marker's Name">

                <select class="stylish-select" id="selectCohort"  form="studentSearch">
                    <option value="anySeminar" selected>Any Seminar</option>
                    <option value="proposalSeminar">Proposal Seminar</option>
                    <option value="finalSeminar">Final Seminar</option>
                </select>
                                <select class="stylish-select" id="selectSem"  form="studentSearch">
                    <option value="anySeminar" selected>Any Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>

         <select class="stylish-select" id="yearsSelect" onload="fetch_select();">
             <option>
              Select year
           </option>
           </select>
                <button  onclick="selectedOption()">Search</button>
<br/>
<br/>
<?php
    //Need to fill in appropriate details here
    $servername = "localhost:3307";
    $username = "root";
    $password = "password";
    $dbname = "databaseName";
    
    //No inputs required

    //Start the MySQL connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\r\n";
        exit(1);
    }

    //Get all the years available in the database.
    //$years = array();
    $sql = "SELECT year FROM weighting_final";
    $result = $conn->query($sql);
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            
              echo "<option>".$row['year']."</option>";

            //$years[] = $row['year'];
        }
    } else {
        echo "No results found.\r\n";
    }
    $conn->close();
    
    //echo "Years: ";
    //foreach($years as $s){
    //    echo $s." ";
    //}
    //echo "\r\n";

?>



<br/>


<button  onclick="tablesToExcel(['markersData'], ['Markers Data'], 'Markers.xls', 'Excel')">Export to Excel</button>

        <div id="markersdata" class="col-md-20">

</div>
</div>
</body>
</html>
