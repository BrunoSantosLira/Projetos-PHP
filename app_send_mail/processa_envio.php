<?php
    require('bibliotecas/PHPMailer/Exception.php');
    require('bibliotecas/PHPMailer/OAuth.php');
    require('bibliotecas/PHPMailer/PHPMailer.php');
    require('bibliotecas/PHPMailer/POP3.php');
    require('bibliotecas/PHPMailer/SMTP.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Dados{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        
        public $status = array('id_erro'=> null, 'descrição_erro'=> '');

        function __get($atributo){
            return $this->$atributo;
        }

        function __set($atributo,$valor){
            $this->$atributo = $valor;
        }

        function mensagemVálida(){
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem) ){
                return false;
            }else{
                return true;
            }

        }

    }

    $dados1 = new Dados();
    $dados1->__set('para',$_POST['para']);
    $dados1->__set('assunto',$_POST['assunto']);
    $dados1->__set('mensagem',$_POST['mensagem']);

    if(!$dados1->mensagemVálida()){
        echo 'mensagem inválida';
        header('Location: index.php');
    }else{
         $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com ';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'testeemailsend25@gmail.com';                     //SMTP username
            $mail->Password   = 'slcnevwtqztvkzxe';                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;
            $mail->setLanguage('pt');
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('testeemailsend25@gmail.com', 'Bruno');
            $mail->addAddress($dados1->__get('para'));     //Add a recipient
            //$mail->addAddress('ellen@example.com');               //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $dados1->__get('assunto');
            $mail->Body    = $dados1->__get('mensagem');
            $mail->AltBody = 'Por favor, utilize um client que tenha suporte a HTML para ver o restante do conteúdo.';

            $mail->send();

            $dados1->status['id_erro'] = 1;
            $dados1->status['descrição_erro'] = 'Mensagem enviada com sucesso!!!';

        } 
        catch (Exception $e) {
            $dados1->status['id_erro'] = 2;
            $dados1->status['descrição_erro'] = "Não foi possível enviar este email. Detalhes: {$mail->ErrorInfo}";
        } 
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>

    <body>
        <div class="container">

            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
            
            <div class="row">

                <div class="col-md-12">
                    <?php if ($dados1->status['id_erro'] == 1) { ?>

                        <div class="container">
                            <h1 class="text-success display-4">Successo</h1>
                            <p> <?php echo $dados1->status['descrição_erro'] ?> </p>
                            <a href="index.php" class="btn btn-success btn-lg mt-2 text-white">Voltar</a>
                        </div>
                        
                    <?php } else { ?>

                        <div class="container">
                            <h1 class="text-danger display-4">Erro detectado</h1>
                            <p> <?php echo $dados1->status['descrição_erro'] ?> </p>
                            <a href="index.php" class="btn btn-danger btn-lg mt-2 text-white">Voltar</a>
                        </div>

                    <?php } ?>
                </div>

            </div>

        </div>
    </body>

</html>