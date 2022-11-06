<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Ticket;

class TicketProcessor implements ProcessorInterface
{
    private $decorated;

    public function __construct(ProcessorInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * @throws \Exception
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
       if( $operation->getMethod()=="POST"){
           $data->setSeat($this->generateSeat());
       }

        return $this->decorated->process($data, $operation, $uriVariables, $context);
    }

    /**
     * @throws \Exception
     */
    protected function generateSeat(): int
    {
        return random_int(0,32);
    }
}
