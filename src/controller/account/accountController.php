<?php 
require_once __DIR__ . '/../mainController.php';

require_once __DIR__ . '/../../models/User.php';
$userModel  = new User($mysqli);

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}

if(!isset($_SESSION["mailUser"])){
    header("location: login.php");
    exit();
}

if(isset($_GET["logout"])){ 
    logout(); 
}

if($_SERVER["REQUEST_METHOD"] === "POST"){ // change attribute value
    changeAttributesValue(); 
}

if (isset($_SESSION["isAdmin"])){ 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}

checkSessionStatus();

function changeAttributesValue(){
    global $userModel;

    $allowedInputs = [
        "nameUser", "phoneUser", "a_district", "a_numHouse", 
        "a_referencePoint", "a_street", "a_city", "a_state"
    ];

    foreach($allowedInputs as $inputName){
        if(isset($_POST[$inputName])){
            $newValue = trim($_POST[$inputName]);

            if($newValue != ''){ // update at DB
                if($inputName == "referencePoint" || $inputName == "state"){ // special inputs -> options or can be null
                    $userModel->updateData($inputName, $newValue, $_SESSION['idUser']);
                }else{ // regular input
                    if($newValue != $_SESSION[$inputName]){
                        $userModel->updateData($inputName, $newValue, $_SESSION['idUser']);
                    }
                }
                // update field to new value
                $_SESSION[$inputName] = $newValue;
            }
        }
    }
}