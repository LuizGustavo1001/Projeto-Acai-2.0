<?php
    include "../../databaseConnection.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require __DIR__ . '/../../composer/vendor/autoload.php';

    if(! isset($_SESSION)){
        session_start();
    }
    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();
    }
    if(! isset($_SESSION["sendMail"])){
        header("location: password.php");
        exit();
    }else{
        $token = bin2hex(random_bytes(3));
        $emailReciever = $_SESSION["sendMail"];
        $email = new PHPMailer;

        try{
            $email->isSMTP();
            $email->Host = "smtp.gmail.com"; 
            $email->SMTPAuth = true; 
            $email->Username = "testemailsluiz@gmail.com"; 
            $email->Password = "aemd afyi ofth dauh"; 

            $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $email->Port = 587; 

            $email->setFrom("testemailsluiz@gmail.com", "Açaí Amazônia Ipatinga");
            $email->addAddress($emailReciever);
            $email->isHTML(true); 
            $email->CharSet = "UTF-8";

            $email->Subject = "Token de Recuperação de Senha"; 
            $email->Body = "<h1>Açaí e Polpas Amazônia</h1>"; 
            $email->Body .= "<p>Seu Token de Verificação de Email para Redefinir de Senha: <strong><h2>$token</h2></strong></p>";
            $email->Body .= "<p>Insira o Token acima na área destinada no site abaixo</p>";
            $email->Body .= "
                <p>O Token é válido até <strong>Fechar a Página do Token</strong> no site, após isso é necessário solicitar outro</p>
            ";
            $email->Body .= "<p>Atenciosamente, <strong>Equipe Açaí e Polpas Amazônia</strong></p>";
            $email->Body .= "<p>Este é um email automático, não responda.</p>";
            $email->AltBody = "Açaí e Polpas Amazônia\n Seu Token de Verificação de Email: $token";

            if($email->send()){
                $_SESSION["passwordToken"] = $token;
                header("location: rescuePassword.php"); 
                exit();
                
            }else {
                echo "Email não enviado";
            }
        }catch(Exception $e){
            echo "Erro ao enviar o email: {$email->ErrorInfo}";
        }

    }