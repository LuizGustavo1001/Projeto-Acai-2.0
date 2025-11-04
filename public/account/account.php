<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        changeColumn();
    }
    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();
    }
    if(! isset($_SESSION["userMail"])){
        header("location: login.php");
        exit();
    }

    checkSession("account");
    if(isset($_GET["logout"])){
        session_destroy(); 
        header("location: login.php?logout=1");
        exit();
    }

    function changeColumn(){
        // function to change the value on Database associated to the value changed at the form in HTML
        global $mysqli;
        $allowedInputs = [
            "userName", "userPhone", "district", "localNum", 
            "referencePoint", "street", "city", "state"
        ];
        $getChanges = "";

        for($i = 0; $i < sizeof($allowedInputs); $i++){
            if(isset($_POST[$allowedInputs[$i]])){
                $newValue = trim($_POST[$allowedInputs[$i]]);

                if($newValue != ""){
                    $changeData = $mysqli->prepare(
                        "UPDATE user_data SET $allowedInputs[$i] = ? WHERE idUser = ?;"
                    );
                    $changeData->bind_param("si", $newValue, $_SESSION["idUser"]);

                    if($allowedInputs[$i] == "referencePoint" or $allowedInputs[$i] == "state"){
                        // special inputs -> can be null or the option is always selected on the form
                        if($newValue != $_SESSION[$allowedInputs[$i]]){
                            $changeData->execute();
                            $changeData->close();
                            switch($i){
                                case 0:
                                    $_SESSION["userName"] = $newValue;
                                    break;
                                case 1:
                                    $_SESSION["userPhone"] = $newValue;
                                    break;
                                default: 
                                    $_SESSION[$allowedInputs[$i]] = $newValue;
                                    break;
                            }

                            $getChanges .= "c{$allowedInputs[$i]}=1&";
                        }
                    }else{
                        if($newValue != $_SESSION[$allowedInputs[$i]]){
                            $changeData->execute();
                            switch($i){
                                case 0:
                                    $_SESSION["userName"] = $newValue;
                                    break;
                                case 1:
                                    $_SESSION["userPhone"] = $newValue;
                                    break;
                                default: 
                                    $_SESSION[$allowedInputs[$i]] = $newValue;
                                    break;
                            }
                            $getChanges .= "c{$allowedInputs[$i]}=1&";
                        }else{
                            // writed value in the form is the same as the one at the Database -> no change
                            $getChanges .= "c{$allowedInputs[$i]}=2&";
                        }
                    }
                }
            }
        }
        header("location: account.php?" . rtrim($getChanges, "&"));
        exit();
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

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "account") ?>">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <style>
        .container-background{
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751727599/accountBg_xgmzw6.png) right center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>

    <title>Açaí e Polpas Amazônia - Minha Conta</title>

</head>
<body>
    <?php displayHeader(1)?>
    
    
    <main>
        <section class="container">
            <div class="left-container">
                <nav class="nav-bar">
                    <ul>
                        <li><a href="../index.php">Página Principal</a></li>
                        <li>/</li>
                        <li><a href="account.php">Página do Usuário</a></li>
                    </ul>

                    <a href="account.php?logout=1" class="link-button">Sair</a>
                </nav>
                
                <div class="container-forms">
                    <div class="container-forms-title">
                        <h1>Área do Usuário</h1>
                        <p>Edite <strong>seus dados</strong> aqui.</p>
                        <p>Ao clicar em <strong>"editar"</strong> todos os campos preenchidos serão <strong>verificados</strong>.</p>
                    </div>

                    <form method="post">
                        <?php
                            // mapping to show better names for the client
                            $fieldLabels = [
                                "userName"       => "Nome de Usuário",
                                "userPhone"      => "Telefone de Contato",
                                "district"       => "Bairro",
                                "localNum"       => "Número da Residência",
                                "referencePoint" => "Ponto de Referência",
                                "street"         => "Rua",
                                "city"           => "Cidade",
                                "state"          => "Estado"
                            ];

                            // go throught all the GET parameters
                            foreach ($_GET as $key => $value) {
                                // Example: $key = "cuserName", $value = "1"
                                if (preg_match('/^c(.+)$/', $key, $matches)) {
                                    $field = $matches[1]; // take "userName", "city", etc.

                                    if (isset($fieldLabels[$field])) {
                                        $label = $fieldLabels[$field];
                                        switch ($value) {
                                            case "1":
                                                echo "
                                                    <div class='successText'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                            <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z' />
                                                        </svg>
                                                        <p>Sucesso ao alterar <span>{$label}</span></p>
                                                    </div>
                                                ";
                                                break;
                                            case "2":
                                                echo "
                                                    <p class='errorText'>
                                                        <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                                                        O valor inserido em <span>{$label}</span> é o mesmo já cadastrado.
                                                    </p>
                                                    ";
                                                break;
                                        }
                                    }
                                }
                            }
                        ?>
                        <div class="form-item regular-input">
                            <label for="iuserName">Nome: </label>
                            <div class="form-input">
                                <input type="text" name="userName" id="iuserName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['userName']; ?>" >
                            </div>
                        </div>

                        <div class="form-item regular-input">
                            <label for="iuserPhone">Telefone de Contato:</label>
                            <div class="form-input">
                                <input type="text" name="userPhone" id="iuserPhone" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['userPhone']; ?>" >
                            </div>
                        </div>

                        <div class="form-item regular-input">
                            <label for="istreet">Rua: </label>
                            <div class="form-input">
                                <input type="text" name="street" id="istreet" maxlength="50" placeholder="<?php echo $_SESSION['street']; ?>" >
                            </div>
                        </div>

                        <div class="form-item regular-input">
                            <label for="ilocalNum">Número: </label>
                            <div class="form-input">
                                <input type="number" name="localNum" id="ilocalNum" max="99999999" placeholder="<?php echo $_SESSION['localNum']; ?>">
                            </div>
                        </div>

                        <div class="form-item regular-input">
                            <label for="iuserDistrict">Bairro: </label>
                            <div class="form-input">
                                <input type="text" name="district" id="iuserDistrict" maxlength="40" placeholder="<?php echo $_SESSION['district']; ?>" >
                            </div>
                        </div>

                        <div class="form-item regular-input">
                            <label for="iuserCity">Cidade: </label>
                            <div class="form-input">
                                <input type="text" name="city" id="iuserCity" maxlength="40" placeholder="<?php echo $_SESSION['city']; ?>">
                            </div>
                        </div>

                        <div class="form-item regular-input">
                            <label for="istate">Estado</label>
                            <select name="state" id="istate">
                                <option value="AC" <?php echo optionSelect("state","AC") ?>>Acre</option>
                                <option value="AL" <?php echo optionSelect("state","AL") ?>>Alagoas</option>
                                <option value="AP" <?php echo optionSelect("state","AP") ?>>Amapá</option>
                                <option value="AM" <?php echo optionSelect("state","AM") ?>>Amazonas</option>
                                <option value="BA" <?php echo optionSelect("state","BA") ?>>Bahia</option>
                                <option value="CE" <?php echo optionSelect("state","CE") ?>>Ceará</option>
                                <option value="DF" <?php echo optionSelect("state","DF") ?>>Distrito Federal</option>
                                <option value="ES" <?php echo optionSelect("state","ES") ?>>Espírito Santo</option>
                                <option value="GO" <?php echo optionSelect("state","GO") ?>>Goiás</option>
                                <option value="MA" <?php echo optionSelect("state","MA") ?>>Maranhão</option>
                                <option value="MT" <?php echo optionSelect("state","MT") ?>>Mato Grosso</option>
                                <option value="MS" <?php echo optionSelect("state","MS") ?>>Mato Grosso do Sul</option>
                                <option value="MG" <?php echo optionSelect("state","MG") ?>>Minas Gerais</option>
                                <option value="PA" <?php echo optionSelect("state","PA") ?>>Pará</option>
                                <option value="PB" <?php echo optionSelect("state","PB") ?>>Paraíba</option>
                                <option value="PR" <?php echo optionSelect("state","PR") ?>>PARANÁ</option>
                                <option value="PE" <?php echo optionSelect("state","PE") ?>>Pernambuco</option>
                                <option value="PI" <?php echo optionSelect("state","PI") ?>>Piauí</option>
                                <option value="RJ" <?php echo optionSelect("state","RJ") ?>>Rio de Janeiro</option>
                                <option value="RN" <?php echo optionSelect("state","RN") ?>>Rio Grande do Norte</option>
                                <option value="RS" <?php echo optionSelect("state","RS") ?>>Rio Grande do Sul</option>
                                <option value="RO" <?php echo optionSelect("state","RO") ?>>Rondônia</option>
                                <option value="RR" <?php echo optionSelect("state","RR") ?>>Roraima</option>
                                <option value="SC" <?php echo optionSelect("state","SC") ?>>Santa Catarina</option>
                                <option value="SP" <?php echo optionSelect("state","SP") ?>>São Paulo</option>
                                <option value="SE" <?php echo optionSelect("state","SE") ?>>Sergipe</option>
                                <option value="TO" <?php echo optionSelect("state","TO") ?>>Tocantins</option>
                            </select>
                        </div>

                        <div class="form-item regular-input">
                            <label for="ireferencePoint">Ponto de Referência: </label>
                            <div class="form-input">
                                <input type="text" name="referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['referencePoint']; ?>">
                            </div>
                        </div>
                        <button class="regular-button">Editar</button>

                        <div class="form-footer">
                            <a href="changes/newPassword.php">Alterar Senha</a>
                            <a href="changes/newEmail.php">Alterar Email</a>
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