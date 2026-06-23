<?php

namespace Ellephanty\Alerty;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Envía un correo con la información del error
 * @param \Exception $exception
 * @param string $subject
 */
function exception_email($exception, $subject = 'Exception report', $extraData = [])
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

    //Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body    = html_exception_email($exception, $extraData);

    if (!getenv('EMAIL_EXCEPTION_ENVIRONMENT') || getenv('APP_ENVIRONMENT') == getenv('EMAIL_EXCEPTION_ENVIRONMENT')) {
        $mail->send();
    }
}

/**
 * Genera el HTML del correo
 * @param  \Exception $exception
 * @return string
 */
function html_exception_email($exception, $extraData = [])
{
    $errorData = [
        'environment' => getenv('APP_ENVIRONMENT') ?: 'N/A',
        'message' => $exception->getMessage(),
        'file'      => $exception->getFile(),
        'line'      => $exception->getLine(),
        'trace'     => array_slice($exception->getTrace(), 0, 6),
        'extra'     => $extraData
    ];

    $traceHtml = '';
    foreach ($errorData['trace'] as $t) {
        $file = isset($t['file']) ? $t['file'] : 'N/A';
        $line = isset($t['line']) ? $t['line'] : 'N/A';
        $func = isset($t['function']) ? $t['function'] : null;

        $traceHtml .= "<li>";

        if ($file !== 'N/A') {
            $traceHtml .= "<strong>{$file}</strong>";
        } else {
            $traceHtml .= "<strong>Internal call</strong>";
        }

        if ($line !== 'N/A') {
            $traceHtml .= " (línea {$line})";
        }

        if ($func) {
            $traceHtml .= " - {$func}()";
        }

        $traceHtml .= "</li>";
    }

    ob_start();
    include __DIR__ . '/templates/exception_email.php';
    return ob_get_clean();
}
