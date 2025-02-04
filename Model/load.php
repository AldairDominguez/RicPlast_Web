
<?php
// -----------------------------------------------------------------------
//DEFINIR ALIAS DE SEPARADOR
// -----------------------------------------------------------------------
if (!defined("URL_SEPARATOR")) {
    define("URL_SEPARATOR", '../');
}

if (!defined("DS")) {
    define("DS", DIRECTORY_SEPARATOR);
}

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
if (!defined('SITE_ROOT')) {
    define('SITE_ROOT', realpath(dirname(__FILE__)));
}

if (!defined("LIB_PATH_INC")) {
    define("LIB_PATH_INC", SITE_ROOT.DS);
}

require_once(LIB_PATH_INC.'config.php');
require_once(LIB_PATH_INC.'functions.php');
require_once(LIB_PATH_INC.'session.php');
require_once(LIB_PATH_INC.'upload.php');
require_once(LIB_PATH_INC.'database.php');
require_once(LIB_PATH_INC.'sql.php');

?>
