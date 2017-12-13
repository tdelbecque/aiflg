<?php  // Hi emacs ! -*- mode: c; -*- 

require_once 'utils.php';
require_once 'users.php';
require_once 'structures.php';

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

      case 'allstructures':
	header('Content-type:application/json;charset=utf-8');
	echo getAllStructuresJSON ($uid);
	break;
	
      case 'uniqueid':
	header('Content-type:application/json;charset=utf-8');
	echo json_encode (array ('value' => AIFLG_createUniqueId ()));
	break;
	
      case 'newusers':
	header ('Content-type:application/json;charset=utf-8');
	echo newUser ($_POST);
	break;

      case 'addusers':
	header ('Content-type:application/json;charset=utf-8');
	echo addUser ($_POST);
	break;
	
      default:
	header('Content-type:application/json;charset=utf-8');
	echo json_encode ($_GET['query']);
      }
  } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-type:application/json;charset=utf-8');
    if (is_null ($_POST['query']))
      echo json_encode (['error' => "no query"]);
    else {
      if (strpos ($_SERVER ['HTTP_ACCEPT'], 'application/json') === FALSE) {
	echo json_encode (['error' => "must accept application/json MIME type"]);
      } else
	switch ($_POST['query']) {
	case 'allusers':
	  echo getAllUsersJSON ($uid);
	  break;
	  
	case 'uniqueid':
	  echo json_encode (array ('value' => AIFLG_createUniqueId ()));
	  break;
	  
	case 'newusers':
	  echo newUser ($_POST);
	  break;
	  
	case 'updateusers':
	  echo updateUser ($_POST);
	  break;
	  
	case 'addusers':
	  echo addUser ($_POST);
	  break;

	case 'deleteusers':
	  echo deleteUser ($_POST);
	  break;
	  
	case 'allstructures':
	  echo getAllStructuresJSON ($uid);
	  break;

	default:
	  error_log ("undefined query : ${_POST['query']}");
	  echo json_encode (['error' => "undefined query"]);
	  break;
	}
    }
  } else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
  }
 }

?>
