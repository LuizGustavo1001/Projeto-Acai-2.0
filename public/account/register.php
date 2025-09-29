<?php
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";

    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();
    }

    if (isset($_SESSION["userMail"])) {
        header("location: account.php");
        exit();
    }

    if (isset($_GET["userAdd"])) {
        header("location: login.php?registered=1");
        exit();
    }

    if(
        isset(
        $_POST["name"],
        $_POST["email"],
        $_POST["phone"],
        $_POST["street"],
        $_POST["houseNum"],
        $_POST["district"],
        $_POST["city"],
        $_POST["reference"],
        $_POST["password"]
    )
    ){
        addUser();
    }

    function addUser()
    {
        // function to register a new user as client(default)
        global $mysqli;

        $inputEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        $verifyEmail = $mysqli->prepare("
            SELECT userMail 
            FROM user_data 
            WHERE userMail = ?
        ");
        $verifyEmail->bind_param("s", $inputEmail);

        if ($verifyEmail->execute()) {
            $resultEmail = $verifyEmail->get_result();
            $verifyEmail->close();

            switch ($resultEmail->num_rows) {
                case 0:
                    // there's no user registered with the email input -> register new one
                    $domain = substr(strrchr($inputEmail, "@"), 1);
                    if (checkdnsrr($domain, "MX")) {
                        // verify if the email domain exists
                        $name = mb_convert_case($_POST['name'], MB_CASE_TITLE, "UTF-8");
                        $phone = $_POST["phone"];
                        $street = $_POST["street"];
                        $houseNum = $_POST["houseNum"];
                        $district = mb_convert_case($_POST["district"], MB_CASE_TITLE, "UTF-8");
                        $city = mb_convert_case($_POST["city"], MB_CASE_TITLE, "UTF-8");
                        $reference = isset($_POST["reference"]) ? $_POST["reference"] : null;
                        $state = $_POST["state"];
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        $insertData = $mysqli->prepare("
                                INSERT INTO user_data (userName, userMail, userPassword, userPhone, district, localNum, referencePoint, street, city, state) VALUES 
                                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                                "
                        );
                        $insertData->bind_param("ssssssssss", $name, $inputEmail, $password, $phone, $district, $houseNum, $reference, $street, $city, $state);
                        if ($insertData->execute()) {
                            $insertData->close();

                            // insert the user as client(default)
                            $clientId = $mysqli->insert_id;
                            $insertClient = $mysqli->prepare("INSERT INTO client_data (idClient) VALUES (?)");
                            $insertClient->bind_param("i", $clientId);
                            $insertClient->execute();
                            $insertClient->close();

                            header("location: register.php?userAdd=1");
                            exit();
                        } else {
                            header("location: ../errorPage.php");
                            exit();
                        }
                    } else {
                        header("location: register.php?invalidDomain=1");
                        exit();
                    }
                default:
                    header("location: register.php?emailExists=1");
                    exit();
            }
        } else {
            header("location: ../errorPage.php");
            exit();
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
    <link
        href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap"
        rel="stylesheet">

    <?php faviconOut(); ?>

    <link rel="stylesheet" href="../CSS/general-style.css">
    <link rel="stylesheet" href="../CSS/account-styles.css">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <style>
        .account-right-div {
            background: url(https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751724099/signUpBg_l5rd50.png) center center;
            background-size: cover;
            background-repeat: no-repeat;

        }
        main{
            margin: 0;
            height: 120vh;
        }
    </style>

    <title>Açaí e Polpas Amazônia - Registrar</title>

</head>

<body>
    <section class="account-hero">
        <div class="account-left-div">

            <?php headerOut(1) ?>

            <main>
                <section class="account-header">
                    <ul>
                        <li><a href="../index.php">Página Principal</a></li>
                        <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>

                        <li><a href="login.php">Página de Login</a></li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>

                        <li><a href="register.php">Página de Resgistro</a></li>
                    </ul>

                </section>

                <section class="account-forms">
                    <div class="section-header-title">
                        <h1>Área de Registro</h1>
                        <p><strong>Registre-se</strong> para <strong>Continuar Comprando</strong> em nosso site</p>
                    </div>
                    <?php
                        if (isset($_GET["invalidDomain"])) {
                            echo "
                                    <p class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        Erro: Domínio do Email Digitado <strong>É Inválido</strong>, tente novamente com outro domínio ou <strong>cadastre-se</strong> no link abaixo
                                    </p>
                                ";
                        }
                        if (isset($_GET["emailExists"])) {
                            echo "
                                    <p class=\"errorText\">
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                                        Email <strong>já cadastrado</strong>, tente novamente com <strong>Outro Endereço de Email</strong>
                                    </p>
                                ";
                        }

                    ?>
                    <form method="post">
                        <div class="form-item">
                            <label for="iname">Nome: <span>*</span></label>
                            <input type="text" name="name" id="iname" maxlength="30" minlength="8"
                                placeholder="Nome Completo" required>
                        </div>

                        <div class="form-item">
                            <label for="iemail">Email: <span>*</span></label>
                            <input type="email" name="email" id="iemail" maxlength="50" placeholder="email@exemplo.com"
                                required>
                        </div>

                        <div class="form-item">
                            <label for="inumber">Telefone de Contato: <span>*</span></label>
                            <input type="text" name="phone" id="inumber" minlength="15" maxlength="16"
                                pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="(XX) 9 8888 8888" required>
                        </div>

                        <div class="form-item">
                            <label for="istreet">Rua: <span>*</span></label>
                            <input type="text" name="street" id="istreet" maxlength="50" placeholder="Nome da Rua Aqui"
                                required>
                        </div>

                        <div class="form-item">
                            <label for="ihouseNum">Número da Residência: <span>*</span></label>
                            <input type="number" name="houseNum" id="ihouseNum" max="99999999"
                                placeholder="Número  da Residência Aqui" required>
                        </div>

                        <div class="form-item">
                            <label for="idistrict">Bairro: <span>*</span></label>
                            <input type="text" name="district" id="idistrict" maxlength="40"
                                placeholder="Nome do Bairro Aqui" required>
                        </div>

                        <div class="form-item">
                            <label for="icity">Cidade: <span>*</span></label>
                            <input type="text" name="city" id="icity" maxlength="40" placeholder="Nome da Cidade Aqui"
                                required>
                        </div>

                        <div class="form-item">
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

                        <div class="form-item">
                            <label for="ireference">Ponto de Referência:</label>
                            <input type="text" name="reference" id="ireference" maxlength="50">
                        </div>

                        <div class="form-item">
                            <label for="ipassword">Senha: <span>*</span></label>
                            <input type="password" name="password" id="ipassword" maxlength="30"
                                placeholder="• • • • • • • •" required>
                        </div>

                        <div>
                            <button>
                                Cadastrar-se
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </section>
            </main>
            <?php footerOut(); ?>
        </div>
        <div class="account-right-div"></div>
    </section>
</body>
</html>