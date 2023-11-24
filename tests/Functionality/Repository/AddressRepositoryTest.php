<?php

namespace App\Tests\Functionality\Repository;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressRepositoryTest extends WebTestCase
{
    private AddressRepository $addressRepository;
    private Generator $faker;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->addressRepository = static::getContainer()->get(AddressRepository::class);
        $this->faker = Factory::create();
    }

    public function test_address_added_successfully(): void
    {
        $address = $this->faker->address();
        $coordinates = $this->faker->localCoordinates();
        $latitude = $coordinates['latitude'];
        $longitude = $coordinates['longitude'];
        $point = "POINT($latitude $longitude)";

        // act
        /**@var $result Address */
        $result = $this->addressRepository->savePoint($address, $point);

        // assert
        $this->assertEquals($address, $result->getAddress());
    }
}
