<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FlightRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlightRepository::class)]
#[ApiResource(
  description: "Flight",
  normalizationContext: ['groups' => ['flight:read']],
)]
class Flight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['flight:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['flight:read','ticket:read'])]
    #[Assert\NotBlank]
    private ?Airport $sourceAirport = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['flight:read','ticket:read'])]
    #[Assert\NotBlank]
    private ?Airport $destinationAirport = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['flight:read','ticket:read'])]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $departureTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceAirport(): ?Airport
    {
        return $this->sourceAirport;
    }

    public function setSourceAirport(?Airport $sourceAirport): self
    {
        $this->sourceAirport = $sourceAirport;

        return $this;
    }

    public function getDestinationAirport(): ?Airport
    {
        return $this->destinationAirport;
    }

    public function setDestinationAirport(?Airport $destinationAirport): self
    {
        $this->destinationAirport = $destinationAirport;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(\DateTimeInterface $departureTime): self
    {
        $this->departureTime = $departureTime;

        return $this;
    }
}
