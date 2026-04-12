<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    $currentDate = date("Y-m-d");
    $currentHour = date("H:i:s");

    if (isset($_SESSION["isAdmin"])){ setCookies("adminNotAllowed", "../manager/admin.php", 0); }
    
    if(isset($_SESSION["clientMail"])){
        header("location: account.php");
        exit(); 
    }

    if(isset($_POST["email"])){ login(); }

    function login(){
        global $mysqli;

        // verify email domain
        $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)){ setCookies("invalidEmail", "login.php", 0); }

        $inputPassword = $_POST["password"];

        $getUser = $mysqli->prepare("
            SELECT idUser, userMail, userPassword, userName, userPhone, district, city, street, localNum, referencePoint, state
            FROM user_data
            WHERE userMail = ?
            LIMIT 1
        ") or die("var getUser (login.php): " . $mysqli->errno);
        $getUser->bind_param("s", $inputEmail);

        $getUser->execute();
        $result = $getUser->get_result();
        $getUser->close();

        if($result->num_rows === 0){ setCookies("errorLogin", "login.php", 0); }

        $user = $result->fetch_assoc();
        if(! password_verify($inputPassword, $user["userPassword"])){ setCookies("errorLogin", "login.php", 0); }

        // verify user type
        $getUserType = $mysqli->prepare("SELECT idClient FROM client_data WHERE idClient = ?") or die("var getUserType (login.php): " . $mysqli->error);
        $getUserType->bind_param("i", $user["idUser"]);

        $getUserType->execute();
        $userType = $getUserType->get_result();
        $getUserType->close();

        $uType = $userType->num_rows === 0 ? "admin" : "client";

        // start session
        session_regenerate_id(true);
        $_SESSION = [
            "idUser"         => $user["idUser"],
            "userPhone"      => $user["userPhone"],
            "userName"       => $user["userName"],
            "userMail"       => $inputEmail,
            "district"       => $user["district"],
            "localNum"       => $user["localNum"],
            "referencePoint" => $user["referencePoint"],
            "street"         => $user["street"],
            "city"           => $user["city"],
            "state"          => $user["state"],
            "lastActivity"   => time()
        ];

        if($uType === "client"){
            $currentDate = date("Y-m-d");
            $currentHour = date("H:i:s");

            $newOrder = $mysqli->prepare("INSERT INTO order_data (idClient, orderDate, orderHour) VALUES (?, ?,?)") or die("var newOrder (login.php): " . $mysqli->error);
            $newOrder->bind_param("iss", $_SESSION["idUser"], $currentDate, $currentHour);

            $newOrder->execute();
            $newOrder->close();

            $_SESSION["idOrder"] = $mysqli->insert_id;
            verifyOrders();

            setCookies("loginSuccess", "../index.php", 1);
        }else{
            $_SESSION["isAdmin"] = true;
            verifyOrders();

            header("location: ../manager/admin.php");
            exit();
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

    <title>Açaí e Polpas Amazônia | Login</title>
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
                <?php echo getIcon("login")?>
            </div>

            <div class="title">
                <h1>Área de Login</h1>
                <p>Realize seu <strong>login</strong> para <strong>continuar comprando</strong> em nosso site.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="imail">Endereço de Email</label>
                    <input type="email" name="email" id="imail" placeholder="exemplo@dominio.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="regular-input-box">
                    <label for="ipassword">Senha</label>
                    <input type="password" name="password" id="ipassword" placeholder="********" required>
                </div>

                <button class="regular-btn">Entrar</button>
            </form>

            <div class="fot">
                <a href="password.php">Esqueceu sua senha?</a>
                <a href="register.php">Ainda não está registrado?</a>
            </div>
        </div>
        <div class="fot-copy">2026 &copy; Açaí e Polpas Amazônia</div>
    </main>

    <script src="/js/general.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>