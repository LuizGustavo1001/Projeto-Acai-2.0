<?php 
require_once __DIR__ . '/../mainController.php';

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Order.php';

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

$userModel  = new User($mysqli);
$orderModel = new Order($mysqli);

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
    $_SESSION['idUser']             = htmlspecialchars($userData["idUser"], ENT_QUOTES, 'UTF-8');
    $_SESSION['phoneUser']          = htmlspecialchars($userData["phoneUser"], ENT_QUOTES, 'UTF-8');
    $_SESSION['nameUser']           = htmlspecialchars($userData["nameUser"], ENT_QUOTES, 'UTF-8');
    $_SESSION['mailUser']           = htmlspecialchars($userData["mailUser"], ENT_QUOTES, 'UTF-8');
    $_SESSION['a_district']         = htmlspecialchars($userData["a_district"], ENT_QUOTES, 'UTF-8');
    $_SESSION['a_numHouse']         = htmlspecialchars($userData["a_numHouse"], ENT_QUOTES, 'UTF-8');
    $_SESSION['a_referencePoint']   = htmlspecialchars($userData["a_referencePoint"], ENT_QUOTES, 'UTF-8');
    $_SESSION['a_street']           = htmlspecialchars($userData["a_street"], ENT_QUOTES, 'UTF-8');
    $_SESSION['a_city']             = htmlspecialchars($userData["a_city"], ENT_QUOTES, 'UTF-8');
    $_SESSION['a_state']            = htmlspecialchars($userData["a_state"], ENT_QUOTES, 'UTF-8');
    $_SESSION['lastActivity']       = time();

    verifyOrders();

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