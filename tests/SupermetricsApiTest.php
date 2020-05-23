<?php

use App\Model\RegisterRequest;
use App\Model\RegisterResponse;
use App\Service\SupermetricsApi;
use GuzzleHttp\Client;
use PhpParser\JsonDecoder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class SupermetricsApiTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $id = 'id';
        $email = 'email@email';
        $name = 'John Doe';
        $sl_token = 'token';

        $apiResponse = [
            'data' => [
                'sl_token' => $sl_token,
                'email' => $email,
                'client_id' => $id,
            ]
        ];

        $streamInterface = $this->createMock(StreamInterface::class);
        $streamInterface
            ->expects(self::once())
            ->method('getContents')
            ->willReturn(json_encode($apiResponse));

        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($streamInterface);

        $client = $this->createMock(Client::class);
        $client
            ->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                'https://api.supermetrics.com/assignment/register',
                [
                    'json' => [
                        'client_id' => $id,
                        'email' => $email,
                        'name' => $name,
                    ]
                ]
            )
            ->willReturn($responseInterface);

        $decoder = $this->createMock(JsonDecoder::class);
        $decoder
            ->expects(self::once())
            ->method('decode')
            ->with(json_encode($apiResponse))
            ->willReturn($apiResponse)
        ;

        $request = new RegisterRequest();
        $request
            ->setClientId($id)
            ->setEmail($email)
            ->setName($name);

        $service = new SupermetricsApi($client, $decoder);
        $response = $service->register($request);
        $this->assertInstanceOf(RegisterResponse::class, $response);
    }
}
