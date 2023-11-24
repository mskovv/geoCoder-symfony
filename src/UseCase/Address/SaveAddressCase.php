<?php

declare(strict_types=1);

namespace App\UseCase\Address;

use App\Repository\AddressRepository;
use JsonException;
use Throwable;

readonly class SaveAddressCase
{
    /**
     * @param AddressRepository $addressRepository
     */
    public function __construct(private AddressRepository $addressRepository)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(string $response): void
    {
        $geoCode = $this->prepareResponseData($response);
        $address = $this->prepareRegion($geoCode) . ', ' . $geoCode['GeoObject']['name'];
        $coordinates = $this->prepareCoordinates($geoCode);
        $point = "POINT($coordinates[0] $coordinates[1])";

        $this->addressRepository->savePoint($address, $point);
    }

    /**
     * @throws Throwable
     */
    private function prepareResponseData(string $response): array
    {
        try {
            $result = json_decode($response, true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Throwable $exception) {
            throw new JsonException($exception->getMessage());
        }

        return $result['response']['GeoObjectCollection']['featureMember'][0];
    }

    private function prepareRegion(array $geoCode): string
    {
        $region = explode(', ', $geoCode['GeoObject']['description'] ?? '');

        return implode(', ', array_reverse($region));
    }

    private function prepareCoordinates(array $geoCode): array
    {
        return explode(' ', $geoCode['GeoObject']['Point']['pos']);
    }

}