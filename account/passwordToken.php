<?php 
    include "../databaseConnection.php";
    require "../mailLibrary/src/PHPMailer.php";
    require "../mailLibrary/src/SMTP.php";
    require "../mailLibrary/src/Exception.php";
        
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if(! isset($_SESSION)){
        session_start(); // iniciar a sessão

    }


    if(! isset($_SESSION["userMail"])){ // entrando na página sem solicitar um token
        header("location: password.php");
    }else{
        $token = bin2hex(random_bytes(3));

    $emailReciever = $_SESSION["userMail"];

    $email = new PHPMailer;

    try{
        $email->isSMTP();
        $email->Host = "smtp.gmail.com"; 
        $email->SMTPAuth = true; 
        $email->Username = "testemailsluiz@gmail.com"; 
        $email->Password = "aemd afyi ofth dauh"; 

        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port = 587; 

        $email->setFrom("testemailsluiz@gmail.com", "Açaí Amazônia Ipatinga"); // remetente
        $email->addAddress($emailReciever); // destinatário
        $email->isHTML(true); 
        $email->CharSet = "UTF-8"; // suportar caracteres especiais 

        $email->Subject = "Token de Recuperação de Senha"; 
        $email->Body = "<h1>Açaí Amazônia Ipatinga</h1>"; 
        $email->Body .= "<p>Seu Token de Verificação de Email para Redefinir de Senha: <strong><h2>$token</h2></strong></p>";
        $email->Body .= "<p>Insira o Token acima na área destinada no site abaixo</p>";
        $email->Body .= "
            <p>O Token é válido até <strong>Fechar a Página do Token</strong> no site, após isso é necessário solicitar outro</p>
        ";
        $email->Body .= "<p>Atenciosamente, <strong>Equipe Açaí Amazônia Ipatinga</strong></p>";
        $email->Body .= "<p>Este é um email automático, não responda.</p>";
        $email->AltBody = "Açaí da Amazônia\n Seu Token de Verificação de Email: $token";

        if($email->send()){ // email enviado com sucesso
            $_SESSION["passwordToken"] = $token;
            header("location: rescuePassword.php"); 
            
        }else {
            echo "Email não enviado";
        }
    }catch(Exception $e){
        echo "Erro ao enviar o email: {$email->ErrorInfo}";
    }

    }

