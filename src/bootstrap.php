<?php 
require_once __DIR__ . '/database/connection.php';
require_once __DIR__ . '/../config/composer/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

date_default_timezone_set('America/Sao_Paulo');

if(! isset($_SESSION)){ 
    session_start(); 
}