<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/general-style.css">
    <link rel="stylesheet" href="styles/index.css">

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <title>Açaí Amazônia Ipatinga</title>

</head>
<body>

    <main style="display: flex; flex-direction: column; align-items: center; justify-content: center ; font-size: 2em; text-align: center;">
        <p>
            <em>Algum erro ocorreu no Sistema Interno, tente novamente mais tarde ou entre em contato com nosso suporte</em>
        </p>
        <a href="index.php" style="margin-top: 2em; text-decoration: underline;">Página Principal</a>
        <?php 
            echo var_dump($_POST);

        ?>

    </main>
    
</body>
</html>