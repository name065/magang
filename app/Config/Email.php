<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig {
    public string $fromEmail  = 'arekcilik26@gmail.com';
    public string $fromName   = 'PELUIT';

    public string $protocol   = 'smtp';
    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = 'arekcilik26@gmail.com';
    public string $SMTPPass   = 'lzru mgjj nugc foru';
    public int    $SMTPPort   = 587;
    public string $SMTPCrypto = 'tls';

    public string $mailType   = 'html';
    public string $charset    = 'utf-8';
    public bool   $wordWrap   = true;

    // Penting untuk SMTP Gmail (biar tidak error)
    public string $newline    = "\r\n";
    public string $CRLF       = "\r\n";

    // optional (kalau mau debug sementara)
    public int $SMTPTimeout = 5;
}