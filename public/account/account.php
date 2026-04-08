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
                <svg class="icon" viewBox='0 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg' aria-label='user_id icon'>
                    <path fill-rule='evenodd' clip-rule='evenodd' d='M10.4173 4.1665H14.584C18.5123 4.1665 20.4766 4.1665 21.6969 5.38689C22.9173 6.60728 22.9173 8.57146 22.9173 12.4998C22.9173 16.4282 22.9173 18.3924 21.6969 19.6128C20.4766 20.8332 18.5123 20.8332 14.584 20.8332H10.4173C6.48894 20.8332 4.52477 20.8332 3.30437 19.6128C2.08398 18.3924 2.08398 16.4282 2.08398 12.4998C2.08398 8.57146 2.08398 6.60728 3.30437 5.38689C4.52477 4.1665 6.48894 4.1665 10.4173 4.1665ZM13.8027 9.37484C13.8027 8.94337 14.1525 8.59359 14.584 8.59359H19.7923C20.2238 8.59359 20.5736 8.94337 20.5736 9.37484C20.5736 9.80631 20.2238 10.1561 19.7923 10.1561H14.584C14.1525 10.1561 13.8027 9.80631 13.8027 9.37484ZM14.8444 12.4998C14.8444 12.0684 15.1942 11.7186 15.6257 11.7186H19.7923C20.2238 11.7186 20.5736 12.0684 20.5736 12.4998C20.5736 12.9313 20.2238 13.2811 19.7923 13.2811H15.6257C15.1942 13.2811 14.8444 12.9313 14.8444 12.4998ZM15.8861 15.6248C15.8861 15.1934 16.2359 14.8436 16.6673 14.8436H19.7923C20.2238 14.8436 20.5736 15.1934 20.5736 15.6248C20.5736 16.0563 20.2238 16.4061 19.7923 16.4061H16.6673C16.2359 16.4061 15.8861 16.0563 15.8861 15.6248ZM11.459 9.37484C11.459 10.5255 10.5263 11.4582 9.37565 11.4582C8.22506 11.4582 7.29232 10.5255 7.29232 9.37484C7.29232 8.22424 8.22506 7.2915 9.37565 7.2915C10.5263 7.2915 11.459 8.22424 11.459 9.37484ZM9.37565 17.7082C13.5423 17.7082 13.5423 16.7755 13.5423 15.6248C13.5423 14.4742 11.6768 13.5415 9.37565 13.5415C7.07446 13.5415 5.20898 14.4742 5.20898 15.6248C5.20898 16.7755 5.20898 17.7082 9.37565 17.7082Z' fill='currentColor'/>
                </svg>
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