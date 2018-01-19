<!-- hey emacs -->
<?php
  require_once ('utils.php');
  $uid = AIFLG_getUIDForKey ($_GET['key']);

  if (! is_null ($uid)) {
     $cookie = AIFLG_newAuthCookieForUID ($uid);
     setcookie(AIFLG::AUTHCOOKIE_KEY, $cookie, time ()+3600*24*30, null, null, false, true);
     header("Location: welcome.php");
  } else {
     header ("Location: loginform.php?retry=1");
  }
?>
