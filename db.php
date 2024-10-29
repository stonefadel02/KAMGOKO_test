<?php

require 'connexion.php';

function getDB() {
    $dbConnection = new PDO("pgsql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}
?>
