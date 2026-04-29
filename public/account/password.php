<?php require_once __DIR__ . '../../../src/controller/account/passwordController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">
        
    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Recuperar Senha</title>
</head>

<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = 'login.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("key")?>
            </div>

            <div class="title">
                <h1>Esqueceu sua senha?</h1>
                <p>
                    Insira o <strong>endereço de email</strong> vinculado a sua conta para enviarmos um <strong>token de recuperação</strong>. <br>
                    Após clicar em enviar <strong>aguarde alguns segundos</strong>.
                </p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="iemail">Endereço de Email: </label>
                    <input type="email" name="email" id="iemail" maxlength="50" placeholder="email@exemplo.com" required>
                </div>

                <button class="regular-btn await">
                    <span>Enviar Código</span>
                    <div class="wheel spinning inactive"></div>
                </button>
            </form>
        </div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>