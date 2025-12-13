<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";

session_start();

if (file_exists("../config.php")) {
    require_once "../config.php";
} else {
    require_once "../config.dev.php";
}

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require PATH . '/src/' . $class . '.php';
});
require_once PATH . "/src/controller/routerController.php";
