<?php/*
    session_start();
    if(!isset($_SESSION['auth']) && $_SESSION['auth']!=" "){
        header("Location:login.php");
    }*/
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
        
        function validateNum(){
            var studentNumber = document.forms[1].elements["manualStudentNumber"].value;
            //console.log(studentNumber.length);
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
            // var dataString = '&name='+studentName+'&num='+studentNumber+'&sem='+checkedSeminar;
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
                url: "superAdd.php",
                data: {table:"student",task:"add",sem:checkedSeminar,fname:studentName[0],lname:studentName[studentName.length-1],num:studentNumber,title:stitle},
                cache: false,
                success: function(html){
                    //alert(html)
                    //console.log(html);
                    alert('Successfully added '+studentName+' with student number: '+studentNumber+' to '+checkedSeminar+' cohort');
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
                url: "superAdd.php",
                data: {table:"student",task:"remove",sem:checkedSeminar,fname:studentName[0],lname:studentName[1],num:studentNumber,title:"Mr"},
                cache: false,
                success: function(html){
                    alert(html)
                    // alert('Successfully added '+studentName+' with student number: '+studentNumber+' to '+checkedSeminar+' cohort');
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
            
            document.getElementById('manualAdd').innerHTML="<h4 style=\"text-align:center;display:block\"><u>Add Supervisor</u></h4><form method=\"POST\" onsubmit=\"addStudent();return false;\" id=\"manualAdd\"> Name: <select class=\"stylish-select\" style=\"margin-right:5px\" id=\"stitle\"><option value=\"Mr\">Mr</option><option value=\"Ms\">Ms</option><option value=\"Miss\">Miss</option><option value=\"Mrs\">Mrs</option></select><input class=\"stylish-input\" type=\"text\" name=\"manualStudentName\" placeholder=\"Enter Student Name\"><br><br> <label id=\"numberLabel\" for=\"manualStudentNumber\">Number:</label> <input type=\"text\" onkeyup=\"validateNum()\" id=\"manualStudentNumber\" placeholder=\"Enter Student Number\"><br><br><label id=\"proposalId\" for=\"proposal\">Proposal Seminar</label><input style=\"margin-right:5px\"type=\"radio\" name=\"seminar\" id=\"proposal\" value=\"proposal\"><label for=\"final\">Final Seminar</label><input type=\"radio\" id=\"final\" name=\"seminar\" value=\"final\"><br><br><input style=\"float:center\" type=\"submit\"></form>";
            document.getElementById('manualRemove').innerHTML=" <h4 style=\"text-align:center\"><u>Remove Supervisor</u></h4><form method=\"POST\" onsubmit=\"removeStudent();return false;\" id=\"manualRemove\"> Name: <input type=\"text\" name=\"manualStudentName\" placeholder=\"Enter Student Name\" ><br><br><label id=\"numberLabel\" for=\"manualStudentNumber\"> Number:</label> <input method=\"post\" type=\"text\" id=\"manualStudentNumber\" placeholder=\"Enter Student Number\"><br><br><label id=\"proposalId\" for=\"proposal\">Proposal Seminar</label><input type=\"radio\" style=\"margin-right:5px\" name=\"seminar\" id=\"proposal\" value=\"proposal\"><label for=\"final\">Final Seminar</label><input type=\"radio\" id=\"final\" name=\"seminar\" value=\"final\"><br><br><input style=\"float:center\" type=\"submit\" name=\"manualButton\" value=\"Manually Remove\"></form>";
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
              <li><a href="#">Home</a></li>
              <li><a href="#dataEntry">Enter Marks</a></li>
              <li><a href="#contact">Change Algorithm</a></li>
              <li><a href="#addRemove">Add/Remove</a></li>
              <li><a href="#export"> Export Data </a></li>
              <li><a href="logout.php"> Logout </a></li>
              <li><a class="navbar-box"> Currently working on Database</a></li>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container starter-template">
        <div class="jumbotron text-center ">
              <h1><span class="fa fa-lock"></span> Add Students or Markers </h1>

              <a onClick="spawnStudent();" class="btn btn-default"><span class="fa fa-user"></span>Add/Remove supervisor</a>
              
              <!-- <a href="#openModal">Open Modal</a> -->
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
