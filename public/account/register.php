<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";
    
    if (isset($_SESSION["isAdmin"])) { setCookies("adminNotAllowed", "../mannager/admin.php", 0); }

    if (isset($_SESSION["userMail"])) {
        header("location: account.php");
        exit();
    }

    if (isset($_GET["userAdd"])) { setCookies("registered", "login.php", 1);  }

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
        ") or die($mysqli->errno);
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
                    ) or die($mysqli->errno);

                    $insertData->bind_param("ssssssssss", $name, $inputEmail, $password, $phone, $district, $houseNum, $reference, $street, $city, $state);
                    $insertData->execute();
                    $insertData->close();

                    // insert the user as client (default)
                    $clientId = $mysqli->insert_id;
                    $insertClient = $mysqli->prepare("INSERT INTO client_data (idClient) VALUES (?)") or die($mysqli->errno);
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

    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">
    
    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Registrar</title>
</head>

<body>
    <div class="dazzles-bg mobile"></div>

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
                    <path d="M24.9993 20.8334C29.6017 20.8334 33.3327 17.1025 33.3327 12.5001C33.3327 7.89771 29.6017 4.16675 24.9993 4.16675C20.397 4.16675 16.666 7.89771 16.666 12.5001C16.666 17.1025 20.397 20.8334 24.9993 20.8334Z" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M31.2507 27.7647C29.3207 27.3253 27.2109 27.0833 25.0007 27.0833C15.7959 27.0833 8.33398 31.2805 8.33398 36.4583C8.33398 41.636 8.33398 45.8333 25.0007 45.8333C36.8494 45.8333 40.2746 43.7118 41.2648 40.6249" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M37.4993 41.6667C42.1017 41.6667 45.8327 37.9357 45.8327 33.3333C45.8327 28.731 42.1017 25 37.4993 25C32.897 25 29.166 28.731 29.166 33.3333C29.166 37.9357 32.897 41.6667 37.4993 41.6667Z" stroke="currentColor" stroke-width="2.5"/>
                    <path d="M37.5 30.5557V36.1111" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34.7227 33.3333H40.2783" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <div class="title">
                <h1>Área de Registro</h1>
                <p><strong>Registre-se</strong> para <strong>começar a comprar</strong> em nosso site.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input">
                    <label for="iname">Nome *</label>
                    <input type="text" name="name" id="iname" maxlength="30" minlength="8"
                                placeholder="Nome Completo" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="regular-input">
                    <label for="iemail">Endereço de Email *</label>
                    <input type="email" name="email" id="iemail" maxlength="50"
                                placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="regular-input">
                    <label for="inumber">Telefone de Contato *</label>
                    <input type="text" name="phone" id="inumber" minlength="15" maxlength="16"
                        pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="(XX) 9 8888 8888" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                </div>

                <div class="wrap">
                    <div class="regular-input">
                        <label for="istreet">Rua *</label>
                        <input type="text" name="street" id="istreet" maxlength="50"
                            placeholder="Nome da Rua Aqui" value="<?= htmlspecialchars($_POST['street'] ?? '') ?>" required>
                    </div>
                    <div class="regular-input">
                        <label for="ihouseNum">Número *</label>
                        <input type="number" name="houseNum" id="ihouseNum" max="99999999"
                            placeholder="Número  da Residência Aqui" value="<?= htmlspecialchars($_POST['houseNum'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="regular-input">
                    <label for="idistrict">Bairro *</label>
                    <input type="text" name="district" id="idistrict" maxlength="40"
                        placeholder="Nome do Bairro Aqui" value="<?= htmlspecialchars($_POST['district'] ?? '') ?>" required>
                </div>

                <div class="wrap">
                    <div class="regular-input">
                        <label for="icity">Cidade *</label>
                        <input type="text" name="city" id="icity" maxlength="40"
                            placeholder="Nome da Cidade Aqui" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
                    </div>
                    <div class="regular-input">
                        <label for="istate">Estado: </label>
                        <select name="state" id="istate">
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">PARANÁ</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>
                </div>

                <div class="regular-input">
                    <label for="ireference">Ponto de Referência:</label>
                    <input type="text" name="reference" id="ireference" maxlength="50"
                        value="<?= htmlspecialchars($_POST['reference'] ?? '') ?>">
                </div>

                <div class="regular-input">
                    <label for="ipassword">Senha *</label>
                    <input type="password" name="password" id="ipassword" placeholder="********" required maxlength="30">
                </div>

                <button class="regular-btn">Registrar-se</button>
            </form>
        </div>
    </main>

    <script src="/js/script.js"></script>
</body>
</html>