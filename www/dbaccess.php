<?php  // Hi emacs ! -*- mode: c; -*-

class AIFLG_DATA_QUERY_RESULTSET {
    public $rs = null;
    public $connection;

    public function __construct ($connection) {
        $this -> connection = $connection;
    }

    function __destruct () {
        $this -> cleanup ();
    }

    public function cleanup () {
        if (! is_null ($this -> rs)) {
            $this -> rs -> closeCursor ();
            $this -> rs = null;
        }
    }

    public function executePrepared ($query, $params) {
        $this -> cleanup ();
        $rs = $this -> connection -> executePrepared ($query, $params);
    }
}

class AIFLG_DATA {
    const dbuser = 'aiflg';
    const dbpwd = 'aiflg';
    const dburl = 'mysql:host=localhost;dbname=aiflg;charset=utf8';

    public $connection;
    public function getConnection () {
        return $this -> connection;
    }

    public function __construct () {
        $this -> connection = new PDO (AIFLG_DATA::dburl, AIFLG_DATA::dbuser, AIFLG_DATA::dbpwd);
    }

    public function executePrepared ($query, $params) {
        $stmt = $this -> connection -> prepare ($query);
        $stmt -> execute ($params);
        return $stmt;
    }

    public function getDistinctValuesAscendent ($table, $field, $condition, $params) {
        if (is_null ($condition)) {
            $query = "select distinct $field from $table order by $field ASC";
            $rs = $this -> executePrepared ($query, array ());
        } else {
            $query = "select distinct $field from $table where $condition order by $field ASC";
            if (is_null ($params)) $params = array ();
            $rs = $this -> executePrepared ($query, $params);
        }
        $ret = array ();
        while ($r = $rs -> fetch ()) {
            array_push ($ret, $r [$field]);
        }
        $rs -> closeCursor ();
        return $ret;
    }
}

$dbuser = 'aiflg';
$dbpwd = 'aiflg';
$dburl = 'mysql:host=localhost;dbname=aiflg;charset=utf8';

$AIFLG_DATAOBJ = new AIFLG_DATA ();
$AIFLG_BDD = $AIFLG_DATAOBJ -> getConnection ();
/*$AIFLG_BDD = new PDO ($dburl, $dbuser, $dbpwd);*/
$AIFLG_BDD->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
/*
$AIFLG_KEYS_TABLE = "keys_table";
$AIFLG_AUTHCOOKIES_TABLE = "cookies_table";

$AIFLG_ROLES_TABLE = "roles_table";
$AIFLG_STRUCTURES_TABLE = "structures_table";
$AIFLG_PRODUCERS_TABLE = 'producers_table';
$AIFLG_PARCELS_TABLE = "parcels_table";
*/

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
