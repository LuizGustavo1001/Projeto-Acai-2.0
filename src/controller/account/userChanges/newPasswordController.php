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
    // verify input password
    $sanitizedPassword = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

    $verifyPassword = $userModel->verifyEmail($_SESSION['mailUser']);

    if($verifyPassword['emailExists']){
        if(password_verify($sanitizedPassword, $verifyPassword['password'])){
            // verify password diff
            $sanitizedNewPassword = htmlspecialchars($_POST['newPassword'], ENT_QUOTES, 'UTF-8');

            if($sanitizedPassword == $sanitizedNewPassword){
                redirectWithMessage("sameP", "newPassword.php", 0);
            }else{ // encrypt new password and update at database
                $hashedPassword = password_hash($sanitizedNewPassword, PASSWORD_DEFAULT);
                $updatePassword = $userModel->updateData('passwordUser', $hashedPassword, $_SESSION['idUser']);

                if($updatePassword){ // logout
                    session_destroy();
                    redirectWithMessage("newPassword", "../login.php", 1);
                }
            }
        }else{
            redirectWithMessage("wrongP", "newPassword.php", 0);
        }
    }
}