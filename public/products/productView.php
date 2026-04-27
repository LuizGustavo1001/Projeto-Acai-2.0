<?php require_once __DIR__ . '/../../src/controller/products/productViewController.php'; ?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("products") ?>">

    <?php displayFavicon()?>

    <script>
        // Update the prices of each product variant selected in real time
        document.addEventListener('DOMContentLoaded', function () {
            const priceElements = document.querySelectorAll('.product-price-value')
            const sizeSelectors = document.querySelectorAll('.product-variant-selector')

            function updatePrices(){
                sizeSelectors.forEach((selector, index) => {
                    const selectedOption = selector.options[selector.selectedIndex]
                    const price = selectedOption.dataset.price || 'Preço indisponível'
                    if(priceElements[index]){
                        priceElements[index].querySelector('span').textContent = 'R$ ' + price
                    }
                })
            }
            updatePrices()

            sizeSelectors.forEach(selector => {
                selector.addEventListener('change', updatePrices)
            })
        })
    </script>
    
    <style>
        body{
            align-items: center;
            justify-content: center;
        }
    </style>

    <title>Açaí e Polpas Amazônia | <?php echo $dataMap['prodName']?> </title>
</head>

<body>
    <main class="rise-above product-view">
        <div class="back-button" onclick="window.location.href = 'products.php'">
            <?php echo getIcon("back") ?>
            <span>Voltar</span>
        </div>
        
        <?php fillPage(); ?>

        <div class="fot-copy">2026 &copy; Açaí e Polpas Amazônia</div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>