<?php 
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../models/User.php';

$userModel = new User($mysqli);

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}

if(isset($_SESSION["isAdmin"])){ 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}

// trying to access the page without token
if(! isset($_SESSION["sendMail"])){ 
    header("location: password.php");
    exit();
}else{
    if(isset($_POST["password"])){ // modify password in Database
        $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $updateData = $userModel->updateData('passwordUser', $newPassword, $_SESSION['sendIdUser']);

        if($updateData){
            unset($_SESSION["userMail"]);
            redirectWithMessage("newPassword", "login.php", 1); 
        }
    }
}