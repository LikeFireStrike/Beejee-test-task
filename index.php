<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('Core/Autoloader.php');

// Register classes
Autoloader::register();
// App initialization
$app = new App;
// Run the app
$app->run();