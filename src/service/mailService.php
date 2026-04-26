<?php 
namespace Services;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once __DIR__ . '/../../config/composer/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../config/');
$dotenv->load();

class MailService{
    private $mailer;
    private $from;

    public function __construct(){
        $DNS = $_ENV['MAILER_DNS'];
        $transport = Transport::fromDsn($DNS);
        $this->mailer = new Mailer($transport);

        $this->from = $_ENV['MAIL_FROM'] ?? 'no-reply@acaiamazonia.com';
    }

    public function send($mailTo, $subject, $message){
        $email = (new Email())
            ->from($this->from)
            ->to($mailTo)
            ->subject($subject)
            ->html($message);

        $this->mailer->send($email);
        
        return true;
    }
}