<?php
  session_start();

  /* Include config file */
  $config = include_once("lib/config.php");

  /* If session isn't set or expired, remove session and redirect to login page */
  if (!isset($_SESSION['uname']) || time() - $_SESSION['logtime'] > $config['session_expiry']) {
    session_destroy();
    header("Location: login.php");
    exit;
  } else { /* Repport activity to session */
    $_SESSION['logtime'] = time();
  }
?>
