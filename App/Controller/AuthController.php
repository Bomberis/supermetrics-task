<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Exception\RegistrationException;
use App\Service\AuthService;

class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register user
     * @return void
     */
    public function register(): void
    {
        try {
            $this->authService->register();
        } catch (RegistrationException $exception) {
            $this->json([$exception->getMessage()]);
        }
    }
}
