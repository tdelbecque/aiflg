<?php // -*- mode: c; -*-
require_once 'dbaccess.php';
require_once 'utils.php';
require_once 'structures.php';

function getAllProducersJson ($postdata) {
  global $AIFLG_ROLE_ADMIN0;
  global $AIFLG_ROLE_ADMIN1;
  global $AIFLG_ROLE_OP0;
  global $AIFLG_ROLE_OP1;  

  $uid = checkCookie ();
  if (is_null ($uid)) {
    return json_encode (['error' => "bad authentication cookie"]);
  }

  switch (AIFLG_getRoleForUID ($uid)) {
  case $AIFLG_ROLE_ADMIN0:
  case $AIFLG_ROLE_ADMIN1:
    return getAllProducersForAdminJson ();
  case $AIFLG_ROLE_OP0:
  case $AIFLG_ROLE_OP1:
    return getAllProducersForOpJson ($uid);
  default:
    return json_encode (['error' => "undefined role"]);
  }
}

function getAllProducersForAdminJson () {
  global $AIFLG_STRUCTURES_TABLE;
  global $AIFLG_PRODUCERS_TABLE;

  $structures = AIFLG_getStructures ();
  $structuresOption = array ();
  foreach ($structures as $s) {
    if ($s ['sid'] != "0")
      array_push ($structuresOption,
		  array ('label' => $s ['label'],
			 'value' => $s ['sid']));
  }
  
  $fields = array (array ('name' => 'pid', 'label' => 'Id Producteur',
			  'type' => 'text', 'noneditable' => TRUE),
		   array ('name' => 'structure', 'label' => 'Code Structure',
			  'type' => 'select', 'options' => $structuresOption),
		   array ('name' => 'code', 'label' => 'Code Producteur',
			  'type' => 'text'),
		   array ('name' => 'nom', 'label' => 'Nom',
			  'type' => 'text'),
		   array ('name' => 'adr1', 'label' => 'Voie et no',
			  'type' => 'text'),
		   array ('name' => 'cp', 'label' => 'Code postal',
			  'type' => 'text'),
		   array ('name' => 'ville', 'label' => 'Ville',
			  'type' => 'text'),
		   array ('name' => 'telephone', 'label' => 'Téléphone',
			  'type' => 'text'),
		   array ('name' => 'fax', 'label' => 'Fax',
			  'type' => 'text'),
		   array ('name' => 'mobile', 'label' => 'Mobile',
			  'type' => 'text'),
		   array ('name' => 'email', 'label' => 'E-mail',
			  'type' => 'text')
		   );
  $i = 1;
  foreach ($fields as &$f)
    $f ['crank'] = $f ['frank'] = $i++;

  $query = "select A.*, B.sid as structure from $AIFLG_PRODUCERS_TABLE A join $AIFLG_STRUCTURES_TABLE B on A.sid = B.sid order by B.label, A.nom";
  $rs = AIFLG_query ($query);
  $rows = array ();
  $i = 1;
  while ($r = $rs -> fetch ()) {
    $values = array ();
    $options = array ();
    foreach ($fields as $g) {
      $n = $g ['name'];
      $values [$n] = $r [$n];
      $options [$n] = array ();
    }
    $row = array ('id' => $i ++, 'values' => $values,
		  'options' => $options, 'editable' => true, 'deletable' => true);
    array_push ($rows, $row);
  }
  $rs -> closeCursor ();

  return json_encode (array ('fields' => $fields,
			     'key' => 'pid',
			     'rows' => $rows));
}

function getAllProducersForOpJson ($uid) {
  try {
    $sid = AIFLG_getStructureForUID ($uid);
    $fields = array (array ('name' => 'pid', 'label' => 'Id Producteur',
			    'type' => 'text', 'noneditable' => TRUE),
		     array ('name' => 'nom', 'label' => 'Nom',
			    'type' => 'text'),
		     array ('name' => 'adr1', 'label' => 'Voie et no',
			    'type' => 'text'),
		     array ('name' => 'cp', 'label' => 'Code postal',
			    'type' => 'text'),
		     array ('name' => 'ville', 'label' => 'Ville',
			    'type' => 'text'),
		     array ('name' => 'telephone', 'label' => 'Téléphone',
			    'type' => 'text'),
		     array ('name' => 'fax', 'label' => 'Fax',
			    'type' => 'text'),
		     array ('name' => 'mobile', 'label' => 'Mobile',
			    'type' => 'text'),
		     array ('name' => 'email', 'label' => 'E-mail',
			    'type' => 'text')
		     );
    $i = 1;
    foreach ($fields as &$f)
      $f ['crank'] = $f ['frank'] = $i++;

  return json_encode (array ('fields' => $fields,
			       'key' => 'pid',
			       'rows' => array ()));
  }
  catch (Exception $e) {
    return json_encode (['error' => ""]);
  }
}

function newProducer ($postData) {
  global $AIFLG_ROLE_ADMIN0;
  global $AIFLG_ROLE_ADMIN1;
  global $AIFLG_ROLE_OP0;
  global $AIFLG_ROLE_OP1;  
  $uid = checkCookie ();
  if (is_null ($uid)) {
    return json_encode (['error' => "bad authentication cookie"]);
  }

  switch (AIFLG_getRoleForUID ($uid)) {
  case $AIFLG_ROLE_ADMIN0:
  case $AIFLG_ROLE_ADMIN1:
    return newProducerForAdmin ();
  case $AIFLG_ROLE_OP0:
  case $AIFLG_ROLE_OP1:
    return newProducerForOp ($uid);
  default:
    return json_encode (['error' => "undefined role"]);
  }
}

function newProducerForAdmin () {
  return json_encode (array ("pid" => AIFLG_createUniqueID ()));
}

function newProducerForOp ($uid) {
  return json_encode (array ("pid" => AIFLG_createUniqueID ()));
}

?>
