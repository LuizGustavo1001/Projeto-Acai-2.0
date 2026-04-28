<?php
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../service/mailService.php';

if (isset($_SESSION["isAdmin"])){ 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}

if(isset($_SESSION["mailUser"])){
    header("location: account.php");
    exit();
}

if(isset($_GET["userAdd"])) { 
    redirectWithMessage("registered", "login.php", 1);
}

use Services\MailService;

$userModel      = new User($mysqli);
$mailService    = new MailService();

if(isset($_POST["name"], $_POST["email"], $_POST["phone"],
    $_POST["street"], $_POST["houseNum"] ,$_POST["district"],
    $_POST["city"], $_POST["reference"], $_POST["password"])){
    addUser();
}

function addUser(){
    global $userModel, $mailService;

    // verify if input mail exists at database
    $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    try{
        $emailExists = $userModel->verifyEmail($inputEmail);

        if($emailExists){
            redirectWithMessage("emailExists", "register.php", 0);
        }

        $name       = $_POST['name'];
        $phone      = $_POST['phone'];
        $street     = $_POST['street'];
        $houseNum   = $_POST['houseNum'];
        $district   = $_POST['district'];
        $city       = $_POST['city'];
        $reference  = ($_POST['reference'] == null) ? null : $_POST['reference'];
        $state      = $_POST['state'];
        $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // insert new user
        $newId = $userModel->addUser(
            $name, $inputEmail, $phone,
            $password, $district, $street,
            $reference, $houseNum, $city, $state
        );

        // create linked customer to newId
        $userModel->addCustomer($newId);

        verifyOrders();

        $mailService->send(
            'cliente@email.com',
            'Cadastro em Açaí e Polpas Amazônia',
            '
            <div style="text-align: center; font-family: sans-serif">
                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1775011739/acai_vooaxn.png" alt="Brand logo" style="width: 75px;">
                <h1>Açaí e Polpas Amazônia</h1>
                <p>Você criou uma conta em <a href="#">Açaí e Polpas Amazônia</a></p>
                <p>Atenciosamente, <strong>Equipe Açaí e Polpas Amazônia</strong></p>
                <p><small>Este é um email automático, não responda.</small></p>
            </div>
            '
        );

        redirectWithMessage("registered", "login.php", 1);
    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "register.php", 0);
    }

    
}