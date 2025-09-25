<?php
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";

    require_once '../../composer/vendor/autoload.php';

    use Dotenv\Dotenv;
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Cloudinary;
    use Cloudinary\Api\Upload\UploadApi; # API para upload de imagens

    function printLabel($fieldLabels){
        foreach ($_GET as $key => $value) {
            if (preg_match('/^c(.+)$/', $key, $matches)) {
                $field = $matches[1];

                if (isset($fieldLabels[$field])) {
                    $label = $fieldLabels[$field];

                    switch ($value) {
                        case '1':
                            echo "
                            <p class=\"successText\">Sucesso ao alterar <strong>{$label}</strong></p>";
                            break;
                        case '2':
                            echo "
                                <p class=\"errorText\">
                                    <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                                    O valor inserido em <strong>{$label}</strong> é o mesmo já cadastrado.
                                </p>
                            ";
                            break;
                    }
                }
            }
        }
    }

    function printForms(){
        global $mysqli;
        if(isset($_GET["Category"])){
            $changes = "";
            $fieldLabels = [
                'userName'       => 'Nome de Usuário',
                'userPhone'      => 'Telefone de Contato',
                'district'       => 'Bairro',
                'localNum'       => 'Número da Residência',
                'referencePoint' => 'Ponto de Referência',
                'street'         => 'Rua',
                'city'           => 'Cidade',
                'state'          => 'Estado',
                'adminPicture'   => 'Foto de Perfil',
                'imageURL'       => 'Foto do Produto',
                'nameProduct'    => 'Nome do Produto',
                'brandProduct'   => 'Marca do Produto',
                'priceProduct'   => 'Preço Individual do Produto',
                'availability'   => 'Disponibilidade do Produto',
                'typeProduct'    => 'Tipo do Produto',
            ];
            switch($_GET['Category']){
                case "client":
                    $getClient = $mysqli->prepare("SELECT idUser, userName, userPhone, street, localNum, district, city, state, referencePoint FROM user_data WHERE idUser = ?");
                    $getClient->bind_param("i", $_GET["id"]);
                    $getClient->execute();
                    $result = $getClient->get_result();
                    $getClient->close();
                    $client = $result->fetch_assoc();

                    $changes = verifyChanges($client);

                    if(isset($_GET["makeAdmin"])){
                        // tornando um cliente em administrador
                        $dotenv = Dotenv::createImmutable("../../composer");
                        $dotenv->load();
                        $config = new Configuration($_ENV["CLOUDINARY_URL"]);
                        $cld = new Cloudinary($config);
                        $upload = new UploadApi($config);
                        $response = null;

                        $publicId = "adminPic" . str_pad($client["idUser"], 3, "0", STR_PAD_LEFT);
                        // imagem padrão ao adicionar um novo admin
                        $imageDefault = "https://res.cloudinary.com/dw2eqq9kk/image/upload/v1757086840/default_user_icon_yp10ih.png";

                        try {
                            $response = $upload->upload($imageDefault, [
                                "folder"        => "Users-Pictures",
                                "public_id"     => $publicId,
                                "overwrite"     => true,
                                "invalidate"    => true
                            ]);

                            $imageUrl = $response['secure_url'];

                            // removendo o idUser de client_data e adicionando a admin_data
                            $removeFromClient = $mysqli->prepare("DELETE FROM client_data WHERE idClient = ?");
                            $removeFromClient->bind_param("i", $client["idUser"]);
                            $removeFromClient->execute();
                            
                            $addToAdmin = $mysqli->prepare("INSERT INTO admin_data (idAdmin, adminPicture) VALUES(?, ?) ");
                            $addToAdmin->bind_param("is", $client["idUser"], $imageUrl);

                            if($addToAdmin->execute()){
                                header("location: admin.php?NewAdmin=1");
                                exit();
                            }else {
                                echo "Erro ao adicionar admin: " . $addToAdmin->error;
                            }
                        } catch (Exception $e) {
                            echo "Erro no upload: " . $e->getMessage();
                        }

                    }

                    echo "
                        <div class='main-title'>
                            <h1>
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'/>
                                </svg>
                                Alterar Dados de um Cliente
                            </h1>
                            <div class='back-button'>
                                <a href='users.php'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18'/>
                                    </svg>
                                    Voltar
                                </a>
                            </div>
                        </div>
                        ";
                        printLabel($fieldLabels);
                        echo 
                        "<button type='button' onclick=\"window.location.href='changeItem.php?Category=client&makeAdmin=1&id=". $_GET["id"] ."'\">Torná-lo um Administrador</button>
                        <div class='form-inputs'>
                            <div class='form-item'>
                                <label for='iclientName'>Nome: </label>
                                <div class='form-input'>
                                    <input type='text' name='userName' id='iclientName' maxlength='30' minlength='8' placeholder='".$client['userName']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iclientPhone'>Telefone de Contato:</label>
                                <div class='form-input'>
                                    <input type='text' name='userPhone' id='iclientPhone' minlength='15' maxlength='16' pattern='\(\d{2}\) \d \d{4} \d{4}' placeholder='".$client['userPhone']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='istreet'>Rua: </label>
                                <div class='form-input'>
                                    <input type='text' name='street' id='istreet' maxlength='50' placeholder='".$client['street']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='ilocalNum'>Número: </label>
                                <div class='form-input'>
                                    <input type='number' name='localNum' id='ilocalNum' max='99999999' placeholder='".$client['localNum']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iuserDistrict'>Bairro: </label>
                                <div class='form-input'>
                                    <input type='text' name='district' id='iuserDistrict' maxlength='40' placeholder='".$client['district']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iuserCity'>Cidade: </label>
                                <div class='form-input'>
                                    <input type='text' name='city' id='iuserCity' maxlength='40' placeholder='".$client['city']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='istate'>Estado: </label>
                                <div class='form-input'>
                                    <select name='state' id='istate'>
                                        <option value='AC'".optionSelectAlt($client,"state","AC").">Acre</option>
                                        <option value='AL'".optionSelectAlt($client,"state","AL").">Alagoas</option>
                                        <option value='AP'".optionSelectAlt($client,"state","AP").">Amapá</option>
                                        <option value='AM'".optionSelectAlt($client,"state","AM").">Amazonas</option>
                                        <option value='BA'".optionSelectAlt($client,"state","BA").">Bahia</option>
                                        <option value='CE'".optionSelectAlt($client,"state","CE").">Ceará</option>
                                        <option value='DF'".optionSelectAlt($client,"state","DF").">Distrito Federal</option>
                                        <option value='ES'".optionSelectAlt($client,"state","ES").">Espírito Santo</option>
                                        <option value='GO'".optionSelectAlt($client,"state","GO").">Goiás</option>
                                        <option value='MA'".optionSelectAlt($client,"state","MA").">Maranhão</option>
                                        <option value='MT'".optionSelectAlt($client,"state","MT").">Mato Grosso</option>
                                        <option value='MS'".optionSelectAlt($client,"state","MS").">Mato Grosso do Sul</option>
                                        <option value='MG'".optionSelectAlt($client,"state","MG").">Minas Gerais</option>
                                        <option value='PA'".optionSelectAlt($client,"state","PA").">Pará</option>
                                        <option value='PB'".optionSelectAlt($client,"state","PB").">Paraíba</option>
                                        <option value='PR'".optionSelectAlt($client,"state","PR").">Paraná</option>
                                        <option value='PE'".optionSelectAlt($client,"state","PE").">Pernambuco</option>
                                        <option value='PI'".optionSelectAlt($client,"state","PI").">Piauí</option>
                                        <option value='RJ'".optionSelectAlt($client,"state","RJ").">Rio de Janeiro</option>
                                        <option value='RN'".optionSelectAlt($client,"state","RN").">Rio Grande do Norte</option>
                                        <option value='RS'".optionSelectAlt($client,"state","RS").">Rio Grande do Sul</option>
                                        <option value='RO'".optionSelectAlt($client,"state","RO").">Rondônia</option>
                                        <option value='RR'".optionSelectAlt($client,"state","RR").">Roraima</option>
                                        <option value='SC'".optionSelectAlt($client,"state","SC").">Santa Catarina</option>
                                        <option value='SP'".optionSelectAlt($client,"state","SP").">São Paulo</option>
                                        <option value='SE'".optionSelectAlt($client,"state","SE").">Sergipe</option>
                                        <option value='TO'".optionSelectAlt($client,"state","TO").">Tocantins</option>
                                    </select>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='ireferencePoint'>Ponto de Referência: </label>
                                <div class='form-input'>
                                    <input type='text' name='referencePoint' id='ireferencePoint' maxlength='50' placeholder=".$client['referencePoint'].">
                                </div>
                            </div>
                        </div>
                        <button>Editar</button>

                        <div style='display: flex; justify-content: space-between; border: none'>
                            <button type='button' onclick=\"window.location.href='../account/changes/newPassword.php'\">Alterar Senha</button>

                            <button type='button' onclick=\"window.location.href='../account/changes/newEmail.php'\">Alterar Email</button>
                        </div>
                    ";
                    
                    break;   
                case "admin":
                    $getAdmin = $mysqli->prepare("
                        SELECT u.userName, u.userPhone, u.street, u.localNum, u.district, u.city, u.state, u.referencePoint, ad.adminPicture 
                        FROM user_data as u 
                            JOIN admin_data AS ad ON u.idUser = ad.idAdmin
                        WHERE u.idUser = ?"
                    );
                    $getAdmin->bind_param("i", $_GET["id"]);
                    $getAdmin->execute();
                    $result = $getAdmin->get_result();
                    $admin = $result->fetch_assoc();

                    $changes = verifyChanges( $admin);

                    echo "
                        <div class='main-title'>
                            <h1>
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'/>
                                </svg>
                                Alterar Dados de um Administrador
                            </h1>
                            <div class='back-button'>
                                <a href='admin.php'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18'/>
                                    </svg>
                                    Voltar
                                </a>
                            </div>
                        </div>
                        ";
                        printLabel($fieldLabels);
                        echo "
                        <div class='form-inputs'>
                            <div class='form-item'>
                                <label for='iadminPicture'>Foto de Perfil: <small>Tamanho Máximo: 2MB</small></label>
                                <div class='form-input'>
                                    <img src='".$admin['adminPicture']."  ' alt='Current Admin Picture'>
                                    <input type='file' name='adminPicture' id='iadminPicture'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iadminName'>Nome: </label>
                                <div class='form-input'>
                                    <input type='text' name='userName' id='iadminName' maxlength='30' minlength='8' placeholder='".$admin['userName']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iadminPhone'>Telefone de Contato:</label>
                                <div class='form-input'>
                                    <input type='text' name='userPhone' id='iadminPhone' minlength='15' maxlength='16' pattern='\(\d{2}\) \d \d{4} \d{4}' placeholder='".$admin['userPhone']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='istreet'>Rua: </label>
                                <div class='form-input'>
                                    <input type='text' name='street' id='istreet' maxlength='50' placeholder='".$admin['street']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='ilocalNum'>Número: </label>
                                <div class='form-input'>
                                    <input type='number' name='localNum' id='ilocalNum' max='99999999' placeholder='".$admin['localNum']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iuserDistrict'>Bairro: </label>
                                <div class='form-input'>
                                    <input type='text' name='district' id='iuserDistrict' maxlength='40' placeholder='".$admin['district']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iuserCity'>Cidade: </label>
                                <div class='form-input'>
                                    <input type='text' name='city' id='iuserCity' maxlength='40' placeholder='".$admin['city']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='istate'>Estado: </label>
                                <div class='form-input'>
                                    <select name='state' id='istate'>
                                        <option value='AC'".optionSelectAlt($admin,"state","AC").">Acre</option>
                                        <option value='AL'".optionSelectAlt($admin,"state","AL").">Alagoas</option>
                                        <option value='AP'".optionSelectAlt($admin,"state","AP").">Amapá</option>
                                        <option value='AM'".optionSelectAlt($admin,"state","AM").">Amazonas</option>
                                        <option value='BA'".optionSelectAlt($admin,"state","BA").">Bahia</option>
                                        <option value='CE'".optionSelectAlt($admin,"state","CE").">Ceará</option>
                                        <option value='DF'".optionSelectAlt($admin,"state","DF").">Distrito Federal</option>
                                        <option value='ES'".optionSelectAlt($admin,"state","ES").">Espírito Santo</option>
                                        <option value='GO'".optionSelectAlt($admin,"state","GO").">Goiás</option>
                                        <option value='MA'".optionSelectAlt($admin,"state","MA").">Maranhão</option>
                                        <option value='MT'".optionSelectAlt($admin,"state","MT").">Mato Grosso</option>
                                        <option value='MS'".optionSelectAlt($admin,"state","MS").">Mato Grosso do Sul</option>
                                        <option value='MG'".optionSelectAlt($admin,"state","MG").">Minas Gerais</option>
                                        <option value='PA'".optionSelectAlt($admin,"state","PA").">Pará</option>
                                        <option value='PB'".optionSelectAlt($admin,"state","PB").">Paraíba</option>
                                        <option value='PR'".optionSelectAlt($admin,"state","PR").">Paraná</option>
                                        <option value='PE'".optionSelectAlt($admin,"state","PE").">Pernambuco</option>
                                        <option value='PI'".optionSelectAlt($admin,"state","PI").">Piauí</option>
                                        <option value='RJ'".optionSelectAlt($admin,"state","RJ").">Rio de Janeiro</option>
                                        <option value='RN'".optionSelectAlt($admin,"state","RN").">Rio Grande do Norte</option>
                                        <option value='RS'".optionSelectAlt($admin,"state","RS").">Rio Grande do Sul</option>
                                        <option value='RO'".optionSelectAlt($admin,"state","RO").">Rondônia</option>
                                        <option value='RR'".optionSelectAlt($admin,"state","RR").">Roraima</option>
                                        <option value='SC'".optionSelectAlt($admin,"state","SC").">Santa Catarina</option>
                                        <option value='SP'".optionSelectAlt($admin,"state","SP").">São Paulo</option>
                                        <option value='SE'".optionSelectAlt($admin,"state","SE").">Sergipe</option>
                                        <option value='TO'".optionSelectAlt($admin,"state","TO").">Tocantins</option>
                                    </select>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='ireferencePoint'>Ponto de Referência: </label>
                                <div class='form-input'>
                                    <input type='text' name='referencePoint' id='ireferencePoint' maxlength='50' placeholder=".$admin['referencePoint'].">
                                </div>
                            </div>
                        </div>
                        <button>Editar</button>

                        <div style='display: flex; justify-content: space-between; border: none'>
                            <button type='button' onclick=\"window.location.href='../account/changes/newPassword.php'\">Alterar Senha</button>

                            <button type='button' onclick=\"window.location.href='../account/changes/newEmail.php'\">Alterar Email</button>
                        </div>";
                    break;
                case "product":
                    $getProduct = $mysqli->prepare("
                        SELECT idProduct, nameProduct, brandProduct, priceProduct, imageURL, availability, typeProduct
                        FROM product
                        WHERE idProduct = ?"
                    );
                    $getProduct->bind_param("i", $_GET["id"]);
                    $getProduct->execute();
                    $result = $getProduct->get_result();
                    $product = $result->fetch_assoc();

                    $changes = verifyChanges( $product);

                    if($changes != ""){
                        header("location: changeItem.php?Category=product&id=". $product["idProduct"] .$changes);

                    }
                    echo "<div class='main-title'>
                            <h1>
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z'/>
                                </svg>

                                Alterar Dados de um Produto
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
                        ";
                        printLabel($fieldLabels);
                        echo "
                        <div class='form-inputs'>
                            <div class='form-item'>
                                <label for='iprodPicture'>Foto:</label>
                                <div class='form-input'>
                                    <img src='".$product['imageURL']."' alt='Current Product Picture'>
                                    <input type='file' name='imageURL' id='iprodPicture'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='inameProd'>Nome: </label>
                                <div class='form-input'>
                                    <input type='text' name='nameProduct' id='inameProd' maxlength='40' placeholder='".$product['nameProduct']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='ibrandProd'>Marca:</label>
                                <div class='form-input'>
                                    <input type='text' name='brandProduct' id='ibrandProd' maxlength='40' placeholder='".$product['brandProduct']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='ipriceProd'>Preço Individual: </label>
                                <div class='form-input'>
                                    <input type='number' name='priceProduct' id='ipriceProd' placeholder='".$product['priceProduct']."'>
                                </div>
                            </div>
                            <div class='form-item'>
                                <label for='iavailability'>Disponibilidade: </label>
                                <select name='availability' id='iavailability'>
                                    <option value='0'".optionSelectAlt($product,"availability","0").">Indisponível</option>
                                    <option value='1'".optionSelectAlt($product,"availability","1").">Disponível</option>
                                </select>
                            </div>
                            <div class='form-item'>
                                <label for='itypeProd'>Tipo: </label>
                                <div class='form-input'>
                                    <select name='typeProduct' id='itypeProd'>
                                        <option value='Cream'".optionSelectAlt($product,"typeProduct","Cream").">Creme</option>
                                        <option value='Additional'".optionSelectAlt($product,"typeProduct","Additional").">Adicional</option>
                                        <option value='Other'".optionSelectAlt($product,"typeProduct","Other").">Outro Tipo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button>Editar</button>
                    ";
                    break;
            }
            
        }
    }

    function verifyChanges($itemData){
        global $mysqli;
        $allowedColumns = [
            "userName", "userPhone", "adminPicture", "street", 
            "localNum", "district", "city", "state", "referencePoint",
            "nameProduct", "brandProduct", "imageURL", "priceProduct",
            "availability", "typeProduct"
        ];

        $getChanges = "";
        for($i = 0; $i < sizeof($allowedColumns); $i++){
            $inputName = $allowedColumns[$i];
            $newValue = null;

            switch($inputName){
                case "adminPicture":
                    // tratamento diferente para adminPicture, uma vez que é um arquivo
                    if (isset($_FILES["adminPicture"]) && $_FILES["adminPicture"]["error"] === UPLOAD_ERR_OK) {
                        // Alterar a foto de perfil na Nuvem
                        //$dbTable = "admin_data";
                        
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
                                "folder"        => "Users-Pictures",
                                "public_id"     => $publicId,
                                "overwrite"     => true,
                                "invalidate"    => true
                            ]);
                            
                            // Alterando o valor da URL da imagem(antes como nome da imagem)
                            $newValue = $response['secure_url'];
                            
                        } catch (Exception $e) {
                            echo "Erro no upload: " . $e->getMessage();
                        }
                        
                    }
                    
                    break;
                case "imageURL":
                    if (isset($_FILES["imageURL"]) && $_FILES["imageURL"]["error"] === UPLOAD_ERR_OK) {
                        //$dbTable = "product";
                        // Alterar a foto na Nuvem
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
                                "folder"        => "Projeto_Acai/Products",
                                "public_id"     => $publicId,
                                "overwrite"     => true,
                                "invalidate"    => true
                            ]);
                            
                            // Alterando o valor da URL da imagem(antes como nome da imagem)
                            $newValue = $response['secure_url'];
                            
                        } catch (Exception $e) {
                            echo "Erro no upload: " . $e->getMessage();
                        }
                    }
                    break;
                default:
                    if (isset($_POST[$inputName])) {
                        $newValue = trim($_POST[$inputName]);
                        if($inputName == "referencePoint" or $inputName == "state" or $inputName == "availability" or $inputName == "typeProduct"){
                            // evitar de trocar valores que podem ser nulos ou que são opções sem digitar nada
                            if($newValue == $itemData[$inputName]){
                                $newValue = null;
                            }
                        }
                        $dbTable = match($inputName) {
                            "userName", "userPhone", "street", "localNum", "district",
                            "city", "state", "referencePoint"   => "user_data",
                            "adminPicture"                      => "admin_data",
                            "nameProduct", "brandProduct", "imageURL", "priceProduct",
                            "availability", "typeProduct"       => "product",
                            default                             => "user_data"
                        };
                        
                    }
                    break;
            }

            // tentando atualizar, caso tenha um valor novo digitado
            if (!empty($newValue)) {
                $query = match ($dbTable) {
                    "admin_data"    => "UPDATE admin_data   SET $inputName = ? WHERE idAdmin = ?",
                    "product"       => "UPDATE product      SET $inputName = ? WHERE idProduct = ?",
                    default         => "UPDATE user_data    SET $inputName = ? WHERE idUser = ?"
                };

                $changeData = $mysqli->prepare($query);
                $id = match($dbTable){
                    "product"   => "idProduct",
                    default     => "idUser"
                };
                $changeData->bind_param("si", $newValue, $itemData[$id]);

                if ($newValue != $itemData[$inputName]) {
                    $changeData->execute();
                    $_SESSION[$inputName] = $newValue;
                    $getChanges .= "c{$inputName}=1&";
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
    <link
        href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap"
        rel="stylesheet">

    <?php faviconOut(); ?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="stylesheet" href="../CSS/mannager.css">
    <link rel="stylesheet" href="../CSS/mannager-settings.css">

    <title>Açaí e Polpas Amazônia - Alterar Item</title>

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
</head>

<body>
    <main>
        <form method="POST">
            <?php
                
            ?>
            <?php printForms()?>
            
        </form>
    </main>


</body>

</html>