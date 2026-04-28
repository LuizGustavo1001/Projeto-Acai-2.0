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

if(isset($_POST["email"], $_POST['newEmail'])){
    // verify input email
    $sanitizedEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    try{
        $emailExists = $userModel->verifyEmail($sanitizedEmail);

        if(!$emailExists){
            redirectWithMessage("wrongMail", "newEmail.php", 0);
        }

        // update email value at database
        $sanitizedNewEmail = filter_var($_POST['newEmail'], FILTER_SANITIZE_EMAIL);

        if($sanitizedEmail == $sanitizedNewEmail){
            redirectWithMessage("sameMail", "newEmail.php", 0);
        }

        // change at database
        $userModel->updateData("mailUser", $sanitizedNewEmail, $_SESSION['idUser']);

        session_destroy();
        redirectWithMessage("newEmail", "../login.php", 1);

    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "newEmail.php", 0);
    }
}
