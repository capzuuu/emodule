<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_signing_email(string $toEmail, string $toName, string $docTitle, string $signingToken, string $pin = '', string $createdAt = ''): bool
{
    $signingUrl  = baseurl('/sign/' . $signingToken);
    $appName     = $_ENV['APP_NAME']       ?? 'SunnSign';
    $host        = $_ENV['MAIL_HOST']      ?? 'smtp.gmail.com';
    $port        = (int)($_ENV['MAIL_PORT'] ?? 587);
    $username    = $_ENV['MAIL_USERNAME']  ?? '';
    $password    = $_ENV['MAIL_PASSWORD']  ?? '';
    $fromEmail   = $_ENV['MAIL_FROM']      ?? $username;
    $fromName    = $_ENV['MAIL_FROM_NAME'] ?? $appName;

    $logoUrl      = asset('dist/assets/img/Final_SUNN_Logo.png');
    $sentDate     = $createdAt ? (new DateTime($createdAt, new DateTimeZone('Asia/Manila')))->format('F j, Y \a\t g:i A') : (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('F j, Y \a\t g:i A');
    $year         = date('Y');
    $toNameSafe   = htmlspecialchars($toName);
    $docTitleSafe = htmlspecialchars($docTitle);
    $pinBlock = $pin !== '' ? "
            <table width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:24px;'>
                <tr>
                    <td style='background:#fafafa;border:1px solid #e4e4e7;border-radius:8px;padding:14px 16px;text-align:center;'>
                        <div style='font-size:11px;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;'>Your Verification Code</div>
                        <div style='font-size:28px;font-weight:700;color:#18181b;letter-spacing:0.3em;'>{$pin}</div>
                        <div style='font-size:11px;color:#a1a1aa;margin-top:6px;'>Enter this code when prompted to verify your identity.</div>
                    </td>
                </tr>
            </table>" : '';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $port;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = "Sign Request: {$docTitleSafe}";
        $mail->Body    = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width,initial-scale=1.0'>
        </head>
        <body style='margin:0;padding:0;background:#f4f4f5;font-family:\"Segoe UI\",Arial,sans-serif;'>

        <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f5;padding:40px 16px;'>
        <tr><td align='center'>
        <table width='520' cellpadding='0' cellspacing='0' style='max-width:520px;width:100%;'>

            <tr>
                <td style='padding-bottom:24px;text-align:center;'>
                    <img src='{$logoUrl}' alt='SUNN' width='48' height='48'
                        style='border-radius:50%;display:block;margin:0 auto 10px;'>
                    <div style='font-size:13px;font-weight:700;color:#18181b;letter-spacing:0.2px;'>
                        State University of Northern Negros
                    </div>
                    <div style='font-size:11px;color:#71717a;margin-top:2px;'>{$appName}</div>
                </td>
            </tr>

            <tr>
                <td style='background:#ffffff;border-radius:12px;border:1px solid #e4e4e7;padding:36px 40px;'>

                    <p style='margin:0 0 20px;font-size:14px;color:#3f3f46;line-height:1.6;'>
                        Hi <strong style='color:#18181b;'>{$toNameSafe}</strong>,
                    </p>

                    <p style='margin:0 0 20px;font-size:14px;color:#52525b;line-height:1.7;'>
                        You have been requested to sign the following document:
                    </p>

                    <table width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:28px;'>
                        <tr>
                            <td style='background:#fafafa;border:1px solid #e4e4e7;border-radius:8px;padding:14px 16px;'>
                                <div style='font-size:11px;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;'>Document</div>
                                <div style='font-size:15px;font-weight:600;color:#18181b;'>{$docTitleSafe}</div>
                            </td>
                        </tr>
                    </table>

                    {$pinBlock}

                    <table width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:24px;'>
                        <tr>
                            <td align='center'>
                                <a href='{$signingUrl}'
                                style='display:inline-block;background:linear-gradient(135deg,#4e73df,#224abe);color:#ffffff;text-decoration:none;
                                        padding:11px 32px;border-radius:8px;font-size:14px;font-weight:600;
                                        letter-spacing:0.2px;'>
                                    Sign Document &rarr;
                                </a>
                            </td>
                        </tr>
                    </table>

                    <p style='margin:0 0 24px;font-size:11px;color:#a1a1aa;text-align:center;'>
                        Or open this link in your browser:<br>
                        <a href='{$signingUrl}' style='color:#52525b;word-break:break-all;'>{$signingUrl}</a>
                    </p>

                    <hr style='border:none;border-top:1px solid #f4f4f5;margin:0 0 20px;'>

                    <table cellpadding='0' cellspacing='0'>
                        <tr>
                            <td style='padding:2px 0;font-size:11.5px;color:#a1a1aa;'>
                                Sent by &nbsp;<span style='color:#52525b;font-weight:600;'>{$appName} &mdash; SUNN ICT Office</span>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:2px 0;font-size:11.5px;color:#a1a1aa;'>
                                Date &nbsp;<span style='color:#52525b;font-weight:600;'>{$sentDate}</span>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>

            <tr>
                <td style='padding:20px 0;text-align:center;'>
                    <p style='margin:0;font-size:11px;color:#a1a1aa;'>
                        This link is unique to you. Do not share it with others.
                    </p>
                    <p style='margin:4px 0 0;font-size:11px;color:#d4d4d8;'>
                        &copy; {$year} State University of Northern Negros
                    </p>
                </td>
            </tr>

        </table>
        </td></tr>
        </table>

        </body>
        </html>";

        $pinAlt = $pin !== '' ? "\nYour verification code: {$pin}\n" : '';
        $mail->AltBody = "Hi {$toNameSafe},\n\nYou have been requested to sign: {$docTitleSafe}\n{$pinAlt}\nSign here: {$signingUrl}\n\nSent by {$appName} on {$sentDate}.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('SunnSign mailer error: ' . $mail->ErrorInfo);
        return false;
    }
}

function send_signed_copy_email(string $toEmail, string $toName, string $docTitle, string $pdfPath, string $signedAt = ''): bool
{
    $appName   = $_ENV['APP_NAME']       ?? 'SunnSign';
    $host      = $_ENV['MAIL_HOST']      ?? 'smtp.gmail.com';
    $port      = (int)($_ENV['MAIL_PORT'] ?? 587);
    $username  = $_ENV['MAIL_USERNAME']  ?? '';
    $password  = $_ENV['MAIL_PASSWORD']  ?? '';
    $fromEmail = $_ENV['MAIL_FROM']      ?? $username;
    $fromName  = $_ENV['MAIL_FROM_NAME'] ?? $appName;

    $logoUrl      = asset('dist/assets/img/Final_SUNN_Logo.png');
    $signedDate   = $signedAt ? (new DateTime($signedAt, new DateTimeZone('Asia/Manila')))->format('F j, Y \a\t g:i A') : (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('F j, Y \a\t g:i A');
    $year         = date('Y');
    $toNameSafe   = htmlspecialchars($toName);
    $docTitleSafe = htmlspecialchars($docTitle);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $username;
        $mail->Password   = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $port;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        $mail->addAttachment($pdfPath, $docTitleSafe . '.pdf');

        $mail->isHTML(true);
        $mail->Subject = "Your Signed Copy: {$docTitleSafe}";
        $mail->Body    = "
        <!DOCTYPE html>
        <html lang='en'>
        <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'></head>
        <body style='margin:0;padding:0;background:#f4f4f5;font-family:\"Segoe UI\",Arial,sans-serif;'>
        <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f5;padding:40px 16px;'>
        <tr><td align='center'>
        <table width='520' cellpadding='0' cellspacing='0' style='max-width:520px;width:100%;'>
            <tr>
                <td style='padding-bottom:24px;text-align:center;'>
                    <img src='{$logoUrl}' alt='SUNN' width='48' height='48' style='border-radius:50%;display:block;margin:0 auto 10px;'>
                    <div style='font-size:13px;font-weight:700;color:#18181b;'>State University of Northern Negros</div>
                    <div style='font-size:11px;color:#71717a;margin-top:2px;'>{$appName}</div>
                </td>
            </tr>
            <tr>
                <td style='background:#ffffff;border-radius:12px;border:1px solid #e4e4e7;padding:36px 40px;'>
                    <p style='margin:0 0 20px;font-size:14px;color:#3f3f46;line-height:1.6;'>
                        Hi <strong style='color:#18181b;'>{$toNameSafe}</strong>,
                    </p>
                    <p style='margin:0 0 20px;font-size:14px;color:#52525b;line-height:1.7;'>
                        Thank you for signing. Your signed copy of the document is attached to this email for your records.
                    </p>
                    <table width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:24px;'>
                        <tr>
                            <td style='background:#fafafa;border:1px solid #e4e4e7;border-radius:8px;padding:14px 16px;'>
                                <div style='font-size:11px;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;'>Document</div>
                                <div style='font-size:15px;font-weight:600;color:#18181b;'>{$docTitleSafe}</div>
                            </td>
                        </tr>
                    </table>
                    <hr style='border:none;border-top:1px solid #f4f4f5;margin:0 0 20px;'>
                    <table cellpadding='0' cellspacing='0'>
                        <tr>
                            <td style='padding:2px 0;font-size:11.5px;color:#a1a1aa;'>
                                Signed on &nbsp;<span style='color:#52525b;font-weight:600;'>{$signedDate}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style='padding:20px 0;text-align:center;'>
                    <p style='margin:0;font-size:11px;color:#a1a1aa;'>Please keep this email for your records.</p>
                    <p style='margin:4px 0 0;font-size:11px;color:#d4d4d8;'>&copy; {$year} State University of Northern Negros</p>
                </td>
            </tr>
        </table>
        </td></tr>
        </table>
        </body></html>";

        $mail->AltBody = "Hi {$toNameSafe},\n\nThank you for signing \"{$docTitleSafe}\".\nYour signed copy is attached.\n\nSigned on {$signedDate}.\n\n{$appName} — SUNN ICT Office";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('SunnSign signed-copy mailer error: ' . $mail->ErrorInfo);
        return false;
    }
}
