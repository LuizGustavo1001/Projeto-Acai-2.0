<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();

    }

    if(! isset($_SESSION["userMail"])){ 
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
            ");

            $stmt->bind_param("ss", $newPassword, $_SESSION["userMail"]);
            if($stmt->execute()){
                $stmt->close();

                unset($_SESSION["userMail"]);
                header("location: login.php?newPassword=1");
                exit();
            }else{
                header("location: ../errorPage.php");
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

    <?php displayFavicon()?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "account") ?>">


    <style>
        .container-background{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/newPassword_zwuuss.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }   
    </style>

    <title>Açaí e Polpas Amazônia - Recuperar Senha</title>

</head>
<body>
    <?php headerOut(1)?>
    <section class="container">
        <div class="left-container">
            <nav>
                <ul>
                    <li>
                        <a href="../index.php">Página Principal</a>
                    </li>

                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>

                    <li>
                        <a href="login.php">Página de Login</a>
                    </li>

                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>

                    <li>
                        <a href="">Recuperação de Senha</a>
                    </li>
                </ul>
            </nav>

            <div class="container-forms">
                <div class="container-forms-title">
                    <h1>Recuperação de Senha</h1>
                    <p>Insira sua <strong>Nova Senha</strong> no espaço abaixo para alterá-la permanentemente.</p>
                </div>

                 <form method="POST">
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
                            <label for="ipassword">Nova Senha: </label>
                            <input type="password" name="password" id="ipassword" maxlength="50" placeholder="Digite Sua nova Senha Aqui" required>
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

            </div>

        </div>

        <div class="right-container">
            <div class="container-background"></div>
        </div>
    </section>

    <!--
    <section class="account-hero">
        <div class="account-left-div">
            

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
                        <h1>Recuperação de Senha</h1>
                        <p>
                            Insira sua <strong>Nova Senha</strong> no espaço abaixo para alterá-la permanentemente.
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
                            <label for="ipassword">Nova Senha: </label>
                            <input type="password" name="password" id="ipassword" maxlength="50" placeholder="Digite Sua nova Senha Aqui" required>
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
            
        </div>
        <div class="account-right-div"></div>
    </section>
                        -->
    <?php footerOut();?>
</body>
</html>