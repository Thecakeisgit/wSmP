<?php
  session_start();

  /* If session is set, redirect to panel */
  if (isset($_SESSION['uname'])) {
    header("Location: ./");
    exit;
  }

  /* Include config file */
  $config = include_once("lib/config.php");

  /* Proceed to login check */
  if (!empty($_POST)) {
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];

    /* Check if user and password match to database */
    if(!empty($uname) && !empty($pass)) {

      try {
        $oDatabase = new PDO('mysql:host=' . $config['database']['host']
          . ';port=' . $config['database']['port']
          . ';dbname=' . $config['database']['dbname']
          . ';charset=utf8', $config['database']['user'], $config['database']['pass']);
      } catch(Exception $e) {
        echo "Database connection fail !";
        exit;
      }

      /* Hash the password and add salt to ensure it will be unredable in database */
      $pass = hash('sha512', $config['secret'] . $pass);

      /* Prepare & execute mysql request */
      $oReq = $oDatabase->prepare('SELECT * FROM users WHERE uname = :uname AND pass = :pass');
      $oReq->execute(array(
        'uname' => $uname,
        'pass' => $pass
      ));

      /* Fetch answer */
      $serverAnswer = $oReq->fetch();

      /* Process answer */
      if (!$serverAnswer) {
        $connectFail = true;
      } else {
        $_SESSION['uname'] = $uname; // Set the username
        $_SESSION['uflags'] = $serverAnswer['flags']; // Set the login flags
        $_SESSION['logtime'] = time(); // Set the login time for session expire

        header("Location: ./");
        exit;
      }
    } else {
      $emptyField = true;
    }
  }
 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>wSmP :: Login</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/imgs/logo-b.svg" sizes="96x96">

    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
  </head>
  <body>
    <div class="login-page">
      <a href="https://github.com/Thecakeisgit/wSmP" class="logo">
        <img src="assets/imgs/logo-w.svg" alt="logo" />
      </a>
      <form method="post" action="" class="login-form">
        <?php
          if (isset($emptyField)) {
            if (empty($uname)) {
              echo "Empty username.";
            }
            if (empty($pass)) {
              echo "Empty password.";
            }
          } else if (isset($connectFail)) {
            echo "Wrong login or password.";
          }
         ?>
        <input name="uname" type="text" placeholder="Username or Email address" />
        <input name="pass" type="password" placeholder="Password" />
        <button class="login-button">Login</button>
      </form>
    </div>
    <!-- Footer -->
    <footer>wSmP an Open Source project.</footer>
  </body>
</html>
