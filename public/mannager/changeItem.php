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

    // trying to modify your own profile as admin
    if(isset($_GET["id"], $_GET["category"])){
        if($_GET["id"] == $_SESSION["idUser"] and $_GET["category"] == "admin"){ 
            // trying to edit your own profile
            header("location: settings.php");
            exit();
        }
    }
    
    function printLabel($fieldLabels)
    {
        foreach ($_GET as $key => $value) {
            if (preg_match('/^c(.+)$/', $key, $matches)) {
                $field = $matches[1];
                if (isset($fieldLabels[$field])) {
                    $label = $fieldLabels[$field];
                    switch ($value) {
                        case '1':
                            echo "
                                <div class=\"successText\">
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z' />
                                    </svg>
                                    <p>Sucesso ao alterar <strong>{$label}</strong></p>
                                </div>
                            ";
                            break;
                        case '2':
                            echo "
                                <div class=\"errorText\">
                                    <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                                    <p>O valor inserido em <strong>{$label}</strong> é o mesmo já cadastrado.</p>
                                </div>
                            ";
                            break;
                    }
                }
            }
        }
    }

    //removing user or product
    function removeItem($local, $id){
        global $mysqli;
        $table = match($local){
            "user"      => "user_data",
            "product"   => "product_data",
            "version"   => "product_version",
            default     => "",
        };
        
        $idItem = match($local){
            "user"      => "idUser",
            "product"   => "idProduct",
            "version"   => "idVersion",
        };

        if($table == ""){
            header("location: ../errorPage.php");
            exit();
        }
        
        $removeColumn = $mysqli->prepare("DELETE FROM $table WHERE $idItem = ?");
        $removeColumn->bind_param("i", $id);
        if($removeColumn->execute()){
            $removeColumn->close();
            
            addChange("Remover", $table, $id, "");
            $location = match($local){
                "user" => "clients.php",
                "product", "version" => "products.php",
                default => "",
            };
            if($location == ""){
                header("location: ../errorPage.php");
                exit();
            }
            header("location: {$location}?removeS=1");
            exit();
        }else{
            header("location: ../errorPage.php");
            exit();
        }
    }

    function displayForms(){
        global $mysqli;
        if (isset($_GET["category"])) {
            switch($_GET['category']){
                case "client":
                    $table = "user_data";
                    $idType = "idUser";
                    $pageLink = "clients.php";
                    $name = "Cliente";
                    $pageTitle = "<span>Alterar dados de um</span> Cliente";
                    $pageSvg = "
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path fill-rule='evenodd' d='M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z' clip-rule='evenodd' />
                        </svg>
                    ";
                    $removeText = "<a href=\"changeItem.php?category=client&id=". $_GET["id"] ."&remove=1\">Remover Cliente</a>";
                    break;
                case "admin":
                    $table = "user_data";
                    $idType = "idUser";
                    $pageLink = "admin.php";
                    $name = "Admin";
                    $pageTitle = "<span>Alterar dados de um</span> Administrador";
                    $pageSvg = "
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path fill-rule='evenodd' d='M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z' clip-rule='evenodd' />
                            <path d='M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z' />
                        </svg>
                    ";
                    $removeText = "";
                    break;
                case "product":
                    $table = "product_data";
                    $idType = "idProduct";
                    $pageLink = "products.php";
                    $name = "Produto";
                    $pageTitle = "<span>Alterar dados de um </span> Produto";
                    $pageSvg = "
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path d='M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z'/>
                            <path fill-rule='evenodd' d='m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd'/>
                        </svg>
                    ";
                    $removeText = "<a href=\"changeItem.php?category=product&id=". $_GET["id"] ."&remove=1\">Remover Produto</a>";
                    break;
                case "version":
                    $table = "product_version";
                    $idType = "idVersion";
                    $pageLink = "products.php";
                    $name = "Versão";
                    $pageTitle = "<span>Alterar dados da</span> Versão de um Produto";
                    $pageSvg = "
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path d='M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z'/>
                            <path fill-rule='evenodd' d='m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd'/>
                        </svg>
                    ";
                    $removeText = "<a href=\"changeItem.php?category=version&id=". $_GET["id"] ."&remove=1\">Remover Versão</a>";
                    break;
                default:
                    $table = "";
                    $idType = "";
                    $pageLink = "admin.php";
                    $name = "";
                    $pageTitle = "Erro";
                    $pageSvg = "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z' />
                        </svg>
                    ";
                    $removeText = "";
                    break;
            }

            echo "
                <div class='form-title'>
                    <div class='back-button'>
                            <a href='{$pageLink}'>
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3'/>
                                </svg>
                                Voltar
                            </a>
                        </div>
                    <h1>
                        <div>
                            {$pageSvg}
                            {$pageTitle}
                        </div>
                        {$removeText}
                    </h1>
                </div>
            ";

            if($table == ""){
                echo "<p>Nenhum item encontrado com a categoria selecionada: <strong>{$name}</strong>.</p>";
            }else{
                
                $verifyId = $mysqli->prepare("SELECT  * FROM $table WHERE $idType = ?");
                $verifyId->bind_param("i", $_GET["id"]);
                $verifyId->execute();
                $result = $verifyId->get_result();
                $amount = $result->num_rows;
                $verifyId->close();

                if($amount == 0){
                    echo "<p>Erro: Nenhum {$name} Encontrado com o Identificador selecionado</p>";
                }else{
                    $changes = "";
                    $fieldLabels = [
                        'userName'          => 'Nome de Usuário',
                        'userPhone'         => 'Telefone de Contato',
                        'district'          => 'Bairro',
                        'localNum'          => 'Número da Residência',
                        'referencePoint'    => 'Ponto de Referência',
                        'street'            => 'Rua',
                        'city'              => 'Cidade',
                        'state'             => 'Estado',
                        'adminPicture'      => 'Foto de Perfil',
                        'imageURL'          => 'Foto do Produto',
                        'nameProduct'       => 'Nome da Versão do Produto',
                        'printName'         => 'Nome Amigável do Produto',
                        'realName'          => 'Nome Real do Produto',
                        'sizeProduct'       => 'Tamanho do Produto',
                        'flavor'            => 'Sabor do Produto',
                        'brandProduct'      => 'Marca do Produto',
                        'priceProduct'      => 'Preço Individual do Produto',
                        'availability'      => 'Disponibilidade do Produto',
                        'typeProduct'       => 'Tipo do Produto',
                    ];

                    printLabel($fieldLabels);

                    switch ($_GET['category']) {
                        case "client":
                            $getClient = $mysqli->prepare("
                                SELECT idUser, userName, userPhone, street, localNum, district, city, state, referencePoint 
                                FROM user_data 
                                WHERE idUser = ?
                            ");
                            $getClient->bind_param("i", $_GET["id"]);
                            $getClient->execute();
                            $result = $getClient->get_result();

                            $getClient->close();
                            $client = $result->fetch_assoc();

                            $changes = verifyChanges($client);
                            if ($changes != "") {
                                header("location: changeItem.php?category=client&id=" . $client["idUser"] . "&" . $changes);
                                exit();
                            }

                            if (isset($_GET["makeAdmin"])) {
                                // client into administrator
                                $dotenv     = Dotenv::createImmutable("../../composer");
                                $dotenv->load();
                                $config     = new Configuration($_ENV["CLOUDINARY_URL"]);
                                $cld        = new Cloudinary($config);
                                $upload     = new UploadApi($config);
                                $response   = null;

                                // default administrator image
                                $imageDefault = "https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757086840/default_user_icon_yp10ih.png";
                                $publicId = "adminPic" . str_pad($client["idUser"], 3, "0", STR_PAD_LEFT);
                                
                                try {
                                    $response = $upload->upload($imageDefault, [
                                        "folder" => "Users-Pictures",
                                        "public_id" => $publicId,
                                        "overwrite" => true,
                                        "invalidate" => true
                                    ]);

                                    $imageUrl = $response['secure_url'];

                                    // removing idUser(id_client) from client_data
                                    // adding in admin_data
                                    $removeFromClient = $mysqli->prepare("DELETE FROM client_data WHERE idClient = ?");
                                    $removeFromClient->bind_param("i", $client["idUser"]);
                                    $removeFromClient->execute();
                                    $removeFromClient->close();

                                    $addToAdmin = $mysqli->prepare("INSERT INTO admin_data (idAdmin, adminPicture) VALUES(?, ?) ");
                                    $addToAdmin->bind_param("is", $client["idUser"], $imageUrl);

                                    if ($addToAdmin->execute()) {
                                        $addToAdmin->close();
                                        header("location: admin.php?NewAdmin=1");
                                        exit();
                                    } else {
                                        $addToAdmin->close();
                                        echo "Erro ao adicionar admin: " . $addToAdmin->error;
                                    }
                                } catch (Exception $e) {
                                    echo "Erro no upload: " . $e->getMessage();
                                }
                            }
                            if(isset($_GET["remove"])){
                                removeItem("user", $_GET["id"]);
                            }

                            echo "
                            <a href='changeItem.php?category=client&makeAdmin=1&id=" . $_GET["id"] . "' class='link-button' style='margin-bottom: 1em'>Torná-lo um Administrador</a>
                                <div class='form-main'>
                                    <div class='form-inputs'>
                                        <div class='form-regular-inputs'>
                                            <div class='form-item regular-input'>
                                                <label for='iclientName'>Nome: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='userName' id='iclientName' maxlength='30' minlength='8' placeholder='" . $client['userName'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='iclientPhone'>Telefone de Contato:</label>
                                                <div class='form-input'>
                                                    <input type='text' name='userPhone' id='iclientPhone' minlength='15' maxlength='16' pattern='\(\d{2}\) \d \d{4} \d{4}' placeholder='" . $client['userPhone'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='istreet'>Rua: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='street' id='istreet' maxlength='50' placeholder='" . $client['street'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='ilocalNum'>Número: </label>
                                                <div class='form-input'>
                                                    <input type='number' name='localNum' id='ilocalNum' max='99999999' placeholder='" . $client['localNum'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='iuserDistrict'>Bairro: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='district' id='iuserDistrict' maxlength='40' placeholder='" . $client['district'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='iuserCity'>Cidade: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='city' id='iuserCity' maxlength='40' placeholder='" . $client['city'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='istate'>Estado: </label>
                                                <div class='form-input'>
                                                    <select name='state' id='istate'>";
                                                        echo "<option value='AC'". optionSelectAlt($client, "state", "AC"). ">Acre</option>";
                                                        echo "<option value='AL'". optionSelectAlt($client, "state", "AL"). ">Alagoas</option>";
                                                        echo "<option value='AP'". optionSelectAlt($client, "state", "AP"). ">Amapá</option>";
                                                        echo "<option value='AM'". optionSelectAlt($client, "state", "AM"). ">Amazonas</option>";
                                                        echo "<option value='BA'". optionSelectAlt($client, "state", "BA"). ">Bahia</option>";
                                                        echo "<option value='CE'". optionSelectAlt($client, "state", "CE"). ">Ceará</option>";
                                                        echo "<option value='DF'". optionSelectAlt($client, "state", "DF"). ">Distrito Federal</option>";
                                                        echo "<option value='ES'". optionSelectAlt($client, "state", "ES"). ">Espírito Santo</option>";
                                                        echo "<option value='GO'". optionSelectAlt($client, "state", "GO"). ">Goiás</option>";
                                                        echo "<option value='MA'". optionSelectAlt($client, "state", "MA"). ">Maranhão</option>";
                                                        echo "<option value='MT'". optionSelectAlt($client, "state", "MT"). ">Mato Grosso</option>";
                                                        echo "<option value='MS'". optionSelectAlt($client, "state", "MS"). ">Mato Grosso do Sul</option>";
                                                        echo "<option value='MG'". optionSelectAlt($client, "state", "MG"). ">Minas Gerais</option>";
                                                        echo "<option value='PA'". optionSelectAlt($client, "state", "PA"). ">Pará</option>";
                                                        echo "<option value='PB'". optionSelectAlt($client, "state", "PB"). ">Paraíba</option>";
                                                        echo "<option value='PR'". optionSelectAlt($client, "state", "PR"). ">Paraná</option>";
                                                        echo "<option value='PE'". optionSelectAlt($client, "state", "PE"). ">Pernambuco</option>";
                                                        echo "<option value='PI'". optionSelectAlt($client, "state", "PI"). ">Piauí</option>";
                                                        echo "<option value='RJ'". optionSelectAlt($client, "state", "RJ"). ">Rio de Janeiro</option>";
                                                        echo "<option value='RN'". optionSelectAlt($client, "state", "RN"). ">Rio Grande do Norte</option>";
                                                        echo "<option value='RS'". optionSelectAlt($client, "state", "RS"). ">Rio Grande do Sul</option>";
                                                        echo "<option value='RO'". optionSelectAlt($client, "state", "RO"). ">Rondônia</option>";
                                                        echo "<option value='RR'". optionSelectAlt($client, "state", "RR"). ">Roraima</option>";
                                                        echo "<option value='SC'". optionSelectAlt($client, "state", "SC"). ">Santa Catarina</option>";
                                                        echo "<option value='SP'". optionSelectAlt($client, "state", "SP"). ">São Paulo</option>";
                                                        echo "<option value='SE'". optionSelectAlt($client, "state", "SE"). ">Sergipe</option>";
                                                        echo "<option value='TO'". optionSelectAlt($client, "state", "TO"). ">Tocantins</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='ireferencePoint'>Ponto de Referência: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='referencePoint' id='ireferencePoint' maxlength='50' placeholder=" . $client['referencePoint'] . ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            ";
                            break;

                        case "admin":
                            $getAdmin = $mysqli->prepare("
                                SELECT u.idUser, u.userName, u.userPhone, u.street, u.localNum, u.district, u.city, u.state, u.referencePoint, ad.adminPicture 
                                FROM user_data as u 
                                    JOIN admin_data AS ad ON u.idUser = ad.idAdmin
                                WHERE u.idUser = ?"
                            );
                            $getAdmin->bind_param("i", $_GET["id"]);
                            $getAdmin->execute();
                            $result = $getAdmin->get_result();
                            $admin = $result->fetch_assoc();

                            $getAdmin->close();
                            

                            $changes = verifyChanges($admin);
                            
                           
                            if ($changes != "") {
                                header("location: changeItem.php?category=admin&id=" . $admin["idUser"] . "&" . $changes);
                                exit();
                            }

                            if (isset($_GET["makeClient"])) {
                                // turning administrator into client
                                $dotenv = Dotenv::createImmutable("../../composer");
                                $dotenv->load();
                                $config = new Configuration($_ENV["CLOUDINARY_URL"]);
                                $cld = new Cloudinary($config);
                                $upload = new UploadApi($config);
                                $response = null;

                                $publicId = "adminPic" . str_pad($admin["idUser"], 3, "0", STR_PAD_LEFT);

                                try {
                                    // removing the admin picture from before
                                    $response = $upload->destroy("Users-Pictures/$publicId");
                                    if (isset($response["result"]) && $response["result"] !== "ok") {
                                        echo "Aviso: Imagem não encontrada ou não removida.";
                                    }

                                    // removing idAdmin from admin_data, adding into client_data
                                    $removeFromAdmin = $mysqli->prepare("DELETE FROM admin_data WHERE idAdmin = ?");
                                    $removeFromAdmin->bind_param("i", $admin["idUser"]);
                                    $removeFromAdmin->execute();
                                    $removeFromAdmin->close();

                                    $addToClient = $mysqli->prepare("INSERT INTO client_data (idClient) VALUES(?) ");
                                    $addToClient->bind_param("i", $admin["idUser"]);

                                    if ($addToClient->execute()) {
                                        $addToClient->close();
                                        header("location: clients.php?NewClient=1");
                                        exit();
                                    } else {
                                        $addToClient->close();
                                        echo "Erro ao adicionar cliente: " . $addToClient->error;
                                    }
                                } catch (Exception $e) {
                                    echo "Erro no upload: " . $e->getMessage();
                                }
                            }

                            echo "
                                <a href='changeItem.php?category=admin&makeClient=1&id=" . $_GET["id"] . "' class='link-button' style='margin-bottom: 1em'>Torná-lo um cliente</a>
                                
                                <div class='form-main'>
                                    <div class='form-inputs'>
                                        <div class='form-picture'>
                                            <div class='form-item regular-input'>
                                                <label for='iadminPicture'>Foto de Perfil: <small>Tamanho Máximo: 2MB</small></label>
                                                <div class='form-input'>
                                                    <img src='" . $admin['adminPicture'] . "  ' alt='Current Admin Picture'>
                                                    <input type='file' name='adminPicture' id='iadminPicture'>
                                                </div>
                                            </div>
                                        </div>

                                        <div class='form-regular-inputs'>
                                            
                                            <div class='form-item regular-input'>
                                                <label for='iadminName'>Nome: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='userName' id='iadminName' maxlength='30' minlength='8' placeholder='" . $admin['userName'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='iadminPhone'>Telefone de Contato:</label>
                                                <div class='form-input'>
                                                    <input type='text' name='userPhone' id='iadminPhone' minlength='15' maxlength='16' pattern='\(\d{2}\) \d \d{4} \d{4}' placeholder='" . $admin['userPhone'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='istreet'>Rua: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='street' id='istreet' maxlength='50' placeholder='" . $admin['street'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='ilocalNum'>Número: </label>
                                                <div class='form-input'>
                                                    <input type='number' name='localNum' id='ilocalNum' max='99999999' placeholder='" . $admin['localNum'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='iuserDistrict'>Bairro: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='district' id='iuserDistrict' maxlength='40' placeholder='" . $admin['district'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='iuserCity'>Cidade: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='city' id='iuserCity' maxlength='40' placeholder='" . $admin['city'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='istate'>Estado: </label>
                                                <div class='form-input'>
                                                    <select name='state' id='istate'>";
                                                        echo "<option value='AC'". optionSelectAlt($admin, "state", "AC"). ">Acre</option>";
                                                        echo "<option value='AL'". optionSelectAlt($admin, "state", "AL"). ">Alagoas</option>";
                                                        echo "<option value='AP'". optionSelectAlt($admin, "state", "AP"). ">Amapá</option>";
                                                        echo "<option value='AM'". optionSelectAlt($admin, "state", "AM"). ">Amazonas</option>";
                                                        echo "<option value='BA'". optionSelectAlt($admin, "state", "BA"). ">Bahia</option>";
                                                        echo "<option value='CE'". optionSelectAlt($admin, "state", "CE"). ">Ceará</option>";
                                                        echo "<option value='DF'". optionSelectAlt($admin, "state", "DF"). ">Distrito Federal</option>";
                                                        echo "<option value='ES'". optionSelectAlt($admin, "state", "ES"). ">Espírito Santo</option>";
                                                        echo "<option value='GO'". optionSelectAlt($admin, "state", "GO"). ">Goiás</option>";
                                                        echo "<option value='MA'". optionSelectAlt($admin, "state", "MA"). ">Maranhão</option>";
                                                        echo "<option value='MT'". optionSelectAlt($admin, "state", "MT"). ">Mato Grosso</option>";
                                                        echo "<option value='MS'". optionSelectAlt($admin, "state", "MS"). ">Mato Grosso do Sul</option>";
                                                        echo "<option value='MG'". optionSelectAlt($admin, "state", "MG"). ">Minas Gerais</option>";
                                                        echo "<option value='PA'". optionSelectAlt($admin, "state", "PA"). ">Pará</option>";
                                                        echo "<option value='PB'". optionSelectAlt($admin, "state", "PB"). ">Paraíba</option>";
                                                        echo "<option value='PR'". optionSelectAlt($admin, "state", "PR"). ">Paraná</option>";
                                                        echo "<option value='PE'". optionSelectAlt($admin, "state", "PE"). ">Pernambuco</option>";
                                                        echo "<option value='PI'". optionSelectAlt($admin, "state", "PI"). ">Piauí</option>";
                                                        echo "<option value='RJ'". optionSelectAlt($admin, "state", "RJ"). ">Rio de Janeiro</option>";
                                                        echo "<option value='RN'". optionSelectAlt($admin, "state", "RN"). ">Rio Grande do Norte</option>";
                                                        echo "<option value='RS'". optionSelectAlt($admin, "state", "RS"). ">Rio Grande do Sul</option>";
                                                        echo "<option value='RO'". optionSelectAlt($admin, "state", "RO"). ">Rondônia</option>";
                                                        echo "<option value='RR'". optionSelectAlt($admin, "state", "RR"). ">Roraima</option>";
                                                        echo "<option value='SC'". optionSelectAlt($admin, "state", "SC"). ">Santa Catarina</option>";
                                                        echo "<option value='SP'". optionSelectAlt($admin, "state", "SP"). ">São Paulo</option>";
                                                        echo "<option value='SE'". optionSelectAlt($admin, "state", "SE"). ">Sergipe</option>";
                                                        echo "<option value='TO'". optionSelectAlt($admin, "state", "TO"). ">Tocantins</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='ireferencePoint'>Ponto de Referência: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='referencePoint' id='ireferencePoint' maxlength='50' placeholder=" . $admin['referencePoint'] . ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            ";
                            break;
                        
                        case "product":
                            $getProduct = $mysqli->prepare("
                                SELECT idProduct, printName, altName, brandProduct, typeProduct
                                FROM product_data
                                WHERE idProduct = ?"
                            );
                            $getProduct->bind_param("i", $_GET["id"]);
                            $getProduct->execute();
                            $result = $getProduct->get_result();
                            $product = $result->fetch_assoc();

                            $getProduct->close();
                            $changes = verifyChanges($product);

                            if ($changes != "") {
                                header("location: changeItem.php?category=product&id=" . $product["idProduct"] . "&" . $changes);
                                exit();
                            }

                            if(isset($_GET["remove"])){
                                removeItem("product", $_GET["id"]);
                            }
                            
                            echo "
                                <div class='form-main'>
                                    <div class='form-inputs'>
                                        <div class='form-regular-inputs'>
                                            <div class='form-item regular-input'>
                                                <label for='iprintName'>Nome Amigável: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='printName' id='iprintName' maxlength='60' placeholder='" . $product['printName'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='ialtName'>Nome Alternativo: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='altName' id='ialtName' maxlength='40' placeholder='" . $product['altName'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='ibrandProd'>Marca:</label>
                                                <div class='form-input'>
                                                    <input type='text' name='brandProduct' id='ibrandProd' maxlength='40' placeholder='" . $product['brandProduct'] . "'>
                                                </div>
                                            </div>
                                            <div class='form-item regular-input'>
                                                <label for='itypeProd'>Tipo: </label>
                                                <div class='form-input'>
                                                    <select name='typeProduct' id='itypeProd'>";
                                                        echo "<option value='Creme'"    . optionSelectAlt($product, "typeProduct", "Creme")     . ">Creme</option>"; 
                                                        echo "<option value='Adicional'". optionSelectAlt($product, "typeProduct", "Adicional") . ">Adicional</option>";  
                                                        echo "<option value='Outro' "   . optionSelectAlt($product, "typeProduct", "Outro")     . ">Outro Tipo</option>   
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            ";
                            break;
                        
                        case "version":
                            $getVersion = $mysqli->prepare("
                                SELECT idVersion, nameProduct, sizeProduct, priceProduct, availability, imageURL, flavor
                                FROM product_version
                                WHERE idVersion = ?"
                            );
                            $getVersion->bind_param("i", $_GET["id"]);
                            $getVersion->execute();
                            $result = $getVersion->get_result();
                            $version = $result->fetch_assoc();
                            $getVersion->close();

                            $changes = verifyChanges($version);

                            if ($changes != "") {
                                header("location: changeItem.php?category=version&id=" . $version["idVersion"] . "&" . $changes);
                                exit();
                            }

                            if(isset($_GET["remove"])){
                                removeItem("version", $_GET["id"]);
                            }

                            echo "
                                <div class='form-main'>
                                    <div class='form-inputs'>
                                        <div class='form-picture'>
                                            <div class='form-item regular-input'>
                                                <label for='iprodPicture'>Foto:</label>
                                                <div class='form-input form-picture-display'>
                                                    <img src='" . $version['imageURL'] . "' alt='Current Product Picture'>
                                                    <input type='file' name='imageURL' id='iprodPicture'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='form-regular-inputs'>
                                            <div class='form-item regular-input'>
                                                <label for='inameProd'>Nome: </label>
                                                <div class='form-input'>
                                                    <input type='text' name='nameProduct' id='inameProd' maxlength='40' placeholder='" . $version['nameProduct'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='isizeProduct'>Tamanho:</label>
                                                <div class='form-input'>
                                                    <input type='text' name='sizeProduct' id='isizeProduct' maxlength='20' placeholder='" . $version['sizeProduct'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='iflavor'>Sabor:</label>
                                                <div class='form-input'>
                                                    <input type='text' name='flavor' id='iflavor' maxlength='40' placeholder='" . $version['flavor'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='ipriceProd'>Preço Individual: </label>
                                                <div class='form-input'>
                                                    <input type='number' name='priceProduct' id='ipriceProd' placeholder='" . $version['priceProduct'] . "'>
                                                </div>
                                            </div>

                                            <div class='form-item regular-input'>
                                                <label for='iavailability'>Disponibilidade: </label>
                                                <select name='availability' id='iavailability'>";
                                                    echo "<option value='indisponivel'". optionSelectAlt($version, "availability", "0"). ">Indisponível</option>";
                                                    echo "<option value='disponivel'". optionSelectAlt($version, "availability", "1"). ">Disponível</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            ";
                            break;
                    }
                    echo "<button class='regular-button'>Editar</button>";

                    if($_GET["category"] == "admin" or $_GET["category"] == "client"){
                        echo "
                            <div class='form-links'>
                                <a href='../account/changes/newPassword.php'>Alterar Senha</a>
                                <a href='../account/changes/newEmail.php'>Alterar Email</a>
                            </div>
                        ";
                    }
                    echo "</div>";
                }
            }
        }
    }

    function verifyChanges($itemData)
    {
        global $mysqli;
        $allowedColumns = [
            "userName", "userPhone", "adminPicture", "street",
            "localNum", "district", "city", "state",
            "referencePoint", "nameProduct", "brandProduct", "imageURL",
            "priceProduct", "availability", "typeProduct", "printName",
            "realName", "sizeProduct","flavor"
        ];

        $getChanges = "";
        for ($i = 0; $i < sizeof($allowedColumns); $i++) {
            $inputName = $allowedColumns[$i];
            $newValue = null;
            $dbTable = "";

            switch ($inputName) {
                case "adminPicture":
                    // different treatment for adminPicture, because it's a file
                    if (isset($_FILES["adminPicture"]) && $_FILES["adminPicture"]["error"] === UPLOAD_ERR_OK) {
                        // changing the profile picture at the Cloud
                        $dotenv = Dotenv::createImmutable("../../composer");
                        $dotenv->load();
                        $config = new Configuration($_ENV["CLOUDINARY_URL"]);
                        $cld = new Cloudinary($config);
                        $upload = new UploadApi($config);
                        $response = null;

                        $publicId = "adminPic" . str_pad($itemData["idUser"], 3, "0", STR_PAD_LEFT);
                        $fileTmpPath = $_FILES["adminPicture"]["tmp_name"];

                        try {
                            $response = $upload->upload($fileTmpPath, [
                                "folder" => "Users-Pictures",
                                "public_id" => $publicId,
                                "overwrite" => true,
                                "invalidate" => true
                            ]);
                            // changing the image URL value to the real URL(not the image name)
                            $newValue = $response['secure_url'];
                        } catch (Exception $e) {
                            echo "Erro no upload: " . $e->getMessage();
                        }
                    }
                    $dbTable = "admin_data";
                    break;
                case "imageURL": // product image
                    if (isset($_FILES["imageURL"]) && $_FILES["imageURL"]["error"] === UPLOAD_ERR_OK) {
                        // changing the picture at the Cloud
                        $dotenv = Dotenv::createImmutable("../../composer");
                        $dotenv->load();
                        $config = new Configuration($_ENV["CLOUDINARY_URL"]);
                        $cld = new Cloudinary($config);
                        $upload = new UploadApi($config);
                        $response = null;

                        $publicId = $itemData["nameProduct"];
                        $fileTmpPath = $_FILES["imageURL"]["tmp_name"];

                        try {
                            $response = $upload->upload($fileTmpPath, [
                                "folder" => "Projeto_Acai/Products",
                                "public_id" => $publicId,
                                "overwrite" => true,
                                "invalidate" => true
                            ]);
                            // changing the image URL value to the real URL(not the image name)
                            $newValue = $response['secure_url'];
                        } catch (Exception $e) {
                            echo "Erro no upload: " . $e->getMessage();
                        }
                    }
                    $dbTable = "product_version";
                    break;
                default:
                    if (isset($_POST[$inputName])) {
                        $newValue = trim($_POST[$inputName]);
                        if ($inputName == "referencePoint" or $inputName == "state" or $inputName == "availability" or $inputName == "typeProduct" or $inputName == "flavor") {
                            // avoid changing null allowed options
                            if ($newValue == $itemData[$inputName]) {
                                $newValue = null;
                            }
                        }
                        $dbTable = match ($inputName) {
                            "userName", "userPhone", "street", "localNum", 
                            "district", "city", "state", "referencePoint"           => "user_data",

                            "adminPicture"                                          => "admin_data",

                            "printName", "realName", "brandProduct", "typeProduct"  => "product_data",

                            "nameProduct", "imageURL", "priceProduct", 
                            "availability", "sizeProduct", "flavor"                 => "product_version",
                        };
                    }
                    break;
            }

            // if there's a value, try to update at the database
            if (! empty($newValue)) {
                $oldValueQuery = match($dbTable){
                    // try to get the old value that is on the database
                    "admin_data"        => "SELECT $inputName FROM $dbTable WHERE idAdmin   = ? LIMIT 1",
                    "product_data"      => "SELECT $inputName FROM $dbTable WHERE idProduct = ? LIMIT 1",
                    "product_version"   => "SELECT $inputName FROM $dbTable WHERE idVersion = ? LIMIT 1",
                    default             => "SELECT $inputName FROM $dbTable WHERE idUser    = ? LIMIT 1"
                };
                $getOldValue = $mysqli->prepare($oldValueQuery);

                $query = match ($dbTable) {
                    "admin_data"            => "UPDATE admin_data       SET $inputName = ? WHERE idAdmin    = ?",
                    "product_data"          => "UPDATE product_data     SET $inputName = ? WHERE idProduct  = ?",
                    "product_version"       => "UPDATE product_version  SET $inputName = ? WHERE idVersion  = ?",
                    default                 => "UPDATE user_data        SET $inputName = ? WHERE idUser     = ?",
                };
                $changeData = $mysqli->prepare($query);

                $idField = match ($dbTable) {
                    "user_data",
                    "admin_data"            => "idUser",
                    "product_data"          => "idProduct",
                    "product_version"       => "idVersion",
                    default                 => "idUser"
                };

                $idValue = $itemData[$idField];

                $getOldValue->bind_param("i", $idValue);
                $getOldValue->execute();
                $result   = $getOldValue->get_result();
                $result   = $result->fetch_assoc();
                $oldValue = $result[$inputName] ?? null;
                $getOldValue->close();

                if ($newValue != $itemData[$inputName]) {
                    $changeData->bind_param("si", $newValue, $idValue);
                    $changeData->execute();
                    $changeData->close();
                    
                    // update priceDate
                    if ($allowedColumns[$i] == "priceProduct") {
                        $currentDate = date("Y-m-d");
                        $changePriceDate = $mysqli->prepare("UPDATE product_version SET priceDate = ? WHERE idVersion = ?");
                        $changePriceDate->bind_param("si", $currentDate, $itemData["idVersion"]);
                        $changePriceDate->execute();
                        $changePriceDate->close();
                    }
                    
                    // update session
                    if ($dbTable != "product_data" or $dbTable != "product_version") {
                        $_SESSION[$inputName] = $newValue;
                    }

                    $getChanges .= "c{$inputName}=1&";
                    addChange("Modificar", $dbTable, $idValue, $inputName, $oldValue, $newValue);
                } else {
                    $getChanges .= "c{$inputName}=2&";
                }
            }
        }
        return rtrim($getChanges, "&");
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

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "mannagerSettings") ?>">

    <title>Açaí e Polpas Amazônia - Alterar Item</title>
</head>

<body>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <?php displayForms()?>
        </form>
    </main>
</body>
</html>