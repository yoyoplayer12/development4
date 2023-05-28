<?php

spl_autoload_register(function ($class) {
    require_once(__DIR__ . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $class . ".php");
    require_once(__DIR__ . '/vendor/autoload.php');
});
//autostart sessions
session_start();
