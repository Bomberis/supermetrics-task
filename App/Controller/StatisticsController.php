<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Service\AuthService;
use App\Service\StatsProvider;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class StatisticsController extends AbstractController
{
    private StatsProvider $statsProvider;
    private AuthService $authService;

    public function __construct(StatsProvider $statsProvider, AuthService $authService)
    {
        $this->statsProvider = $statsProvider;
        $this->authService = $authService;
    }

    /**
     * @param $request array
     * @return ResponseInterface
     */
    public function getStatistics(array $request): ResponseInterface
    {
        if (!$this->authService->isAuth()) {
            return $this->json([], parent::RESPONSE_UNAUTHORIZED);
        }

        if (!isset($request['page'])) {
            return $this->json([], parent::RESPONSE_BAD_REQUEST);
        }

        $stats = $this->statsProvider->generate(
            $this->authService->getToken(),
            (int) $request['page']
        );

        return $this->json($stats);
    }
}
