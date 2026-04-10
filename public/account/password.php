<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    if (isset($_SESSION["isAdmin"])) { setCookies("adminNotAllowed", "../mannager/admin.php", 0); }

    if(isset($_SESSION["userMail"])){
        // trying to access the page without token
        header("location: login.php");
        exit();
    }

    // verify if the email input are in the Database
    if(isset($_POST["email"])){
        $sanitizedMail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        $stmt = $mysqli->prepare("SELECT userMail FROM user_data WHERE userMail = ?") or die("var stmt (password.php):" . $mysqli->errno);
        $stmt->bind_param("s", $sanitizedMail);

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        switch($result->num_rows){
            case 0: 
                setCookies("wrongMail", "password.php", 0);
            
            default: // send email
                $_SESSION["sendMail"] = $sanitizedMail;
                header("Location: passwordToken.php");
                exit();
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
                <h1>Esqueceu sua senha?</h1>
                <p>Insira o <strong>endereço de email</strong> vinculado a esta conta para <br> enviarmos um <strong>token de recuperação</strong> para você alterá-la.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input">
                    <label for="iemail">Endereço de Email: </label>
                    <input type="email" name="email" id="iemail" maxlength="50" placeholder="email@exemplo.com" required>
                </div>

                <button class="regular-btn">Enviar Código</button>
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
                        <h1>Esqueceu Sua <br> Senha?</h1>
                        <p>
                            Insira o <strong>endereço de email</strong> vinculado a esta conta para <br> enviarmos um <strong>token de recuperação</strong> para você alterá-la.
                        </p>
                    </div>

                    <form method="POST">
                        <?php
                            if(isset($_GET["wrongMail"])) {
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>
                                            Erro: <span>Email Inserido</span> não está cadastrado. Tente Novamente.
                                        </p>
                                    </div>
                                ";
                            }
                        ?>
                        <div class="form-item regular-input">
                            <label for="iemail">Endereço de Email: </label>
                            <input type="email" name="email" id="iemail" maxlength="50" placeholder="email@exemplo.com" required>
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