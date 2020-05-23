<?php

namespace App\Core;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class AbstractController
{
    public const RESPONSE_OK = 200;
    public const RESPONSE_NOT_FOUND = 404;
    public const RESPONSE_UNAUTHORIZED = 401;
    public const RESPONSE_NO_CONTENT = 204;
    public const RESPONSE_BAD_REQUEST = 400;

    /**
     * @param array $body
     * @param int $status
     * @return Response
     */
    public function json(array $body = [], int $status = self::RESPONSE_OK): ResponseInterface
    {
        return new Response($status, [], json_encode($body));
    }
}
