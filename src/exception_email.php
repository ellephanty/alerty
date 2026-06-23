<?php

namespace Ellephanty\Alerty;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Envía un correo con la información del error
 * @param Exception $exception
 * @param string $subject
 */
function exception_email($exception, $subject = 'Exception report')
{

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = getenv('EMAIL_HOST');
    $mail->SMTPAuth   = getenv('EMAIL_AUTH') ? filter_var(getenv('EMAIL_AUTH'), FILTER_VALIDATE_BOOLEAN) : true;
    $mail->Username   = getenv('EMAIL_USER');
    $mail->Password   = getenv('EMAIL_PASS');
    $mail->SMTPSecure = getenv('EMAIL_SECURE');
    $mail->Port       = getenv('EMAIL_PORT');

    //Recipients
    $mail->setFrom(getenv('EMAIL_FROM'), getenv('EMAIL_FROM_NAME') ? getenv('EMAIL_FROM_NAME') : getenv('EMAIL_FROM'));
    $mail->addAddress(getenv('EMAIL_EXCEPTION_TO'));

    $errorData = [
        'environment' => getenv('APP_ENVIRONMENT') ? getenv('APP_ENVIRONMENT') : 'N/A',
        'message' => $exception->getMessage(),
        'file'    => $exception->getFile(),
        'line'    => $exception->getLine(),
        'trace'   => $exception->getTrace()
    ];

    // Enviar por correo el error completo
    $html = json_encode($errorData, JSON_PRETTY_PRINT);

    //Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = $html;

    if (!getenv('EMAIL_EXCEPTION_ENVIRONMENT') || getenv('APP_ENVIRONMENT') == getenv('EMAIL_EXCEPTION_ENVIRONMENT')) {
        $mail->send();
    } else {
        echo $html;
    }
}
