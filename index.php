<?php

session_start();

// CONFIG
define('_MULTIPLIER', date('n')); // option to set the multiplier
define('_USER_PASS', ''); // set password here

// hack autoloader
foreach (glob(".classes/*.php") as $includeFile) {
    include_once $includeFile;
}

// register new app
$app = new App;

// load template file
include_once '.template.php';
