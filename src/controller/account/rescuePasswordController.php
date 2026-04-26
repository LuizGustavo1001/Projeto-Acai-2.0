<?php 
require_once __DIR__ . '/../mainController.php';

if (isset($_SESSION["isAdmin"])) { 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}
if(! isset($_SESSION["passwordToken"])){
    header("location: password.php");
    exit();
}

// verify if the token is the same sended to the email
if(isset($_POST["token"])){
    if($_SESSION["passwordToken"] == $_POST["token"]){
        unset($_SESSION["passwordToken"]);
        header("location: newPassword.php");
        exit();
    }else{ // wrong token
        redirectWithMessage("wrongToken", "rescuePassword.php", 0); 
    }
}