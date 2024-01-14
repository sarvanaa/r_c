<?php

header("Access-Control-Allow-Origin: *"); // Allows all origins
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allowed request methods
header("Access-Control-Allow-Headers: Content-Type:application/json");



define('DB_NAME', 'rcdb');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');