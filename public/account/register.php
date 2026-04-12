<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";
    
    if (isset($_SESSION["isAdmin"])) { setCookies("adminNotAllowed", "../manager/admin.php", 0); }

    if (isset($_SESSION["userMail"])) {
        header("location: account.php");
        exit();
    }

    if (isset($_GET["userAdd"])) { setCookies("registered", "login.php", 1); }

    if( isset($_POST["name"], $_POST["email"], $_POST["phone"],
        $_POST["street"], $_POST["houseNum"] ,$_POST["district"],
        $_POST["city"], $_POST["reference"], $_POST["password"])){
        addUser();
    }

    function addUser(){
        // function to register a new user as client(default)
        global $mysqli;

        $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $verifyEmail = $mysqli->prepare("
            SELECT userMail 
            FROM user_data 
            WHERE userMail = ?
        ") or die("var verifyEmail (register.php): " . $mysqli->errno);
        $verifyEmail->bind_param("s", $inputEmail);

        $verifyEmail->execute();
        $resultEmail = $verifyEmail->get_result();
        $verifyEmail->close();

        switch($resultEmail->num_rows){
            case 0:
                // there's no user registered with the email input -> register new one
                $domain = substr(strrchr($inputEmail, "@"), 1);
                if (checkdnsrr($domain, "MX")) {
                    // verify if the email domain exists
                    $name       = mb_convert_case($_POST['name'], MB_CASE_TITLE, "UTF-8");
                    $phone      = $_POST["phone"];
                    $street     = $_POST["street"];
                    $houseNum   = $_POST["houseNum"];
                    $district   = mb_convert_case($_POST["district"], MB_CASE_TITLE, "UTF-8");
                    $city       = mb_convert_case($_POST["city"], MB_CASE_TITLE, "UTF-8");
                    $reference  = $_POST["reference"] ?? null;
                    $state      = $_POST["state"];
                    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $insertData = $mysqli->prepare("
                            INSERT INTO user_data (userName, userMail, userPassword, userPhone, district, localNum, referencePoint, street, city, state) VALUES 
                                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    ) or die("var insertData (register.php): " . $mysqli->errno);

                    $insertData->bind_param("ssssssssss", $name, $inputEmail, $password, $phone, $district, $houseNum, $reference, $street, $city, $state);
                    $insertData->execute();
                    $insertData->close();

                    // insert the user as client (default)
                    $clientId = $mysqli->insert_id;
                    $insertClient = $mysqli->prepare("INSERT INTO client_data (idClient) VALUES (?)") or die("var insertClient (register.php): " . $mysqli->errno);
                    $insertClient->bind_param("i", $clientId);
                    $insertClient->execute();
                    $insertClient->close();
                }else{
                    setCookies("invalidDomain", "register.php", 0); 
                }
            default:
                setCookies("emailExists", "register.php", 0);
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

    <title>Açaí e Polpas Amazônia | Registrar</title>
</head>

<body>
    <div class="dazzles-bg mobile"></div>

    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/index.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("newUser")?>
            </div>

            <div class="title">
                <h1>Área de Registro</h1>
                <p><strong>Registre-se</strong> para <strong>começar a comprar</strong> em nosso site.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="iname">Nome *</label>
                    <input type="text" name="name" id="iname" maxlength="30" minlength="8"
                                placeholder="Nome Completo" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="regular-input-box">
                    <label for="iemail">Endereço de Email *</label>
                    <input type="email" name="email" id="iemail" maxlength="50"
                                placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="regular-input-box">
                    <label for="inumber">Telefone de Contato *</label>
                    <input type="text" name="phone" id="inumber" minlength="15" maxlength="16"
                        pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="(XX) 9 8888 8888" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="istreet">Rua *</label>
                        <input type="text" name="street" id="istreet" maxlength="50"
                            placeholder="Nome da Rua Aqui" value="<?= htmlspecialchars($_POST['street'] ?? '') ?>" required>
                    </div>
                    <div class="regular-input-box">
                        <label for="ihouseNum">Número *</label>
                        <input type="number" name="houseNum" id="ihouseNum" max="99999999"
                            placeholder="Número  da Residência Aqui" value="<?= htmlspecialchars($_POST['houseNum'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="idistrict">Bairro *</label>
                    <input type="text" name="district" id="idistrict" maxlength="40"
                        placeholder="Nome do Bairro Aqui" value="<?= htmlspecialchars($_POST['district'] ?? '') ?>" required>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="icity">Cidade *</label>
                        <input type="text" name="city" id="icity" maxlength="40"
                            placeholder="Nome da Cidade Aqui" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
                    </div>
                    <div class="regular-input-box">
                        <label for="istate">Estado: </label>
                        <select name="state" id="istate"> <?php displayStateOptions() ?> </select>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="ireference">Ponto de Referência:</label>
                    <input type="text" name="reference" id="ireference" maxlength="50"
                        value="<?= htmlspecialchars($_POST['reference'] ?? '') ?>">
                </div>

                <div class="regular-input-box">
                    <label for="ipassword">Senha *</label>
                    <input type="password" name="password" id="ipassword" placeholder="********" required maxlength="30">
                </div>

                <button class="regular-btn">Registrar-se</button>
            </form>
        </div>
    </main>

    <script src="/js/general.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>