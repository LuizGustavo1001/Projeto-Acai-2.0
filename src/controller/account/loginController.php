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
    global $userModel, $orderModel;

    $inputEmail = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

    if(!$inputEmail){
        redirectWithMessage("errorLogin", "login.php", 0);
    }

    try{
        // verify if the email exists at database
        $userData = $userModel->getByEmail($inputEmail);

        if(!$userData){
            redirectWithMessage("errorLogin", "login.php", 0);
        }

        //verify if input password match with db password
        $inputPassword = $_POST['password'];
        if(!password_verify($inputPassword, $userData['passwordUser'])){
            redirectWithMessage("errorLogin", "login.php", 0);
        }

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

        verifyOrders();

        // redirect based on userType
        if($userData['typeUser'] == 'admin'){
            $_SESSION["isAdmin"] = true;
            header("location: ../manager/admin.php");
            exit();
        }

        // create new order for customer
        $newOrder = $orderModel->addOrder($_SESSION["idUser"]);
        $_SESSION["idOrder"] = $newOrder['newId'];
        redirectWithMessage("loginSuccess", "../index.php", 1);
    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "login.php", 0);
    } 
}