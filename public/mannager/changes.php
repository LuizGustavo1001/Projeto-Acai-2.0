<?php 
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";
    include "../printStyles.php";

    $amount = getAmountItem("change");

    if(isset($_GET["reverse"])){
        if($_GET["reverse"] == 1 and isset($_GET["idChange"], $_GET["idAttribute"], $_GET["table"], $_GET["type"])){
            $table = $_GET["table"];
            $idItem = match($table){
                "user_data"     => "idUser",
                "product_data"  => "idProduct",
                "version"       => "idVersion",
                default         => "",
            };
            if($idItem != ""){
                switch($_GET["type"]){
                    case "Adicionar":
                        // remove the tuple from the database that was added 
                        $removeTuple = $mysqli->prepare("DELETE FROM $table WHERE $idItem = ? ");
                        $removeTuple->bind_param("i", $_GET["idAttribute"]);
                        $removeTuple->execute();

                        $removeFromChanges = $mysqli->prepare("DELETE FROM change_data WHERE idChange = ?");
                        $removeFromChanges->bind_param("i", $_GET["idChange"]);
                        $removeFromChanges->execute();

                        header("location: changes.php?revAdd=1");
                        exit();
                        
                    case "Remover":
                        // not ready yet
                        displayPopUp("", "");
                        break;
                    case "Modificar":
                        $getModify = $mysqli->prepare("
                            SELECT oldValue, newValue, objectChanged 
                            FROM attribute_change 
                            WHERE idChange = ?
                        ");
                        $getModify->bind_param("i", $_GET["idChange"]);
                        $getModify->execute();

                        $result = $getModify->get_result();
                        $modifiedAttribute = $result->fetch_assoc();
                        if($modifiedAttribute){
                            $object   = $modifiedAttribute["objectChanged"];
                            $oldValue = $modifiedAttribute["oldValue"];
                            $newValue = $modifiedAttribute["newValue"];

                            $query = "UPDATE $table SET `$object` = ? WHERE `$object` = ?";
                            $reverseValues = $mysqli->prepare($query);
                            $reverseValues->bind_param("ss", $oldValue, $newValue);
                            $reverseValues->execute();

                            $removeFromChanges = $mysqli->prepare("DELETE FROM change_data WHERE idChange = ?");
                            $removeFromChanges->bind_param("i", $_GET["idChange"]);
                            $removeFromChanges->execute();

                            header("location: changes.php?revMod=1");
                            exit();
                        }else{
                            echo "Erro: mudança não encontrada.";
                            exit();
                        }     
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
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php displayFavicon()?>

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "mannager") ?>">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <style>
        td strong{
            color: var(--secondary-clr);
        }
    </style>

    <title>Açaí e Polpas Amazônia - Alterações</title>
</head>
<body>
    <?php 
        if(isset($_GET["revAdd"]))
            displayPopUp("revAdd", "");
        else if(isset($_GET["revMod"]))
            displayPopUp("revMod", "");
        else if(isset($_GET["revRem"]))
            displayPopUp("revRem", "");
        if(isset($_GET["adminNotAllowed"]))
            displayPopUp("adminNotAllowed", "");
    ?>

    <?php displayManagerNav("change")?>

    <main>
        <?php
            if(isset($_GET["adminNotAllowed"]))
                displayPopUp("adminNotAllowed", "");
            else if(isset($_GET["makeAdmin"]))
                displayPopUp("makeAdmin", "");
        ?>
        
        <?php displayPageNav("change")?>

        <section class="info-section">
            <ul>
                <li class="amount">
                    <p><span><?php echo $amount?></span></p>
                    <p>Alterações Registradas</p>
                </li>
            </ul>
        </section>

        <form method="GET" class="search-section">
            <label for="isearch">Pesquisar pelo <strong>nome do administrador</strong> ou <strong>código</strong></label>
            <div class="search-bar">
                <label for="isearch" style="display: flex; align-items: center; cursor: pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                    </svg>
                </label>
                <input type="text" name='query' id="isearch" class="alt-input">
            </div>
        </form>

        <?php 
            if(isset($_GET["query"])){
                searchColumns($_GET["query"], "change");
            }
            
            displayTable("change");
        ?>
    </main>
</body>
</html>