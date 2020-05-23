<?php


namespace App\Model;


class RegisterResponse
{
    private string $clientId;
    private string $email;
    private string $slToken;

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return RegisterResponse
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return RegisterResponse
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlToken(): string
    {
        return $this->slToken;
    }

    /**
     * @param string $slToken
     * @return RegisterResponse
     */
    public function setSlToken(string $slToken): self
    {
        $this->slToken = $slToken;
        return $this;
    }
}
