<?php 
require_once __DIR__ . '/../../mainController.php';
require_once __DIR__ . '/../../../models/User.php';

// trying to access the page without autentication
if(!isset($_SESSION["mailUser"])){
    header("location: ../login.php");
    exit();
}

checkSessionStatus();

$userModel = new User($mysqli);

if(isset($_POST['password'], $_POST['newPassword'])){
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['newPassword'];

    try{
        $hashedPassword = $userModel->getPasswordById($_SESSION['idUser']);

        if(! password_verify($currentPassword, $hashedPassword)){
            redirectWithMessage("wrongP", "newPassword.php", 0);
        }

        if($currentPassword == $newPassword){
            redirectWithMessage("sameP", "newPassword.php", 0);
        }

        // update password
        $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $userModel->updateData('passwordUser', $newHashed, $_SESSION['idUser']);

        session_destroy();
        redirectWithMessage("newPassword", "../login.php", 1);

    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "newPassword.php", 0);
    }
}