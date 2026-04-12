<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../footerHeader.php";
    require_once "managerPHP.php";
    require_once "../printStyles.php";

    $amount = getAmountItem("product");
    $amount2 = getAmountItem("version");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="/js/generalScripts.js"></script>

    <link rel="stylesheet" href="<?php printStyle("universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("mannager") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia - Produtos</title>
    
</head>

<body>
    <?php displayManagerNav("product")?>

    <main>
        <?php
            if(isset($_GET["adminNotAllowed"]))
                displayPopUp("adminNotAllowed", "");
            else if(isset($_GET["makeAdmin"]))
                displayPopUp("makeAdmin", "");
        ?>
        
        <?php displayPageNav("product")?>

        <section class="info-section">
            <ul class="info-list">
                <li>
                    <ul class="info-sublist">
                        <li class="amount">
                            <p><span><?php echo $amount?></span></p>
                            <p>Total de Produtos</p>
                        </li>
                        
                        <li class="amount">
                            <p><span><?php echo $amount2?></span></p>
                            <p>Total de Versões</p>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul class="info-sublist">
                        <li>
                            <a href="addItem.php?type=product" class="link-button">Adicionar Produto</a>
                        </li>

                        <li>
                            <a href="addItem.php?type=prodVersion" class="link-button">Adicionar Versão</a>
                        </li>
                    </ul>
                </li> 
            </ul>
        </section>

        <form method="GET" class="search-section">
            <label for="isearch">Pesquisar Produto pelo <strong>nome</strong> ou <strong>código</strong></label>
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
                searchColumns($_GET["query"], "product");
            }
            
            displayTable("product");
        ?>
    </main>
</body>
</html>