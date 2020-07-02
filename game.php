<?php
include("config.php");
   session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $sql = "SELECT username FROM users WHERE username = '$username' AND password = '$password'";

  $result = mysqli_query($link, $sql);

  console_log($result);

  if(mysqli_num_rows($result) == 1) {
    $_SESSION["logged_in"] = true;
    $_SESSION["username"] = $username;
  } else {
    print '<script>alert("User does not exist...");</script>';
  }
}

?>


<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Connect 4</title>

    <meta property="og:image" content="img/favicon.png">
    <meta name="description" content="tdis site provides an interactive connect 4 game, fully online witd personal accounts and game-history tracking">
<meta name="keywords" content="connect4,355,queenscollege,nyc">
<meta name="autdor" content="Jairo Molina & Wei Ting">

<link rel="icon" href="img/favicon.png"/>

<!-- bootstrap css -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- custom css -->
  <link  href="css/custom.css" rel="stylesheet">
<!-- custom js -->
  <script src="js/custom.js"></script>
  </head>
  <body>

    <form action = "" method = "post" id = "loginform" >

      <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
      <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />

      <input type = "submit" value = " Submit "/><br />

    </form>

     <div class = "connect4-section" id = "connect4">
       <div class = "red-heading">
         <h2> CONNECT 4 </h2>
       </div>

       <div style = "border-style: solid;
       border-color: green;" class = "container-fluid">
         <div class = "row">

           <div class = "col-md-2 left-pane">
             <p> TURN: </p>
             <p id = "user_turn">BLUE PLAYER</p>
           </div>

         <div class = "col-md-8 mid-section">
           <div class="table-responsive-lg">
        <table class="table" id = "tblMain">
          <tbody class = "table-body">

            <?php
              for($row = 0; $row < 6; $row++) {
                echo "<tr>";
                for($col = 0; $col < 7; $col++) {
                  echo "<td id = '" . $row .",".$col."' class = 'tab-col'>";
                  echo "</td>";
                }
                echo "</tr>";
              }
              echo "<script type = 'text/javascript'>startGame()</script>";
             ?>

            </tbody>
        </table>
</div>
         </div>

         <div class = "col-md-2 right-pane">
           <p id = "user_winner"></p>
           <button onclick="resetGame()" type="button" class="btn btn-outline-primary">RESTART</button>
         </div>

     </div>
   </div>
     </div>

     <?php
     if($_SESSION['logged_in'] && $_SESSION['logged_in'] != '') {

       ?>
       <script type = "text/javascript"> document.getElementById("loginform").style.display="none"; document.getElementById("connect4").style.display="block";
       </script>
       <?
     }
       ?>

<!-- jquery cdn -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <!-- jquery cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<!-- bootstrap min js -->
<script src="js/bootstrap.min.js"></script>
  </body>
</html>

<?php

?>
