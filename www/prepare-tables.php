<?php // -*- mode: c; -*-
require_once ('utils.php');

function prepare_structures () {
  $T = array ();

  $stmt = AIFLG_executePrepared ("select label from " . AIFLG::STRUCTURES_TABLE . 
                                 " where sid='-1'", array());

  while ($l = $stmt -> fetch ()) {
    array_push ($T, array ($l [0], AIFLG::createUniqueID ()));
  }

  $stmt -> closeCursor ();

  foreach ($T as $t) {
    AIFLG_executePrepared ("update " . AIFLG::STRUCTURES_TABLE . 
                           " set sid=:sid where label=:label",
			   array (':label' => $t[0],
				  ':sid' => $t[1]));
  }
}

function prepare_producteurs () {
  $T = array ();
  $stmt = AIFLG_executePrepared ("select code from producteurs where pid is null", array());

  while ($l = $stmt -> fetch ()) {
    array_push ($T, array ($l [0], AIFLG::createUniqueID ()));
  }

  $stmt -> closeCursor ();

foreach ($T as $t) {
  AIFLG_executePrepared ("update producteurs set pid=:pid where code=:code",
			 array (':code' => $t[0],
				':pid' => $t[1]));
 }
}

function prepare_parcelles () {
  $T = array ();

  $stmt = AIFLG_executePrepared ("select id_parcelle from " . AIFLG::PARCELS_TABLE, array());
  while ($l = $stmt -> fetch ()) {
    array_push ($T, array ('id' => $l [0], 'parcid' => AIFLG::createUniqueID ()));
  }

  $stmt -> closeCursor ();

  foreach ($T as $x) {
    AIFLG_executePrepared ("update " . AIFLG::PARCELS_TABLE . 
                           " set parcid=:parcid where id_parcelle=:id", $x);
  }
}

prepare_parcelles ();

?>
