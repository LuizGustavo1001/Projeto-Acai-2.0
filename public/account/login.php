<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    $currentDate = date("Y-m-d");
    $currentHour = date("H:i:s");

    if (isset($_SESSION["isAdmin"])){ setCookies("adminNotAllowed", "../mannager/admin.php", 0); }
    
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
        ") or die($mysqli->errno);
        $getUser->bind_param("s", $inputEmail);

        $getUser->execute();
        $result = $getUser->get_result();
        $getUser->close();

        if($result->num_rows === 0){ setCookies("errorLogin", "login.php", 0); }

        $user = $result->fetch_assoc();
        if(! password_verify($inputPassword, $user["userPassword"])){ setCookies("errorLogin", "login.php", 0); }

        // verify user type
        $getUserType = $mysqli->prepare("SELECT idClient FROM client_data WHERE idClient = ?") or die($mysqli->error);
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

            $newOrder = $mysqli->prepare("INSERT INTO order_data (idClient, orderDate, orderHour) VALUES (?, ?,?)") or die($mysqli->error);
            $newOrder->bind_param("iss", $_SESSION["idUser"], $currentDate, $currentHour);

            $newOrder->execute();

            $_SESSION["idOrder"] = $mysqli->insert_id;
            $newOrder->close();
            verifyOrders();

            setCookies("loginSuccess", "../index.php", 1);
        }else{
            $_SESSION["isAdmin"] = true;
            verifyOrders();

            header("location: ../mannager/admin.php");
            exit();
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

    <title>Açaí e Polpas Amazônia | Login</title>
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
                <svg class="icon" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25 6.77075C24.1371 6.77075 23.4375 7.47031 23.4375 8.33325C23.4375 9.19619 24.1371 9.89575 25 9.89575C33.3419 9.89575 40.1042 16.6581 40.1042 24.9999C40.1042 33.3418 33.3419 40.1041 25 40.1041C24.1371 40.1041 23.4375 40.8037 23.4375 41.6666C23.4375 42.5295 24.1371 43.2291 25 43.2291C35.0677 43.2291 43.2292 35.0676 43.2292 24.9999C43.2292 14.9322 35.0677 6.77075 25 6.77075Z" fill="currentColor"/>
                    <path d="M21.8125 19.8548C21.2023 19.2447 21.2023 18.2553 21.8125 17.6451C22.4227 17.035 23.4119 17.035 24.0221 17.6451L30.2721 23.8952C30.8823 24.5054 30.8823 25.4946 30.2721 26.1048L24.0221 32.3548C23.4119 32.965 22.4227 32.965 21.8125 32.3548C21.2023 31.7446 21.2023 30.7554 21.8125 30.1452L25.395 26.5625H8.33398C7.47105 26.5625 6.77148 25.8629 6.77148 25C6.77148 24.1371 7.47105 23.4375 8.33398 23.4375H25.395L21.8125 19.8548Z" fill="currentColor"/>
                </svg>
            </div>

            <div class="title">
                <h1>Área de Login</h1>
                <p>Realize seu <strong>login</strong> para <strong>continuar comprando</strong> em nosso site.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input">
                    <label for="imail">Endereço de Email</label>
                    <input type="email" name="email" id="imail" placeholder="exemplo@dominio.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="regular-input">
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
    </main>

    <script src="/js/script.js"></script>
</body>
</html>