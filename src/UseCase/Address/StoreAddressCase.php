<?php

declare(strict_types=1);

namespace App\UseCase\Address;

use App\Client\Yandex\YandexClient;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

readonly class StoreAddressCase
{

    public function __construct(
        private YandexClient $client,
        private SaveAddressCase $case,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handle(string $address): void
    {
        $this->logger->info($address);
        $response = $this->client->sendData($address);
        try {
            $this->case->handle($response);
        } catch (RuntimeException $exception) {
            $this->logger->error($exception->getMessage());
            throw new RuntimeException($exception->getMessage());
        }
    }
}