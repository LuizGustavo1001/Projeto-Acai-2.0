<?php 
    include "../../../databaseConnection.php";
    include "../../generalPHP.php";
    include "../../footerHeader.php";

    if(! isset($_SESSION["userMail"])){
        header("location: ../login.php");
        exit();

    }

     if (isset($_SESSION["isAdmin"])) {
        header("location: ../../mannager/admin.php?adminNotAllowed=1");
        exit();

    }

    checkSession("insideAccount");

    if(isset($_POST["email"]) && isset($_POST["newEmail"])){
        $sanitizedEmail = filter_var( $_POST["email"], FILTER_SANITIZE_EMAIL);
        
        $stmt = $mysqli->prepare("SELECT clientMail FROM client_data WHERE idClient = ?");
        $stmt->bind_param("i", $_SESSION["idClient"]);

        if($stmt->execute()){
            $sanitizedNewEmail = filter_var($_POST["newEmail"], FILTER_SANITIZE_EMAIL);
            $domain = substr(strrchr($sanitizedNewEmail, "@"), 1);
            if(checkdnsrr($domain, "MX")){
                $result = $result->fetch_assoc();

                if($result["clientMail"] != $sanitizedEmail){
                    header("location: newEmail.php?wrongEmail=1");
                    exit;
                }else if($sanitizedNewEmail == $sanitizedMail){
                    header("location: newEmail.php?sameEmail=1");
                    exit;
                }else{
                    $updateEmail = $mysqli->prepare("
                        UPDATE client_data
                        SET clientMail = ?
                        WHERE idClient = ?
                    ");

                    $updateEmail->bind_param("si", $sanitizedNewEmail, $_SESSION["idClient"]);
                    if($updateEmail->execute()){
                        $updateEmail->close();
                        session_destroy();

                        header("location: ../login.php?newEmail");
                        exit;
                    }else{
                        header("location: ../../errorPage.php");
                    }
                }
            }else{
                header("location: newEmail.php?wrongEmail=1");
            }
        }else{
            header("location: ../../errorPage.php");
            exit;
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

    <link rel="stylesheet" href="../../CSS/general-style.css">
    <link rel="stylesheet" href="../../CSS/account-styles.css">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

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
            
            <?php headerOut(2)?>

            <main>
                <section class="account-header">
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

                        <li><a href="newEmail.php">Alterar Email</a></li>
                    </ul>

                </section>

                <section class="account-forms">
                    <div class="section-header-title">
                        <h1>Alterar Email</h1>
                        <p>
                            Insira a <strong>Senha anteriormente vinculada</strong> a esta conta para <br> enviarmos um <strong>Código de Recuperação</strong> para você alterá-la.
                        </p>
                    </div>
                    <form action="" method="post">
                         <?php 
                            if(isset($_GET["wrongEmail"])) {
                                echo "
                                    <p class=\"errorText\">
                                        Erro: <strong>Email Anterior Inserido</strong> não está cadastrado <br>
                                        Tente Novamente com outro Endereço de Correspondências Eletrônicas.
                                    </p>
                                ";

                            }else if(isset($_GET["sameEmail"])){
                                echo "
                                    <p class=\"errorText\">
                                        Erro: <strong>Email Anterior</strong> e <strong>Novo Email</strong> inseridos são os mesmos<br>
                                        Tente Novamente com outro Endereço de Correspondências Eletrônicas.
                                    </p>
                                ";
                            }
                        ?>

                        <div class="form-item">
                            <label for="iemail">Email Anterior: </label>
                            <input type="email" name="email" id="iemail" maxlength="50" placeholder="• • • • • • • • • •" required>
                        </div>

                        <div class="form-item">
                            <label for="inewEmail">Novo Email: </label>
                            <input type="email" name="newEmail" id="inewEmail" maxlength="50" placeholder="• • • • • • • • • •" required>
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
        
        <div class="account-right-div">

        </div>
    </section>

    

    
</body>
</html>