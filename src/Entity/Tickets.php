<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TicketsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketsRepository::class)]
#[ApiResource]
class Tickets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Departure_time = null;

    #[ORM\Column]
    private ?int $source_airport = null;

    #[ORM\Column]
    private ?int $destination_airport_id = null;

    #[ORM\Column]
    private ?int $seat = null;

    #[ORM\Column(length: 255)]
    private ?string $passport_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->Departure_time;
    }

    public function setDepartureTime(\DateTimeInterface $Departure_time): self
    {
        $this->Departure_time = $Departure_time;

        return $this;
    }

    public function getSourceAirpotr(): ?int
    {
        return $this->source_airpotr;
    }

    public function setSourceAirport(int $source_airport): self
    {
        $this->source_airport = $source_airport;

        return $this;
    }

    public function getDestinationAirportId(): ?int
    {
        return $this->destination_airport_id;
    }

    public function setDestinationAirportId(int $destination_airport_id): self
    {
        $this->destination_airport_id = $destination_airport_id;

        return $this;
    }

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    public function setSeat(int $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getPassportId(): ?string
    {
        return $this->passport_id;
    }

    public function setPassportId(string $passport_id): self
    {
        $this->passport_id = $passport_id;

        return $this;
    }
}
