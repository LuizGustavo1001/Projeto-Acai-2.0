<?php 
    $host     = "localhost"; 
    $db       = "acai_admin";
    $user     = "root";
    $password = "";

    $mysqli = new mysqli($host, $user, $password, $db);

    if ($mysqli->connect_errno) {
        echo "Falha ao conectar ao servidor: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } 

