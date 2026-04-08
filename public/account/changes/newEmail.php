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

    if(isset($_POST["email"], $_POST['newEmail'])){
        // update email address
        $sanitizedEmail = filter_var( $_POST["email"], FILTER_SANITIZE_EMAIL);
        
        $stmt = $mysqli->prepare("SELECT userMail FROM user_data WHERE idUser = ?") or die($mysqli->errno);
        $stmt->bind_param("i", $_SESSION["idUser"]);

        $stmt->execute();
        $sanitizedNewEmail = filter_var($_POST["newEmail"], FILTER_SANITIZE_EMAIL);
        $domain = substr(strrchr($sanitizedNewEmail, "@"), 1);

        if(checkdnsrr($domain, "MX")){ // checking if the email domain exists
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if($row["userMail"] != $sanitizedEmail){
                setCookies("wrongMail", "newEmail.php", 0);
            }else if($sanitizedNewEmail == $sanitizedMail){
                setCookies("sameMail", "newEmail.php", 0);
            }else{
                // change the email at the Database
                $updateEmail = $mysqli->prepare("
                    UPDATE user_data
                    SET userMail = ?
                    WHERE idUser = ?
                ");

                $updateEmail->bind_param("si", $sanitizedNewEmail, $_SESSION["idUser"]) or die($mysqli->errno);
                $updateEmail->execute();
                $updateEmail->close();
                session_destroy();

                setCookies("newEmail", "../login.php", 1);
            }
        }else{
            setCookies("wrongMail", "newEmail.php", 0);
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

    <title>Açaí e Polpas Amazônia | Alterar Email</title>
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
                <?php echo getIcon("mail")?>
            </div>

            <div class="title">
                <h1>Alterar Email</h1>
                <p>Insira o <strong>endereço de email vinculado</strong> a sua conta e o <strong>novo email</strong> para realizar a alteração.</p>
            </div>

            <form method="post">
                <div class=" regular-input">
                    <label for="iemail">Email anterior: </label>
                    <input type="email" name="email" id="iemail" maxlength="50" placeholder="exemplo@dominio.com" required>
                </div>

                <div class=" regular-input">
                    <label for="inewEmail">Novo email: </label>
                    <input type="email" name="newEmail" id="inewEmail" maxlength="50" placeholder="exemplo@dominio.com" required>
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
                        <li><a href="newEmail.php">Alterar Email</a></li>
                    </ul>
                </nav>
                <div class="container-forms">
                    <div class="container-forms-title">
                        <h1>Alterar Email</h1>
                        <p>
                            Insira o <strong>endereço de email vinculado</strong> a sua conta e o <strong>novo email</strong> para realizar a alteração.
                        </p>
                    </div>
                    <form method="POST">
                        <?php
                            if(isset($_GET["wrongEmail"])) {
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>
                                            Erro: <strong>Email Anterior Inserido</strong> não está cadastrado. Tente Novamente com outro Endereço de Correspondências Eletrônicas.
                                        </p>
                                    </div>
                                ";
                            }else if(isset($_GET["sameMail"])){
                                echo "
                                    <div class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        <p>
                                            Erro: <strong>Email Anterior</strong> e <strong>Novo Email</strong> inseridos são os mesmos. Tente Novamente com outro Endereço de Correspondências Eletrônicas.
                                        </p>
                                    </div>
                                ";
                            }
                        ?>
                        <div class="form-item regular-input">
                            <label for="iemail">Email anterior: </label>
                            <input type="email" name="email" id="iemail" maxlength="50" placeholder="exemplo@dominio.com" required>
                        </div>
                        <div class="form-item regular-input">
                            <label for="inewEmail">Novo email: </label>
                            <input type="email" name="newEmail" id="inewEmail" maxlength="50" placeholder="exemplo@dominio.com" required>
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