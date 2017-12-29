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

function AIFLG_getStructureForUID ($uid) {
  global $AIFLG_ROLES_TABLE;
  
  $stmt = AIFLG_executePrepared ("select sid from $AIFLG_ROLES_TABLE where uid=:uid",
				 array (':uid' => $uid));
  if ($stmt -> rowCount () != 1) {
    $stmt -> closeCursor ();
    throw new AIFLG_DBException ("structure issue for $uid");
  }
  $sid = $stmt -> fetch () ['sid'];
  $stmt -> closeCursor ();
  return $sid;
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
    if ($r ['sid'] != 0) $row ['deletable'] = TRUE;
    array_push ($rows, $row);
  }
  $rs -> closeCursor ();
  $data = array ('fields' => $fields,
		 'key' => 'sid',
		 'rows' => $rows);
  return json_encode ($data);
}

function updateStructure ($structureData) {
  global $AIFLG_STRUCTURES_TABLE;
  
  AIFLG_executePrepared ("update $AIFLG_STRUCTURES_TABLE set label=:label, description=:description where sid=:sid",
			 array (':sid' => $structureData['sid'],
				':label' => $structureData['label'],
				':description' => $structureData['description']));
  return json_encode (array ("status" => "ok"));
}

function newStructure ($uid) {
  return json_encode (array ("sid" => AIFLG_createUniqueID ()));
}

function addStructure ($structureData) {
  global $AIFLG_STRUCTURES_TABLE;

  $stmt = AIFLG_executePrepared ("select * from $AIFLG_STRUCTURES_TABLE where sid = ':sid'",
				 array (':sid' => $structureData ['sid']));

  $r = $stmt -> fetch ();
  $stmt -> closeCursor ();
  if ($r) return json_encode (array ('error' => "Cannot create new structure for sid = ${structureData['sid']}"));
  AIFLG_executePrepared ("insert into $AIFLG_STRUCTURES_TABLE values (:sid, :label, :type, :description)",
			 array (':sid' => $structureData ['sid'],
				':label' => $structureData ['label'],
				':type' => $structureData ['_type'],
				':description' => $structureData ['description']));
  return json_encode (array ("status" => "ok"));
}

function deleteStructure ($structureData) {
  global $AIFLG_STRUCTURES_TABLE;

  AIFLG_executePrepared ("delete from $AIFLG_STRUCTURES_TABLE where sid = :sid",
			 array (':sid' => $structureData ['sid']));

  return json_encode (array ("status" => "ok"));  
}

?>
