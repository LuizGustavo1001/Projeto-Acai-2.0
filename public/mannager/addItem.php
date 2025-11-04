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
                                $idProduct = $mysqli->insert_id;

                                addChange("Adicionar", "product_data", $idProduct, "");

                                header("location: products.php?addProduct=1");
                                exit();
                            default: // there's already a product in the database with the same name as writed
                                header("location: addItem.php?type=product&add=0");
                                break;
                        }
                    }
                    echo "
                        <div class='form-title'>
                            <div class='back-button'>
                                    <a href='products.php'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3'/>
                                        </svg>
                                        Voltar
                                    </a>
                                </div>
                            <h1>
                                <div>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                        <path d='M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z'/>
                                        <path fill-rule='evenodd' d='m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd'/>
                                    </svg>
                                    <span>Adicionar</span> produto
                                </div>
                            </h1>
                        </div>

                        <div class='form-main'>
                            <div class='form-inputs'>
                                <div class='form-regular-inputs'>
                                    <div class='form-item regular-input'>
                                        <label for='iprintName'>Nome Amigável: </label>
                                        <div class='form-input'>
                                            <input type='text' name='printName' id='iprintName' maxlength='60' placeholder='Nome do Produto Aqui' required>
                                        </div>
                                    </div>
                                    <div class='form-item regular-input'>
                                        <label for='ialtName'>Nome Alternativo: </label>
                                        <div class='form-input'>
                                            <input type='text' name='altName' id='ialtName' maxlength='40' placeholder='Nome Alternativo do Produto Aqui' required>
                                        </div>
                                    </div>
                                    <div class='form-item regular-input'>
                                        <label for='ibrandProd'>Marca:</label>
                                        <div class='form-input'>
                                            <input type='text' name='brandProduct' id='ibrandProd' maxlength='40' placeholder='Marca do Produto Aqui' required>
                                        </div>
                                    </div>
                                    <div class='form-item regular-input'>
                                        <label for='itypeProd'>Tipo: </label>
                                        <div class='form-input'>
                                            <select name='typeProduct' id='itypeProd'>
                                                <option value='Creme'>Creme</option>
                                                <option value='Adicional'>Adicional</option>
                                                <option value='Outro'>Outro Tipo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class='regular-button'>Adicionar</button>
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

                                $IdVersion = $mysqli->insert_id;
                                addChange("Adicionar", "product_version", $IdVersion, "");
                                header("location: products.php?addVersion=1");
                                exit();
                            default: // there's already a product in the database with the same name as writed
                                header("location: addItem.php?type=prodVersion&add=0");
                                break;
                        }
                    }
                    
                    echo "
                        <div class='form-title'>
                            <div class='back-button'>
                                <a href='products.php'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3'/>
                                    </svg>
                                    Voltar
                                </a>
                            </div>
                            <h1>
                                <div>
                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                        <path d='M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z'/>
                                        <path fill-rule='evenodd' d='m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd'/>
                                    </svg>
                                    <span>Adicionar</span> versão de um produto
                                </div>
                            </h1>
                        </div>

                        <div class='form-main'>
                            <div class='form-inputs'>
                                <div class='form-picture'>
                                    <div class='form-item regular-input'>
                                        <label for='iprodPicture'>Foto:</label>
                                        <div class='form-input form-picture-display'>
                                            <input type='file' name='imageURL' id='iprodPicture'>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-regular-inputs'>
                                    <div class='form-item regular-input'>
                                        <label for='iprodRef'>* Produto Referenciado: </label>
                                        <select name='idProduct' id='iprodRef'>";
                                        $getProducts = $mysqli->query("SELECT idProduct, printName FROM product_data");
                                        while($product = $getProducts->fetch_assoc()){
                                            echo "<option value='{$product['idProduct']}'>{$product['printName']}</option>";
                                        }
                                        echo "</select>
                                    </div>

                                    <div class='form-item regular-input'>
                                        <label for='inameProd'>* Nome: </label>
                                        <div class='form-input'>
                                            <input type='text' name='nameProduct' id='inameProd' maxlength='40' placeholder='Nome do Produto Aqui' required>
                                        </div>
                                    </div>

                                    <div class='form-item regular-input'>
                                        <label for='isizeProduct'>* Tamanho:</label>
                                        <div class='form-input'>
                                            <input type='text' name='sizeProduct' id='isizeProduct' maxlength='20' placeholder='Tamanho/Peso do Produto Aqui' required>
                                        </div>
                                    </div>

                                    <div class='form-item regular-input'>
                                        <label for='iflavor'>Sabor:</label>
                                        <div class='form-input'>
                                            <input type='text' name='flavor' id='iflavor' maxlength='40' placeholder='Sabor do Produto Aqui'>
                                        </div>
                                    </div>

                                    <div class='form-item regular-input'>
                                        <label for='ipriceProd'>* Preço Individual: </label>
                                        <div class='form-input'>
                                            <input type='text' name='priceProduct' id='ipriceProd' placeholder='Preço do Produto Aqui' required>
                                        </div>
                                    </div>

                                    <div class='form-item regular-input'>
                                        <label for='iavailability'>Disponibilidade: </label>
                                        <select name='availability' id='iavailability'>
                                            <option value='1'>Disponível</option>
                                            <option value='0'>Indisponível</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button class='regular-button'>Adicionar</button>
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
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php displayFavicon()?>

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "mannagerSettings") ?>">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <title>Açaí e Polpas Amazônia - Produtos</title>
</head>

<body>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <?php 
                if(isset($_GET['add'])){
                    echo "
                        <div class=\"errorText\">
                            <i class=\"fa-solid fa-triangle-exclamation\"></i>
                            <p>
                                Erro: <strong>Nome Amigável </strong> ou <strong>Nome Alternativo</strong> já estão cadastrador no sistema. Tente novamente com outro nome.
                            </p>
                        </div>
                    ";
                }
            ?>
            <?php printForms()?>
        </form>
    </main>
</body>
</html>