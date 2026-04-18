<?php 
    $config = require __DIR__ . "/../../config/database.php";

    $mysqli = new mysqli(
        $config["host"],
        $config["user"],
        $config["password"],
        $config["database"]
    );

    if ($mysqli->connect_errno) echo "Falha ao conectar ao servidor: {$mysqli->connect_error}";