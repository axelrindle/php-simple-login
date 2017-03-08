<?php namespace axelrindle\SimpleLogin;

  use \Simplon\Mysql\Mysql;

  session_start();

  class SimpleLogin {

    private static $dbConn;
    private static $table = "Users";

    public static function connect($host, $user, $password, $database = "MyWebApp", $table = "Users")
    {
      self::$dbConn = new Mysql(
        $host,
        $user,
        $password,
        $database
      );
      self::$table = $table;
    }

    public static function login($user, $pass) : bool
    {
      $result = self::$dbConn->fetchRow(
        'SELECT * FROM `' . self::$table . '` WHERE user = \'' . $user . '\''
      );

      $remotePass = $result['password'];
      if(password_verify($pass, $remotePass))
      {
        session_destroy();
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['loggedin'] = true;
        return true;
      }
      return false;
    }

    public static function isLoggedIn() : bool
    {
      return $_SESSION['loggedin'] || false;
    }

    public static function user() : string
    {
        return $_SESSION['user'];
    }

    public static function logout()
    {
      self::$dbConn = null;
      $_SESSION['user'] = null;
      $_SESSION['loggedin'] = false;
      session_destroy();
    }

  }
