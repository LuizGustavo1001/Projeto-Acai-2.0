<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";

    $currentDate = date("Y-m-d");
    $currentHour = date("H:i:s");

    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();

    }

    if(isset($_SESSION["clientMail"])){
        header("location: account.php");
        exit();
        
    }

    if(isset($_POST["email"])){
        login();
    }

    function login(){
        global $mysqli;

        $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        if(! filter_var($inputEmail, FILTER_VALIDATE_EMAIL)){
            header("location: login.php?invalidEmail=1");
            exit();
        }

        $inputPassword = $_POST["password"];
        $storedPassword = null;
        $userType = null;
        $userId = null;

        // tentar buscar como admin
        $query = $mysqli->prepare("
            SELECT idAdmin AS idUser, adminMail AS userMail, adminPassword AS userPassword
            FROM admin_data 
            WHERE adminMail = ?
        ");
        $query->bind_param("s", $inputEmail);
        $query->execute();
        $result = $query->get_result();

        if($row = $result->fetch_assoc()){
            $userType       = "admin";
            $userId         = $row["idUser"];
            $storedPassword = $row["userPassword"];
        }
        $query->close();

        // tentar como cliente
        if(! $userType){ 
            $query = $mysqli->prepare("
                SELECT idClient AS idUser, clientMail AS userMail, clientPassword AS userPassword
                FROM client_data
                WHERE clientMail = ?;
            ");
            $query->bind_param("s", $inputEmail);
            $query->execute();
            $result = $query->get_result();

            if($row = $result->fetch_assoc()){
                $userType       = "client";
                $userId         = $row["idUser"];
                $storedPassword = $row["userPassword"];
            }
            $query->close();
        }

        // nenhum usuário foi encontrado
        if(! $userType){ 
            header("location: login.php?errorLogin=1");
            exit();
        }

        // verificar senha
        if(!password_verify($inputPassword, $storedPassword)){
            header("location: login.php?errorLogin=1");
            exit();
        }

        // buscar dados completos do usuário
        match($userType){
            "admin" =>  $rescueData = $mysqli->prepare("
                            SELECT ad.idAdmin AS idUser, ad.adminName AS userName, ad.adminPhone AS userPhone, 
                                    a.district, a.localNum, a.referencePoint, a.street, a.city, a.state 
                            FROM admin_data AS ad
                                JOIN admin_address AS a ON ad.idAdmin = a.idAdmin
                            WHERE ad.adminMail = ?"
                        ),
            default => $rescueData = $mysqli->prepare("
                            SELECT d.idClient as idUser, d.clientName AS userName, d.clientPhone AS userPhone, 
                                    a.district, a.city, a.street, a.localNum, a.referencePoint, a.state
                            FROM client_data AS d 
                                JOIN client_address AS a ON d.idClient = a.idClient
                            WHERE d.clientMail = ?"
                    ),

        };
        $rescueData->bind_param("s", $inputEmail);
        if(! $rescueData->execute()){
            die("Erro SQL: " . $mysqli->error);
        }

        $result = $rescueData->get_result();
        $user = $result->fetch_assoc();
        $rescueData->close();

        if(! $user){
            header("location: ../errorPage.php");
            exit();
        }

        // criando sessão
        $_SESSION["idUser"]         = $user["idUser"];
        $_SESSION["userPhone"]      = $user["userPhone"];
        $_SESSION["userName"]       = $user["userName"];
        $_SESSION["userMail"]       = $inputEmail;
        $_SESSION["district"]       = $user["district"];
        $_SESSION["localNum"]       = $user["localNum"];
        $_SESSION["referencePoint"] = $user["referencePoint"];
        $_SESSION["street"]         = $user["street"];
        $_SESSION["city"]           = $user["city"];
        $_SESSION["state"]          = $user["state"];
        $_SESSION['lastActivity']   = time(); // marca o início da sessão

        // criando pedido, caso seja um cliente
        if($userType == "client"){
            $currentDate = date("Y-m-d");
            $currentHour = date("H:i:s");

            $newOrder = $mysqli->prepare("INSERT INTO order_data (idClient, orderDate, orderHour) VALUES (?, ?, ?);");
            $newOrder->bind_param("iss", $_SESSION["idUser"], $currentDate,  $currentHour);

            if($newOrder->execute()){
                $_SESSION["idOrder"] = $mysqli->insert_id;
                $newOrder->close();

                verifyOrders();
                header("Location: ../index.php?loginSuccess=1");
                exit();
            }else{
                header("location: ../errorPage.php");
                exit();
            }
        }else{
            $_SESSION["isAdmin"]    = true;
            header("location: ../mannager/admin.php");
            exit();
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

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

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
                    <form method="post">
                        <?php 
                            if(isset($_GET["errorLogin"])){
                                echo "
                                    <p class=\"errorText\">
                                        Erro: Email ou Senha <strong>Incorretos</strong>, tente novamente ou <strong>cadastre-se</strong> no link abaixo
                                    </p>
                                ";

                            }

                            if(isset($_GET["timeout"])){
                                echo "
                                    <p class=\"errorText\">
                                        Erro: <strong>Sessão Expirada</strong>, realize seus Login novamente
                                    </p>
                                ";

                            }

                            if(isset($_GET["unkUser"])){
                                echo "
                                    <p class=\"errorText\">
                                        Erro: <strong>Realize seu login</strong> para <strong>Adicionar Produtos</strong> ao carrinho
                                    </p>
                                ";

                            }

                            if(isset($_GET["registered"])){
                                echo "
                                    <p class =\"successText\">
                                        Credencias <strong>Cadastradas com Sucesso</strong>, <strong>realize seu Login</strong>
                                    </p>
                                ";
                            }

                            if(isset($_GET["newEmail"])){
                                echo "
                                    <p class = \"successText\">
                                        Email <strong>Alterado com sucesso</strong> 
                                        <br> 
                                        Realize seu Login
                                    </p>
                                ";

                            }

                            if(isset($_GET["newPassword"])){
                                echo "
                                    <p class=\"successText\">
                                        Senha <strong>Alterada com sucesso</strong> 
                                        <br> 
                                        Realize seu Login
                                    </p>
                                ";
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