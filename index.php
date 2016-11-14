<?php
  /* Login security */
  require("lib/isLogged.php");

  if (isset($_GET['page'])) {
    /* Define base url based on flags */
    if ($_SESSION['uflags'] == '!') { // Root access
      $baseUrl = "panel/root/";
    } else {
      $baseUrl = "panel/user/";
    }

    if(file_exists("panel/" . $_GET['page'] . ".php"))
      include("panel/" . $_GET['page'] . ".php");

    if(file_exists($baseUrl . $_GET['page'] . ".php"))
      include($baseUrl . $_GET['page'] . ".php");
  } else {
    header("Location: ./?page=home");
    exit;
  }
 ?>
