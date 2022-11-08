<?php

namespace App\Tests;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Flight;
use App\Entity\Ticket;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;


class FlightTest extends ApiTestCase
{
//    use RefreshDatabaseTrait;
//    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {

        $response = static::createClient()->request('GET', '/api/flights?page=1');


        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
          "@context" => "/api/contexts/Flight",
          "@id" => "/api/flights",
          "@type" => "hydra:Collection",
          "hydra:member" => [
            [
              "@id" => "/api/flights/1",
              "@type" => "Flight",
              "id" => 1,
              "sourceAirport" => [
                "@id" => "/api/airports/1",
                "@type" => "Airport",
                "name" => "Airport 0"
              ],
              "destinationAirport" => [
                "@id" => "/api/airports/1",
                "@type" => "Airport",
                "name" => "Airport 0"
              ],
              "departureTime" => "2022-11-09T14:09:12+00:00"
            ],
            [
              "@id" => "/api/flights/2",
              "@type" => "Flight",
              "id" => 2,
              "sourceAirport" => [
                "@id" => "/api/airports/4",
                "@type" => "Airport",
                "name" => "Airport 3"
              ],
              "destinationAirport" => [
                "@id" => "/api/airports/3",
                "@type" => "Airport",
                "name" => "Airport 2"
              ],
              "departureTime" => "2022-11-10T14:09:12+00:00"
            ],
            [
              "@id" => "/api/flights/3",
              "@type" => "Flight",
              "id" => 3,
              "sourceAirport" => [
                "@id" => "/api/airports/5",
                "@type" => "Airport",
                "name" => "Airport 4"
              ],
              "destinationAirport" => [
                "@id" => "/api/airports/5",
                "@type" => "Airport",
                "name" => "Airport 4"
              ],
              "departureTime" => "2022-11-11T14:09:12+00:00"
            ],
            [
              "@id" => "/api/flights/4",
              "@type" => "Flight",
              "id" => 4,
              "sourceAirport" => [
                "@id" => "/api/airports/5",
                "@type" => "Airport",
                "name" => "Airport 4"
              ],
              "destinationAirport" => [
                "@id" => "/api/airports/1",
                "@type" => "Airport",
                "name" => "Airport 0"
              ],
              "departureTime" => "2022-11-12T14:09:12+00:00"
            ],
            [
              "@id" => "/api/flights/5",
              "@type" => "Flight",
              "id" => 5,
              "sourceAirport" => [
                "@id" => "/api/airports/5",
                "@type" => "Airport",
                "name" => "Airport 4"
              ],
              "destinationAirport" => [
                "@id" => "/api/airports/2",
                "@type" => "Airport",
                "name" => "Airport 1"
              ],
              "departureTime" => "2022-11-13T14:09:12+00:00"
            ]
          ],
          "hydra:totalItems" => 5
        ]);


        $this->assertCount(5, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Flight::class);
    }

    public function testCreateFlight(): void
    {
        $response = static::createClient()->request('POST', '/api/flights', [
          'json' => [
            "sourceAirport" => "/api/airports/1",
            "destinationAirport" => "/api/airports/2",
            "departureTime" => "2022-11-08 13:39:34"
          ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
          '@context' => '/api/contexts/Flight',
          '@id' => $response->toArray()['@id'],
          '@type' => 'Flight',
          'id' => $response->toArray()['id'],
          'sourceAirport' =>
            array (
              '@id' => '/api/airports/1',
              '@type' => 'Airport',
              'name' => 'Airport 0',
            ),
          'destinationAirport' =>
            array (
              '@id' => '/api/airports/2',
              '@type' => 'Airport',
              'name' => 'Airport 1',
            ),
          'departureTime' =>  $response->toArray()['departureTime'],

        ]);
        $this->assertMatchesRegularExpression('~^/api/flights/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Flight::class);
    }

    public function testCreateInvalidFlight(): void
    {
        static::createClient()->request('POST', '/api/flights', ['json' => [
          'sourceAirport' => '1',
          'destinationAirport' => '2',
          "departureTime" => "2022-11-08 13:39:34"
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
          '@context' => '/api/contexts/Error',
          '@type' => 'hydra:Error',
          'hydra:title' => 'An error occurred',
          'hydra:description' => 'Invalid IRI "1".',
        ]);
    }

    public function testUpdateFlight(): void
    {
        $client = static::createClient();

        $id = $this->findIriBy(Flight::class, ['id' => 6]);

        $client->request('PUT', $id, ['json' => [
        "sourceAirport" => "/api/airports/1",
        "destinationAirport" => "/api/airports/2",
        "departureTime" => "2022-11-08T13:39:34"
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
          '@context' => '/api/contexts/Flight',
          '@id' => '/api/flights/6',
          '@type' => 'Flight',
          'id' => 6,
          'sourceAirport' =>
            array (
              '@id' => '/api/airports/1',
              '@type' => 'Airport',
              'name' => 'Airport 0',
            ),
          'destinationAirport' =>
            array (
              '@id' => '/api/airports/2',
              '@type' => 'Airport',
              'name' => 'Airport 1',
            ),
          'departureTime' =>  '2022-11-08T13:39:34+00:00',
        ]);
    }

    public function testDeleteFlight(): void
    {
        $client = static::createClient();
        $id = $this->findIriBy(Flight::class, ['id' => '8']);

        $client->request('DELETE', $id);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
          static::getContainer()->get('doctrine')->getRepository(Flight::class)->findOneBy(['id' => '/api/flights/8'])
        );
    }



}
