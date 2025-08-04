<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";


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

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <link rel="stylesheet" href="../styles/general-style.css">
    <link rel="stylesheet" href="../styles/account-styles.css">

    <style>
        .account-right-div{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/signUpBg_l5rd50.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }   

    </style>


    <title>Açaí Amazônia Ipatinga - Registrar</title>

</head>
<body>
    <section class="account-hero">
        <div class="account-left-div">
            <header>
                <ul class="left-header">
                    <li class="acai-icon">
                        <a href="../index.php">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079683/acai-icon-white_fll4gt.png" class="item-translate" alt="Açaí Icon">
                        </a>
                        <p>Açaí Amazônia Ipatinga</p>
                    </li>
                </ul>

                <ul class="right-header">
                    <li>
                        <a href="../account/account.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            <p><span>Minha</span> Conta</p>
                        </a>
                    </li>
                    <li>
                        <a href="../products/products.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                            </svg>

                            <p>Produtos</p>
                        </a>
                    </li>
                    <li style="display: flex; align-items: top">
                        <a href="../cart/cart.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>

                            <p>Carrinho</p>
                        </a>
                        <?php verifyCartAmount();?>
                    </li>
                </ul>

            </header>

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

            <footer>
                <ul>
                    <li>
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Endereço: 
                        </strong>
                        <a href="#">
                            <p>Endereço Google Maps</p>
                        </a>
                    </li>

                    <li>
                        <strong>
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079684/instagram-icon_wjguqu.png" alt="instagram logo">
                            Instagram: 
                        </strong>
                        <a href="#" target="_blank">
                            <p>@Instagram</p>
                        </a>
                    </li>

                    <li>
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            Telefone: 
                        </strong>
                        <a href="tel:31957401232" target="_blank">
                            <p>Telefone Aqui</p>
                        </a>
                    </li>

                    <li>
                        <strong>
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079686/whatsapp-icon_ketibs.png" alt="whatsapp logo">
                            WhatsApp: 
                        </strong>
                        <a href="#" target="_blank">
                            <p>WhatsApp Aqui</p>
                        </a>
                    </li>

                    <li style="color: rgb(238, 224, 250); opacity: 0.8;">
                        2025 &copy; Açaí Amazônia Ipatinga. <br> Todos os direitos reservados
                    </li>
                    <li>
                        <strong>
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079684/github-icon_xab4gh.png" alt="">
                            Desenvolvido Por:
                        </strong>
                        <a href="github.com/luizgustavo1001" target="_blank">
                            <p>Luiz Gustavo</p>
                        </a>
                    </li>
                </ul>
            </footer>
        </div>
        <div class="account-right-div">

        </div>
    </section>
    
    
</body>
</html>