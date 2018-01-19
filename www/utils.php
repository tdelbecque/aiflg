<?php  // Hi emacs ! -*- mode: c; -*-
require_once ('constants.php');
require_once ('dbaccess.php');

$AIFLG_AUTHCOOKIE_KEY = "authkey";
$AIFLG_ROLE_ADMIN0 = "ADMIN/0";
$AIFLG_ROLE_ADMIN1 = "ADMIN/1";
$AIFLG_ROLE_OP0 = "OP/0";
$AIFLG_ROLE_OP1 = "OP/1";

$AIFLG_ROLES = [
		['value' => $AIFLG_ROLE_ADMIN0, 'label' => 'Administrateur principal', 'level' => 0],
		['value' => $AIFLG_ROLE_ADMIN1, 'label' => 'Administrateur',           'level' => 1],
		['value' => $AIFLG_ROLE_OP0,    'label' => 'Opérateur principal',      'level' => 2],
		['value' => $AIFLG_ROLE_OP1,    'label' => 'Opérateur',                'level' => 3]];

function AIFLG_encrypt ($s) {
  return md5($s);
}

function AIFLG_createUniqueID () {
  return uniqid ("");
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

class AIFLG_Exception extends Exception {};

function AIFLG_getUIDandRole () {
  global $AIFLG_ROLE_ADMIN0;
  global $AIFLG_ROLE_ADMIN1;
  global $AIFLG_ROLE_OP0;
  global $AIFLG_ROLE_OP1;  

  $uid = checkCookie ();
  if (is_null ($uid)) throw new AIFLG_Exception ('No cookie, or no user for this cookie');

  $role = AIFLG_getRoleForUID ($uid);
  switch ($role) {
  case $AIFLG_ROLE_ADMIN0:
  case $AIFLG_ROLE_ADMIN1:
  case $AIFLG_ROLE_OP0:
  case $AIFLG_ROLE_OP1:
    return array ('uid' => $uid, 'role' => $role);
  default:
    throw new AIFLG_Exception ("No role for user $uid");
  }
}


class AIFLG_User {
  const _role = '_role';
  const _sid = 'sid';
  const _description = 'description';
  
  public $uid;
  public $role;
  public $sid;
  public $description;
  
  static function get ($uid) {
    $query = "select * from " . AIFLG::ROLES_TABLE . " where uid = :uid";
    $stmt = AIFLG_executePrepared ($query, array (':uid' => $uid));
    if ($stmt -> rowCount () != 1) throw new AIFLG_Exception ("No record for use $uid");
    $x = $stmt -> fetch ();
    $stmt -> closeCursor ();
    $ret = new AIFLG_User ();
    $ret -> uid = $uid;
    $ret -> role = $x [self::_role];
    $ret -> sid = $x [self::_sid];
    $ret -> description = $x [self::_description];
    return $ret;
  }

  static function getCurrent () {
    $uid = checkCookie ();
    if (is_null ($uid)) throw new AIFLG_Exception ('No cookie, or no user for this cookie');
    return self::get ($uid);
  }

  function isAdmin () {
    return $this -> role == AIFLG::ROLE_ADMIN0 OR $this -> role == AIFLG::ROLE_ADMIN1;
  }
}

class AIFLG_Producer {
  const _sid = 'sid';
    
  public $pid;
  public $sid;

  static function get ($pid) {
    $query = "select * from " . AIFLG::PRODUCERS_TABLE . " where pid = :pid";
    $stmt = AIFLG_executePrepared ($query, array (':pid' => $pid));
    if ($stmt -> rowCount () != 1) throw new AIFLG_Exception ("no single record for pid = $pid");
    $x = $stmt -> fetch ();
    $stmt -> closeCursor ();
    $ret = new AIFLG_Producer ();
    $ret -> pid = $pid;
    $ret -> sid = $x [self::_sid];
    return $ret;
  }

}

?>
