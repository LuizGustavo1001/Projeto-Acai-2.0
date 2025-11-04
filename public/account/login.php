<?php
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

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

    function login() {
    global $mysqli;
    session_start();

    $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
        header("location: login.php?invalidEmail=1");
        exit();
    }

    $inputPassword = $_POST["password"];

    $getUser = $mysqli->prepare("
        SELECT idUser, userMail, userPassword, userName, userPhone, district, city, street, localNum, referencePoint, state
        FROM user_data
        WHERE userMail = ?
        LIMIT 1
    ");
    $getUser->bind_param("s", $inputEmail);
    $getUser->execute();
    $result = $getUser->get_result();
    $getUser->close();

    if ($result->num_rows === 0) {
        header("location: login.php?errorLogin=1");
        exit();
    }

    $user = $result->fetch_assoc();
    if (!password_verify($inputPassword, $user["userPassword"])) {
        header("location: login.php?errorLogin=1");
        exit();
    }

    // verificar tipo de usuário
    $getUserType = $mysqli->prepare("SELECT idClient FROM client_data WHERE idClient = ?");
    $getUserType->bind_param("i", $user["idUser"]);
    $getUserType->execute();
    $userType = $getUserType->get_result();
    $getUserType->close();

    $uType = $userType->num_rows === 0 ? "admin" : "client";

    // iniciar sessão
    session_regenerate_id(true);
    $_SESSION = [
        "idUser"         => $user["idUser"],
        "userPhone"      => $user["userPhone"],
        "userName"       => $user["userName"],
        "userMail"       => $inputEmail,
        "district"       => $user["district"],
        "localNum"       => $user["localNum"],
        "referencePoint" => $user["referencePoint"],
        "street"         => $user["street"],
        "city"           => $user["city"],
        "state"          => $user["state"],
        "lastActivity"   => time()
    ];

    if ($uType === "client") {
        $currentDate = date("Y-m-d");
        $currentHour = date("H:i:s");

        $newOrder = $mysqli->prepare("INSERT INTO order_data (idClient, orderDate, orderHour) VALUES (?, ?,?)");
        $newOrder->bind_param("iss", $_SESSION["idUser"], $currentDate, $currentHour);

        if ($newOrder->execute()) {
            $_SESSION["idOrder"] = $mysqli->insert_id;
            $newOrder->close();
            verifyOrders();
            header("Location: ../index.php?loginSuccess=1");
            exit();
        } else {
            header("location: ../errorPage.php");
            exit();
        }
    } else {
        $_SESSION["isAdmin"] = true;
        verifyOrders();
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

    <?php displayFavicon()?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "account") ?>">
    

    <style>
        .container-background{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751727177/loginBg_gqxl8c.png) center center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>

    <title>Açaí e Polpas Amazônia - Login</title>
</head>
<body>
    <?php displayHeader(1)?>

    <main>
        <section class="container">
            <div class="left-container">
                <nav class="nav-bar">
                    <ul>
                        <li><a href="../index.php">Página Principal</a></li>
                        <li>/</li>
                        <li><a href="login.php">Página de Login</a></li>
                    </ul>
                </nav>

                <div class="container-forms">
                    <div class="container-forms-title">
                        <h1>Área de Login</h1>
                        <p>Realize seu <strong>login</strong> para <strong>continuar comprando</strong> em nosso site.</p>
                    </div>

                    <form method="POST">
                        <?php 
                            if(isset($_GET["errorLogin"])){
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                                        <p>Erro: Email ou Senha <span>Incorretos</span>, tente novamente ou <span>cadastre-se</span> no link abaixo.</p>
                                    </div>
                                ";
                            }

                            if(isset($_GET["timeout"])){
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>Erro: <span>Sessão Expirada</span>, realize seus Login novamente.</p>
                                    </div>
                                ";
                            }

                            if(isset($_GET["unkUser"])){
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>Erro: <span>Realize seu login</span> para <span>Adicionar Produtos</span> ao carrinho.</p>
                                    </div>
                                ";
                            }

                            if(isset($_GET["registered"])){
                                echo "
                                    <div class =\"successText\">
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z' />
                                        </svg>
                                        <p>Credencias <span>Cadastradas com Sucesso</span>, <span>realize seu Login</span>.</p>
                                    </div>
                                ";
                            }

                            if(isset($_GET["newEmail"])){
                                echo "
                                    <div class = \"successText\">
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z' />
                                        </svg>
                                        <p>Email <span>Alterado com sucesso</span> Realize seu Login.</p>
                                    </div>
                                ";
                            }

                            if(isset($_GET["newPassword"])){
                                echo "
                                    <div class=\"successText\">
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z' />
                                        </svg>
                                        <p>Senha <span>Alterada com sucesso</span> Realize seu Login.</p>
                                    </div>
                                ";
                            }
                        ?>
                        <div class="form-item regular-input">
                            <label for="iemail">Email: </label>
                            <input type="email" name="email" id="iemail" maxlength="50" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="form-item regular-input">
                            <label for="ipassword">Senha: </label>
                            <input type="password" name="password" id="ipassword" maxlength="30" required>
                        </div>

                        <div>
                            <button class="regular-button">
                                Entrar
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                </svg>
                            </button>
                        </div>

                        <div class="form-footer">
                            <a href="password.php">Esqueceu a senha?</a>
                            <a href="register.php">Ainda não está registrado?</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="right-container">
                <div class="container-background"></div>
            </div>
        </section>        
    </main>

    <?php displayFooter();?>
</body>
</html>