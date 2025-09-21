<?php 
    include "../../databaseConnection.php";
    include "../footerHeader.php";

    if(! isset($_SESSION)){
        session_start();
    }

    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();

    }

    if(! isset($_SESSION["passwordToken"])){ // entrando na página sem solicitar um token
       header("location: password.php");
       exit();

    }else{
        if(isset($_POST["token"])){
            if($_SESSION["passwordToken"] == $_POST["token"]){
                unset($_SESSION["passwordToken"]);
                header("location: newPassword.php");
                exit();

            }else{
                header("location: rescuePassword.php?wrongToken=1");
                exit();
            }
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
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/rescuePassword_gtxplo.png) center center;
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
                        <h1>Código para Verificação de Email</h1>
                        <p>
                            Insira o <strong>Código</strong> enviado para o Email  
                            <strong style="color: var(--secondary-clr)" ><?php echo $_SESSION["userMail"]?></strong> 
                            abaixo para <strong>alterar sua senha</strong>
                        </p>
                    </div>
                    <form action="" method="post">
                        <?php 
                            if(isset($_GET["wrongToken"])) {
                                echo "
                                    <p class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        Erro: <strong>Token Inserido</strong> Incorreto <br>
                                        Tente Novamente com outro Token.
                                    </p>
                                ";

                            }
                        ?>

                        <div class="form-item">
                            <label for="itoken">Token de Recuperação: </label>
                            <input type="text" name="token" id="itoken" maxlength="50" placeholder="Digite o Token de Recuperação Aqui" required>
                        </div>

                        <div>
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Enviar
                            </button>
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