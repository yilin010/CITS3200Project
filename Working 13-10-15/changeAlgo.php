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

 $(document).ready(function () {
            //iterate through each textboxes and add keyup
            //handler to trigger sum event
$(".removeass").on("click", calculateSum);

$("#weightTable").on("keyup", ".txt", function () {
    calculateSum();
        });



function calculateSum() {
    var sum = 0;
    //iterate through each textboxes and add the values
    $(".txt").each(function () {

        //add only if the value is number
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value);
        }
            $("#sum").html(sum.toFixed(2));


        if (sum > 100){

 alert("Assessment weightings cannot sum to more than 100%");
     $("#sum").html('Error');

           return;
        }

    });
    //.toFixed() method will roundoff the final sum to 2 decimal places
}

})



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
  function addAssessment(){

       var table = document.getElementById('weightTable'),
       tbody = table.getElementsByTagName('tbody')[0],
       clone = tbody.rows[0].cloneNode(true);
       if(table.rows.length == 12){
           alert("Can only have up to 10 Assessments");
           return;
       }
       var new_row = updateRow(clone.cloneNode(true), ++tbody.rows.length, true);
    $('#weightTable tr:last').before(new_row);

  }


  function removeAssessment(){
      var tableInfo = document.getElementById('weightTable').rows;


      for(var i=tableInfo.length-2;i>0;i--){
          console.log("looking at row "+i);

          if(tableInfo[i].getElementsByTagName('td')[0].getElementsByTagName('input')[0].checked == true){

                  if(tableInfo.length == 3){
          alert("Must have at least one assessment.");
          return;
      }
              console.log("row "+i +" is going to be deleted");
              document.getElementById('weightTable').deleteRow(i);
          }
      }

  }

  function save(){
  	var tableInfo = document.getElementById('weightTable').rows;
    var cohort = document.getElementsByTagName('select')[0].value;
  	var assessmentArray = [];
  	var weightageArray =[];
  	var x = document.getElementsByClassName("txt");
  	var y = document.getElementsByClassName("assessName");
  	var saveSum = parseFloat(document.getElementById('sum').innerText).toFixed(2);
  	
  	if (saveSum !== parseFloat(100).toFixed(2)){

  		alert("Unable to save assessment. Total weightings not 100%");
  		return;
  	}
    
    if (cohort == "anySeminar") {
        alert("Please select a cohort.");
        return;
    }

  	for(var j=0;j<10;j++){

  		weightageArray[j] = 0;

  	}

  	for(var i=0;i<tableInfo.length-2;i++){


	         if(x.length > 0 && tableInfo[i+1].cells[2].children[0].value >0){ //check if has a value


	         	weightageArray[i] = tableInfo[i+1].cells[2].children[0].value;
	         } else {

	         }

	        }

	        for(var j=0;j<10;j++){

	        	assessmentArray[j] = "null";

	        }

	        for(var i=0;i<tableInfo.length-2;i++){

	        if(y.length > 0 && tableInfo[i+1].cells[2].children[0].value >0){ //check if has a value

	        	assessmentArray[i] = tableInfo[i+1].cells[1].children[0].value;

	        }else {

	        }

	       }

	       var mark1 = weightageArray[0];
	       var mark2 = weightageArray[1];
	       var mark3 = weightageArray[2];
	       var mark4 = weightageArray[3];
	       var mark5 = weightageArray[4];
	       var mark6 = weightageArray[5];
	       var mark7 = weightageArray[6];
	       var mark8 = weightageArray[7];
	       var mark9 = weightageArray[8];
	       var mark10 = weightageArray[9];


	       var name1 = assessmentArray[0];
	       var name2 = assessmentArray[1];
	       var name3 = assessmentArray[2];
	       var name4 = assessmentArray[3];
	       var name5 = assessmentArray[4];
	       var name6 = assessmentArray[5];
	       var name7 = assessmentArray[6];
	       var name8 = assessmentArray[7];
	       var name9 = assessmentArray[8];
	       var name10 = assessmentArray[9];

									//if name is empty, null, if weight is empty 0
									$.ajax({
										type:"POST",
										url: "modifyAlgorithm.php",
										data: {cohort:cohort, mark1:weightageArray[0], mark2:weightageArray[1], mark3:weightageArray[2], mark4:weightageArray[3], mark5:weightageArray[4], mark6:weightageArray[5], mark7:weightageArray[6], mark8:weightageArray[7], mark9:weightageArray[8], mark10:weightageArray[9], mark1name:assessmentArray[0], mark2name:assessmentArray[1],  mark3name:assessmentArray[2],  mark4name:assessmentArray[3],  mark5name:assessmentArray[4],  mark6name:assessmentArray[5], mark7name:assessmentArray[6], mark8name:assessmentArray[7],mark9name:assessmentArray[8], mark10name:assessmentArray[9]},
										cache: false,
										success: function(html){
											alert('Successfully changed assessments weightings');
                                            
										}
									})

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
            <li class="active"><a href="changeAlgo.php">Change Algorithm</a></li>
            <li><a href="addRemove.php">Add/Remove</a></li>
            <li><a href="displayData.php"> Display Data </a></li>
            <li><a href="Stats.php"> Cohort Data </a></li>
            <li><a href="logout.php"> Logout </a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<div class="container">
          <div class="starter-template">
            <h1>Change Algorithm</h1>
            <!-- <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p> -->
          </div>

          <div class="row">
              <div class="col-md-10">
Select cohort to change assessment components
                <select class="stylish-select" id="selectCohort"  form="studentSearch">
                    <option value="anySeminar" selected>Select Cohort</option>
                    <option value="proposal">Proposal Seminar</option>
                    <option value="final">Final Seminar</option>
                </select>
            </div>
        </div>
                <div id="outputDiv"></div>
        <div class="col-md-10">

        <table id="weightTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center;vertical-align: middle">Selected</th>
                        <th style="text-align: center">Assessment Name</th>
                        <th style="text-align: center">Weighting</th>
                    </tr>
                </thead>

            <tr>
                                <td style="text-align: center;vertical-align: middle;width: 78px"><input type="checkbox" name="selected"></td>
                                                <td style="width: 78px"><input type="text" class="assessName" style="border: 0px solid;text-align: center" name="marker" placeholder="Enter Assessment Name"></td>
                <td style="width: 78px"><input type="text" style="border: 0px solid;text-align: center" class="txt" name="weightage22" placeholder="Enter Weighting in %"></td>

            </tr>
    <tr>
        <td></td>
        <td>Total Weightings</td>
        <td><span id="sum"></span></td>
    </tr>
        </table>

    </div>

    </div>

            <div class="row">
            <div class="col-md-3" style="margin-left:15px;">
                <input type="button" class="addass" style="margin-top: 0.5cm;" name="addAssessment" value="+ Add an assessment" onClick="addAssessment()" >
            </div>

            <div class="col-md-3"  style="margin-left:15px;">
                <input type="button" class="removeass" style="margin-top: 0.5cm;" name="removeAssessment" value="- Remove selected assessments" onClick="removeAssessment()" >
            </div>

            <div class="col-md-3" style="margin-left:15px;">
                <input type="button" class="saveAss" style="margin-top: 0.5cm;" name="saveTable" value="Save" onClick="save()" >
            </div>
        </div>
    </div><!-- /.container -->

    </body>
        </html>

