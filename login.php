<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
        <script>
            function authenticate(){
                var username = document.getElementsByName("username")[0].value;
                var password = document.getElementsByName("password")[0].value;
                console.log("HELO");
                $.ajax({
                    type:"POST",
                    url:"password.php",
                    data:{user:username,pass:password},
                    cache:false,
                    success: function(html){
                        console.log(html);
                        if(html == "verified"){
                            // return 1;
                            window.location.href = "profile.php";
                            alert("congrats on signing up ");


                        }
                        else {
                            alert("badpass");
                        }
                    }
                })
            }
        </script>
    </head>
    <body>

    <div class="container">

          <div class="starter-template">
            <!-- <h1>Honours/Masters Seminar Marking Database</h1> -->
            <!-- <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p> -->
          </div>
          <div class="col-sm-6 col-sm-offset-3">

              <h1><span class="fa fa-sign-in"></span> Database Login</h1>

          <form  method="POST" onsubmit="authenticate()">
              <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control" name="username">
              </div>
              <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password">
              </div>

              <button type="submit" class="btn btn-warning btn-lg">Login</button>
          </form>

          <hr>
    </div>
</body>

</html>
