<?php

namespace App\Tests;


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Ticket;

class TicketTest extends ApiTestCase
{

    public function testGetCollection(): void
    {

        $response = static::createClient()->request('GET', '/api/tickets');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
          '@context' => '/api/contexts/Ticket',
          '@id' => '/api/tickets',
          '@type' => 'hydra:Collection',
          'hydra:member' => [
            0 => [
              '@id' => '/api/tickets/1',
              '@type' => 'Ticket',
              'id' => 1,
              'flight' =>
                [
                  '@id' => '/api/flights/2',
                  '@type' => 'Flight',
                  'sourceAirport' => [
                    '@id' => '/api/airports/4',
                    '@type' => 'Airport',
                    'name' => 'Airport 3',
                  ],
                  'destinationAirport' =>
                    [
                      '@id' => '/api/airports/3',
                      '@type' => 'Airport',
                      'name' => 'Airport 2',
                    ],
                  'departureTime' => '2022-11-10T14:09:12+00:00',
                ],
              'passenger' =>
                array(
                  '@id' => '/api/passengers/4',
                  '@type' => 'Passenger',
                  'passportId' => 'eewfw8958dra6s7',
                ),
              'seat' => 11,
            ],
            1 => [
              '@id' => '/api/tickets/2',
              '@type' => 'Ticket',
              'id' => 2,
              'flight' => [
                '@id' => '/api/flights/4',
                '@type' => 'Flight',
                'sourceAirport' =>
                  [
                    '@id' => '/api/airports/5',
                    '@type' => 'Airport',
                    'name' => 'Airport 4',
                  ],
                'destinationAirport' =>
                  [
                    '@id' => '/api/airports/1',
                    '@type' => 'Airport',
                    'name' => 'Airport 0',
                  ],
                'departureTime' => '2022-11-12T14:09:12+00:00',
              ],
              'passenger' =>
                array(
                  '@id' => '/api/passengers/1',
                  '@type' => 'Passenger',
                  'passportId' => 'eawd58sew9786fr',
                ),
              'seat' => 6,
            ],
            2 => [
              '@id' => '/api/tickets/3',
              '@type' => 'Ticket',
              'id' => 3,
              'flight' => [
                '@id' => '/api/flights/2',
                '@type' => 'Flight',
                'sourceAirport' =>
                  [
                    '@id' => '/api/airports/4',
                    '@type' => 'Airport',
                    'name' => 'Airport 3',
                  ],
                'destinationAirport' =>
                  [
                    '@id' => '/api/airports/3',
                    '@type' => 'Airport',
                    'name' => 'Airport 2',
                  ],
                'departureTime' => '2022-11-10T14:09:12+00:00',
              ],
              'passenger' =>
                [
                  '@id' => '/api/passengers/4',
                  '@type' => 'Passenger',
                  'passportId' => 'eewfw8958dra6s7',
                ],
              'seat' => 19,
            ],
            3 => [
              '@id' => '/api/tickets/4',
              '@type' => 'Ticket',
              'id' => 4,
              'flight' =>
                [
                  '@id' => '/api/flights/3',
                  '@type' => 'Flight',
                  'sourceAirport' =>
                    [
                      '@id' => '/api/airports/5',
                      '@type' => 'Airport',
                      'name' => 'Airport 4',
                    ],
                  'destinationAirport' => [
                    '@id' => '/api/airports/5',
                    '@type' => 'Airport',
                    'name' => 'Airport 4',
                  ],
                  'departureTime' => '2022-11-11T14:09:12+00:00'
                ],
              'passenger' => [
                '@id' => '/api/passengers/3',
                '@type' => 'Passenger',
                'passportId' => '8w9adr6sef8w5e7',
              ],
              'seat' => 30,
            ],
          ],
          'hydra:totalItems' => 4,
        ]);


        $this->assertCount(4, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Ticket::class);
    }

    public function testCreateTicket(): void
    {
        $response = static::createClient()->request('POST', '/api/tickets', ['json' => [
          'flight' => '/api/flights/1',
          'passenger' => '/api/passengers/2',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
          '@context' => '/api/contexts/Ticket',
          '@id' => $response->toArray()['@id'],
          '@type' => 'Ticket',
          'id' => $response->toArray()['id'],
          'flight' =>
           [
              '@id' => '/api/flights/1',
              '@type' => 'Flight',
              'sourceAirport' =>
                [
                  '@id' => '/api/airports/1',
                  '@type' => 'Airport',
                  'name' => 'Airport 0',
                ],
              'destinationAirport' =>
                [
                  '@id' => '/api/airports/1',
                  '@type' => 'Airport',
                  'name' => 'Airport 0',
                ],
              'departureTime' => '2022-11-09T14:09:12+00:00',
            ],
          'passenger' =>
            [
              '@id' => '/api/passengers/2',
              '@type' => 'Passenger',
              'passportId' => '5e7wd9r688awfes',
            ],
          'seat' => $response->toArray()['seat'],
        ]);
        $this->assertMatchesRegularExpression('~^/api/tickets/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Ticket::class);
    }

    public function testCreateInvaliTicket(): void
    {
        static::createClient()->request('POST', '/api/tickets', ['json' => [
          'flight' => '1',
          'passenger' => '2',
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
          '@context' => '/api/contexts/Error',
          '@type' => 'hydra:Error',
          'hydra:title' => 'An error occurred',
          'hydra:description' =>  'Invalid IRI "1".',
        ]);
    }

    public function testUpdateTicket(): void
    {
        $client = static::createClient();

        $id = $this->findIriBy(Ticket::class, ['id' => '3']);

        $client->request('PUT', $id, ['json' => [
          'flight' => '/api/flights/1',
          'passenger' => '/api/passengers/1',
          'seat' => 5,
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
          '@context' => '/api/contexts/Ticket',
          '@id' => '/api/tickets/3',
          '@type' => 'Ticket',
          'id' => 3,
          'flight' =>
            [
              '@id' => '/api/flights/1',
              '@type' => 'Flight',
              'sourceAirport' =>
               [
                  '@id' => '/api/airports/1',
                  '@type' => 'Airport',
                  'name' => 'Airport 0',
                ],
              'destinationAirport' =>
                [
                  '@id' => '/api/airports/1',
                  '@type' => 'Airport',
                  'name' => 'Airport 0',
                ],
              'departureTime' => '2022-11-09T14:09:12+00:00',
            ],
          'passenger' =>
            [
              '@id' => '/api/passengers/1',
              '@type' => 'Passenger',
              'passportId' => 'eawd58sew9786fr',
            ],
          'seat' => 5,
        ]);
    }

    public function testDeleteTicket(): void
    {
        $client = static::createClient();
        $id = $this->findIriBy(Ticket::class, ['id' => '4']);

        $client->request('DELETE', $id);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
          static::getContainer()->get('doctrine')->getRepository(Ticket::class)->findOneBy(['id' => '/api/tickets/4'])
        );
    }

}
