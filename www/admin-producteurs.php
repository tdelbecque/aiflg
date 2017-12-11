<?php
require 'dbaccess.php';

$bdd = new PDO ($dburl, $dbuser, $dbpwd);
$q = $bdd -> query ("select * from producteurs");
$prods = [];
while ($p = $q -> fetch ()) {
	array_push ($prods,
		[ 	'f_code' => $p ['code'],
			'f_nom' => $p ['nom'],
			'f_adr1' => $p ['adr1'],
			'f_adr2' => $p ['adr2'],
			'f_adr3' => $p ['adr3'],
			'f_cp' => $p ['cp'],
			'f_ville' => $p ['ville'],
			'f_telephone' => $p ['telephone'],
			'f_fax' => $p ['fax'],
			'f_mobile' => $p ['mobile'],
			'f_no_exploitant' => $p ['no_exploitant'],
			'f_email' => $p ['email'],
			'f_code_structure' => $p ['code_structure'] ]
			);
}
$q->closeCursor();
header ('Content-type: application/json');
$data = ['data' => $prods];
echo json_encode ($data);
?>
<?php
/*
       code		varchar(20) primary key,
       nom  	 	text,
       adr1		text,
       adr2		text,
       adr3		text,
       cp		char(5),
       ville		text,
       telephone	text,
       fax		text,
       mobile		text,
       no_exploitant	text,
       email		text,
       code_structure	text);
*/
?>