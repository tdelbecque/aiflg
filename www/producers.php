<?php // -*- mode: c; -*-
require_once 'dbaccess.php';
require_once 'utils.php';
require_once 'structures.php';

function getAllProducersJson (AIFLG_User $user, $postdata) {
  return $user -> isAdmin () ?  
    getAllProducersForAdminJson () :
    getAllProducersForOpJson ($user);
  
 }

function getAllProducersForAdminJson () {
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

  $query = "select A.*, B.sid as structure from " . AIFLG::PRODUCERS_TABLE . 
      " A join " . AIFLG::STRUCTURES_TABLE . " B on A.sid = B.sid order by B.label, A.nom";
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

function getAllProducersForOpJson (AIFLG_User $user) {
  $fields = array (array ('name' => 'pid', 'label' => 'Id Producteur',
			  'type' => 'text', 'noneditable' => TRUE),
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
  
  $query = 'select * from ' . AIFLG::PRODUCERS_TABLE . ' where sid = :sid order by nom';
  $stmt = AIFLG_executePrepared ($query, array (':sid' => $user -> sid));
  $rows = array ();
  $i = 1;
  while ($r = $stmt -> fetch ()) {
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
  $stmt -> closeCursor ();
  
  return json_encode (array ('fields' => $fields,
			     'key' => 'pid',
			     'rows' => $rows));
}

function newProducer (AIFLG_User $user, $postData) {
  return $user -> isAdmin () ?
    newProducerForAdmin () :
    newProducerForOp ($user);
}

function newProducerForAdmin () {
  return json_encode (array ("pid" => AIFLG::createUniqueID ()));
}

function newProducerForOp (AIFLG_User $user) {
  return json_encode (array ("pid" => AIFLG::createUniqueID ()));
}

function addProducer (AIFLG_User $user, $queryData) {
  $query = "insert into " . AIFLG::PRODUCERS_TABLE .
    ' (code, nom, adr1, cp, ville, telephone, fax, mobile, email, pid, sid) values ' .
    ' (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ';

  AIFLG_executePrepared ($query,
			 array ($queryData ['code'],
				$queryData ['nom'],
				$queryData ['adr1'],
				$queryData ['cp'],
				$queryData ['ville'],
				$queryData ['telephone'],
				$queryData ['fax'],
				$queryData ['mobile'],
				$queryData ['email'],
				$queryData ['pid'],
				$user -> isAdmin () ? $queryData ['structure'] : $user -> sid));
  return json_encode (array ("status" => "ok"));
}


function updateProducer (AIFLG_User $user, $queryData) {
  // If the user is not an administrator, get the sid for the pis, and check if the user has rights on this sid:
  if (! $user -> isAdmin ()) {
    $producer = AIFLG_Producer::get ($queryData ['pid']);
    $queryData ['structure'] = $producer -> sid;
  }
  if ($user -> isAdmin () or ($user -> sid === $queryData ['structure'])) {
    $query = 'update ' . AIFLG::PRODUCERS_TABLE . ' set ' .
      'code = ?, nom = ?, adr1 = ?, cp = ?, ville = ?, telephone = ?, fax = ?, mobile = ?, email = ?, sid = ? ' .
      'where pid = ?';
    AIFLG_executePrepared ($query,
			   array ($queryData ['code'],
				  $queryData ['nom'],
				  $queryData ['adr1'],
				  $queryData ['cp'],
				  $queryData ['ville'],
				  $queryData ['telephone'],
				  $queryData ['fax'],
				  $queryData ['mobile'],
				  $queryData ['email'],
				  $queryData ['structure'],
				  $queryData ['pid']));
  } else {
    return json_encode (array ("status" => "error", "error" => "this user cannot update this producer"));
  }
  return json_encode (array ("status" => "ok"));
}

?>
