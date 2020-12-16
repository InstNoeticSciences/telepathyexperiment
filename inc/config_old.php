<?php
    // database access parameters
    define(db_host, "db2218.perfora.net");
    define(db_name, "db314053431");
    define(db_user, "dbo314053431");
    define(db_pass, "ReVou99834");
    define(db_type, "MySQL");
/*
    define(db_host, "localhost");
    define(db_name, "telephonetelepathy");
    define(db_user, "root");
    define(db_pass, "rach3l");
    define(db_type, "MySQL");
*/
    // captcha keys
    define(captcha_pub, "6Ley8vISAAAAAKg4pFk6B_8KLmRMi9moW4m9Accg");
    define(captcha_prv, "6Ley8vISAAAAANBWRgrr1GtlI2oql3NfjAEWjcfX");

    // boundary values
    define('MAX_LOGIN_ATTEMPTS', 3);
    define("MAX_FRIENDS", 3);
    define("MAX_TRIALS", 6);
    define("PIN_MIN", 10000);
    define("PIN_MAX", 99999);
    define("FIRST_EXTENSION","1000");
    define("LAST_EXTENSION","9999");

    // administration parameters
    define('SYSTEM_ADMIN', 'gre.bris@gmail.com');
    define('SUSPECT_VALUES', '/Content-Type:|Bcc:|Cc:/i');
    define('TWILIO_PHONE', '(315) 605-2265');
    define('MODE_READONLY', 'readonly');

    // graph configuration
    $graph_cfg = array (
        'title'=>'Experimental Performance against Chance',
        'background-color'=>'FFFFFF',
        'graph-background-color'=>'FFFFFF',
        'font-color'=>'000000',
        'border-color'=>'009900',
        'column-color'=>'00FF00',
        'column-shadow-color'=>'009900',
        'column-font-color-q1'=>'000000',
        'column-font-color-q2'=>'000000',
        'random-column-color'=>1
    );
?>
