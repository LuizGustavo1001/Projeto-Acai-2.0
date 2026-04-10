<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "/generalPHP.php";
    require_once "/footerHeader.php";
    require_once "/printStyles.php";

    if (isset($_SESSION["isAdmin"])) { setCookies("adminNotAllowed", "../mannager/admin.php", 0); }

    if(! isset($_SESSION["sendMail"])){ 
        // trying to access the page without token
        header("location: password.php");
        exit();
    }else{
        if(isset($_POST["password"])){
            $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("
                UPDATE user_data 
                SET userPassword = ?
                WHERE userMail = ?
            ") or die("var stmt (newPassword.php): " . $mysqli->errno);

            $stmt->bind_param("ss", $newPassword, $_SESSION["userMail"]);
            $stmt->execute();
            $stmt->close();

            unset($_SESSION["userMail"]);
            setCookies("newPassword", "login.php", 1); 
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php printStyle("universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("general") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Recuperar Senha</title>
</head>

<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/index.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-box">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("key")?>
            </div>

            <div class="title">
                <h1>Recuperação de Senha</h1>
                <p>Insira sua <strong>nova senha</strong> no espaço abaixo.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input">
                    <label for="ipassword">Nova Senha: </label>
                    <input type="password" name="password" id="ipassword" maxlength="50" placeholder="Digite Sua nova Senha Aqui" required>
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
                        <li><a href="../index.php">Página Principal</a></li>
                        <li>/</li>
                        <li><a href="login.php">Página de Login</a></li>
                        <li>/</li>
                        <li><a href="password.php">Recuperação de Senha</a></li>
                    </ul>
                </nav>

                <div class="container-forms">
                    <div class="container-forms-title">
                        <h1>Recuperação de Senha</h1>
                        <p>Insira sua <strong>nova senha</strong> no espaço abaixo para alterá-la.</p>
                    </div>

                    <form method="POST">
                        <div class="form-item regular-input">
                            <label for="ipassword">Nova Senha: </label>
                            <input type="password" name="password" id="ipassword" maxlength="50" placeholder="Digite Sua nova Senha Aqui" required>
                        </div>

                        <div>
                            <button class="regular-button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Enviar
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