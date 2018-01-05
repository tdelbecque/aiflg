<?php // -*- mode: c; -*-

require_once 'utils.php';

$AIFLG_Varietes = array (array ('value' => "AGA", "label" => "AGA"),
			 array ('value' => "AMA", "label" => "AMA"),
			 array ('value' => "ANA", "label" => "ANA"),
			 array ('value' => "ANT", "label" => "ANT"),
			 array ('value' => "ASIA", "label" => "ASIA"),
			 array ('value' => "AVA", "label" => "AVA"),
			 array ('value' => "CAN", "label" => "CAN"),
			 array ('value' => "CAPR", "label" => "CAPR"),
			 array ('value' => "CHA", "label" => "CHA"),
			 array ('value' => "CIF", "label" => "CIF"),
			 array ('value' => "CIG", "label" => "CIG"),
			 array ('value' => "CIJ", "label" => "CIJ"),
			 array ('value' => "CIR", "label" => "CIR"),
			 array ('value' => "CIR121", "label" => "CIR121"),
			 array ('value' => "CLE", "label" => "CLE"),
			 array ('value' => "DAR", "label" => "DAR"),
			 array ('value' => "DON", "label" => "DON"),
			 array ('value' => "DREA", "label" => "DREA"),
			 array ('value' => "ELE", "label" => "ELE"),
			 array ('value' => "ELSA", "label" => "ELSA"),
			 array ('value' => "ELSI", "label" => "ELSI"),
			 array ('value' => "ESSAI", "label" => "ESSAI"),
			 array ('value' => "FAV", "label" => "FAV"),
			 array ('value' => "FLO", "label" => "FLO"),
			 array ('value' => "GAR", "label" => "GAR"),
			 array ('value' => "GLAD", "label" => "GLAD"),
			 array ('value' => "GUS", "label" => "GUS"),
			 array ('value' => "HAR", "label" => "HAR"),
			 array ('value' => "JOL", "label" => "JOL"),
			 array ('value' => "MAG", "label" => "MAG"),
			 array ('value' => "MALL", "label" => "MALL"),
			 array ('value' => "MAN", "label" => "MAN"),
			 array ('value' => "MAR", "label" => "MAR"),
			 array ('value' => "MARA", "label" => "MARA"),
			 array ('value' => "MARIG", "label" => "MARIG"),
			 array ('value' => "MAT", "label" => "MAT"),
			 array ('value' => "MON", "label" => "MON"),
			 array ('value' => "MUR", "label" => "MUR"),
			 array ('value' => "OSI", "label" => "OSI"),
			 array ('value' => "PORT", "label" => "PORT"),
			 array ('value' => "SAN", "label" => "SAN"),
			 array ('value' => "VRNP", "label" => "VRNP"),
			 array ('value' => "VRP", "label" => "VRP"));

$AIFLG_TypePlant = array (array ("value" => "FHS", "label" => "Frigo hémisphère sud"),
			   array ("value" => "FR", "label" => "Frigo"),
			   array ("value" => "FRA", "label" => "Frigo A"),
			   array ("value" => "FRA2", "label" => "Frigo A+"),
			   array ("value" => "FRAIS", "label" => "Frais"),
			   array ("value" => "FRB", "label" => "Frigo B"),
			   array ("value" => "MG", "label" => "Motte gelée"),
			   array ("value" => "MO", "label" => "Motte"),
			   array ("value" => "MTP", "label" => "Mini tray-plant"),
			   array ("value" => "TP", "label" => "Tray-plant"),
			   array ("value" => "TPA", "label" => "Tray-plant altitude"),
			   array ("value" => "TPAP", "label" => "tray-plant altitude planasa"),
			   array ("value" => "TPLG", "label" => "Tray-plant longue conservation"),
			   array ("value" => "TPSF", "label" => "Tray-plant sans froid"),
			   array ("value" => "TPTLC", "label" => "Tray-plant très longue conservation"),
			   array ("value" => "WB", "label" => "WB"));

$AIFLG_TypeChauffage = array (array ("value" => "CH SP", "label" => "Chauffé semi précoce"),
			      array ("value" => "CH SUP", "label" => "Chauffé très précode"),
			      array ("value" => "CH", "label" => "Chauffé"),
			      array ("value" => "CHP", "label" => "Chauffage partiel"),
			      array ("value" => "FR", "label" => "Froid"),
			      array ("value" => "HG", "label" => "Hors gel"));

$AIFLG_TypeAbri = array (array ("value" => "CH", "label" => "CH"),
			 array ("value" => "GT", "label" => "GT"),
			 array ("value" => "IN", "label" => "IN"),
			 array ("value" => "MC", "label" => "MC"),
			 array ("value" => "MCDP", "label" => "MCDP"),
			 array ("value" => "PC", "label" => "PC"),
			 array ("value" => "SV", "label" => "SV"),
			 array ("value" => "T5", "label" => "T5"),
			 array ("value" => "T6", "label" => "T6"),
			 array ("value" => "T9", "label" => "T9"));

function getAllParcelsJson (AIFLG_User $user, $queryData) {
  global $AIFLG_PARCELS_TABLE;
  global $AIFLG_Varietes;
  global $AIFLG_TypeAbri;
  global $AIFLG_TypeChauffage;
  global $AIFLG_TypePlant;

  $fields = array (array ('name' => 'parcid', 'label' => 'Id Parcelle',
			  'type' => 'text', 'noneditable' => TRUE),
		   array ('name' => 'sid', 'label' => 'Id Structure',
			  'type' => 'text', 'noneditable' => TRUE),
		   array ('name' => 'pid', 'label' => 'Id Producteur',
			  'type' => 'text', 'noneditable' => TRUE),
		   array ('name' => 'nom_parcelle', 'label' => 'Nom',
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
			  'type' => 'select', 'options' => $AIFLG_Varietes),
		   array ('name' => 'type_abri', 'label' => "Type d'abri",
			  'type' => 'select', 'options' => $AIFLG_TypeAbri),
		   array ('name' => 'type_chauffage', 'label' => "Type de chauffage",
			  'type' => 'select', 'options' => $AIFLG_TypeChauffage),
		   array ("name" => "type_plant", 'label' => "Type de plant",
			  "type" => "select", "options" => $AIFLG_TypePlant),
		   array ('name' => 'nb_plant', 'label' => 'Nb plants',
			  'type' => 'text'),
		   array ('name' => 'densite', 'label' => 'Densité',
			  'type' => 'text'),
		   array ('name' => 'volume_pm', 'label' => 'Volume pm',
			  'type' => 'text'),
		   array ('name' => 'precocite_pm', 'label' => 'Précocité pm',
			  'type' => 'text'));

  $i = 1;
  foreach ($fields as &$f)
    $f ['crank'] = $f ['frank'] = $i++;

  $query = "select * from $AIFLG_PARCELS_TABLE";
  $rs = AIFLG_query ($query);
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


?>
