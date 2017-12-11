<?php
  require_once 'utils.php';
  require_once 'pages.php';
  
  $uid = checkCookie ();
  error_log ("uid = $uid");
  if (is_null ($uid)) {
     header("Location: loginform.php");
  }
  else {
     pageForUID ($uid);
     }
?>

