<?php  // Hi emacs ! -*- mode: c; -*- 

require_once 'utils.php';

$uid = checkCookie ();
if (is_null ($uid)) { 
  if (strpos ($_SERVER ['HTTP_ACCEPT'], 'html') != FALSE) {
    header("Location: loginform.php");
  } else if (strpos ($_SERVER ['HTTP_ACCEPT'], 'json') != FALSE) {
    echo json_encode ("ERROR");
  } else
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
 } else {
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (is_null ($_GET['query']))
      header("Location: welcome.php");
    else
      switch ($_GET['query']) {
      case 'allusers':
	header('Content-type:application/json;charset=utf-8');
	echo getAllUsersJSON ($uid);
	break;
	
      default:
	header('Content-type:application/json;charset=utf-8');
	echo json_encode ($_GET['query']);
      }
  } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['query']) {
    case 'updateuser':
      header('Content-type:application/json;charset=utf-8');
      echo updateUser ($_POST);
    }
  } else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
  }
 }

?>
