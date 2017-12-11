<?php
require 'dbaccess.php';

$bdd = new PDO ($dburl, $dbuser, $dbpwd);
$q = $bdd -> query ("select * from parcelles");
$prods = [];
while ($p = $q -> fetch ()) {
	array_push ($prods,
		[ 	'f_id_parcelle' => $p ['id_parcelle'],
			'f_nom_parcelle' => $p ['nom_parcelle'],
			'f_surface' => $p ['surface'],
			'f_no_exploitant' => $p ['no_exploitant'],
			'f_date_plantation' => $p ['date_plantation'],
			'f_ref_cadaste' => $p ['ref_cadaste'],
			'f_code_parcelle' => $p ['code_parcelle'],
			'f_code_variete' => $p ['code_variete'],
			'f_code_producteur' => $p ['code_producteur'],
			'f_fiche_bloquee' => $p ['fiche_bloquee'],
			'f_annee' => $p ['annee'],
			'f_type_plant' => $p ['type_plant'],
			'f_nb_plant' => $p ['nb_plant'],
			'f_densite' => $p ['densite'],
			'f_type_abri' => $p ['type_abri'],
			'f_dummy' => $p ['dummy'],
			'f_type_chauffage' => $p ['type_chauffage'],
			'f_itineraire' => $p ['itineraire'],
			'f_departement' => $p ['departement'],
			'f_volume_pm' => $p ['volume_pm'],
			'f_precocite_pm' => $p ['precocite_pm'],
			'f_region' => $p ['region'],
			'f_fin_plantation' => $p ['fin_plantation']
]
			);
}
$q->closeCursor();
header ('Content-type: application/json');
$data = ['data' => $prods];
echo json_encode ($data);
?>

