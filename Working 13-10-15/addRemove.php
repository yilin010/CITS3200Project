<?php
    session_start();
    if(!isset($_SESSION['auth']) && $_SESSION['auth']!=" "){
        header("Location:login.php");
    }
?>
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
        function changeCohort(){
            var manualAdd = document.getElementById('manualAdd');
            var manualRemove = document.getElementById('manualRemove');
            manualAdd.innerHTML = "";
            manualRemove.innerHTML = "";
            // document.getElementById('insert').style.text-align ='center';
            document.getElementById('insert').innerHTML="<h4 style=\"text-align:center\"><u>Rollover Cohort</u></h4><form onsubmit=\"\" method=\"post\"><input class=\"btnRollover\" type=\"button\" value=\"Rollover Cohort\" ></form>";
        }
        function validateNum(){
            var studentNumber = document.forms[1].elements["manualStudentNumber"].value;
            if( (studentNumber.length > 0 && studentNumber.length<8) || isNaN(studentNumber))
                document.forms[1].elements["manualStudentNumber"].className = 'invalidNums';
            else document.forms[1].elements["manualStudentNumber"].className = '';
        }
        function addStudent(){
            var studentName = document.forms[1].elements["manualStudentName"].value.split(" ");
            console.log(studentName);
            var studentNumber = document.forms[1].elements["manualStudentNumber"].value;
            var checkedSeminar = document.forms[1].elements["seminar"].value;
            var stitle = document.getElementById('stitle').value
            if(studentName==""){
                alert("Please enter student name");
                return;
            }
            if(studentNumber==""){
                alert("Please enter student number");
                return;
            }
            if (checkedSeminar==""){
                alert("Please select a cohort");
                return;
            }
            $.ajax({
                type:"POST",
                url: "ManualAddRemove.php",
                data: {table:"student",task:"add",sem:checkedSeminar,fname:studentName[0],lname:studentName[studentName.length-1],num:studentNumber,title:stitle},
                cache: false,
                success: function(html){
                    alert('Successfully added '+studentName+' with student number: '+studentNumber+' to the '+checkedSeminar+' cohort');
                }
            })
        }
        function addMarker(){
            var markerName = document.forms[1].elements["manualMarkerName"].value.split(" ");
            if(markerName==""){
                alert("Please enter marker name");
                return;
            }
            $.ajax({
                type:"POST",
                url: "ManualAddRemove.php",
                data: {table:"marker",task:"add",fname:markerName[0],lname:markerName[1]},
                cache: false,
                success: function(html){
                    console.log(html);
                    alert('Successfully added '+markerName);
                }
            })
        }
        function removeStudent(){
            var studentName = document.forms[2].elements["manualStudentName"].value.split(" ");
            var studentNumber = document.forms[2].elements["manualStudentNumber"].value;
            var checkedSeminar = document.forms[2].elements["seminar"].value;
            if(studentName==""){
                alert("Please enter student name");
                return;
            }
            if(studentNumber==""){
                alert("Please enter student number");
                return;
            }
            if (checkedSeminar==""){
                alert("Please select a cohort");
                return;
            }
            $.ajax({
                type:"POST",
                url: "ManualAddRemove.php",
                data: {table:"student",task:"remove",sem:checkedSeminar,fname:studentName[0],lname:studentName[1],num:studentNumber,title:"Mr"},
                cache: false,
                success: function(html){
                    alert('Successfully removed '+studentName+' with student number: '+studentNumber+' to the '+checkedSeminar+' cohort');
                }
            })
        }
        function removeMarker(){
            var markerName = document.forms[2].elements["manualMarkerName"].value.split(" ");
            if(markerName==""){
                alert("Please enter marker name");
                return;
            }
            $.ajax({
                type:"POST",
                url: "ManualAddRemove.php",
                data: {table:"marker",task:"remove",fname:markerName[0],lname:markerName[1]},
                cache: false,
                success: function(html){
                    alert('Successfully removed '+markerName);
                }
            })

        }
        function spawnStudent(){
            // var excelInput = document.createElement('input');
            // excelInput.setAttribute("type","file");
            // excelInput.setAttribute("style","padding-top: 5px;");
            // excelInput.setAttribute("name","addExcel");
            // excelInput.setAttribute("accept",".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain");
            // document.getElementById('insert').appendChild(excelInput);
            document.getElementById('insert').innerHTML="<h4 style=\"text-align:left\"><u>Add Using File</u></h4><form action=\"addFile.php\" method=\"post\" enctype=\"multipart/form-data\" accept=\".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain\"><input type=\"file\" name=\"fileUpload\" id=\"fileUpload\"><input type=\"submit\" value=\"Upload File\" name=\"submit\"></form>";
            document.getElementById('manualAdd').innerHTML="<h4 style=\"text-align:center;display:block\"><u>Add Manually</u></h4><form method=\"POST\" onsubmit=\"addStudent();return false;\" id=\"manualAdd\"> Name: <select class=\"stylish-select\" style=\"margin-right:5px\" id=\"stitle\"><option value=\"Mr\">Mr</option><option value=\"Ms\">Ms</option><option value=\"Miss\">Miss</option><option value=\"Mrs\">Mrs</option><option value=\"Dr\">Dr</option></select><input class=\"stylish-input\" type=\"text\" name=\"manualStudentName\" placeholder=\"Enter Student Name\"><br><br> <label id=\"numberLabel\" for=\"manualStudentNumber\">Number:</label> <input type=\"text\" onkeyup=\"validateNum()\" id=\"manualStudentNumber\" placeholder=\"Enter Student Number\"><br><br><label id=\"proposalId\" for=\"proposal\">Proposal Seminar</label><input style=\"margin-right:5px\"type=\"radio\" name=\"seminar\" id=\"proposal\" value=\"proposal\"><label for=\"final\">Final Seminar</label><input type=\"radio\" id=\"final\" name=\"seminar\" value=\"final\"><br><br><input style=\"float:center\" type=\"submit\" value =\"Manually Add\"></form>";
            document.getElementById('manualRemove').innerHTML=" <h4 style=\"text-align:center\"><u>Remove Manually</u></h4><form method=\"POST\" onsubmit=\"removeStudent();return false;\" id=\"manualRemove\"> Name: <input type=\"text\" name=\"manualStudentName\" placeholder=\"Enter Student Name\" ><br><br><label id=\"numberLabel\" for=\"manualStudentNumber\"> Number:</label> <input method=\"post\" type=\"text\" id=\"manualStudentNumber\" placeholder=\"Enter Student Number\"><br><br><label id=\"proposalId\" for=\"proposal\">Proposal Seminar</label><input type=\"radio\" style=\"margin-right:5px\" name=\"seminar\" id=\"proposal\" value=\"proposal\"><label for=\"final\">Final Seminar</label><input type=\"radio\" id=\"final\" name=\"seminar\" value=\"final\"><br><br><input style=\"float:center\" type=\"submit\" name=\"manualButton\" value=\"Manually Remove\"></form>";
        }
        function spawnMarker(){
            spawnStudent();
            var num = document.getElementsByName('manualStudentName').length;
            for(var i=num-1;i>-1;i--){
                document.getElementsByName('manualStudentName')[i].setAttribute("placeholder","Enter Marker Name");
                // document.getElementsByName('manualStudentNumber')[i].setAttribute("placeholder","Enter Marker Number");
                document.getElementsByName('manualStudentName')[i].setAttribute("name","manualMarkerName");
                // document.getElementsByName('manualStudentNumber')[i].setAttribute("name","manualMarkerNumber");
            }
            var labels = document.getElementsByTagName('label');
            for(var i = 0;i<labels.length;i++){
                labels[i].innerHTML = "";
            }
            document.forms[1].elements['final'].parentNode.removeChild(document.forms[1].elements['final']);
            document.forms[1].elements['proposal'].parentNode.removeChild(document.forms[1].elements['proposal']);
            document.forms[2].elements['final'].parentNode.removeChild(document.forms[2].elements['final']);
            document.forms[2].elements['proposal'].parentNode.removeChild(document.forms[2].elements['proposal']);
            document.getElementById('manualStudentNumber').parentNode.removeChild(document.getElementById('manualStudentNumber'));
            document.getElementById('manualStudentNumber').parentNode.removeChild(document.getElementById('manualStudentNumber'));
            document.forms[1].setAttribute("onsubmit","addMarker(); return false;");
            document.forms[2].setAttribute("onsubmit","removeMarker(); return false;");
        }
		function rollover(){
            $.ajax({
                type:"POST",
                url: "NewRoll.php",
                cache: false,
                success: function(html){
                    alert(html)
                }
            })
		}
        function manualAdd(){
            alert("MANUAL INSERT");
        }
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
              <li><a href="profile.php">Home</a></li>
              <li><a href="profile.php">Enter Marks</a></li>
            <li><a href="changeAlgo.php">Change Algorithm</a></li>
            <li class="active"><a href="addRemove.php">Add/Remove</a></li>
            <li><a href="displayData.php"> Display Data </a></li>
            <li><a href="Stats.php"> Cohort Data </a></li>
            <li><a href="logout.php"> Logout </a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container starter-template">
        <div class="jumbotron text-center ">
              <h1><span class="fa fa-lock"></span> Add Students or Markers </h1>
              <a onClick="spawnStudent();" class="btn btn-default"><span class="fa fa-user"></span>Add/Remove Student</a>
              <a onClick="spawnMarker();" class="btn btn-default"><span class="fa fa-user"></span>Add/Remove Marker</a>
              <a href="#openModal" class="btn btn-default btnRollover"><span class="fa fa-user"></span>Rollover Cohort</a>
              <!-- <a href="#openModal">Open Modal</a> -->
          </div>
            <div id="openModal" class="modalDialog">
            	<div>
            		<a href="#close" title="Close" class="close">X</a>
                    <span style="font-size:3.0em;color:orange;margin-top:30px" class="glyphicon glyphicon-alert"></span>
                    <h2 class="modalWarning">Warning</h2>
                    <p class="modalP">Rolling a cohort from a proposal seminar into a final seminar will prevent you from making changes to the proposal seminar</p>
                    <br>
                    <p class="modalP">Make sure you have saved all changes for the current proposal seminar</p>
                    <input class="btn btn-confirm" type="button" onclick=" rollover()" value="Continue with rollover">
                    <a href="#close" class="btn btn-cancel"><span class="fa fa-user"></span>Cancel</a>
                </div>
            </div>
           <div class="row">
              <div class="col-md-4" id="insert"></div>
              <div  class="col-md-4" id="manualAdd"></div>
          </div>
          <div class="row" style="margin-top:30px">
              <div class="col-md-4"></div>
              <div class="col-md-4" id="manualRemove"></div>
          </div>
        </div>
    </div>
 </body>
