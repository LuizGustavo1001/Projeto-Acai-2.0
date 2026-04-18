<?php require_once __DIR__ . '/../../src/controller/account/loginController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Login</title>
</head>

<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/index.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("login")?>
            </div>

            <div class="title">
                <h1>Área de Login</h1>
                <p>Realize seu <strong>login</strong> para <strong>continuar comprando</strong> em nosso site.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="imail">Endereço de Email</label>
                    <input type="email" name="email" id="imail" placeholder="exemplo@dominio.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="regular-input-box">
                    <label for="ipassword">Senha</label>
                    <input type="password" name="password" id="ipassword" placeholder="********" required>
                </div>

                <button class="regular-btn">Entrar</button>
            </form>

            <div class="fot">
                <a href="password.php">Esqueceu sua senha?</a>
                <a href="register.php">Ainda não está registrado?</a>
            </div>
        </div>
        <div class="fot-copy">2026 &copy; Açaí e Polpas Amazônia</div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>