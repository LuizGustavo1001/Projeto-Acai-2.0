<?php 
    $host     = "container_database"; 
    $db       = "acai_admin";
    $user     = "user";
    $password = "1111";

    $mysqli = new mysqli($host, $user, $password, $db);

    if ($mysqli->connect_errno) echo "Falha ao conectar ao servidor: {$mysqli->connect_error}";