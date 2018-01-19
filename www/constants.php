<?php // -*- mode: c++; -*-

  /* 
   * class AIFLG contains all constants of the application
   */
class AIFLG {
    const AUTHCOOKIE_KEY = "authkey";

    /*
     * tables in the data base
     */
    
    const ROLES_TABLE = 'roles_table';
    const PARCELS_TABLE = 'parcels_table';
    const STRUCTURES_TABLE = 'structures_table';
    const PRODUCERS_TABLE = 'producers_table';
    const KEYS_TABLE = "keys_table";
    const AUTHCOOKIES_TABLE = "cookies_table";
    
    
    const ROLE_ADMIN0 = 'ADMIN/0';
    const ROLE_ADMIN1 = 'ADMIN/1';
    const ROLE_OP0 = 'OP/0';
    const ROLE_OP1 = 'OP/1';
    
    const ROLES = [['value' => AIFLG::ROLE_ADMIN0, 'label' => 'Administrateur principal', 'level' => 0],
                   ['value' => AIFLG::ROLE_ADMIN1, 'label' => 'Administrateur',           'level' => 1],
                   ['value' => AIFLG::ROLE_OP0,    'label' => 'Opérateur principal',      'level' => 2],
                   ['value' => AIFLG::ROLE_OP1,    'label' => 'Opérateur',                'level' => 3]];

    const varietes = array (array ('value' => "AGA", "label" => "AGA"),
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

    const typePlant = array (array ("value" => "FHS", "label" => "Frigo hémisphère sud"),
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

    const typeChauffage = array (array ("value" => "CH SP", "label" => "Chauffé semi précoce"),
                                 array ("value" => "CH SUP", "label" => "Chauffé très précode"),
                                 array ("value" => "CH", "label" => "Chauffé"),
                                 array ("value" => "CHP", "label" => "Chauffage partiel"),
                                 array ("value" => "FR", "label" => "Froid"),
                                 array ("value" => "HG", "label" => "Hors gel"));

    const typeAbri = array (array ("value" => "CH", "label" => "CH"),
                            array ("value" => "GT", "label" => "GT"),
                            array ("value" => "IN", "label" => "IN"),
                            array ("value" => "MC", "label" => "MC"),
                            array ("value" => "MCDP", "label" => "MCDP"),
                            array ("value" => "PC", "label" => "PC"),
                            array ("value" => "SV", "label" => "SV"),
                            array ("value" => "T5", "label" => "T5"),
                            array ("value" => "T6", "label" => "T6"),
                            array ("value" => "T9", "label" => "T9"));

    public static function encrypt ($s) {
        return md5($s);
    }

    public static function createUniqueID () {
        return uniqid ("");
    }

    public static function createAuthCookieValue () {
        return uniqid ("AUTHCOOKIE_");
    }

}

      ?>
