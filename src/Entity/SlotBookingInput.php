<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class SlotBookingInput
{
    #[Groups('slot:booking:input')]
    private int $command;

    /**
     * @return int
     */
    public function getCommand(): int
    {
        return $this->command;
    }

    /**
     * @param int $command
     */
    public function setCommand(int $command): void
    {
        $this->command = $command;
    }


}
