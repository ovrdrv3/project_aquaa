<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);

    // Turn off all error reporting
    // error_reporting(0);
    // Report all PHP errors
    error_reporting(-1);

 ?>
