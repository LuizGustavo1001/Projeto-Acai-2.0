<?php 
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../models/User.php';

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}

if (isset($_SESSION["isAdmin"])){ 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}

 // trying to access the page without being logged-in
if(isset($_SESSION["mailUser"])){
    header("location: login.php");
    exit();
}

$userModel = new User($mysqli);

// verify if the email input exists on Database
if(isset($_POST['email'])){
    try{
        $sanitizedMail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        $emailExists = $userModel->verifyEmail($sanitizedMail);

        if(!$emailExists){
            redirectWithMessage("wrongMail", "password.php", 0);
        }

        $_SESSION["sendMail"] = $sanitizedMail;

        header("Location: passwordToken.php");
        exit();
    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "passwordController.php", 0);
    }
}
