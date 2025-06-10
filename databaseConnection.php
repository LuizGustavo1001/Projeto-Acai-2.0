<?php 
    $host = "localhost";
    $db = "projeto_acai";
    $usuario = "root";
    $senha = "";

    $mysqli = new mysqli($host, $usuario, $senha, $db);

    if($mysqli->connect_errno){ // numero do erro 
        echo "Falha ao conectar ao servidor: (" .$mysqli->connect_errno. ")" . $mysqli->connect_error;
    }

?>


