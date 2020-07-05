<?php
include("config.php");
   session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['winner'])) {
      $winner = $_POST['winner'];
      $gameID = $_POST['w_game_id'];

      $sql = "UPDATE Games SET winner = '$winner' WHERE gameID = '$gameID'";

      mysqli_query($link,$sql);
    }

    $username = "empty";
  if(isset($_POST['submit'])) {
    if($_POST['submit'] == 'Login'){
    if( isset($_POST['username'])) {
      $username = $_POST['username'] ?? '';
      ?>
      <script> var player_name = '<?php echo $username; ?>'</script>
      <?
      $password = $_POST['password'] ?? '';

      $sql = "SELECT username FROM Users WHERE username = '$username' AND password = '$password'";

      $result = mysqli_query($link, $sql);

      if(mysqli_num_rows($result) == 1) {
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $username;
      } else {
        print '<script>alert("User does not exist...");</script>';
      }
    } else {
      print '<script>alert("Enter username and password...");</script>';

    }
    } else if ($_POST['submit'] == 'Register') {

      if( isset($_POST['username'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT username FROM  Users WHERE username = '$username'";

        if($result = mysqli_query($link,$sql)) {
          if(!mysqli_num_rows($result)) {
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $username;

            $sql = ("INSERT INTO Users(username, password) VALUES ('$username', '$password')");

            $result = mysqli_query($link, $sql);
            ?>
            <script> var player_name = '<?php echo $username; ?>'</script>
            <?
            print '<script>alert("Successfully registered!");</script>';

          } else {
            print '<script>alert("Username is taken...");</script>';

          }
        }

      } else {
        print '<script>alert("Enter username and password...");</script>';
      }


    } else if ($_POST['submit'] == 'Guest') {
      $_SESSION['logged_in'] = true;

      if(isset($_POST['gameid'])) {
        //
      }
    }

  }

  if ( isset($_POST['coord'])) {

    $gameID = $_POST['gameID'];
    $coord = $_POST['coord'];
    $player = $_POST['player'];
    // echo "gameID: " . $gameID . "\nCoord: " . $coord . "\nPlayer: " . $player;

    $sql = ("INSERT INTO Moves(gameID, coordinate, playerID) VALUES ('$gameID', '$coord', '$player')");

    mysqli_query($link,$sql);

  } else if ( isset($_POST['restart'])) {

    $gameID = $_POST['restart'];
    $sql = ("DELETE FROM Moves WHERE gameID = '$gameID'");
    mysqli_query($link,$sql);
      }


      if(isset($_POST['newgame'])) {
        $gameID = $_POST['newgame'];
        $name = $_POST['playername'];
          $sql = ("INSERT INTO Games(gameID, userID) VALUES ('$gameID', '$name')");
        mysqli_query($link,$sql);
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

    <div class = "login-section" id = "login-section1">
      <form action ="game.php" method = "post" id = "login-form">

        <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
        <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />

        <input type = "submit" name = "submit" value = "Login"/>
        <button onclick="getRegisterForm()" type = "button">Register</button>
        <button onclick="getGuestForm()" type = "button">Guest</button>
      </form>

      <form action ="game.php" method = "post" id = "register-form" >

        <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
        <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
          <label>Re-enter Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />

        <button onclick="getLoginForm()" type = "button">Login</button>
        <input type = "submit" name = "submit" value = "Register"/>
        <button onclick="getGuestForm()" type = "button">Guest</button>
      </form>

      <form action ="game.php" method = "post" id = "guest-form" >

        <label>Enter GameID:</label><input type = "text" name = "gameid" class = "box"/><br /><br />


        <button onclick="getLoginForm()" type = "button">Login</button>
        <button onclick="getRegisterForm()" type = "button">Register</button>
        <input type = "submit" name = "submit"  value = "Guest"/>
      </form>

    </div>

    <div class = "pregame" id = "pre-game">

      <form action ="game.php" method = "post" id = "pregame-form" >

        <label>Enter GameID:</label><input type = "text" name = "gameid" class = "box"/><br /><br />
        <input type = "submit" name = "submit"  value = "Start"/>
      </form>

    </div>

     <div class = "connect4-section" id = "connect4">
       <div class = "red-heading">
         <h2> CONNECT 4 </h2>
       </div>

       <div style = "border-style: solid;
       border-color: green;" class = "container">
         <div class = "row">

           <div class = "col-md-2 left-pane">
             <p> TURN: </p>
             <p id = "user_turn">
               <?php
               if(isset($_SESSION['username'])){
                 echo $_SESSION['username'];
               } else {
                 echo "BLUE PLAYER";
               }
               ?>
             </p>
             <p> Game Unique ID: <br></p>
             <p id = "game-id"></p>
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
           <p style = "color: white;" id="response"></p>
           <?php
              if(isset($_SESSION['username'])) {

            ?>
           <a href="logout.php" class="btn btn-outline-primary">Logout</a>
           <?
         } else {
           ?>
           <a href="logout.php" class="btn btn-outline-primary">Exit</a>
           <?
         }
         ?>
         </div>


     </div>
   </div>
     </div>

     <?php

     if($_SESSION['logged_in'] && $_SESSION['logged_in'] != '') {

       ?>
       <script type = "text/javascript">
       document.getElementById("login-section1").style.display="none";
       document.getElementById("connect4").style.display="block";
       </script>
       <?
     }
       ?>

<!-- jquery cdn -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- jquery poppers cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<!-- bootstrap min js -->
<script src="js/bootstrap.min.js"></script>
  </body>
</html>
