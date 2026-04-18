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

    $verifyEmail = $userModel->verifyEmail($sanitizedEmail);

    if($verifyEmail['emailExists']){
        // verify new email domain
        $sanitizedNewEmail = filter_var($_POST['newEmail'], FILTER_SANITIZE_EMAIL);

        $domain = substr(strrchr($sanitizedNewEmail, "@"), 1);
        if(checkdnsrr($domain, "MX")){ 
            if($sanitizedEmail == $sanitizedNewEmail){
                redirectWithMessage("sameMail", "newEmail.php", 0);
            }else{ // change at database
                $updateMail = $userModel->updateData("mailUser", $sanitizedNewEmail, $_SESSION['idUser']);

                if($updateMail){ // logout
                    session_destroy();
                    redirectWithMessage("newEmail", "../login.php", 1);
                }
            }
        }
    }else{
        redirectWithMessage("wrongMail", "newEmail.php", 0);
    }

}
