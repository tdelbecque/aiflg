<?php  // Hi emacs ! -*- mode: c; -*- 
require_once ('dbaccess.php');

$AIFLG_AUTHCOOKIE_KEY = "authkey";
$AIFLG_ROLE_ADMIN0 = "ADMIN/0";

$AIFLG_ROLES = [
		['value' => 'ADMIN/0', 'label' => 'Administrateur principal'],
		['value' => 'ADMIN/1', 'label' => 'Administrateur'],
		['value' => 'OP/0',    'label' => 'Opérateur principal'],
		['value' => 'OP/1',    'label' => 'Opérateur']];

function AIFLG_encrypt ($s) {
  return md5($s);
}

function AIFLG_createUID () {
  return uniqid ("UID_");
}

function AIFLG_createAuthCookieValue () {
  return uniqid ("AUTHCOOKIE_");
}

function AIFLG_getUIDForKey ($key) {
  global $AIFLG_KEYS_TABLE;
  
  $ekey = AIFLG_encrypt ($key); 
  $query = "select * from $AIFLG_KEYS_TABLE where encrypted_key = '$ekey'";
  $rs = AIFLG_queryAtMostUnique ($query);
  if ($rs -> rowCount () === 0) 
    $ret = NULL;
  else {
    $x = $rs -> fetch ();
    $ret = $x ['uid'];
  }
  $rs -> closeCursor ();
  return $ret;
}

function AIFLG_getKeyForUID ($uid) {
  global $AIFLG_KEYS_TABLE;
  $query = "select * from $AIFLG_KEYS_TABLE where uid = '$uid'";
  $rs = AIFLG_queryUnique ($query);
  $x = $rs -> fetch ();
  $ret =  $x ['_key'];
  $rs -> closeCursor ();
  return $ret;	 

}

function AIFLG_updateUserKey ($uid, $key) {
  global $AIFLG_KEYS_TABLE;
  
  $ekey = AIFLG_encrypt ($key);
  $query = "update $AIFLG_KEYS_TABLE set _key='$key', encrypted_key='$ekey' where uid='$uid'";
  AIFLG_execute ($query);
}

function AIFLG_getRoleForUID ($uid) {
  global $AIFLG_ROLES_TABLE;
  
  $query = "select * from $AIFLG_ROLES_TABLE where uid = '$uid'";
  $rs = AIFLG_queryAtMostUnique ($query);
  if ($rs -> rowCount () === 0)
    $ret = NULL;
  else {
    $x = $rs -> fetch ();
    $ret =  $x ['_role'];
  }
  $rs -> closeCursor ();
  return $ret;	 
}

function AIFLG_getUIDForAuthCookie ($cookie) {
  global $AIFLG_AUTHCOOKIES_TABLE;

  $ecookie = AIFLG_encrypt ($cookie);
  $query = "select * from $AIFLG_AUTHCOOKIES_TABLE where encrypted_cookie = '$ecookie'";
  $rs = AIFLG_queryAtMostUnique ($query);
  if ($rs -> rowCount () == 0)
    $ret = NULL;
  else {
    $x = $rs -> fetch ();
    $ret = $x ['uid'];
  }
  $rs -> closeCursor ();
  return $ret;	 	 	 
}

function AIFLG_dropAuthCookieForUID ($uid) {
  global $AIFLG_AUTHCOOKIES_TABLE;

  $query = "delete from $AIFLG_AUTHCOOKIES_TABLE where uid='$uid'";
  AIFLG_execute ($query);
}

function AIFLG_newAuthCookieForUID ($uid) {
  global $AIFLG_AUTHCOOKIES_TABLE;
  AIFLG_dropAuthCookieForUID ($uid);
  $cookie = AIFLG_createAuthCookieValue ();
  $ecookie = AIFLG_encrypt ($cookie);
  $query = "insert into $AIFLG_AUTHCOOKIES_TABLE values ('$uid', '$cookie', '$ecookie')";
  AIFLG_execute ($query);
  return  $cookie;
}

function checkCookie () {
  global $AIFLG_AUTHCOOKIE_KEY;
  return is_null ($_COOKIE[$AIFLG_AUTHCOOKIE_KEY]) ? NULL : AIFLG_getUIDForAuthCookie ($_COOKIE[$AIFLG_AUTHCOOKIE_KEY]);
}

?>
