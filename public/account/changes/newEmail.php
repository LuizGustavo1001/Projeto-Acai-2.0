<?php 
    include "../../../databaseConnection.php";
    include "../../generalPHP.php";
    include "../../footerHeader.php";
    include "../../printStyles.php";

    // trying to access the page without autentication
    if(! isset($_SESSION["userMail"])){
        header("location: ../login.php");
        exit();
    }

    checkSession("insideAccount");

    if(isset($_POST["email"], $_POST['newEmail'])){
        // update email address

        $sanitizedEmail = filter_var( $_POST["email"], FILTER_SANITIZE_EMAIL);
        
        $stmt = $mysqli->prepare("SELECT userMail FROM user_data WHERE idUser = ?");
        $stmt->bind_param("i", $_SESSION["idUser"]);

        if($stmt->execute()){
            $sanitizedNewEmail = filter_var($_POST["newEmail"], FILTER_SANITIZE_EMAIL);
            $domain = substr(strrchr($sanitizedNewEmail, "@"), 1);
            if(checkdnsrr($domain, "MX")){ // checking if the email domain exists
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();

                if($row["userMail"] != $sanitizedEmail){
                    header("location: newEmail.php?wrongEmail=1");
                    exit();
                }else if($sanitizedNewEmail == $sanitizedMail){
                    header("location: newEmail.php?sameEmail=1");
                    exit();
                }else{
                    // change the email at the Database
                    $updateEmail = $mysqli->prepare("
                        UPDATE user_data
                        SET userMail = ?
                        WHERE idUser = ?
                    ");

                    $updateEmail->bind_param("si", $sanitizedNewEmail, $_SESSION["idUser"]);
                    if($updateEmail->execute()){
                        $updateEmail->close();
                        session_destroy();

                        header("location: ../login.php?newEmail");
                        exit();
                    }else{
                        header("location: ../../errorPage.php");
                        exit();
                    }
                }
            }else{
                header("location: newEmail.php?wrongEmail=1");
                exit();
            }
        }else{
            header("location: ../../errorPage.php");
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

    <link rel="stylesheet" href="<?php printStyle("2", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("2", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("2", "account") ?>">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <style>
        .container-background{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/forgotPassword_lx206b.png) center center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>

    <title>Açaí e Polpas Amazônia - Alterar Email</title>

</head>
<body>
    <?php displayHeader(2)?>

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
                            }else if(isset($_GET["sameEmail"])){
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
 
    <?php displayFooter();?>
</body>
</html>