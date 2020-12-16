<?php
require('Core/Autoloader.php');

// Register classes
Autoloader::register();
// App initialization
$app = new App;
// Run the app
$app->run();
