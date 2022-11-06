<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TicketRepository;
use App\State\TicketProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(
  description: "Ticket",
  operations: [
    new Patch(denormalizationContext: ['groups' => ['ticket:update']]),
    new Put(denormalizationContext: ['groups' => ['ticket:update']]),
    new Get(normalizationContext: ['groups' => ['ticket:read']]),
    new Post(denormalizationContext: ['groups' => ['ticket:write']]),
    new Delete(),
  ],
  processor: TicketProcessor::class,
)]

class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ticket:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticket:read','ticket:create','ticket:update'])]
    #[Assert\NotBlank]
    private ?Flight $flight = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticket:read','ticket:create','ticket:update'])]
    #[Assert\NotBlank]
    private ?Passenger $passenger = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['ticket:read','ticket:update'])]
    private ?int $seat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    public function setFlight(?Flight $flight): self
    {
        $this->flight = $flight;

        return $this;
    }

    public function getPassenger(): ?Passenger
    {
        return $this->passenger;
    }

    public function setPassenger(?Passenger $passenger): self
    {
        $this->passenger = $passenger;

        return $this;
    }

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    /**
     * @throws \Exception
     */
    public function setSeat(?int $seat): self
    {
        $this->seat = $seat;

        return $this;
    }
}
