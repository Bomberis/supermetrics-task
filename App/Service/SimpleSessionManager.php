<?php

namespace App\Service;

class SimpleSessionManager
{
    public function __construct()
    {
        session_start();
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $expirationTime session expiration time in seconds, 0 infinite
     * @return SimpleSessionManager
     */
    public function set(string $key, string $value, int $expirationTime = 0): self
    {
        $_SESSION[$key] = [
            'value' => $value,
            'expiration' => $this->generateExpiration($expirationTime),
        ];

        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        if (!$this->has($key)) {
            return null;
        }

        $record = $_SESSION[$key];

        if ($record['expiration'] <= time() && $record['expiration'] !== 0) {
            $this->remove($key);
            return null;
        }

        return $record['value'];
    }

    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param int $expirationTime
     * @return int
     */
    private function generateExpiration(int $expirationTime): int
    {
        if ($expirationTime <= 0) {
            return 0;
        }

        return time() + $expirationTime;
    }
}
