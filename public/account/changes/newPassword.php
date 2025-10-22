<?php 
    include "../../../databaseConnection.php";
    include "../../generalPHP.php";
    include "../../footerHeader.php";
    include "../../printStyles.php";

    if(! isset($_SESSION["userMail"])){
        header("location: ../login.php");
        exit();
    }

    checkSession("insideAccount");

    if(isset($_POST['password'], $_POST['newPassword'])){
        $sanitizedPassword = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

        $stmt = $mysqli->prepare("SELECT userPassword FROM user_data WHERE idUser = ?");
        $stmt->bind_param("i", $_SESSION["idUser"]);

        if($stmt->execute()){
            $result = $stmt->get_result();
            $stmt->close();

            $result = $result->fetch_assoc();

            if(! password_verify($sanitizedPassword, $result["userPassword"])){
                header("location: newPassword.php?wrongP");
                exit();

            }else if($_POST["password"] == $_POST["newPassword"]){
                header("location: newPassword.php?sameP");
                exit();
            }else{
                $hashedPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                
                $updatePassword = $mysqli->prepare("
                    UPDATE user_data
                    SET userPassword = ?
                    WHERE idUser = ?
                ");

                $updatePassword->bind_param("si", $hashedPassword, $_SESSION["idUser"]);
                if($updatePassword->execute()){
                    $updatePassword->close();
                    session_destroy();

                    header("location: ../login.php?newPassword");
                    exit();
                }else{
                    header("location: ../../errorPage.php");
                    exit();
                }
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

    <link rel="stylesheet" href="<?php printStyle("2", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("2", "account") ?>">

    <style>
        .container-background{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/forgotPassword_lx206b.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }
    </style>

    <title>Açaí e Polpas Amazônia - Alterar Senha</title>

</head>
<body>
    <?php headerOut(2)?>

    <section class="container">
        <div class="left-container">
            <nav>
                <ul>
                    <li><a href="../../index.php">Página Principal</a></li>
                    <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>

                    <li><a href="../account.php">Página do Usuário</a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>

                    <li><a href="newPassword.php">Alterar Senha</a></li>
                </ul>
            </nav>
            <div class="container-forms">
                <div class="container-forms-title">
                    <h1>Alterar Senha</h1>
                    <p>
                        Insira a <strong>Senha Anteriormente Vinculada</strong> a esta conta e a <strong>Nova Senha Desejada</strong> para alterá-la.
                    </p>
                </div>
                <form method="POST">
                    <?php 
                        if(isset($_GET["wrongP"])) {
                            echo "
                                <p class=\"errorText\">
                                    <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                    Erro: <strong>Senha Anterior Inserida</strong> não está cadastrada<br>
                                    Tente Novamente com outra Senha.
                                </p>
                            ";

                        }else if(isset($_GET["sameP"])){
                            echo "
                                <p class=\"errorText\">
                                    <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                    Erro: <strong>Senha Anterior</strong> e <strong>Nova Senha</strong> inseridas são as mesmas<br>
                                    Tente Novamente com outra Senha.
                                </p>
                            ";
                        }
                    ?>

                    <div class="form-item">
                        <label for="ipassword">Senha Anterior: </label>
                        <input type="password" name="password" id="ipassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                    </div>

                    <div class="form-item">
                        <label for="inewPassword">Nova Senha: </label>
                        <input type="password" name="newPassword" id="inewPassword" maxlength="30" placeholder="• • • • • • • • • •" required>
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
            </div>
            
        </div>

        <div class="right-container">
            <div class="container-background"></div>
        </div>
    </section>

    <?php footerOut();?>
</body>
</html>