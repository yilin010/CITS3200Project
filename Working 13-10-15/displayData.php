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
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    
    <script type="text/javascript" charset="utf-8">
$(function(){
            function contentSwitcher(settings){
                var settings = {
                   contentClass : '.content',
                   navigationId : '#navigation'
                };
                //Hide all of the content except the first one on the nav
                $(settings.contentClass).hide();
                $(settings.navigationId).find('li:first').addClass('active');
                //onClick set the active state, hide the content panels and show the correct one
                $(settings.navigationId).find('a').click(function(e){
                    var contentToShow = $(this).attr('href');
                    contentToShow = $(contentToShow);
                    //dissable normal link behaviour
                    e.preventDefault();
                    //set the proper active class for active state css
                    $(settings.navigationId).find('li').removeClass('active');
                    $(this).parent('li').addClass('active');
                    //hide the old content and show the new
                    $(settings.contentClass).hide();
                    contentToShow.show();
                });
            }
            contentSwitcher();
        });
    function studentData(){
    }
    function getYears(){
        $.ajax({
            type:"POST",
            url:"getYears.php",
            cache:false,
            success:function(html){
                console.log(html);
                var table = html;
                document.getElementById('yearList').innerHTML = table;
                document.getElementById('yearList2').innerHTML = table;   
            }
        });
    }
    function searchStudent(){
        if(document.getElementById('studentSearch').value==""){
            alert("Please enter a student name");
            return;
        }
        if(document.getElementsByTagName('select')[0].value=="anySeminar"){
            alert("Please select a cohort");
            return;
        }
        if(document.getElementsByTagName('select')[1].value=="noYearSelecter"){
            alert("Please select a year");
            return;
        }
        if(document.getElementsByTagName('select')[2].value=="noSemester"){
            alert("Please select a semester");
            return;
        }
        $.ajax({
            type:"POST",
            url:"DisplayDataStudent.php",
            data: {name: document.getElementById('studentSearch').value,
                   cohort: document.getElementsByTagName('select')[0].value,
                   year: document.getElementsByTagName('select')[1].value,
                   semester: document.getElementsByTagName('select')[2].value},
            cache:false,
            success:function(html){
                document.getElementById('tableout1').innerHTML = html;
            }
        });
    }
    function searchMarker(){
        if(document.getElementById('markerSearch').value==""){
            alert("Please enter a marker name");
            return;
        }
        if(document.getElementsByTagName('select')[3].value=="anySeminar"){
            alert("Please select a cohort");
            return;
        }
        if(document.getElementsByTagName('select')[4].value=="noYearSelecter"){
            alert("Please select a year");
            return;
        }
        if(document.getElementsByTagName('select')[5].value=="noSemester"){
            alert("Please select a semester");
            return;
        }
        $.ajax({
            type:"POST",
            url:"DisplayDataMarker.php",
            data: {name: document.getElementById('markerSearch').value,
                   cohort: document.getElementsByTagName('select')[3].value,
                   year: document.getElementsByTagName('select')[4].value,
                   semester: document.getElementsByTagName('select')[5].value},
            cache:false,
            success:function(html){
                document.getElementById('tableoutput2').innerHTML = html;
            }
        });
    }
    </script>
</head>
<body onload="getYears()">
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
            <li><a href="addRemove.php">Add/Remove</a></li>
            <li class="active"><a href="displayData.php"> Display Data </a></li>
            <li><a href="Stats.php"> Cohort Data </a></li>
            <li><a href="logout.php"> Logout </a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div id="navigation">
    <div class="container starter-template">
        <div class="jumbotron text-center ">
              <h1><span class="fa fa-lock"></span> Display Data </h1>
              <a href="#page1" class="btn btn-default"><span class="fa fa-user"></span>Students</a>
              <a href="#page2" class="btn btn-default"><span class="fa fa-user"></span>Markers</a>
</div>
<div id="page1" class="content">
            <h2> Students' Statistics </h2>
            <div class="col-md-12">
<input type="text" id="studentSearch" placeholder="Enter Student's Name" onkeyup="studentData()">
                <select class="stylish-select" id="selectCohort"  form="studentSearch">
                    <option value="anySeminar" selected>Select Cohort</option>
                    <option value="proposal">Proposal Seminar</option>
                    <option value="final">Final Seminar</option>
                </select>
                <select class="stylish-select" id="yearList"  form="studentSearch" >
                </select>
                <select class="stylish-select" id="sem1"  form="studentSearch" >
                    <option value="noSemester" selected>Select Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
                <input type="button" class="saveAss" style="margin-top: 0.1cm;" name="saveTable" value="Search" onClick="searchStudent()" >
            </div>
<br/>
<br/>
           <div id="tableout1"></div> 
</div>

<div id="page2" class="content">
            <h2> Markers' Statistics </h2>
            <div class="col-md-12">
<input type="text" id="markerSearch" placeholder="Enter Marker's Name">
                <select class="stylish-select" id="selectCohort"  form="studentSearch">
                    <option value="anySeminar" selected>Select Cohort</option>
                    <option value="proposal">Proposal Seminar</option>
                    <option value="final">Final Seminar</option>
                </select>
                <select class="stylish-select" id="yearList2"  form="studentSearch" >
                </select>
                <select class="stylish-select" id="sem2"  form="studentSearch" >
                    <option value="noSemester" selected>Select Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
                <input type="button" class="saveAss" style="margin-top: 0.1cm;" name="saveTable" value="Search" onClick="searchMarker()" >
            </div>
<br/>
<br/>
        <div id='tableoutput2'></div>    
</div>
</body>
</html>
