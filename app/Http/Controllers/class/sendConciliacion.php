<?php
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

include_once getenv("FILE_CONFIG_PHP");

class sendEmailConc {
    public function enviarConciliacion($empresas, $factura, $nombre) {
        setlocale(LC_TIME, "spanish");
        $fecha=Carbon::now();
        
        $correo = trim($empresas->email_empresa);
        $correopass = trim($empresas->password);
        $correoservidor = trim($empresas->servidor_correo);
        $correopuerto = trim($empresas->puerto_correo);
        $correoseguridad = trim($empresas->seguridad_correo); 
        $id_empresa = trim($empresas->id_empresa); 
        $addemail = trim($factura);
        $nomcta=trim($empresas->nomcta);
        $fecha_conc=ucwords(strftime("%B %Y", strtotime($empresas->fecha_conciliacion)));

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = $correoservidor;
        $mail->SMTPAuth = true;
        $mail->Username = $correo;
        $mail->Password = $correopass;
        $mail->SMTPSecure = $correoseguridad;             
        $mail->Port = $correopuerto;
        $mail->setFrom($correo, 'Envio de Conciliacion Bancaria Cuenta '.$nomcta.' '.$fecha_conc);
        $mail->addAddress($addemail);
        $mail->isHTML(true);

        $mail->addAttachment(constant("DATA_EMPRESA").$id_empresa.'/Conciliacion/'.$empresas->id_plan_cuentas.'/'.$empresas->fecha_conciliacion.'/'.'conciliacion_bancaria.pdf');

        $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br> 
        Le informamos que la Conciliacion Bancario de la Cuenta ".$nomcta." ".$fecha_conc." ha sido emitida exitosamente y  
        se encuentra adjunto en el presente correo. <br>" . "Atentamente: ".$correo);
        $mail->Subject = 'Conciliacion Bancaria'; 
        $mail->Body = $bodyContent; 
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }
}