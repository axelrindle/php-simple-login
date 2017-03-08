<?php
  require '../vendor/autoload.php';

  use \axelrindle\SimpleLogin\SimpleLogin;

  session_start();

  // redirect to login.php if not logged in
  if(!SimpleLogin::isLoggedIn())
  {
    header("Location: login.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta Tags for more compability. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">

    <!-- Favicon -->
    <link href="favicon.png" rel="icon">

    <!-- Site title -->
    <title>Secret Area</title>

    <!-- CSS files -->
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>

    <div class="box">
      <h1>Secret Area</h1>
      <h2>Logged in as <?php echo SimpleLogin::user(); ?></h2>
      <button id="logout">Logout</button>
    </div>

    <script src="js/jquery-1.9.0.min.js"></script>
    <script src="js/logout.js"></script>
  </body>
</html>
