<?php
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";

    require_once __DIR__ . '../../composer/vendor/autoload.php';

    use Dotenv\Dotenv;
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Cloudinary;
    use Cloudinary\Api\Upload\UploadApi; # API para upload de imagens

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $config = new Configuration($_ENV["CLOUDINARY_URL"]);

    $cld = new Cloudinary($config);

    $upload = new UploadApi($config);
    $response = null;

    if(isset($_GET["makeAdmin"])){
        $publicId = "adminPic" . str_pad($_SESSION["idUser"], 3, "0", STR_PAD_LEFT); 
        $_FILES["file"] = "https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757086840/default_user_icon_yp10ih.png";
        $fileTmpPath = $_FILES["file"]["tmp_name"];

        try {
            $response = $upload->upload($fileTmpPath, [
                "folder" => "Users-Pictures",
                "public_id" => $publicId,
                "overwrite" => true,
                "invalidate" => true
            ]);

            $imageUrl = $response['secure_url'];
            $password = "teste";

            $query = $mysqli->prepare("
                INSERT INTO admin_data (adminName, adminMail, adminPhone, adminPicture, adminPassword) VALUES
                    (?, ?, ?, ?, ?)
            ");
            $query->bind_param("sssss", $_SESSION["userName"], $_SESSION["userMail"], $_SESSION["userPhone"], $imageURL, $password);

            if($query->execute()){
                $idAdmin = $mysqli->insert_id;
                $query->close();
                $query = $mysqli->prepare("
                    INSERT INTO admin_address (idAdmin, city, district, localNum, referencePoint, state) VALUES
                        (?, ?, ?, ?, ?)
                ");
                $query->bind_param("isssss", $idAdmin, $_SESSION["city"], $_SESSION["district"], $_SESSION["localNum"], $_SESSION["referencePoint"], $_SESSION[""], $_SESSION["state"]);
                
                if($query->execute()){
                    $query->close();

                    $query = $mysqli->prepare("DELETE FROM client_data WHERE idClient = ?");
                    $query->bind_param("i", $idAdmin);

                    if($query->execute()){
                        header("location: admin.php?NewAdmin=1");
                    }

                }
            }
        } catch (Exception $e) {
            echo "Erro no upload: " . $e->getMessage();
        }
    }


    $allowedInputs = ["adminName", "adminPhone", "adminPicture", "district", "localNum", "referencePoint", "street", "city", "state"];

    for($i = 0; $i < sizeof($allowedInputs); $i++){
        if(isset($_POST[$allowedInputs[$i]])){
            $newValue = trim($_POST[$allowedInputs[$i]]);
            if($newValue != ""){
                if($newValue != $_SESSION[$allowedInputs[$i]]){
                        $dbTable = match($allowedInputs[$i]){
                        "adminName", "adminPhone", "adminPicture"           => "admin_data",
                        default                                             => "admin_address"
                    };
                    
                    $changeData = $mysqli->prepare(
                        "UPDATE $dbTable SET $allowedInputs[$i] = ? WHERE idAdmin = ?;"
                    );
                    
                    $changeData->bind_param("ss", $newValue, $_SESSION["idUser"]);
                    
                    if($changeData->execute()){
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
                        header("location: settings.php?columnChange=" . $allowedInputs[(int)$i]);
                    }else{
                        header("location: ../errorPage.php");
                        exit();
                    }
                }else if($newValue == $_SESSION[$allowedInputs[$i]] and ($allowedInputs[$i] != "state" && $allowedInputs[$i] != "referencePoint")){
                    header("location: settings.php?sameData=". $allowedInputs[(int)$i]);
                    exit();
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap"
        rel="stylesheet">

    <?php faviconOut(); ?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="stylesheet" href="../CSS/mannager.css">
    <link rel="stylesheet" href="../CSS/mannager-settings.css">

    <title>Açaí e Polpas Amazônia - Administradores</title>
</head>

<body>
    <main>
        
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

        <form action="" method="POST"> 
            <button type="button" onclick="window.location.href='changeItem.php?makeAdmin=1'">Tornar-lo um Administrador</button>
            <div class="form-inputs">
                <div class="form-item">
                    <label for="iadminPicture">Foto de Perfil: <small>Tamanho Máximo: 2MB</small></label>
                    <div class="form-input">
                        <img src="<?php echo $_SESSION["adminPicture"]?>" alt="Current Admin Picture">
                        <input type="file" name="adminPicture" id="iadminPicture">
                    </div>
                </div>
                <div class="form-item">
                    <label for="iadminName">Nome: </label>
                    <div class="form-input">
                        <input type="text" name="adminName" id="iadminName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['userName']; ?>" >
                    </div>
                </div>
                <div class="form-item">
                    <label for="iadminPhone">Telefone de Contato:</label>
                    <div class="form-input">
                        <input type="text" name="adminPhone" id="iadminPhone" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['userPhone']; ?>" >
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
                            <option value="AC" <?php optionSelect("state","AC") ?>>Acre</option>
                            <option value="AL" <?php optionSelect("state","AL") ?>>Alagoas</option>
                            <option value="AP" <?php optionSelect("state","AP") ?>>Amapá</option>
                            <option value="AM" <?php optionSelect("state","AM") ?>>Amazonas</option>
                            <option value="BA" <?php optionSelect("state","BA") ?>>Bahia</option>
                            <option value="CE" <?php optionSelect("state","CE") ?>>Ceará</option>
                            <option value="DF" <?php optionSelect("state","DF") ?>>Distrito Federal</option>
                            <option value="ES" <?php optionSelect("state","ES") ?>>Espírito Santo</option>
                            <option value="GO" <?php optionSelect("state","GO") ?>>Goiás</option>
                            <option value="MA" <?php optionSelect("state","MA") ?>>Maranhão</option>
                            <option value="MT" <?php optionSelect("state","MT") ?>>Mato Grosso</option>
                            <option value="MS" <?php optionSelect("state","MS") ?>>Mato Grosso do Sul</option>
                            <option value="MG" <?php optionSelect("state","MG") ?>>Minas Gerais</option>
                            <option value="PA" <?php optionSelect("state","PA") ?>>Pará</option>
                            <option value="PB" <?php optionSelect("state","PB") ?>>Paraíba</option>
                            <option value="PR" <?php optionSelect("state","PR") ?>>PARANÁ</option>
                            <option value="PE" <?php optionSelect("state","PE") ?>>Pernambuco</option>
                            <option value="PI" <?php optionSelect("state","PI") ?>>Piauí</option>
                            <option value="RJ" <?php optionSelect("state","RJ") ?>>Rio de Janeiro</option>
                            <option value="RN" <?php optionSelect("state","RN") ?>>Rio Grande do Norte</option>
                            <option value="RS" <?php optionSelect("state","RS") ?>>Rio Grande do Sul</option>
                            <option value="RO" <?php optionSelect("state","RO") ?>>Rondônia</option>
                            <option value="RR" <?php optionSelect("state","RR") ?>>Roraima</option>
                            <option value="SC" <?php optionSelect("state","SC") ?>>Santa Catarina</option>
                            <option value="SP" <?php optionSelect("state","SP") ?>>São Paulo</option>
                            <option value="SE" <?php optionSelect("state","SE") ?>>Sergipe</option>
                            <option value="TO" <?php optionSelect("state","TO") ?>>Tocantins</option>
                        </select>
                    </div>
                </div>
                <div class="form-item">
                    <label for="ireferencePoint">Ponto de Referência: </label>
                    <div class="form-input">
                        <input type="text" name="referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['referencePoint']; ?>">
                    </div>
                </div>
            </div>
            <button>Editar</button>

            <div style="display: flex; justify-content: space-between; border: none">
                <button type="button" onclick="window.location.href='../account/changes/newPassword.php'">Alterar Senha</button>
                
                <button type="button" onclick="window.location.href='../account/changes/newEmail.php'">Alterar Email</button>
            </div>
                
        </form>
        

    </main>


</body>

</html>