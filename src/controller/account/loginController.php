<?php 
require_once __DIR__ . '/../mainController.php';

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Order.php';

$userModel  = new User($mysqli);
$orderModel = new Order($mysqli);

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}

if (isset($_SESSION["isAdmin"])){ 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}

if(isset($_SESSION["mailUser"])){ // alerady logged-in
    header("location: account.php");
    exit();
}

if(isset($_POST["email"])){ 
    login(); 
}

function login(){
    global $userModel;
    global $orderModel;

    // verify email domain
    $inputEmail = $_POST["email"] ?? '';
    if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage("invalidEmail", "login.php", 0);
    }

    // verify if the email exists at database
    $user = $userModel->verifyEmail($inputEmail);

    if(! $user['emailExists']){
        redirectWithMessage("errorLogin", "login.php", 0);
    }

    // verify if input password match with db password
    $inputPassword = $_POST['password'] ?? '';
    if(! password_verify($inputPassword, $user['password'])){ 
        redirectWithMessage("errorLogin", "login.php", 0);
    }

    // retrieve user data and start session
    $userData = $userModel->getById($user['userId']);

    session_regenerate_id(true);
    $_SESSION['idUser']             = $userData["idUser"];
    $_SESSION['phoneUser']          = $userData["phoneUser"];
    $_SESSION['nameUser']           = $userData["nameUser"];
    $_SESSION['mailUser']           = $userData["mailUser"];
    $_SESSION['a_district']         = $userData["a_district"];
    $_SESSION['a_numHouse']         = $userData["a_numHouse"];
    $_SESSION['a_referencePoint']   = $userData["a_referencePoint"];
    $_SESSION['a_street']           = $userData["a_street"];
    $_SESSION['a_city']             = $userData["a_city"];
    $_SESSION['a_state']            = $userData["a_state"];
    $_SESSION['lastActivity']       = time();

    // redirect based on userType
    if($userData['typeUser'] == 'customer'){
        // create new order for customer
        $newOrder = $orderModel->addOrder($_SESSION["idUser"]);

        $_SESSION["idOrder"] = $newOrder['newId'];

        redirectWithMessage("loginSuccess", "../index.php", 1);
    }else{ // admin
        $_SESSION["isAdmin"] = true;
        
        header("location: ../manager/admin.php");
        exit();
    }
}