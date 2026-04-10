<?php 
    require_once __DIR__ . '/../../../databaseConnection.php';
    require_once "../../generalPHP.php";
    require_once "../../footerHeader.php";
    require_once "../../printStyles.php";

    // trying to access the page without autentication
    if(! isset($_SESSION["userMail"])){
        header("location: ../login.php");
        exit();
    }

    checkSession();

    if(isset($_POST['password'], $_POST['newPassword'])){
        // update the password
        $sanitizedPassword = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

        $stmt = $mysqli->prepare("SELECT userPassword FROM user_data WHERE idUser = ?") or die("var stmt (newPassword.php): " . $mysqli->errno);
        $stmt->bind_param("i", $_SESSION["idUser"]);

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $result = $result->fetch_assoc();

        if(! password_verify($sanitizedPassword, $result["userPassword"])){
            setCookies("wrongP", "newPassword.php", 0);

        }else if($_POST["password"] == $_POST["newPassword"]){
            setCookies("sameP", "newPassword.php", 0);
        }else{
            $hashedPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            
            $updatePassword = $mysqli->prepare("
                UPDATE user_data
                SET userPassword = ?
                WHERE idUser = ?
            ");

            $updatePassword->bind_param("si", $hashedPassword, $_SESSION["idUser"]) or die("var updatePassword (newPassword.php): " . $mysqli->errno);
            $updatePassword->execute();
            $updatePassword->close();
            session_destroy();

            setCookies("newPassword", "../login.php", 0);
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Alterar Senha</title>
</head>

<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/account/account.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-box">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("key")?>
            </div>

            <div class="title">
                <h1>Alterar Senha</h1>
                <p>Insira a <strong>senha vinculado</strong> a sua conta e a <strong>nova senha</strong> para realizar a alteração.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input">
                    <label for="ipassword">Senha anterior: </label>
                    <input type="password" name="password" id="ipassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                </div>
                <div class="regular-input">
                    <label for="inewPassword">Nova senha: </label>
                    <input type="password" name="newPassword" id="inewPassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                </div>

                <button class="regular-btn">Enviar</button>
            </form>
        </div>
    </main>

    <script src="/js/script.js"></script>
    <!--
    <main>
        <section class="container">
            <div class="left-container">
                <nav class="nav-bar">
                    <ul>
                        <li><a href="../../index.php">Página Principal</a></li>
                        <li>/</li>
                        <li><a href="../account.php">Página do Usuário</a></li>
                        <li>/</li>
                        <li><a href="newPassword.php">Alterar Senha</a></li>
                    </ul>
                </nav>
                <div class="container-forms">
                    <div class="container-forms-title">
                        <h1>Alterar Senha</h1>
                        <p>
                            Insira a <strong>senha anteriormente vinculada</strong> a esta conta e a <strong>nova senha desejada</strong> para alterá-la.
                        </p>
                    </div>
                    <form method="POST">
                        <?php
                            if(isset($_GET["wrongP"])) {
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>
                                            Erro: <strong>Senha Anterior Inserida</strong> não está cadastrada. Tente Novamente com outra Senha.
                                        </p>
                                    </div>
                                ";
                            }else if(isset($_GET["sameP"])){
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>
                                            Erro: <strong>Senha Anterior</strong> e <strong>Nova Senha</strong> inseridas são as mesmas. Tente Novamente com outra Senha.
                                        </p>
                                    </div>
                                ";
                            }
                        ?>
                        <div class="form-item regular-input">
                            <label for="ipassword">Senha anterior: </label>
                            <input type="password" name="password" id="ipassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                        </div>
                        <div class="form-item regular-input">
                            <label for="inewPassword">Nova senha: </label>
                            <input type="password" name="newPassword" id="inewPassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                        </div>
                        <div>
                            <button class="regular-button">
                                Enviar Código
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="right-container">
                <div class="container-background"></div>
            </div>
        </section>
    </main>
-->
</body>
</html>