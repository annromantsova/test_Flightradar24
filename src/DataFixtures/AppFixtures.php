<?php

namespace App\DataFixtures;

use App\Entity\Airport;
use App\Entity\Flight;
use App\Entity\Passenger;
use App\Entity\Ticket;
use App\Repository\AirportRepository;
use App\Repository\FlightRepository;
use App\Repository\PassengerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    private AirportRepository $airportRepository;
    private FlightRepository $flightRepository;
    private PassengerRepository $passengerRepository;

    public function __construct(
      AirportRepository $airportRepository,
      FlightRepository $flightRepository,
      PassengerRepository $passengerRepository,

    ) {
        $this->airportRepository = $airportRepository;
        $this->flightRepository = $flightRepository;
        $this->passengerRepository = $passengerRepository;
    }

    /**
     * TODO: Splitting Fixtures into Separate Files
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $airport = new Airport();
            $airport->setName("Airport $i");
            $airport->setCountry($faker->countryCode());
            $airport->setCity($faker->city());
            $manager->persist($airport);
        }

        for ($i = 1; $i < 5; $i++) {
            $passenger = new Passenger();
            $passenger->setFirstName($faker->firstName());
            $passenger->setLastName($faker->lastName());
            $passenger->setPassportId($faker->shuffleString('7e8w65sdf98wera'));
            $manager->persist($passenger);
        }

        $manager->flush();

        $passengers = $this->passengerRepository->findAll();
        $airports = $this->airportRepository->findAll();

        for ($i = 1; $i < 6; $i++) {
            $destinationAirport = $airports[array_rand($airports)];
            $sourceAirport = $airports[array_rand($airports)];
            $flight = new Flight();
            $flight->setDepartureTime(new \DateTime("+{$i}day"));
            $flight->setDestinationAirport($destinationAirport);
            $flight->setSourceAirport($sourceAirport);
            $manager->persist($flight);
        }
        $manager->flush();

        $flights = $this->flightRepository->findAll();

        for ($i = 1; $i < 5; $i++) {
            $ticketFlight = $flights[array_rand($flights)];
            $ticketPassenger = $passengers[array_rand($passengers)];
            $ticket = new Ticket();
            $ticket->setSeat(random_int(1, 32));
            $ticket->setFlight($ticketFlight);
            $ticket->setPassenger($ticketPassenger);
            $manager->persist($ticket);
        }

        $manager->flush();
    }
}
