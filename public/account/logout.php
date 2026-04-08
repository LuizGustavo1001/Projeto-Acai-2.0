<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    session_start();
    if(isset($_SESSION)){
        session_unset();
        session_destroy();
        header("Location: ../index.php?logout=1");
        exit();
    }