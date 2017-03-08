# php-simple-login
Simple login system with PHP and MySQL.

## Requirements
* php >= 7.1
* [simplon/mysql package](https://packagist.org/packages/simplon/mysql) (composer dependency)
* jquery >= 1.9 *(recommended)*
* A MySQL database

## Installing and using
First, install the **PHP backend** with [Composer](https://getcomposer.org/)
```
composer require axelrindle/simple-login
```

Then, you will need to create several more files:
* Two **JavaScript** files for handling **login** and **logout** forms and/or buttons on the **frontend**.
* One **PHP** file for accessing the **SimpleLogin backend**.

### JavaScript files
#### login.js
This script is used to handle the **login form** with a **user** and a **password** field. The user can be just a username or an email.

```javascript
$(document).ready(function () {
  $("#loginform").submit(function () { // when the user clicks the submit button in the form

    var user = $("#login__username").val(); // login form username field
    var pass = $("#login__password").val(); // login form password field

    // no check for empty fields because this can be done in HTML by adding 'required' attribute to an input element

    // request login
    $.post("login.php", { mode: "login", user: user, password: pass }, function (data) {
      if (data === "success") {
        window.location.href = "/";
      } else {
        alert(data); // should be removed. just for debugging
      }
    });

    return false; // return false to prevent the page from reloading
  });
});

```
#### logout.js
This script is used to logout the user, for example if he clicks the **Sign out** button.

```javascript
$(document).ready(function () {
  $("#logout").click(function () { // when the user clicks the logout button

    // request logout
    $.post("auth.php", { mode: "logout" }, function (data) {
      if (data === "success") {
        window.location.href = "/";
      } else {
        alert(data); // should be removed. just for debugging
      }
    });

    return false; // return false to prevent the page from reloading
  });
});
```

### PHP files
### auth.php
This file is used to access the **SimpleLogin backend**. It receives a POST request from one of the **JavaScript** files and executes the supplied action (login or logout).

```php
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

  require 'vendor/autoload.php';
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
```

### Integrate the system
Now, that you've set up your backend files, you are ready to integrate them into your HTML code.

First, you should create a file called **login.php** in your document root. It will be the file showing the login form. It could look like this:
```html
<?php
  require 'vendor/autoload.php';

  use \axelrindle\SimpleLogin\SimpleLogin;

  session_start();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'auth.php';
  }

  // redirect to index.php if already logged in
  if(SimpleLogin::isLoggedIn())
  {
    header("Location: index.php");
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
    <title>Login</title>

    <!-- CSS files -->
    <link href="css/login.css" rel="stylesheet">
  </head>
  <body class="align">
    <div class="grid">
      <form id="loginform" class="form login" onsubmit="return false;">

        <div class="form__field">
          <label for="login__username"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#user"></use></svg><span class="hidden">Username</span></label>
          <input id="login__username" type="text" name="username" class="form__input" placeholder="Username" required>
        </div>

        <div class="form__field">
          <label for="login__password"><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#lock"></use></svg><span class="hidden">Password</span></label>
          <input id="login__password" type="password" name="password" class="form__input" placeholder="Password" required>
        </div>

        <div class="form__field">
          <input type="submit" value="Sign In">
        </div>
      </form>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" class="icons">
      <symbol id="arrow-right" viewBox="0 0 1792 1792">
        <path d="M1600 960q0 54-37 91l-651 651q-39 37-91 37-51 0-90-37l-75-75q-38-38-38-91t38-91l293-293H245q-52 0-84.5-37.5T128 1024V896q0-53 32.5-90.5T245 768h704L656 474q-38-36-38-90t38-90l75-75q38-38 90-38 53 0 91 38l651 651q37 35 37 90z"/>
      </symbol>
      <symbol id="lock" viewBox="0 0 1792 1792">
        <path d="M640 768h512V576q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28H416q-40 0-68-28t-28-68V864q0-40 28-68t68-28h32V576q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z"/>
      </symbol>
      <symbol id="user" viewBox="0 0 1792 1792">
        <path d="M1600 1405q0 120-73 189.5t-194 69.5H459q-121 0-194-69.5T192 1405q0-53 3.5-103.5t14-109T236 1084t43-97.5 62-81 85.5-53.5T538 832q9 0 42 21.5t74.5 48 108 48T896 971t133.5-21.5 108-48 74.5-48 42-21.5q61 0 111.5 20t85.5 53.5 62 81 43 97.5 26.5 108.5 14 109 3.5 103.5zm-320-893q0 159-112.5 271.5T896 896 624.5 783.5 512 512t112.5-271.5T896 128t271.5 112.5T1280 512z"/>
      </symbol>
    </svg>

    <!-- JS files -->
    <script src="js/jquery-1.9.0.min.js"></script>
    <script src="js/login.js"></script>
  </body>
</html>
```
Note that you should not change the PHP snippet at the very top of the file.

### Prepare the MySQL database
Now it's time to set up the MySQL database. To do so, connect to the server using your favorite client, and execute the following statement on a database of your choice:

```sql
CREATE TABLE `Users` ( `user` TEXT NOT NULL COMMENT 'The user''s identification, e.g. a username or an email.' , `password` TEXT NOT NULL COMMENT 'The PHP password hash.' , UNIQUE (`user`(32))) ENGINE = InnoDB;
```
This will create a **new table** named **Users**. It has **two columns**, named **user** and **password**. The **user column** will contain the **user's identification**, e.g. a username or an email. The **password column** will contain the **PHP password hash** to verify the password typed in the **login form**.

### Finish up
Add this little snippet to the very top of your main **index.php**:
```php
<?php
  require 'vendor/autoload.php';

  use \axelrindle\SimpleLogin\SimpleLogin;

  session_start();

  // redirect to login.php if not logged in
  if(!SimpleLogin::isLoggedIn())
  {
    header("Location: login.php");
    exit;
  }
?>
```
This snippet is the base for protecting a file with a login. Place it at the very top of every file which you wish to protect.

----
Just to make sure, you should now have a **file structure** similar to this one:
```
.
├── auth.php // auth.php you created before
├── css // some css
│   ├── login.css
│   └── style.css
├── index.php // applications main entry point
├── js // your javascript files (and jquery)
│   ├── jquery-1.9.0.min.js
│   ├── login.js // login.js you created before
│   └── logout.js // logout.js you created before
├── login.php // login.php you created before
└── vendor // composer files
    └── (...)
```
