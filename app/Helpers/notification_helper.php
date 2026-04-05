<?php
/**
 * Kirim notifikasi via EMAIL (menggantikan Telegram).
 * Tetap memakai nama fungsi telegram() agar struktur project tidak berubah.
 */
function telegram($email_to, $message)
{
    $email = \Config\Services::email();

    // Ambil konfigurasi default dari Config\Email dan/atau .env
    $cfg = config('Email');
    $fromEmail = $cfg->fromEmail ?: (getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@localhost');
    $fromName  = $cfg->fromName  ?: (getenv('MAIL_FROM_NAME') ?: 'PELUIT');

    // Pastikan ada FROM, kalau tidak CI4 biasanya gagal kirim
    $email->setFrom($fromEmail, $fromName);

    // Subject default (bisa diganti dari isi message jika diperlukan)
    $subject = 'PELUIT - Notifikasi';
    if (stripos($message, 'Kode OTP') !== false || stripos($message, 'OTP') !== false) {
        $subject = 'PELUIT - Kode OTP Login';
    }

    $email->setTo($email_to);
    $email->setSubject($subject);
    $email->setMessage($message . "\n\n\n-----P E L U I T-----");

    $resp = new \stdClass();
    try {
        if ($email->send()) {
            $resp->ok = true;
            $resp->result = 'sent';
        } else {
            $resp->ok = false;
            // Debugger CI4 akan kasih alasan (FROM kosong / SMTP gagal / dsb)
            $resp->result = $email->printDebugger(['headers', 'subject']);
        }
    } catch (\Throwable $e) {
        $resp->ok = false;
        $resp->result = $e->getMessage();
    }

    return $resp;
}

?>