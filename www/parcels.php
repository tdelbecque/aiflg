<?php // -*- mode: c; -*-

require_once 'utils.php';

function getAllParcelsJson (AIFLG_User $user, $queryData) {
  return $user -> isAdmin () ?
    getAllParcelsForAdminJson () :
    getAllParcelsForOpJson ($user);
}

const commonParcelsFields = array (array ('name' => 'nom_parcelle', 'label' => 'Nom',
					  'type' => 'text'),
				   array ('name' => 'code_parcelle', 'label' => 'Code',
					  'type' => 'text'),
				   array ('name' => 'code_producteur', 'label' => 'Code Producteur',
					  'type' => 'text'),
				   array ('name' => 'itineraire', 'label' => 'Itinéraire',
					  'type' => 'text'),
				   array ('name' => 'surface', 'label' => 'Surface',
					  'type' => 'text'),
				   array ('name' => 'annee', 'label' => 'Année',
					  'type' => 'text'),
				   array ('name' => 'date_plantation', 'label' => 'Date Plantation',
					  'type' => 'text'),
				   array ('name' => 'fin_plantation', 'label' => 'Fin Plantation',
					  'type' => 'text'),
				   array ('name' => 'code_variete', 'label' => 'Variété',
					  'type' => 'select', 'options' => AIFLG::varietes),
				   array ('name' => 'type_abri', 'label' => "Type d'abri",
					  'type' => 'select', 'options' => AIFLG::typeAbri),
				   array ('name' => 'type_chauffage', 'label' => "Type de chauffage",
					  'type' => 'select', 'options' => AIFLG::typeChauffage),
				   array ("name" => "type_plant", 'label' => "Type de plant",
					  "type" => "select", "options" => AIFLG::typePlant),
				   array ('name' => 'nb_plant', 'label' => 'Nb plants',
					  'type' => 'text'));
  
function getAllParcelsForAdminJson () {
  $fields = commonParcelsFields;
  array_unshift ($fields,
		 array ('name' => 'parcid', 'label' => 'Id Parcelle',
			'type' => 'text', 'noneditable' => TRUE),
		 array ('name' => 'sid', 'label' => 'Id Structure',
			'type' => 'text', 'noneditable' => TRUE),
		 array ('name' => 'pid', 'label' => 'Id Producteur',
			'type' => 'text', 'noneditable' => TRUE));
  $i = 1;
  foreach ($fields as &$f)
    $f ['crank'] = $f ['frank'] = $i++;

  $query = "select * from " . AIFLG::PARCELS_TABLE;
  $rs = AIFLG_query ($query);
  $i = 1;
  $rows = array ();
  while ($r = $rs -> fetch ()) {
    $values = array ();
    $options = array ();
    foreach ($fields as $x) {
      $values [$x ['name']] = $r [$x ['name']];
      $options [$x ['name']] = array ();
    }
    $row = array ('id' => $i ++,
		  'values' => $values,
		  'options' => $options,
		  'editable' => TRUE,
		  'deletable' => TRUE);
    array_push ($rows, $row);
  }
  $rs -> closeCursor ();
  $data = array ('fields' => $fields,
		 'key' => 'parcid',
		 'rows' => $rows);
  return json_encode ($data);
}

function getAllParcelsForOpJson (AIFLG_User $user) {
  $fields = commonParcelsFields;
  array_unshift ($fields,
		 array ('name' => 'parcid', 'label' => 'Id Parcelle',
			'type' => 'text', 'noneditable' => TRUE));
  $i = 1;
  foreach ($fields as &$f)
    $f ['crank'] = $f ['frank'] = $i++;
  $query = 'select * from ' . AIFLG::PARCELS_TABLE . ' where sid = :sid';
  $stmt = AIFLG_executePrepared ($query, array (':sid' => $user -> sid));
  $rows = array ();
  $i = 1;
  while ($r = $stmt -> fetch ()) {
    $values = array ();
    $options = array ();
    foreach ($fields as $x) {
      $values [$x ['name']] = $r [$x ['name']];
      $options [$x ['name']] = array ();
    }
    $row = array ('id' => $i ++,
		  'values' => $values,
		  'options' => $options,
		  'editable' => TRUE,
		  'deletable' => TRUE);
    array_push ($rows, $row);
  }
  $stmt -> closeCursor ();
  $data = array ('fields' => $fields,
		 'key' => 'parcid',
		 'rows' => $rows);
  return json_encode ($data);
}

function canUpdateParcel (AIFLG_User $user, $parcelNewData) {
  if ($user -> isAdmin ())
    return TRUE;
  //  $parcelOldData = getParcel ($parcelNewData ['parceld']);
  //$user = getUser ($auth ['uid']);
  return FALSE;
}

function updateParcel (AIFLG_User $user, $parcelData) {
  if (canUpdateParcel ($user, $parcelData)) {
    AIFLG_executePrepared ('update ' . AIFLG::PARCELS_TABLE . ' set ' .
			   'nom_parcelle = :nom_parcelle,' .
			   'code_parcelle = :code_parcelle,' .
			   'code_producteur = :code_producteur,' .
			   'itineraire = :itineraire,' .
			   'surface = :surface,' .
			   'annee = :annee,' .
			   'date_plantation = :date_plantation,' .
			   'fin_plantation = :fin_plantation,' .
			   'code_variete = :code_variete,' .
			   'type_abri = :type_abri,' .
			   'type_chauffage = :type_chauffage,' .
			   'type_plant = :type_plant,' .
			   'nb_plant = :nb_plant,' .
			   'densite = :densite,' .
			   'volume_pm = :volume_pm,' .
			   'precocite_pm = :precocite_pm' .
			   ' where parcid = :parcid',
			   array (':nom_parcelle' => $parcelData ['nom_parcelle'],
				  ':code_parcelle' => $parcelData ['code_parcelle'],
				  ':code_producteur' => $parcelData ['code_producteur'],
				  ':itineraire' => $parcelData ['itineraire'],
				  ':surface' => $parcelData ['surface'],
				  ':annee' => $parcelData ['annee'],
				  ':date_plantation' => $parcelData ['date_plantation'],
				  ':fin_plantation' => $parcelData ['fin_plantation'],
				  ':code_variete' => $parcelData ['code_variete'],
				  ':type_abri' => $parcelData ['type_abri'],
				  ':type_chauffage' => $parcelData ['type_chauffage'],
				  ':type_plant' => $parcelData ['type_plant'],
				  ':nb_plant' => $parcelData ['nb_plant'],
				  ':densite' => $parcelData ['densite'],
				  ':volume_pm' => $parcelData ['volume_pm'],
				  ':precocite_pm' => $parcelData ['precocite_pm'],
				  ':parcid' => $parcelData ['parcid']));
  }
  return json_encode (array ("status" => "ok"));
}

function newParcel (AIFLG_User $user, $queryData) {
  return json_encode (array ("parcid" => AIFLG_createUniqueID ()));
}

?>
