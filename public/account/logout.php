<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    session_start();
    if(isset($_SESSION)){
        session_destroy();
        header("Location: ../index.php");
        exit();
    }