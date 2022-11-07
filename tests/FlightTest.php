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

        $response = static::createClient()->request('GET', '/api/tickets');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
          ['@context' => '/api/contexts/Ticket',
          '@id' => '/api/tickets',
          '@type' => 'hydra:Collection',
          'hydra:totalItems' => 4,
          'hydra:view' => [
            '@id' => '/api/tickets/1',
            '@type' => 'hydra:PartialCollectionView',
            'hydra:first' => '/api/tickets/1',
            'hydra:last' => '/api/tickets/2',
            'hydra:next' => '/api/tickets/3',
          ]
          ],
        ]);


        $this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
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
          '@type' => 'Ticket',
          'flight' => '/api/flights/1',
          'passenger' => '/api/passengers/2'
        ]);
        $this->assertMatchesRegularExpression('~^/tickets/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Ticket::class);
    }

    public function testCreateInvaliTicket(): void
    {
        static::createClient()->request('POST', '/books', ['json' => [
          'flight' => '1',
          'passenger' => '2',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
          '@context' => '/api/contexts/Error',
          '@type' => 'hydra:Error',
          'hydra:title' => 'An error occurred',
          'hydra:description' => 'Invalid IRI \"2\".',
        ]);
    }

    public function testUpdateTicket(): void
    {
        $client = static::createClient();

        $id = $this->findIriBy(Ticket::class, ['id' => '/api/tickets/3']);

        $client->request('PATCH', $id, ['json' => [
          'seat' => '',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
          'flight' => '/api/flights/1',
          'passenger' => '/api/passengers/1',
          'seat' => 5,
        ]);
    }

    public function testDeleteTicket(): void
    {
        $client = static::createClient();
        $id = $this->findIriBy(Ticket::class, ['id' => '/api/tickets/3']);

        $client->request('DELETE', $id);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
          static::getContainer()->get('doctrine')->getRepository(Ticket::class)->findOneBy(['id' => '/api/tickets/3'])
        );
    }



}
