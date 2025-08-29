<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";

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
                    verifyOrders();
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

    <?php faviconOut(); ?>

    <link rel="stylesheet" href="../CSS/general-style.css">
    <link rel="stylesheet" href="../CSS/account-styles.css">

    <style>
        .account-right-div{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751727177/loginBg_gqxl8c.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }   

    </style>

    <title>Açaí e Polpas Amazônia - Login</title>
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
                                echo "<p class=\"successText\">Senha <strong>Alterada com sucesso</strong> <br> Realize seu Login</p>";
                            }else if(isset($_GET["newEmail"])){
                                echo "<p class = \"successText\">Email <strong>Alterado com sucesso</strong> <br> Realize seu Login</p>";
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

            <?php footerOut();?>

        </div>
        <div class="account-right-div">
        
        </div>
    </section>
    

    
    
</body>
</html>