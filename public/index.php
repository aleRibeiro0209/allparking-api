<?php

use App\Core\Router;

require __DIR__ . '/../vendor/autoload.php';

    date_default_timezone_set("America/Sao_Paulo");

    header('Content-type: application/json');
    
    new Router();