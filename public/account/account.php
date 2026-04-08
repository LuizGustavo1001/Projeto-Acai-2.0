<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") { changeColumn(); }

    if (isset($_SESSION["isAdmin"])){ setCookies("adminNotAllowed", "../mannager/admin.php", 0); }

    if(! isset($_SESSION["userMail"])){
        header("location: login.php");
        exit();
    }

    checkSession();

    function changeColumn(){
        // function to change the value on Database associated to the value changed at the form in HTML
        global $mysqli;
        $allowedInputs = [
            "userName", "userPhone", "district", "localNum", 
            "referencePoint", "street", "city", "state"
        ];

        for($i = 0; $i < sizeof($allowedInputs); $i++){
            if(isset($_POST[$allowedInputs[$i]])){
                $newValue = trim($_POST[$allowedInputs[$i]]);

                if($newValue != ""){
                    $changeData = $mysqli->prepare("UPDATE user_data SET $allowedInputs[$i] = ? WHERE idUser = ?;") or die($mysqli->errno);
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
                        }
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

    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Minha Conta</title>

</head>
<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/index.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-box">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("userOuter")?>
            </div>

            <div class="title">
                <h1>Área do Usuário</h1>
                <p>Ao clicar em <strong>"editar"</strong> todos os campos preenchidos serão <strong>verificados</strong>.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input">
                    <label for="iuserName">Nome: </label>
                    <div class="form-input">
                        <input type="text" name="userName" id="iuserName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['userName']; ?>" >
                    </div>
                </div>

                <div class="regular-input">
                    <label for="iuserPhone">Telefone de Contato:</label>
                    <div class="form-input">
                        <input type="text" name="userPhone" id="iuserPhone" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['userPhone']; ?>" >
                    </div>
                </div>

                <div class="wrap">
                    <div class="regular-input">
                        <label for="istreet">Rua: </label>
                        <div class="form-input">
                            <input type="text" name="street" id="istreet" maxlength="50" placeholder="<?php echo $_SESSION['street']; ?>" >
                        </div>
                    </div>
                    <div class="regular-input">
                        <label for="ilocalNum">Número: </label>
                        <div class="form-input">
                            <input type="number" name="localNum" id="ilocalNum" max="99999999" placeholder="<?php echo $_SESSION['localNum']; ?>">
                        </div>
                    </div>
                </div>

                <div class="regular-input">
                    <label for="iuserDistrict">Bairro: </label>
                    <div class="form-input">
                        <input type="text" name="district" id="iuserDistrict" maxlength="40" placeholder="<?php echo $_SESSION['district']; ?>" >
                    </div>
                </div>

                <div class="wrap">
                    <div class="regular-input">
                        <label for="iuserCity">Cidade: </label>
                        <div class="form-input">
                            <input type="text" name="city" id="iuserCity" maxlength="40" placeholder="<?php echo $_SESSION['city']; ?>">
                        </div>
                    </div>
                    <div class="regular-input">
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
                </div>

                <div class="regular-input">
                    <label for="ireferencePoint">Ponto de Referência: </label>
                    <div class="form-input">
                        <input type="text" name="referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['referencePoint']; ?>">
                    </div>
                </div>
                <button class="regular-btn">Editar</button>
            </form>

            <div class="fot">
                <a href="logout.php">Clique aqui para <strong>sair da sua conta</strong></a>
                <a href="changes/newPassword.php">Alterar Senha</a>
                <a href="changes/newEmail.php">Alterar Email</a>
            </div>
        </div>
    </main>

    <script src="/js/script.js"></script>
</body>
</html>