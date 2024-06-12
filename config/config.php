<?php

define("BASE_URL", "http://127.0.0.1/PRAKTIKUM_PHP_SAJT/");
define("ABSOLUTE_PATH", $_SERVER["DOCUMENT_ROOT"]."/PRAKTIKUM_PHP_SAJT/");

// Ostala podesavanja
define("ENV_FAJL", ABSOLUTE_PATH."config/.env");
define("LOG_FAJL", ABSOLUTE_PATH."data/log.txt");

define("SERVER", env("SERVER"));
define("DATABASE", env("DB_NAME"));
define("USERNAME", env("USERNAME"));
define("PASSWORD", env("PASSWORD"));

function env($naziv) {
    if (!file_exists(ENV_FAJL)) {
        die("The environment file does not exist.");
    }

    $podaci = file(ENV_FAJL);
    $vrednost = "";

    foreach ($podaci as $value) {
        $konfig = explode("=", $value, 2);
        if (count($konfig) == 2 && trim($konfig[0]) == $naziv) {
            $vrednost = trim($konfig[1]);
            break;
        }
    }

    if (empty($vrednost) && $naziv != 'PASSWORD') {
        die("Environment variable '$naziv' not found.");
    }

    return $vrednost;
}
?>
