<?php 
require_once __DIR__ . '/../mainController.php';

require_once __DIR__ . '/../../models/User.php';

if (isset($_SESSION["isAdmin"])) { 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}
if(! isset($_SESSION["passwordToken"])){
    header("location: password.php");
    exit();
}

$userModel = new User($mysqli);

// verify if the token is the same sended to the email
if(isset($_POST["token"])){
    if($_SESSION['passwordToken'] != $_POST['token']){ // wrong token
        redirectWithMessage("wrongToken", "rescuePassword.php", 0); 
    }

    // fetch idUser with the email 
    try{
        $_SESSION['sendIdUser'] = $userModel->getByEmail($_SESSION["sendMail"]);

        unset($_SESSION["passwordToken"]);
        header("location: newPassword.php");
        exit();

    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "rescuePassword.php", 0);
    }
}