<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    
    if(! isset($_SESSION["clientMail"])){
        header("location: login.php");
        exit();

    }

    checkSession("account");

    if(isset($_GET["logout"])){
        session_destroy(); 
        header("location: login.php?logout=1");
        exit;

    }

    $allowedInputs = ["clientName", "clientNumber", "district", "localNum", "referencePoint", "street", "city"];

    for($i = 0; $i < sizeof($allowedInputs); $i++){
        for($j = 0; $j < sizeof($allowedInputs); $j++){
            if(isset($_POST[$allowedInputs[$i]])){
                $newValue = trim($_POST[$allowedInputs[$i]]);
                if($newValue !== "" && $newValue != $_SESSION[$allowedInputs[$i]] && $allowedInputs[$i] == $allowedInputs[$j]){
                    $dbTable = "";
                    match($allowedInputs[$i]){
                        "clientName" => $dbTable = "client_data",
                        "clientNumber" => $dbTable = "client_number",
                        default         => $dbTable = "client_address"
                        
                    };
                    $changeData = $mysqli->prepare(
                        "UPDATE $dbTable SET $allowedInputs[$i] = ? WHERE idClient = ?;"

                    );
                    $changeData->bind_param("ss", $newValue, $_SESSION["idClient"]);
                    if($changeData->execute()){
                        $_SESSION[$allowedInputs[$i]] = $newValue;

                    }else{
                        header("location: ../errorPage.php");
                        exit();

                    }
                }
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

    <style>
        .account-right-div{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751727599/accountBg_xgmzw6.png) right center;
            background-size: cover;
            background-repeat: no-repeat;

        }   

    </style>

    <title>Açaí e Polpas Amazônia - Minha Conta</title>

</head>
<body>

    <section class="account-hero">
        <div class="account-left-div">

            <?php headerOut(1)?>

            <main>
                <section class="account-header">
                    <div class="account-header-location">
                        <ul>
                            <li><a href="../index.php">Página Principal</a></li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </li>
                            <li><a href="account.php">Página do Usuário</a></li>
                        </ul>

                        
                    </div>
                    <div class="account-header-button">
                        <a href="account.php?logout=1">
                            <button>Sair</button>
                        </a>

                    </div>
                </section>


                <section class="account-forms">
                    <div class="section-header-title">
                        <h1>Área do Usuário</h1>
                        <p>Edite <strong>Seus Dados</strong> individualmente aqui</p>
                        <p>Ao clicar em <strong>"editar"</strong> todos os campos preenchidos serão <strong>verificados</strong> </p>
                    </div>
                    <form action="" method="POST"> 
                        <div class="form-item">
                            <label for="iclientName">Nome: </label>
                            <div class="form-input">
                                <input type="text" name="clientName" id="iclientName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['clientName']; ?>" >
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="iclientNumber">Telefone de Contato:</label>
                            <div class="form-input">
                                <input type="text" name="clientNumber" id="iclientNumber" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['clientNumber']; ?>" >
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="istreet">Rua: </label>
                            <div class="form-input">
                                <input type="text" name="street" id="istreet" maxlength="50" placeholder="<?php echo $_SESSION['street']; ?>" >
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="ilocalNum">Número: </label>
                            <div class="form-input">
                                <input type="number" name="localNum" id="ilocalNum" max="99999999" placeholder="<?php echo $_SESSION['localNum']; ?>">
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="iuserDistrict">Bairro: </label>
                            <div class="form-input">
                                <input type="text" name="district" id="iuserDistrict" maxlength="40" placeholder="<?php echo $_SESSION['district']; ?>" >
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="iuserCity">Cidade: </label>
                            <div class="form-input">
                                <input type="text" name="city" id="iuserCity" maxlength="40" placeholder="<?php echo $_SESSION['city']; ?>">
                            </div>
                        </div>

                        <div class="form-item">
                            <label for="ireferencePoint">Ponto de Referência: </label>
                            <div class="form-input">
                                <input type="text" name="referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['referencePoint']; ?>">
                            </div>
                        </div>

                        <ul style="display: flex; justify-content: space-between; border: none">
                            <li style="padding: 1em; background: var(--primary-clr);border-radius: var(--border-radius)">
                                <a href="changes/newPassword.php" style="color: white;">
                                    Alterar Senha
                                </a>
                            </li>
                            <li style="padding: 1em; background: var(--primary-clr);border-radius: var(--border-radius)">
                                <a href="changes/newEmail.php" style="color: white;">
                                    Alterar Email
                                </a>
                            </li>
                        </ul>

                        <button>Editar</button>
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