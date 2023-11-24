<?php

declare(strict_types=1);

namespace App\Client\Yandex;

use _PHPStan_53d0d2174\Nette\Neon\Exception;
use App\Exceptions\ClientException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class YandexClient
{
    private string $apiKey;


    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
    )
    {
    }

    public function setApiKey(string $apiKey):void
    {
        if (empty($apiKey)) {
            throw new Exception('Не установлен код доступа к Яндекс.Геокодер');
        }
        $this->apiKey = $apiKey;
    }

    /**
     * @throws ClientException
     */
    public function sendData(string $address): string
    {
        try {
            $response = $this->client->request(
                'GET',
                "https://geocode-maps.yandex.ru/1.x?geocode=$address&apikey=$this->apiKey&format=json&results=1"
            );
        } catch (ClientException $e) {
            $message = 'Не получилось найти координаты для указанного адреса';
            $this->logger->error($e->getMessage());
            throw new ClientException($message);
        }

        return $response->getContent();
    }
}
