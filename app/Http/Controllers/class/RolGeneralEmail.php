<?php
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

include_once getenv("FILE_CONFIG_PHP");

class sendEmailRolGeneral {
    public function Rol_General($empresas,$empleado){
        setlocale(LC_TIME, "spanish");
        $fecha=$empresas->fechrol;
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo); 
        $id_empresa = trim($empresas->id_empresa); 
        $addemail = trim($empresas->email);
        // 'sistemas@stbtechnology.ec', 'Rock091410077*', 'mail.stbtechnology.ec', '465', 'ssl'
        $url = constant("DATA_EMPRESA");
        $mes="Febrero 2020";
        $fecha_papeleta=ucwords(strftime("%B %Y", strtotime($empresas->fechrol)));
        $documento="Rol_Pago_General_".$fecha_papeleta.".pdf";
        $url_pdf= $url.$empresas->id_empresa.'/rol_general/'.$fecha_papeleta.'/'.$empresas->id_departamento.'/'.$documento;

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;             
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Rol General '. $fecha_papeleta);
        $mail->addAddress($empleado);
        $mail->isHTML(true);
        

        $mail->addAttachment($url_pdf);

        $bodyContent = utf8_decode("Estimado(a):<br><br> 
        Le informamos que su Rol General del mes de ".$fecha_papeleta." ha sido emitida exitosamente y  
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: ".$correo);
        $mail->Subject = 'Rol General '. $fecha_papeleta; 
        $mail->Body = $bodyContent; 
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
}