<?php 

namespace App\Core;

use Firebase\JWT\JWT;
use Lcobucci\JWT\Exception;


class Session
{
    public function __construct()
    {
        session_start();
        $flash_messages = $_SESSION['flash_key'] ?? [];
        foreach($flash_messages as $key => &$flash_messages) {
            $flash_messages['removed'] = true;
        }
        $_SESSION['flash_key'] = $flash_messages;
    }

    public function setFlash(string $key, $value): void
    {
        $_SESSION['flash_key'][$key] = [
        'removed' => false,
        'value' => $value
        ];
    }

    public function getFlash(string $key): mixed
    {
        return $_SESSION['flash_key'][$key]['value'] ?? null;
    }

    public function removeFlash(string $key): void
    {
        $flash_messages = $_SESSION['flash_key'] ?? [];
        
        foreach($flash_messages as $key => &$flash_messages) {
            if ($flash_messages['removed']) { unset($flash_messages[$key]);
            }
        }

        $_SESSION['flash_key'] = $flash_messages;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        session_destroy();
    }
}
