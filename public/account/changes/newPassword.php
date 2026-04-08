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

        $stmt = $mysqli->prepare("SELECT userPassword FROM user_data WHERE idUser = ?") or die($mysqli->errno);
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

            $updatePassword->bind_param("si", $hashedPassword, $_SESSION["idUser"]) or die($mysqli->errno);
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
        <div class="back-button" onclick="window.location.href = '/index.php'">
            <svg viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.875 12.5C21.875 17.6777 17.6777 21.875 12.5 21.875C7.32233 21.875 3.125 17.6777 3.125 12.5C3.125 7.32233 7.32233 3.125 12.5 3.125C17.6777 3.125 21.875 7.32233 21.875 12.5Z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M8.33398 12.5H16.6673" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M11.4586 9.375L8.42427 12.4094C8.3742 12.4594 8.3742 12.5406 8.42427 12.5906L11.4586 15.625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-box">
                <svg class='icon-bg' viewBox='0 0 250 250' fill='none' xmlns='http://www.w3.org/2000/svg' aria-label='icon-bg'>
                    <path d='M77.4903 246.66C83.6098 244.613 89.1887 241.685 95.3897 239.168C109.056 233.67 124.206 233.131 138.226 237.643C149.445 241.389 160.021 246.23 171.75 248.205C186.763 250.743 203.204 251.409 217.442 244.531C232.241 237.366 243.491 222.955 247.948 207.204C254.73 183.223 242.736 159.969 235.709 137.606C231.313 123.655 236.025 112.673 241.43 99.9103C248.682 82.8076 253.047 60.1883 247.387 42.0928C244.408 32.8762 239.299 24.4977 232.476 17.6421C225.653 10.7864 217.311 5.64808 208.13 2.6471C191.037 -2.68533 169.537 1.01974 153.188 7.58037C146.61 10.221 140.449 14.0079 133.504 15.6455C126.055 17.3106 118.276 16.6279 111.229 13.6907C89.923 5.04209 64.3335 -4.83468 41.0286 2.6471C26.3113 7.37567 13.899 18.3066 6.68828 31.9294C-2.14412 48.4896 -1.17521 67.2197 3.53677 84.916C7.11665 98.3853 16.408 111.312 16.3978 125.293C16.3978 133.88 13.0627 139.95 9.85 147.646C2.30268 165.711 -3.40881 189.528 2.60865 208.78C5.43498 217.752 10.4064 225.894 17.0891 232.495C23.7718 239.096 31.9626 243.955 40.947 246.649C49.8764 249.377 59.2899 250.124 68.5355 248.84C71.5815 248.394 74.5794 247.664 77.4903 246.66Z' fill='currentColor'/>
                </svg>
                <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.0667 5C21.6586 5.95805 22 7.08604 22 8.29344C22 11.7692 19.1708 14.5869 15.6807 14.5869C15.0439 14.5869 13.5939 14.4405 12.8885 13.8551L12.0067 14.7333C11.272 15.465 11.8598 15.465 12.1537 16.0505C12.1537 16.0505 12.8885 17.075 12.1537 18.0995C11.7128 18.6849 10.4783 19.5045 9.06754 18.0995L8.77362 18.3922C8.77362 18.3922 9.65538 19.4167 8.92058 20.4412C8.4797 21.0267 7.30403 21.6121 6.27531 20.5876C6.22633 20.6364 5.952 20.9096 5.2466 21.6121C4.54119 22.3146 3.67905 21.9048 3.33616 21.6121L2.45441 20.7339C1.63143 19.9143 2.1115 19.0264 2.45441 18.6849L10.0963 11.0743C10.0963 11.0743 9.3615 9.90338 9.3615 8.29344C9.3615 4.81767 12.1907 2 15.6807 2C16.4995 2 17.282 2.15509 18 2.43738" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17.8851 8.29353C17.8851 9.50601 16.8982 10.4889 15.6807 10.4889C14.4633 10.4889 13.4763 9.50601 13.4763 8.29353C13.4763 7.08105 14.4633 6.09814 15.6807 6.09814C16.8982 6.09814 17.8851 7.08105 17.8851 8.29353Z" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </div>

            <div class="title">
                <h1>Alterar Senha</h1>
                <p>Insira a <strong>senha vinculado</strong> a sua conta e a <strong>nova senha</strong> para realizar a alteração.</p>
            </div>

            <form method="post">
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