<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";


    if( isset($_SESSION["clientMail"])){
        header("location: account.php");
        exit();
        
    }

    if(
        isset($_POST["name"], $_POST["email"], 
        $_POST["phone"], $_POST["street"], $_POST["houseNum"], 
        $_POST["district"], $_POST["city"], $_POST["password"])
    ){
        $result = addUser();
        if(isset($result)){
            switch($result){
                case "emailExists":
                    $registerMensage =  "<p class=\"errorText\">Email <strong>já cadastrado</strong>, tente novamente com <strong>outro Email</strong></p>";
                    break;

                case "userAdd":
                    header("location: login.php?register=1");
                    exit();
            }
        }
    }

    function addUser(){
        global $mysqli;

        $email = $_POST["email"];

        $verifyEmail = $mysqli->prepare("SELECT  clientMail FROM client_data WHERE clientMail = ?");
        $verifyEmail->bind_param("s", $email);

        if($verifyEmail->execute()){
            $resultEmail = $verifyEmail->get_result();
            $verifyEmail->close();

            switch($resultEmail->num_rows){
                case 0: // email não existente -> continuar registro
                    $name      = $_POST['name'];
                    $number    = $_POST["phone"];
                    $street    = $_POST["street"];
                    $houseNum  = $_POST["houseNum"];
                    $district  = $_POST["district"];
                    $city      = $_POST["city"];
                    $reference = isset($_POST["reference"]) ? $_POST["reference"] : null;
                    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $insertData = $mysqli->prepare(
                        "INSERT INTO client_data (clientName, clientMail, clientPassword) VALUES (?, ?, ?)"
                    );
                    $insertData->bind_param("sss", $name, $email, $password);

                    if($insertData->execute()){ // adicionar o cliet_number
                        $idClient = $mysqli->insert_id; // recuperar o ultimo id inserido no Banco de Dados
                        $insertData->close();

                        $insertPhone = $mysqli->prepare(
                            "INSERT INTO client_number (idClient, clientNumber) VALUES (?, ?)"
                        );
                        $insertPhone->bind_param("is", $idClient, $number);

                        if($insertPhone->execute()){
                            $insertPhone->close();

                            $insertAddress = $mysqli->prepare(
                                "INSERT INTO client_address (idClient, district, localNum, referencePoint, street, city) VALUES (?, ?, ?, ?, ?, ?)"
                            );

                            $insertAddress->bind_param("isisss",
                                $idClient, $district, $houseNum, $reference, $street, $city
                            );

                            if($insertAddress->execute()){
                                return "userAdd";
                            }else{
                                header("location: ../errorPage.php");
                            }
                        }else{
                            header("location: ../errorPage.php");
                        }
                    }else{
                        header("location: ../errorPage.php");
                    }
                default: // erro: email já existente
                    return "emailExists";
            }
        }else{
            header("location: ../errorPage.php");
        }        
    }

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php faviconOut(); ?>

    <link rel="stylesheet" href="../styles/general-style.css">
    <link rel="stylesheet" href="../styles/account-styles.css">

    <style>
        .account-right-div{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/signUpBg_l5rd50.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }   

    </style>
    
    <title>Açaí e Polpas Amazônia - Registrar</title>

</head>
<body>
    <section class="account-hero">
        <div class="account-left-div">

            <?php headerOut(1)?>

            <main>
                <section class="account-header">
                    <ul>
                        <li><a href="../index.php">Página Principal</a></li>
                        <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>
                        <li><a href="login.php">Página de Login</a></li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>
                        <li><a href="register.php">Página de Resgistro</a></li>
                    </ul>

                </section>

                <section class="account-forms">
                    <div class="section-header-title">
                        <h1>Área de Registro</h1>
                        <p><strong>Registre-se</strong> para <strong>Continuar Comprando</strong> em nosso site</p>
                    </div>
                    <form action="" method="post">
                        <?php if(isset($registerMensage)) echo $registerMensage; ?>
                        <div class="form-item">
                            <label for="iname">Nome:  <span>*</span></label>
                            <input type="text" name="name" id="iname" maxlength="30" minlength="8" placeholder="Nome Completo" required>
                        </div>

                        <div class="form-item">
                            <label for="iemail">Email:  <span>*</span></label>
                            <input type="email" name="email" id="iemail" maxlength="50" placeholder="email@exemplo.com" required>
                        </div>

                        <div class="form-item">
                            <label for="inumber">Telefone de Contato:  <span>*</span></label>
                            <input type="text" name="phone" id="inumber" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="(XX) 9 8888 8888" required>
                        </div>

                        <div class="form-item">
                            <label for="istreet">Rua:  <span>*</span></label>
                            <input type="text" name="street" id="istreet" maxlength="50" placeholder="Nome da Rua Aqui" required>
                        </div>

                        <div class="form-item">
                            <label for="ihouseNum">Número da Residência:  <span>*</span></label>
                            <input type="number" name="houseNum" id="ihouseNum" max="99999999" placeholder="Número  da Residência Aqui" required>
                        </div>

                        <div class="form-item">
                            <label for="idistrict">Bairro:  <span>*</span></label>
                            <input type="text" name="district" id="idistrict" maxlength="40" placeholder="Nome do Bairro Aqui" required>
                        </div>

                        <div class="form-item">
                            <label for="icity">Cidade:  <span>*</span></label>
                            <input type="text" name="city" id="icity" maxlength="40" placeholder="Nome da Cidade Aqui" required>
                        </div>

                        <div class="form-item">
                            <label for="ireference">Ponto de Referência:</label>
                            <input type="text" name="reference" id="ireference" maxlength="50">
                        </div>

                        <div class="form-item">
                            <label for="ipassword">Senha:  <span>*</span></label>
                            <input type="password" name="password" id="ipassword" maxlength="30" placeholder="• • • • • • • •" required>
                        </div>

                        <div>
                            <button>
                                Cadastrar-se
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>
                            </button>
                        </div>
                        
                    </form>
                </section>
            </main>

            <?php footerOut();?>
        </div>
        <div class="account-right-div">

        </div>
    </section>
    
    
</body>
</html>