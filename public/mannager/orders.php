<?php 
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";
    include "../printStyles.php";

    $amount = getAmountItem("order");
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
    <link rel="stylesheet" href="<?php printStyle("1", "mannager") ?>">

    <title>Açaí e Polpas Amazônia - Pedidos</title>
</head>
<body>

    <?php displayManagerNav("order")?>

    <main>
        <?php
            if(isset($_GET["adminNotAllowed"]))
                displayPopUp("adminNotAllowed", "");
            else if(isset($_GET["makeAdmin"]))
                displayPopUp("makeAdmin", "");
        ?>
        
        <?php displayPageNav("order")?>

        <section class="info-section">
            <ul>
                <li class="amount">
                    <p><span><?php echo $amount?></span></p>
                    <p>Total de Pedidos</p>
                </li>
            </ul>
        </section>

        <form method="GET" class="search-section">
            <label for="isearch">Pesquisar pelo <strong>nome do cliente</strong> ou <strong>código</strong></label>
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
                searchColumns($_GET["query"], "order");
            }
            
            displayTable("order");
        ?>
        
    </main>
</body>
</html>