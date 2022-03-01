<?php

session_start();

// CONFIG
define('_MULTIPLIER', date('n')); // option to set the multiplier
define('_USER_PASS', ''); // set password here

foreach (glob(".classes/*.php") as $includeFile) {
    include_once $includeFile;
}

// register new app
$app = new App;

// logout
if (!empty($_GET['logout'])) {
    $app->user->logOut();
}

// load template file
include_once '.template.php';
