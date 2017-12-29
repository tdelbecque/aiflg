<?php  // Hi emacs ! -*- mode: c; -*-

$dbuser = 'aiflg';
$dbpwd = 'aiflg';
$dburl = 'mysql:host=localhost;dbname=aiflg;charset=utf8';

$AIFLG_BDD = new PDO ($dburl, $dbuser, $dbpwd);
$AIFLG_KEYS_TABLE = "keys_table";
$AIFLG_AUTHCOOKIES_TABLE = "cookies_table";

$AIFLG_ROLES_TABLE = "roles_table";
$AIFLG_STRUCTURES_TABLE = "structures_table";
$AIFLG_PRODUCERS_TABLE = 'producers_table';

class AIFLG_DBException extends Exception {
  function __construct ($message) {
    parent::__construct ();
    error_log ($message);
  }
}

function AIFLG_query ($q) {
  global $AIFLG_BDD;
  $rs = $AIFLG_BDD -> query ($q);
  if (! $rs) throw new AIFLG_DBException ($q);
  return $rs;
}

function AIFLG_queryAtMostUnique ($q) {
  $rs = AIFLG_query ($q);
  if ($rs -> rowCount () > 1) {
    $rs -> closeCursor ();
    throw new Exception ();
  }
  return $rs;
}

function AIFLG_queryUnique ($q) {
  $rs = AIFLG_query ($q);
  if ($rs -> rowCount () != 1) {
    $rs -> closeCursor ();
    throw new Exception ();
  }
  return $rs;
}

function AIFLG_execute ($q) {
  global $AIFLG_BDD;
  $AIFLG_BDD -> exec ($q);
}

function AIFLG_executePrepared ($query, $params) {
  global $AIFLG_BDD;
  $stmt = $AIFLG_BDD -> prepare ($query);
  $stmt -> execute ($params);
  return $stmt;
}

?>
