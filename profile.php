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
    <!-- Custom styles for this template -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

  <script type="text/javascript">

function updateRow(row, i, reset) {
    // row.cells[0].innerHTML = i;
    //TODO gotta sort this out.
    var inp1 = row.cells[1].getElementsByTagName('input')[0];
    var inp2 = row.cells[2].getElementsByTagName('input')[0];
    inp1.id = 'latbox' + i;
    inp2.id = 'lngbox' + i;

    if (reset) {
        inp1.value = inp2.value = '';
    }
    return row;
}
  function addMarker(){

       var table = document.getElementById('markerTable'),
       tbody = table.getElementsByTagName('tbody')[0],
       clone = tbody.rows[0].cloneNode(true);
       if(table.rows.length == 13){
           alert("Can only have up to 12 markers");
           return;
       }
       var new_row = updateRow(clone.cloneNode(true), ++tbody.rows.length, true);
       tbody.appendChild(new_row)
  }
  function generateMarkers(){

  }
  function removeMarker(){
      var tableInfo = document.getElementById('markerTable').rows;
      if(tableInfo.length == 2){
          alert("Must have at least one marker");
          return;
      }

      for(var i=tableInfo.length-1;i>0;i--){
          console.log("looking at row "+i);
          if(tableInfo[i].getElementsByTagName('td')[0].getElementsByTagName('input')[0].checked == true){
              console.log("row "+i +" is going to be deleted");
              document.getElementById('markerTable').deleteRow(i);
          }
      }

  }
  function keyPressSearch(){
      var studentName = document.getElementsByName('studentName')[0].value;
      var dataString = 'name1='+studentName;
    //   console.log(dataString);
      $.ajax({
            type: "POST",
            url: "manualAdd.php",
            data: dataString,
            cache: false,
            success: function(html)
            {
                if (html.length > 0){
                    var data = html.split(',');
                    console.log(data);
                    var subTable = "<div class=\"col-md-10\">";
                    subTable +="<table class = \"table table-bordered\"style=\"width:500px\">";
                    subTable +="<thead><tr>";
                    subTable +="<th class=\"tableHead\">Student Number</th>";
                    subTable +="<th class=\"tableHead\" nowrap>Student Name</th>";
                    subTable +="<th class=\"tableHead\" nowrap>Cohort</th>";
                    subTable +="</tr></thead>";
                    subTable +="<tbody><tr>";
                    for(var j=0;j<(data.length/3-1);j++){
                        for(var i=0;i<3;i++){
                            subTable += "<td class\"classRow\"><a href=\"generateMarkers()\">"+data[i]+"</a></td>";
                        }
                    }

                    subTable +="</tr></tbody></table></div>";
                    document.getElementById("outputDiv").innerHTML = subTable;
                }
            }
    });

    return false;
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
            <li class="active"><a href="profile.html">Home</a></li>
            <li><a href="#dataEntry">Enter Marks</a></li>
            <li><a href="#contact">Change Algorithm</a></li>
            <li><a href="addRemove.html">Add/Remove</a></li>
            <li><a href="#export"> Export Data </a></li>
            <li><a href="logout.php"> Logout </a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

          <div class="starter-template">
            <h1>Honours/Masters Seminar Marking Database</h1>
            <!-- <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p> -->
          </div>
          <div class="row">
              <div class="col-md-2">
                  <form method="POST" action="manualAdd.php" id="studentSearch">
                      <input class="stylish-input"type="text" name="studentName" onkeyup="keyPressSearch()" placeholder="Enter A Student Name" style="width: 161px;margin-left: 15px;">
                      <!-- <input type="radio" name="proposalSeminar" value="proposal"> Proposal Seminar
                      <input type="radio" name="finalSeminar" value="final"> Final Seminar -->
                 </form>
             </div>
            <div class="col-md-6">
                <select class="stylish-select" id="selectCohort"  form="studentSearch">
                    <option value="anySeminar" selected>Any Seminar</option>
                    <option value="proposalSeminar">Proposal Seminar</option>
                    <option value="finalSeminar">Final Seminar</option>
                </select>
            </div>
        </div>
        <div id="outputDiv"></div>
        <div class="col-md-10">
            <table class = "table table-bordered" id="markerTable" style="margin-top: 30px">
                <thead>
                    <tr>
                        <th style="text-align: center;vertical-align: middle">Selected</th>
                        <th style="text-align: center">Marker Name</th>
                        <th colspan="2" style="text-align: center">Delivery Mark</th>
                        <th style="text-align: center">Content Mark</th>
                        <th style="text-align: center">Final Mark</th>
                    </tr>
                </thead>
                <td style="text-align: center;vertical-align: middle;width: 78px"><input type="checkbox" name="selected"></td>
                <td style="width: 78px"><input type="text" style="border: 0px solid;text-align: center" name="marker" placeholder="Enter A Marker Name"></td>
                <td style="width: 78px"><input type="text" style="border: 0px solid;text-align: center" name="oral" placeholder="Oral Mark"></td>
                <td style="width: 78px"><input type="text" style="border: 0px solid;text-align: center" name="slides" placeholder="Slide Mark"></td>
                <td style="width: 78px"><input type="text" style="border: 0px solid;text-align: center" name="content" placeholder="Content Mark"></td>
                <td style="width: 78px"><input type="text" style="border: 0px solid;text-align: center" name="final" placeholder="Final Mark"></td>
            </table>

        </div>
        <div class="row">
            <div class="col-md-3" style="margin-left:15px;">
                <input type="button" class="btnExample" style="margin-top: 0.5cm;" name="addMarker" value="+ Add a Marker" onClick="addMarker()" >
            </div>
            <div class="col-md-3"  style="margin-left:15px;">
                <input type="button" class="btnExample" style="margin-top: 0.5cm;" name="removeMarker" value="- Remove Selected Markers" onClick="removeMarker()" >
            </div>
            <div class="col-md-3" style="margin-left:15px;">
                <input type="button" class="btnExample" style="margin-top: 0.5cm;" name="saveTable" value="Save" onClick="save()" >
            </div>
        </div>
    </div><!-- /.container -->

</body></html>