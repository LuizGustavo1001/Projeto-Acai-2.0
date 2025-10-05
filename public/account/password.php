<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();
    }
    
    if(isset($_SESSION["userMail"])){
        header("location: login.php");
        exit();
    }

    // verify if the email input are in the Database
    if(isset($_POST["email"])){
        $sanitizedMail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        $stmt = $mysqli->prepare("SELECT userMail FROM user_data WHERE userMail = ?");
        $stmt->bind_param("s", $sanitizedMail);

        if($stmt->execute()){
            $result = $stmt->get_result();
            $stmt->close();
            switch($result->num_rows){
                case 0: 
                    header("Location: password.php?wrongMail=1");
                    exit();
                
                default: // send email
                    $_SESSION["userMail"] = $sanitizedMail;
                    header("Location: passwordToken.php");
                    exit();
            }
            
        }else{
            header("location: ../errorPage.php");
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

    <?php faviconOut()?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "account") ?>">

    <style>
        .account-right-div{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/forgotPassword_lx206b.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }   

    </style>

    <title>Açaí e Polpas Amazônia - Recuperar Senha</title>

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
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>

                        <li><a href="password.php">Recuperação de Senha</a></li>
                    </ul>
                </section>

                <section class="account-forms">
                    <div class="section-header-title">
                        <h1>Esqueceu Sua <br> Senha?</h1>
                        <p>
                            Insira o <strong>Endereço de Email vinculado</strong> a esta conta para <br> enviarmos um código de Recuperação para você alterá-la.
                        </p>
                    </div>
                    <form action="" method="post">
                        <?php 
                            if(isset($_GET["wrongMail"])) {
                                echo "
                                    <p class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        Erro: <strong>Email Inserido</strong> não está cadastrado <br>
                                        Tente Novamente com outro Endereço de Email.
                                    </p>
                                ";

                            }
                        ?>

                        <div class="form-item">
                            <label for="iemail">Endereço de Email: </label>
                            <input type="email" name="email" id="iemail" maxlength="50" placeholder="email@exemplo.com" required>
                        </div>

                        <div>
                            <button>
                                Enviar Código
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </button>
                        </div>
                        
                    </form>
                </section>
            </main>
            <?php footerOut();?>
        </div>
        <div class="account-right-div"></div>
    </section>
</body>
</html>