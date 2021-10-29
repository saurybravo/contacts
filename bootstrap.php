<?php

const ROOT_PATH = __DIR__ . "/";
const CONFIG_PATH = ROOT_PATH . "/config/";
const APP_PATH = ROOT_PATH . "app/";
const MODEL_PATH = APP_PATH . "/Models/";

const CONTROLLER_NAMESPACE = 'App\\Controllers\\';
const CONTROLLER_FOLDER = 'Controllers' . DIRECTORY_SEPARATOR;

//  Configuration Files
require CONFIG_PATH . 'database.php';

//  Configuration Files
require MODEL_PATH . 'Model.php';
require MODEL_PATH . 'Contact.php';

//  Controllers Base Class
require APP_PATH . 'Controllers/Controller.php';

//  Routes
$routes = require __DIR__ . '/routes.php';