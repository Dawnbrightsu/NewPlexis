<?php
/**
 * Plexis Content Management System
 *
 * @author      Steven Wilson (Wilson212)
 * @author      Tony (Syke)
 * @copyright   2013, Plexis Dev Team
 * @license     GNU GPL v3
 * @package     System
 */

// Make sure we are running php version 5.3.2 or newer!!!!
if(!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50302)
    die('PHP version 5.3.2 or newer required to run Plexis. Your version: '. PHP_VERSION);
    
/** Most Accurate Start time */
define('TIME_START', microtime(true));
    
/** Smaller Directory Separator */
define('DS', DIRECTORY_SEPARATOR);

/** Full Root Path to The Cms, No trailing slash */
define('ROOT', dirname(__FILE__));

/** Full Root path to the System folder, No trailing slash */
define('SYSTEM_PATH', ROOT . DS .'system');

/** Define if we are running in a mod rewrite enviroment */
define('MOD_REWRITE', isset($_SERVER["HTTP_MOD_REWRITE"]) && $_SERVER["HTTP_MOD_REWRITE"] == "On");

// Point php to our own php error log
ini_set('error_log', SYSTEM_PATH . DS .'logs'. DS .'php_errors.log');
    
// Include the required script to run the system
require SYSTEM_PATH . DS .'Plexis.php';

// Run the system. Application will be run on success
Plexis::Init();