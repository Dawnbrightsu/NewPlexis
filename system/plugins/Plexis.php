<?php
/*
| --------------------------------------------------------------
| Plexis
| --------------------------------------------------------------
| Author:       Steven Wilson
| Author:       Tony (Syke)
| Copyright:    Copyright (c) 2013, Plexis
| License:      GNU GPL v3
| ---------------------------------------------------------------
| Plugin: Plexis
| ---------------------------------------------------------------
|
| Main plugin for detecting whether the system needs installed
| Displays error message when install folder exists, but the system
| is installed
|
*/
namespace Plugin;

// Bring some classes into scope
use \Plexis as App;
use System\Http\Response;
use System\Web\Layout;

class Plexis
{
    public function __construct()
    {
        // Check for database online, surpress errors
        $DB = App::LoadDBConnection(false);

        // Check if the install directory exists
        $installer = is_dir( ROOT . DS . 'install' );

        // Check if installer files are present
        $locked = file_exists( ROOT . DS . 'install'. DS .'install.lock' );

        // Check if the install folder is still local
        if($DB == false && $locked == false && $installer == true)
        {
            // Temporary redirect (307)
            Response::Redirect('install/index.php', 0, 307);
            die;
        }
        elseif($locked == false && $installer == true)
        {
            //Warn that the installer is accessible.
            Layout::DisplayMessage('error', "The installer is publicly accessible! Please rename, delete or re-lock your install folder");
        }
    }
}
?>