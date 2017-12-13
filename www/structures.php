<?php  // Hi emacs ! -*- mode: c; -*- 
require_once 'dbaccess.php';
require_once 'utils.php';

function AIFLG_getStructures () {
  global $AIFLG_STRUCTURES_TABLE;

  $rs = AIFLG_query ("select * from $AIFLG_STRUCTURES_TABLE");

  $ret = array ();
  while ($r = $rs -> fetch ()) {
    array_push ($ret,
		array ('sid' => $r ['sid'],
		       'label' => $r ['label'],
		       'type' => $r ['_type'],
		       'description' => $r ['description']));
  }
  $rs -> closeCursor ();
  return $ret;
}

function getAllStructuresJson ($uid) {
  global $AIFLG_STRUCTURES_TABLE;

  $query = "select * from $AIFLG_STRUCTURES_TABLE";
  $rs = AIFLG_query ($query);

  $fields = array (
		   array ('name' => 'sid', 'label' => 'Id Structure',
			  'crank' => 1, 'frank' => 1,
			  'type' => 'text', 'noneditable' => TRUE),
		   array ('name' => 'label', 'label' => 'Nom', 
			  'crank' => 2, 'frank' => 2,
			  'type' => 'text'),
		   array ('name' => 'description', 'label' => 'Description',
			  'crank' => 3, 'frank' => 3,
			  'type' => 'text multiple'));

  $rows = array ();
  $i = 1;
  while ($r = $rs -> fetch ()) {
    $value = array ();
    $options = array ();
    foreach ($fields as $f) {
      $values [$f ['name']] = $r [$f ['name']];
      $options [$f ['name']] = [];
    }
    $row = array ('id' => $i ++,
		  'values' => $values,
		  'options' => $options,
		  'editable' => TRUE);
    if ($r ['_type'] != 0) $row ['deletable'] = TRUE;
    array_push ($rows, $row);
  }
  $rs -> closeCursor ();
  $data = array ('fields' => $fields,
		 'key' => 'sid',
		 'rows' => $rows);
  return json_encode ($data);
}

?>
