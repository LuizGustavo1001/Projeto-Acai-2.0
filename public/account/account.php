<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    if(isset($_GET["logout"])){ logout(); }

    if ($_SERVER["REQUEST_METHOD"] === "POST") { changeColumn(); }

    if (isset($_SESSION["isAdmin"])){ setCookies("adminNotAllowed", "../manager/admin.php", 0); }

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
                    $changeData = $mysqli->prepare("UPDATE user_data SET $allowedInputs[$i] = ? WHERE idUser = ?;") or die("var changeData (login.php): " . $mysqli->errno);
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

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
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
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("userOuter")?>
            </div>

            <div class="title">
                <h1>Área do Usuário</h1>
                <p>Ao clicar em <strong>"editar"</strong> todos os campos preenchidos serão <strong>verificados</strong>.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="iuserName">Nome: </label>
                    <div class="form-input">
                        <input type="text" name="userName" id="iuserName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['userName']; ?>" >
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="iuserPhone">Telefone de Contato:</label>
                    <div class="form-input">
                        <input type="text" name="userPhone" id="iuserPhone" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['userPhone']; ?>" >
                    </div>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="istreet">Rua: </label>
                        <div class="form-input">
                            <input type="text" name="street" id="istreet" maxlength="50" placeholder="<?php echo $_SESSION['street']; ?>" >
                        </div>
                    </div>
                    <div class="regular-input-box">
                        <label for="ilocalNum">Número: </label>
                        <div class="form-input">
                            <input type="number" name="localNum" id="ilocalNum" max="99999999" placeholder="<?php echo $_SESSION['localNum']; ?>">
                        </div>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="iuserDistrict">Bairro: </label>
                    <div class="form-input">
                        <input type="text" name="district" id="iuserDistrict" maxlength="40" placeholder="<?php echo $_SESSION['district']; ?>" >
                    </div>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="iuserCity">Cidade: </label>
                        <div class="form-input">
                            <input type="text" name="city" id="iuserCity" maxlength="40" placeholder="<?php echo $_SESSION['city']; ?>">
                        </div>
                    </div>
                    <div class="regular-input-box">
                        <label for="istate">Estado</label>
                        <select name="state" id="istate"> <?php displayStateOptions() ?> </select>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="ireferencePoint">Ponto de Referência: </label>
                    <div class="form-input">
                        <input type="text" name="referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['referencePoint']; ?>">
                    </div>
                </div>
                <button class="regular-btn">Editar</button>
            </form>

            <div class="fot">
                <a href="?logout=1">Clique aqui para <strong>sair da sua conta</strong></a>
                <a href="changes/newPassword.php">Alterar Senha</a>
                <a href="changes/newEmail.php">Alterar Email</a>
            </div>
        </div>
    </main>

    <script src="/js/general.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>