<?php  // Hi emacs ! -*- mode: c++; -*- 
require_once ('dbaccess.php');
require_once 'utils.php';
require_once 'structures.php';

/*
 * User management
 */
function getAllUsersJson ($uid) {
    $structures = AIFLG_getStructures ();
    $structuresOption = array ();
    foreach ($structures as $s) {
        array_push ($structuresOption,
                    array ('label' => $s ['label'],
                           'value' => $s ['sid']));
    }

    $query = "select A.*, B._key as _key from " . 
        AIFLG::ROLES_TABLE . 
        " A join " .
        AIFLG::KEYS_TABLE . 
        " B on A.uid = B.uid";
    $rs = AIFLG_query ($query);
	 
    $fields = array (
                     array ('name' => 'uid', 'label' => 'Id Utilisateur',
                            'crank' => 1, 'frank' => 1,
                            'type' => 'text', 'noneditable' => TRUE),
                     array ('name' => '_key', 'label' => 'Clé secrète',
                            'crank' => 2, 'frank' => 2,
                            'type' => 'text'),
                     array ('name' => 'description', 'label' => 'Description',
                            'crank' => 3, 'frank' => 3,
                            'type' => 'text multiple'),
                     array ('name' => '_role', 'label' => 'Role',
                            'crank' => 4, 'frank' => 4,
                            'type' => 'select', 'options' => AIFLG::ROLES),
                     array ('name' => 'sid', 'label' => 'Structure',
                            'crank' => 5, 'frank' => 5,
                            'type' => 'select', 'options' => $structuresOption));

    $rows = array ();
    $i = 1;
    while ($r = $rs -> fetch ()) {
        $values = array ();
        $options = array ();
        foreach ($fields as $f) {
            $values [$f ['name']] = $r [$f ['name']];
            $options [$f ['name']] = array ();
        }
        if ($r ['uid'] == $uid)
            $options ['_role']['noneditable'] = TRUE;
        $row = array ('id' => $i++, 'values' => $values, 'options' => $options); //, 'editable' => true);
        if ($uid === "0" OR $r ['uid'] !== "0")
            $row ['editable'] = true;

        if ($uid === "0" AND $r ['uid'] !== "0")
            $row ['deletable'] = true;
      
        array_push ($rows, $row);
    }
    $rs -> closeCursor ();
    $data = array ('fields' => $fields,
                   'key' => 'uid',
                   'rows' => $rows);
    return json_encode ($data);
}

function updateUser ($userData) {
    $currentKey = AIFLG_getKeyForUID ($userData['uid']);
    if ($currentKey != $userData ['_key'])
        AIFLG_updateUserKey ($userData['uid'], $userData ['_key']);

    AIFLG_executePrepared ("update " . AIFLG::ROLES_TABLE . 
                           " set _role=:role, description=:description, sid=:sid  where uid=:uid",
                           array (':role' => $userData['_role'],
                                  ':description' => $userData['description'],
                                  ':uid' => $userData['uid'],
                                  ':sid' => $userData['sid']));
			   
    return json_encode (array ("status" => "ok"));
}

function newUser ($data) {
    return json_encode (array ("uid" => AIFLG_createUniqueID (),
                               "_key" => substr (strrev (AIFLG_createUniqueID ()), 0, 5)
                               ));
}

function addUser ($userData) {
    try {
        AIFLG_getKeyForUID ($userData['uid']);
        return json_encode (['error' => "Cannot create new user for uid = ${userData['uid']}"]);
    }
    catch (Exception $e) {
        AIFLG_executePrepared ("insert into " . AIFLG::KEYS_TABLE . " values (:uid, :key, :ekey)",
                               array (':uid' => $userData['uid'],
                                      ':key' => $userData['_key'],
                                      ':ekey' => AIFLG_encrypt ($userData['_key'])));

        AIFLG_executePrepared ("insert into " . AIFLG::ROLES_TABLE . " values (:uid, :role, :sid, :description)",
                               array (':uid' => $userData['uid'],
                                      ':role' => $userData['_role'],
                                      ':sid' => $userData['sid'],
                                      ':description' => $userData['description']));
        return json_encode (array ("status" => "ok"));  
    }
}

function deleteUser ($userData) {
    if ($userData['uid'] !== 0) {
        AIFLG_executePrepared ("delete from " . AIFLG::KEYS_TABLE . " where uid = :uid",
                               array (':uid' => $userData['uid']));
        AIFLG_executePrepared ("delete from " . AIFLG::ROLES_TABLE . " where uid = :uid",
                               array (':uid' => $userData['uid']));    
    } 
    return json_encode (array ("status" => "ok"));  
}

?>
