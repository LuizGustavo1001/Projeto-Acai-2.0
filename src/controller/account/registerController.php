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
    global $userModel;
    global $mailService;
    // verify if input mail exists at database
    $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $verifyEmail = $userModel->verifyEmail($inputEmail);

    if(! $verifyEmail['emailExists']){ // not in the database (can be add)
        // verify email domain
        $domain = substr(strrchr($inputEmail, "@"), 1);
        if(checkdnsrr($domain, "MX")){
            $name       = mb_convert_case($_POST['name'], MB_CASE_TITLE, "UTF-8");
            $phone      = htmlspecialchars($_POST["phone"], ENT_QUOTES, 'UTF-8');
            $street     = htmlspecialchars($_POST["street"], ENT_QUOTES, 'UTF-8');
            $houseNum   = htmlspecialchars($_POST["houseNum"], ENT_QUOTES, 'UTF-8');
            $district   = mb_convert_case($_POST["district"], MB_CASE_TITLE, "UTF-8");
            $city       = mb_convert_case($_POST["city"], MB_CASE_TITLE, "UTF-8");
            $reference  = htmlspecialchars($_POST["reference"], ENT_QUOTES, 'UTF-8') ?? null;
            $state      = htmlspecialchars($_POST["state"], ENT_QUOTES, 'UTF-8');
            $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // insert new user
            $createUser = $userModel->addUser($name, $inputEmail, $phone, $password, $district, $street, $reference, $houseNum, $city, $state);

            if($createUser['insert']){ // insert new customer
                $newId = $createUser['idUser'];
                $createCustomer = $userModel->addCustomer($newId);

                if($createCustomer){ // redirect + e-mail (test)
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
                }
            }
        }else{
            redirectWithMessage("invalidDomain", "register.php", 0); 
        }
    }else{
        redirectWithMessage("emailExists", "register.php", 0);
    }
}