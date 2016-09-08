<?php

define('_DOMAIN_URL_', (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST']);
define('_PROJECT_NAME_', 'CodeLib');
define('_DB_HOST_', 'localhost');
define('_DB_NAME_', 'codelib');
define('_DB_USER_', 'root');
define('_DB_PASSWORD_', '');
define('_DB_PREFIX_', '');
define('_MYSQL_ENGINE_', 'InnoDB');
define('_SEO_', 'true');
define('_VERSION_', '0.0.9');



function ClassLoader($className)
{
    $className = str_replace('\\', '/', $className);
    if(file_exists(__DIR__ .'/classes/'. $className . '.php')){
      require_once(__DIR__ .'/classes/'. $className . '.php');
    } else {
      echo 'ERROR: '. $className;
    }
}
spl_autoload_register('ClassLoader');

session_start();

use Database\DbPDO;

$db = new DbPDO('mysql:host='._DB_HOST_.';dbname='._DB_NAME_.';charset=utf8',_DB_USER_,_DB_PASSWORD_);

