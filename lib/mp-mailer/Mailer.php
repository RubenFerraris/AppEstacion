<?php

/*  Requiere por metodo post:

    send: valor "mail"
    destinatario: email del receptor
    motivo: motivo del mensaje que verá el receptor
    contenido: mensaje que verá el receptor, acepta etiquetas html
*/


class Mailer
{
    
    function __construct(){
    }
    function send($form){
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0 ;
        $mail->Host = HOST_SMTP;
        $mail->Port = PORT;
        $mail->SMTPAuth = SMTP_AUTH; //
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Username = REMITENTE;
        $mail->Password = PASSWORD;

        $mail->setFrom(REMITENTE, NOMBRE);
        $mail->addAddress($form["destinatario"]);

        $mail->isHTML(true);

        $mail->Subject = utf8_decode($form["motivo"]);
        $mail->Body = utf8_decode($form["contenido"]);

        if(!$mail->send()){
            error_log("Mailer no se pudo enviar el correo!" );
            $body = array("errno" => 1, "error" => "No se pudo enviar.");
        }else{
            $body = array("errno" => 0, "error" => "Enviado con exito.");
        }   

        return json_encode($body);
    }
}
?>


