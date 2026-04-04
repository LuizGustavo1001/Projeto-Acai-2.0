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
                    header("location: newEmail.php?wrongMail=1");
                    exit();
                }else if($sanitizedNewEmail == $sanitizedMail){
                    header("location: newEmail.php?sameMail=1");
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
                header("location: newEmail.php?wrongMail=1");
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

    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Alterar Email</title>
</head>

<body>
    <?php 
        if(isset($_GET["wrongMail"])){
            FillWarning("wrongMail", "", 0);
        }else if(isset($_GET["sameMail"]))
            FillWarning("sameMail", "", 0);
    ?>

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
                    <path d="M20.5 19.7361C19.9692 20.2111 19.2684 20.5 18.5 20.5C16.8431 20.5 15.5 19.1569 15.5 17.5C15.5 15.8431 16.8431 14.5 18.5 14.5C19.8062 14.5 20.9175 15.3348 21.3293 16.5M22 14V17H19M11.5 19H6.2C5.0799 19 4.51984 19 4.09202 18.782C3.71569 18.5903 3.40973 18.2843 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V10M20.6067 8.26229L15.5499 11.6335C14.2669 12.4888 13.6254 12.9165 12.932 13.0827C12.3192 13.2295 11.6804 13.2295 11.0677 13.0827C10.3743 12.9165 9.73279 12.4888 8.44975 11.6335L3.14746 8.09863" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
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