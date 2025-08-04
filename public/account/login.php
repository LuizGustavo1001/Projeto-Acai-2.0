<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";

    if(isset($_SESSION["clientMail"])){
        header("location: account.php");
        exit();
        
    }

    // Processar login ANTES de qualquer saída HTML
    if(isset($_POST["email"], $_POST["password"])){
        $result = login();
        if(isset($result)){
            switch($result){
                case "errorLogin":
                    $errorLogin = "
                            <p class=\"errorText\">
                                Erro: Email ou Senha <strong>Incorretos</strong>, tente novamente ou <strong>cadastre-se</strong> no link abaixo
                            </p>";
                    break;
                case "successLogin":
                    header("Location: ../index.php?loginSuccess=1");
                    exit;
                case "errorDB":
                    $errorLogin = "<small>Erro ao executar a query</small>";
                    break;
            }
        }
    }

    if(isset($_GET["timeout"])){
        $errorLogin = "<p class=\"errorText\">Erro: <strong>Sessão Expirada</strong>, realize seus Login novamente</p>";
    }

    if(isset($_GET["unkUser"])){
        $errorLogin = "<p class=\"errorText\">Erro: <strong>Realize seu login</strong> para <strong>Adicionar Produtos</strong> ao carrinho</p>";
    }

    function login(){
        global $mysqli;

        $email = filter_var( $_POST["email"], FILTER_SANITIZE_EMAIL);

        $query = $mysqli->prepare("SELECT * FROM client_data WHERE clientMail = ?");
        $query->bind_param("s", $email);
        if($query->execute()){
            $result = $query->get_result();
            $query->close();
            
            $emailExist = $result->num_rows;

            switch($emailExist){
                case 0: // email não existente no Banco de Dados
                    return "errorLogin";
                default: // continuar login
                    $password = $_POST["password"];

                    $userData = $result->fetch_assoc();
                    $storedPassword = $userData["clientPassword"];

                    if(password_verify($password, $storedPassword)){
                        // senha digitada e a mesma armazenada no Banco de Dados ao descriptografar

                        $rescueData = $mysqli->prepare(
                            "SELECT DISTINCT d.idClient, d.clientName, n.clientNumber, 
                                                    a.district, a.localNum, a.referencePoint, a.street, a.city 
                                    FROM client_data as d 
                                        JOIN client_number as n ON d.idClient = n.idClient
                                        JOIN client_address AS a ON d.idClient = a.idClient
                                    WHERE d.clientMail = ?
                        ");

                        $rescueData->bind_param("s", $email);

                        if($rescueData->execute()){
                            $resultUser = $rescueData->get_result();
                            if($user = $resultUser->fetch_assoc()){
                                $_SESSION["idClient"]       = $user["idClient"];
                                $_SESSION["clientNumber"]   = $user["clientNumber"];
                                $_SESSION["clientName"]     = $user["clientName"];
                                $_SESSION["clientMail"]     = $email;
                                $_SESSION["district"]       = $user["district"];
                                $_SESSION["localNum"]       = $user["localNum"];
                                $_SESSION["referencePoint"] = $user["referencePoint"];
                                $_SESSION["street"]         = $user["street"];
                                $_SESSION["city"]           = $user["city"];
                                $_SESSION['lastActivity']   = time(); // marca o início da sessão

                                // Insere novo pedido sem definir idOrder (auto_increment)
                                
                                date_default_timezone_set('America/Sao_Paulo');
                                $currentDate = date("Y-m-d");
                                $currentHour = date("H:i:s");

                                $newOrder = $mysqli->prepare("INSERT INTO client_order (idClient, orderDate, orderHour) VALUES (?, ?, ?);");
                                $newOrder->bind_param("iss", $_SESSION["idClient"], $currentDate,  $currentHour);
                                if($newOrder->execute()){
                                    // Recupera o idOrder gerado
                                    $_SESSION["idOrder"] = $mysqli->insert_id;
                                    $newOrder->close();

                                    //verifyOrders();
                                    
                                    return "successLogin";
                                }else{
                                    header("location: ../errorPage.php");
                                    exit();
                                } 
                            }else{
                                return "errorDB";
                            }
                        }else{
                            header("location: ../errorPage.php");
                            exit();
                        }
                    }else{
                        return "errorLogin";
                    }
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
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751727177/loginBg_gqxl8c.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }   

    </style>

    <title>Açaí Amazônia Ipatinga - Login</title>
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
                            <p>Minha Conta</p>
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
                    </ul>
                </section>
        
                <section class="account-forms">
                    <div class="section-header-title">
                        <h1>Área de Login</h1>
                        <p>Realize seu <strong>Login</strong> para <strong>Continuar Comprando</strong> em nosso site</p>
                    </div>
                    <form action="" method="post">
                        <?php if (isset($errorLogin)) echo $errorLogin; ?>
                        <?php
                            if(isset($_GET["newPassword"])){
                                echo "<p class=\"successText\">Senha Alterada com sucesso</p>";
                            }
                            if(isset($_GET["register"])) {
                                echo "
                                <p class =\"successText\">
                                    Credencias <strong>Cadastradas com Sucesso</strong>, <strong>realize seu Login</strong>
                                </p>";
                            }
                        ?>
                        
                        <div class="form-item">
                            <label for="iemail">Email: </label>
                            <input type="email" name="email" id="iemail" maxlength="50" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="form-item">
                            <label for="ipassword">Senha: </label>
                            <input type="password" name="password" id="ipassword" maxlength="30" required>
                        </div>

                        <div>
                            <button>
                                Entrar
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                </svg>
                            </button>
                        </div>
                        <div class="account-main-footer">
                            <a href="password.php">Esqueceu a senha?</a>
                            <a href="register.php">Ainda não está registrado?</a>
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