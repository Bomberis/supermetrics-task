<?php

namespace App\Service;

use App\Exception\RegistrationException;
use App\Model\RegisterRequest;

class AuthService
{
    private const AUTH_TOKEN_KEY = 'userToken';
    private const AUTH_EXPIRATION = 60 * 60; // 1h

    private SupermetricsApi $api;
    private SimpleSessionManager $session;

    public function __construct(SupermetricsApi $api, SimpleSessionManager $session)
    {
        $this->api = $api;
        $this->session = $session;
    }

    /**
     * @return void
     * @throws RegistrationException
     */
    public function register(): void
    {
        $request = new RegisterRequest();
        $request
            ->setClientId(getenv('CLIENT_ID'))
            ->setEmail(getenv('EMAIL'))
            ->setName(getenv('NAME'))
        ;

        $response = $this
            ->api
            ->register($request)
        ;

        $this->session->set(self::AUTH_TOKEN_KEY, $response->getSlToken(), self::AUTH_EXPIRATION);
    }

    /**
     * @return bool
     */
    public function isAuth(): bool
    {
        if ($this->session->get(self::AUTH_TOKEN_KEY)) {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->session->get(self::AUTH_TOKEN_KEY);
    }
}
