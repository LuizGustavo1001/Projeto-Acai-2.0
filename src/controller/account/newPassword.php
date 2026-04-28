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

if(! isset($_SESSION["sendMail"])){  // trying to access the page without sending email
    header("location: password.php");
    exit();
}

if(isset($_POST["password"])){ // modify password at Database
    $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $userModel->updateData('passwordUser', $newPassword, $_SESSION['sendIdUser']);

    unset($_SESSION["userMail"]);
    redirectWithMessage("newPassword", "login.php", 1); 
}
