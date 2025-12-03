<?php

/**
 * Mailer đơn giản sử dụng SMTP Gmail
 * Không cần thư viện bên ngoài, sử dụng socket PHP thuần
 */

require_once __DIR__ . '/config.php';

class SimpleSMTPMailer
{
    private $host;
    private $port;
    private $secure;
    private $username;
    private $password;
    private $fromEmail;
    private $fromName;
    private $socket;
    private $error;

    public function __construct()
    {
        $this->host = SMTP_HOST;
        $this->port = SMTP_PORT;
        $this->secure = SMTP_SECURE;
        $this->username = SMTP_USERNAME;
        $this->password = SMTP_PASSWORD;
        $this->fromEmail = SMTP_FROM_EMAIL;
        $this->fromName = SMTP_FROM_NAME;
        $this->error = '';
    }

    /**
     * Gửi email qua SMTP
     * @param string $to Email người nhận
     * @param string $subject Tiêu đề
     * @param string $body Nội dung (plain text)
     * @param string $replyTo Email reply-to (optional)
     * @return bool
     */
    public function send($to, $subject, $body, $replyTo = null)
    {
        if (!SMTP_ENABLED) {
            // Fallback về mail() function nếu SMTP bị tắt
            $headers = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            if ($replyTo) {
                $headers .= "Reply-To: {$replyTo}\r\n";
            }
            return @mail($to, $subject, $body, $headers);
        }

        try {
            // Kết nối đến SMTP server
            $hostname = $this->secure === 'ssl' ? 'ssl://' . $this->host : $this->host;
            $this->socket = @fsockopen($hostname, $this->port, $errno, $errstr, 10);

            if (!$this->socket) {
                $this->error = "Không thể kết nối đến SMTP server: {$errstr} ({$errno})";
                return false;
            }

            // Đọc response đầu tiên
            $this->readResponse();

            // EHLO
            $this->sendCommand("EHLO " . $this->host);
            if (!$this->readResponse()) {
                return false;
            }

            // STARTTLS nếu dùng TLS
            if ($this->secure === 'tls') {
                $this->sendCommand("STARTTLS");
                if (!$this->readResponse()) {
                    return false;
                }
                stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                // Gửi lại EHLO sau TLS
                $this->sendCommand("EHLO " . $this->host);
                if (!$this->readResponse()) {
                    return false;
                }
            }

            // AUTH LOGIN
            $this->sendCommand("AUTH LOGIN");
            if (!$this->readResponse()) {
                return false;
            }

            // Username
            $this->sendCommand(base64_encode($this->username));
            if (!$this->readResponse()) {
                return false;
            }

            // Password
            $this->sendCommand(base64_encode($this->password));
            if (!$this->readResponse()) {
                return false;
            }

            // MAIL FROM
            $this->sendCommand("MAIL FROM: <{$this->fromEmail}>");
            if (!$this->readResponse()) {
                return false;
            }

            // RCPT TO
            $this->sendCommand("RCPT TO: <{$to}>");
            if (!$this->readResponse()) {
                return false;
            }

            // DATA
            $this->sendCommand("DATA");
            if (!$this->readResponse()) {
                return false;
            }

            // Headers và body
            $headers = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
            $headers .= "To: <{$to}>\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n";
            if ($replyTo) {
                $headers .= "Reply-To: {$replyTo}\r\n";
            }
            $headers .= "\r\n";

            $message = $headers . $body . "\r\n.\r\n";
            fwrite($this->socket, $message);

            if (!$this->readResponse()) {
                return false;
            }

            // QUIT
            $this->sendCommand("QUIT");
            $this->readResponse();

            fclose($this->socket);
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            if ($this->socket) {
                fclose($this->socket);
            }
            return false;
        }
    }

    private function sendCommand($command)
    {
        fwrite($this->socket, $command . "\r\n");
    }

    private function readResponse()
    {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }

        $code = (int)substr($response, 0, 3);
        if ($code >= 400) {
            $this->error = "SMTP Error: {$response}";
            return false;
        }

        return true;
    }

    public function getError()
    {
        return $this->error;
    }
}

/**
 * Helper function để gửi email dễ dàng
 * @param string $to Email người nhận
 * @param string $subject Tiêu đề
 * @param string $body Nội dung
 * @param string $replyTo Email reply-to (optional)
 * @return array ['success' => bool, 'message' => string]
 */
function sendEmail($to, $subject, $body, $replyTo = null)
{
    $mailer = new SimpleSMTPMailer();
    $success = $mailer->send($to, $subject, $body, $replyTo);

    return [
        'success' => $success,
        'message' => $success ? 'Email đã được gửi thành công.' : $mailer->getError()
    ];
}
