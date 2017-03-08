<?php

  session_start();

  if(!isset($_POST['mode'])) {
    echo "No mode set!";
    exit;
  }
  if(strcasecmp($_POST['mode'], "login") !== 0 && strcasecmp($_POST['mode'], "logout") !== 0) {
    echo "No valid mode set!";
    exit;
  }

  require '../vendor/autoload.php';
  use \axelrindle\SimpleLogin\SimpleLogin;

  $mode = $_POST['mode'];

  if(strcasecmp($mode, "logout") == 0) { # logout requested
    SimpleLogin::logout();
    echo "success";
    exit;
  } elseif (strcasecmp($mode, "login") == 0) { # login requested

    if(!isset($_POST['user'])) {
      echo "No user set!";
      exit;
    }
    if(!isset($_POST['password'])) {
      echo "No password set!";
      exit;
    }

    $user = $_POST['user'];
    $pass = $_POST['password'];

    // connect to database
    SimpleLogin::connect("localhost", "root", "localrootpw", "development");
    if(SimpleLogin::login($user, $pass))
    {
      echo "success";
    }
    else
    {
      echo "Wrong credentials!";
    }
    exit;
  }
