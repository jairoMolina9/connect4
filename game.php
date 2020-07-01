<?php
include("config.php");
   session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $sql = "SELECT username FROM users WHERE username = '$username' AND password = '$password'";

  $result = mysqli_query($link, $sql);

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
    <meta name="description" content="This site provides an interactive connect 4 game, fully online with personal accounts and game-history tracking">
<meta name="keywords" content="connect4,355,queenscollege,nyc">
<meta name="author" content="Jairo Molina & Wei Ting">

<link rel="icon" href="img/favicon.png">
  </head>
  <!-- bootstrap css -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- custom css -->
    <link href="css/custom.css" rel="stylesheet">
  <body>


    <form action = "" method = "post" id = "loginform" >

      <label>UserName  :</label><input type = "text" name = "username" class = "box"/><br /><br />
      <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />

      <input type = "submit" value = " Submit "/><br />

    </form>

    <?php
    if($_SESSION['logged_in'] && $_SESSION['logged_in'] != '') {
      ?>
      <script type = "text/javascript"> document.getElementById("loginform").style.display="none";
      </script>
      <?
      echo "<div class = 'red-heading'>";
      echo "<h2> Welcome, ";
      echo $_SESSION['username'];
      echo "</h2>";
      echo "</div>";
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
session_destroy();
?>
