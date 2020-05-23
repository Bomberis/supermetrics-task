<?php

namespace App\Service;

use App\Exception\RegistrationException;
use App\Model\PostsRequest;
use App\Model\Post;
use App\Model\RegisterRequest;
use App\Model\RegisterResponse;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use PhpParser\JsonDecoder;

class SupermetricsApi
{
    private const BASE_URL = 'https://api.supermetrics.com/assignment/';
    private const ENDPOINT_REGISTER = 'register';
    private const ENDPOINT_POSTS = 'posts';

    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';

    private Client $client;
    private JsonDecoder $decoder;

    public function __construct(Client $client, JsonDecoder $decoder)
    {
        $this->client = $client;
        $this->decoder = $decoder;
    }

    /**
     * @param RegisterRequest $request
     * @return RegisterResponse
     * @throws RegistrationException
     */
    public function register(RegisterRequest $request): RegisterResponse
    {
        $params = [
            'client_id' => $request->getClientId(),
            'email' => $request->getEmail(),
            'name' => $request->getName(),
        ];

        $data = $this->call(self::METHOD_POST, self::ENDPOINT_REGISTER, $params);

        try {
            // This should be done with object transformer
            $response = new RegisterResponse();
            $response
                ->setClientId($data['client_id'])
                ->setEmail($data['email'])
                ->setSlToken($data['sl_token'])
            ;
        } catch (Exception $exception) {
            throw new RegistrationException;
        }

        return $response;
    }

    /**
     * @param PostsRequest $request
     * @return Post[]|null
     */
    public function getPosts(PostsRequest $request): ?array
    {
        $endpoint = sprintf('%s?%s', self::ENDPOINT_POSTS, http_build_query([
            'sl_token' => $request->getSlToken(),
            'page' => $request->getPage(),
        ]));

        $response = $this->call(self::METHOD_GET, $endpoint);

        // This should be done with object transformer
        $posts = [];

        try {
            foreach ($response['posts'] as $post) {
                $posts[] = (new Post())
                    ->setId($post['id'])
                    ->setFromName($post['from_name'])
                    ->setFromId($post['from_id'])
                    ->setMessage($post['message'])
                    ->setType($post['type'])
                    ->setCreatedTime(new DateTime($post['created_time']))
                ;
            }
        } catch (Exception $exception) {
            return null;
        }

        if (empty($posts)) {
            return null;
        }

        return $posts;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    private function call(string $method, string $endpoint, array $params = []): array
    {
        $apiResponse = $this->client->request(
            $method,
            sprintf('%s%s', self::BASE_URL, $endpoint),
            [
                'json' => $params
            ]
        );

        return ($this->decoder->decode($apiResponse->getBody()->getContents()))['data'];
    }
}
