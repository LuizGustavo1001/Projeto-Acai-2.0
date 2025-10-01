<?php
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";

    require_once '../../composer/vendor/autoload.php';

    use Dotenv\Dotenv;
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Cloudinary;
    use Cloudinary\Api\Upload\UploadApi;
    function printForms(){
        global $mysqli;
        if(isset($_GET["type"])){
            switch($_GET["type"]){
                case "product":
                    if(isset($_POST["printName"])){
                        $verifyName = $mysqli->prepare("SELECT printName, altName FROM product_data WHERE printName = ? OR altName = ?");
                        $verifyName->bind_param("ss", $_POST["printName"], $_POST["altName"]);
                        $verifyName->execute();

                        $result = $verifyName->get_result();
                        $amount = $result->num_rows;
                        $verifyName->close();
                        switch($amount){
                            case 0: // can add the product
                                $name = trim($_POST["printName"]);
                                $altName = trim($_POST["altName"]);

                                $addProduct = $mysqli->prepare("INSERT INTO product_data (printName, altName, brandProduct, typeProduct) VALUES (?, ?, ?, ?)");
                                $addProduct->bind_param("ssss", $name, $altName, $_POST["brandProduct"], $_POST["typeProduct"]);
                                $addProduct->execute();
                                $addProduct->close();
                                header("location: products.php?addProduct=1");
                                exit();
                            default: // there's already a product in the database with the same name as writed
                                header("location: addItem.php?type=product&add=0");
                                break;
                        }
                    }
                    echo "
                        <div class='form-inputs'>
                            <div class='main-title'>
                                <h1>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z'/>
                                    </svg>
                                    Adicionar um Produto
                                </h1>

                                <div class='back-button'>
                                    <a href='products.php'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18'/>
                                        </svg>
                                        Voltar
                                    </a>
                                </div>
                            </div>
                            <div class='form-inputs'>
                                <div class='form-item'>
                                    <label for='iprintName'>Nome Amigável: </label>
                                    <div class='form-input'>
                                        <input type='text' name='printName' id='iprintName' maxlength='60' placeholder='Nome do Produto Aqui'>
                                    </div>
                                </div>
                                <div class='form-item'>
                                    <label for='ialtName'>Nome Alternativo: </label>
                                    <div class='form-input'>
                                        <input type='text' name='altName' id='ialtName' maxlength='40' placeholder='Nome Alternativo do Produto Aqui'>
                                    </div>
                                </div>
                                <div class='form-item'>
                                    <label for='ibrandProd'>Marca:</label>
                                    <div class='form-input'>
                                        <input type='text' name='brandProduct' id='ibrandProd' maxlength='40' placeholder='Marca do Produto Aqui'>
                                    </div>
                                </div>
                                <div class='form-item'>
                                    <label for='itypeProd'>Tipo: </label>
                                    <div class='form-input'>
                                        <select name='typeProduct' id='itypeProd'>
                                            <option value='Cream'>Creme</option>
                                            <option value='Additional'>Adicional</option>
                                            <option value='Other'>Outro Tipo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button>Editar</button>
                        </div>
                    ";
                    break;
                case "prodVersion":
                    if(isset($_POST["nameProduct"])){
                        $verifyName = $mysqli->prepare("SELECT nameProduct FROM product_version WHERE nameProduct = ?");
                        $verifyName->bind_param("s", $_POST["nameProduct"]);
                        $verifyName->execute();

                        $result = $verifyName->get_result();
                        $amount = $result->num_rows;
                        $verifyName->close();
                        switch($amount){
                            case 0: // can add the product
                                $name = trim($_POST["nameProduct"]);
                                $image = "";
                                $dotenv = Dotenv::createImmutable("../../composer");
                                $dotenv->load();
                                $config = new Configuration($_ENV["CLOUDINARY_URL"]);
                                $cld = new Cloudinary($config);
                                $upload = new UploadApi($config);
                                $response = null;
                                $publicId = $name;

                                // changing the picture at the Cloud
                                if (isset($_FILES["imageURL"]) && $_FILES["imageURL"]["error"] === UPLOAD_ERR_OK) {
                                    $fileTmpPath = $_FILES["imageURL"]["tmp_name"];
                                    try {
                                    $response = $upload->upload($fileTmpPath, [
                                        "folder"        => "Projeto_Acai/Products",
                                        "public_id"     => $publicId,
                                        "overwrite"     => true,
                                        "invalidate"    => true
                                    ]);
                                    // changing the image URL value to the real URL(not the image name)
                                    $image = $response['secure_url'];
                                } catch (Exception $e) {
                                    echo "Erro no upload: " . $e->getMessage();
                                }
                                }else{
                                    $imageDefault = "https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png";
                                    try {
                                        $response = $upload->upload($imageDefault, [
                                            "folder"        => "Projeto_Acai/Products",
                                            "public_id"     => $publicId,
                                            "overwrite"     => true,
                                            "invalidate"    => true
                                        ]);
                                        // changing the image URL value to the real URL(not the image name)
                                        $image = $response['secure_url'];
                                        
                                    } catch (Exception $e) {
                                        echo "Erro no upload: " . $e->getMessage();
                                    }
                                }
                                $currentDate = date("Y-m-d");
                                $flavor = $_POST["flavor"];
                                if($_POST["flavor"] == ""){
                                    $flavor = null;
                                }

                                $addProduct = $mysqli->prepare("INSERT INTO product_version (idProduct, nameProduct, imageURL, sizeProduct, flavor, priceProduct, priceDate, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                                $addProduct->bind_param("isssssss", $_POST["idProduct"], $name, $image, $_POST["sizeProduct"], $flavor, $_POST["priceProduct"], $currentDate ,$_POST["availability"]);
                                $addProduct->execute();
                                $addProduct->close();
                                header("location: products.php?addVersion=1");
                                exit();
                            default: // there's already a product in the database with the same name as writed
                                header("location: addItem.php?type=prodVersion&add=0");
                                break;
                        }
                    }
                    
                    echo "
                    <div class='form-inputs'>
                        <div class='main-title'>
                                <h1>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z'/>
                                    </svg>
                                    Adicionar uma Versão de um Produto
                                </h1>
                                <div class='back-button'>
                                    <a href='products.php'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18'/>
                                        </svg>
                                        Voltar
                                    </a>
                                </div>
                            </div>
                        <div class='form-inputs'>
                            <div class='form-item'>
                                <label for='iprodRef'>* Produto Referenciado: </label>
                                <select name='idProduct' id='iprodRef'>";
                                $getProducts = $mysqli->query("SELECT idProduct, printName FROM product_data");
                                while($product = $getProducts->fetch_assoc()){
                                    echo "<option value='{$product['idProduct']}'>{$product['printName']}</option>";
                                }
                            echo "</select>
                            </div>
                            <div class='form-item'>
                                <label for='iprodPicture'>Foto:</label>
                                <div class='form-input'>
                                    <input type='file' name='imageURL' id='iprodPicture'>
                                </div>
                            </div>

                            <div class='form-item'>
                                <label for='inameProd'>* Nome: </label>
                                <div class='form-input'>
                                    <input type='text' name='nameProduct' id='inameProd' maxlength='40' placeholder='Nome do Produto Aqui' required>
                                </div>
                            </div>

                            <div class='form-item'>
                                <label for='isizeProduct'>* Tamanho:</label>
                                <div class='form-input'>
                                    <input type='text' name='sizeProduct' id='isizeProduct' maxlength='20' placeholder='Tamanho/Peso do Produto Aqui' required>
                                </div>
                            </div>

                            <div class='form-item'>
                                <label for='iflavor'>Sabor:</label>
                                <div class='form-input'>
                                    <input type='text' name='flavor' id='iflavor' maxlength='40' placeholder='Sabor do Produto Aqui'>
                                </div>
                            </div>

                            <div class='form-item'>
                                <label for='ipriceProd'>* Preço Individual: </label>
                                <div class='form-input'>
                                    <input type='text' name='priceProduct' id='ipriceProd' placeholder='Preço do Produto Aqui' required>
                                </div>
                            </div>

                            <div class='form-item'>
                                <label for='iavailability'>Disponibilidade: </label>
                                <select name='availability' id='iavailability'>
                                    <option value='0'>Indisponível</option>
                                    <option value='1'>Disponível</option>
                                </select>
                            </div>
                        </div>
                        <button>Editar</button>
                        </div>
                    ";
                    break;
                default:
                    echo "
                        <div class='back-button'>
                            <a href='products.php'>
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18'/>
                                </svg>
                                Voltar
                            </a>
                        </div>
                        Erro: Nenhuma Categoria de Item encontrada com o Tipo selecionado
                    ";
                    break;
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

    <link rel="stylesheet" href="../CSS/mannager-styles.css">
    <link rel="stylesheet" href="../CSS/mannager-settings-styles.css">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <style>
         .main-title{
            display: flex;
            align-items: left;
            flex-direction: column-reverse;
   
            margin: 0;
        }

        .back-button a{
            margin: 0;
        }
    </style>

    <title>Açaí e Polpas Amazônia - Produtos</title>
</head>

<body>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <?php printForms()?>
        </form>
    </main>
</body>
</html>