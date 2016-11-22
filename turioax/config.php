<?php
// TODO realmente se hacen muchas cosas en este config. Se podran hacer menos?

define('DEBUG', false);
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('display_errors', 'on');
define('MR',    '?');  // mod rewrite. Vacia si esta activado, con '?/' si no lo esta

define('DS', DIRECTORY_SEPARATOR);
define('CA_PATH', __DIR__.DS);
define('CA_LIB_PATH', CA_PATH.'lib'.DS);            // libs
define('CA_MD_PATH', CA_PATH.'src'.DS.'models'.DS);  // models
define('CA_VW_PATH', CA_PATH.'src'.DS.'views'.DS);  // views
define('CA_RT_PATH', CA_PATH.'src'.DS.'routes'.DS);  // routes
define('CA_HP_PATH', CA_PATH.'src'.DS.'helpers'.DS);  // helpers

define('CA_URL_BASE', 'http://'.$_SERVER['SERVER_NAME'].'/cruds/turioax/');
define('CA_URL', 'http://'.$_SERVER['SERVER_NAME'].'/cruds/turioax/');

define('SESSION_NAME', 'turioax');
define('PASS_DIGEST_FN', 'sha1');

//define('CA_LOG_FILE', '');
define('CA_LOG_FILE', CA_PATH.'log.json');

define('CA_RESOURCES_PATH', '/tmp/ca-resources-path/');

define('CA_LOGIN_USE', 'ssh');
define('CA_LOGIN_DOMAIN', 'mixteco.utm.mx');    // Para hacer login cortos e info
define('CA_FTP_HOST', 'mixteco.utm.mx');
define('CA_SSH_HOST', 'mixteco.campus.utm');
define('CA_CWEB_HOST', 'correo.utm.mx');
define('CA_CWEB_LOGIN', 'http://correo.utm.mx/');
define('CA_CWEB_LOGOUT', 'http://correo.utm.mx/?_task=logout');
//define('CA_USE_CWEB', false);   // false usa ftp. true usa correo.

define('CA_LOGIN_USE_FILE', false);   // Usa un archivo de correos. Para debugging.
define('CA_LOGIN_FILE', CA_PATH.'users.json');   // Usa un archivo de correos. Para debugging.

//define('CA_AUTHORIZE', true);  // Default true. Activa/desactiva las authorizaciones  por Cargo.a
define('CA_AUTHORIZE', false);

session_name(SESSION_NAME);
session_start();

//require_once(CA_PATH.'vendor'.DS.'autoload.php');
require_once(CA_LIB_PATH.'formateo.php');
require_once(CA_LIB_PATH.'sesion.php');
require_once(CA_LIB_PATH.'http.php');
require_once(CA_LIB_PATH.'authorization.php');
require_once(CA_LIB_PATH.'filters.php');
require_once(CA_LIB_PATH.'logg.php');

if (function_exists('date_default_timezone_set')){
    date_default_timezone_set('America/Mexico_City');
}
require_once(CA_PATH.'vendor'.DS.'php-activerecord'.
    DS.'php-activerecord'.DS.'ActiveRecord.php');

// Activerecord configurations
ActiveRecord\Config::initialize(function($cfg){
    $cfg->set_model_directory(CA_MD_PATH);
    $cfg->set_connections(array(
        'development' => 'mysql://root:@localhost/turismo'));

    $cfg->set_default_connection('development');
});
ActiveRecord\DateTime::$FORMATS['latino']   = 'd/m/Y';
ActiveRecord\DateTime::$DEFAULT_FORMAT      = 'latino';


////////// Para el dispatch
define('DISPATCH_DEBUG_ENABLED', false);
define('DISPATCH_DEBUG_LOG', 'debug.log');
define('DISPATCH_COOKIES_SECRET', 'mary-had-a-little-lamb');
define('DISPATCH_COOKIES_FLASH', '___F');
define('DISPATCH_LAYOUT_DEFAULT', 'layout');

require_once(CA_LIB_PATH.'dispatch.php');
////////// FIN DISPATCH

require_once(CA_HP_PATH.'error_handling.php');
?>
