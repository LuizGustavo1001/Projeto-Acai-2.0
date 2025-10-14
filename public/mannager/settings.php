<?php
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";
    include "../printStyles.php";

    require_once '../../composer/vendor/autoload.php';

    use Dotenv\Dotenv;
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Cloudinary;
    use Cloudinary\Api\Upload\UploadApi;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        changeColumn();
    }

    function changeColumn(){
        global $mysqli;
        $allowedInputs = [
            "userName", "userPhone", "adminPicture", "district", 
            "localNum", "referencePoint", "street", "city", "state"
        ];
        $getChanges = "";

        for($i = 0; $i < sizeof($allowedInputs); $i++){
            $inputName = $allowedInputs[$i];
            $newValue = null;

            switch($inputName){
                case "adminPicture":
                    // different treatment for adminPicture, because it's a file
                    if (isset($_FILES["adminPicture"]) && $_FILES["adminPicture"]["error"] === UPLOAD_ERR_OK) {
                        // changing the profile picture at the Cloud
                        $dbTable = "admin_data";
                        
                        $dotenv = Dotenv::createImmutable("../../composer");
                        $dotenv->load();
                        $config = new Configuration($_ENV["CLOUDINARY_URL"]);
                        $cld = new Cloudinary($config);
                        $upload = new UploadApi($config);
                        $response = null;

                        $publicId = "adminPic" . str_pad($_SESSION["idUser"], 3, "0", STR_PAD_LEFT);
                        $fileTmpPath = $_FILES["adminPicture"]["tmp_name"];

                        try {
                            $response = $upload->upload($fileTmpPath, [
                                "folder"        => "Users-Pictures",
                                "public_id"     => $publicId,
                                "overwrite"     => true,
                                "invalidate"    => true
                            ]);
                            // changing the image URL value to the real URL(not the image name)
                            $newValue = $response['secure_url'];
                            
                        } catch (Exception $e) {
                            echo "Erro no upload: " . $e->getMessage();
                        }
                    }
                    break;
                default:
                    if (isset($_POST[$inputName])) {
                        $newValue = trim($_POST[$inputName]);
                        if($inputName == "referencePoint" or $inputName == "state"){
                            // evitar de trocar valores que podem ser nulos ou que são opções
                            if($newValue == $_SESSION[$inputName]){
                                $newValue = null;
                            }
                        }
                        $dbTable = "user_data";
                    }
                    break;
            }

            // if there's a value, try to update at the database
            if (!empty($newValue)) {
                $query = match ($dbTable) {
                    "admin_data"    => "UPDATE admin_data SET $inputName = ? WHERE idAdmin = ?",
                    default         => "UPDATE user_data SET $inputName  = ? WHERE idUser  = ?"
                };

                $changeData = $mysqli->prepare($query);
                $changeData->bind_param("si", $newValue, $_SESSION["idUser"]);

                if($newValue != $_SESSION[$inputName]){
                    $changeData->execute();
                    $_SESSION[$inputName] = $newValue;
                    $getChanges .= "c{$inputName}=1&";
                }else{
                    $getChanges .= "c{$inputName}=2&";
                }
                $changeData->close();
            }
        }
        header("location: settings.php?" . rtrim($getChanges, "&"));
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

    <?php faviconOut()?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "mannager") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "mannagerSettings") ?>">

    <style>
        .main-title h1{
            flex-direction: row;
            justify-content: space-between;
            font-size: 1em;
            
        }

        @media(min-width: 1024px){
            .main-title h1{
                font-size: 1.2em;
            }
        }

        form{
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
        }

    </style>

    <title>Açaí e Polpas Amazônia - Configurações</title>
</head>

<body>
    <main>
        <div>
            <div class="back-button">
                <a href="admin.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Voltar
                </a>
            </div>
            <div class="main-title">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    Alterar Dados Pessoais
                </h1>
            
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <?php
                $fieldLabels = [
                    "userName"       => "Nome de Usuário",
                    "userPhone"      => "Telefone de Contato",
                    "district"       => "Bairro",
                    "localNum"       => "Número da Residência",
                    "referencePoint" => "Ponto de Referência",
                    "street"         => "Rua",
                    "city"           => "Cidade",
                    "state"          => "Estado",
                    "adminPicture"   => "Foto de Perfil"
                ];

                // Percorre todos os parâmetros GET
                foreach ($_GET as $key => $value) {
                    if (preg_match('/^c(.+)$/', $key, $matches)) {
                        $field = $matches[1];

                        if (isset($fieldLabels[$field])) {
                            $label = $fieldLabels[$field];

                            switch ($value) {
                                case "1":
                                    echo "<p class='successText'>Sucesso ao alterar <strong>{$label}</strong></p>";
                                    break;
                                case "2":
                                    echo "
                                    <p class='errorText'>
                                        <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                                        O valor inserido em <strong>{$label}</strong> é o mesmo já cadastrado.
                                    </p>";
                                    break;
                            }
                        }
                    }
                }
            ?>
            <div class="form-item">
                <label for="iadminPicture">Foto de Perfil:</label>
                <div class="form-input">
                    <img src="<?php echo $_SESSION["adminPicture"]?>" alt="Current Admin Picture">
                    <input type="file" name="adminPicture" id="iadminPicture">
                </div>
            </div>

            <div class="form-item">
                <label for="iadminName">Nome: </label>
                <div class="form-input">
                    <input type="text" name="userName" id="iadminName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['userName']; ?>" >
                </div>
            </div>

            <div class="form-item">
                <label for="iadminPhone">Telefone de Contato:</label>
                <div class="form-input">
                    <input type="text" name="userPhone" id="iadminPhone" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['userPhone']; ?>" >
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
                <label for="istate">Estado: </label>
                <div class="form-input">
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

            <div class="form-item">
                <label for="ireferencePoint">Ponto de Referência: </label>
                <div class="form-input">
                    <input type="text" name="referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['referencePoint']; ?>">
                </div>
            </div>
            <button>Editar</button>

            
        </form>
        <div style="display: flex; justify-content: space-between; border: none">
            <a href="../account/changes/newPassword.php">Alterar Senha</a>
            <a href="../account/changes/newEmail.php">Alterar Email</a>
        </div>
    </main>
</body>
</html>