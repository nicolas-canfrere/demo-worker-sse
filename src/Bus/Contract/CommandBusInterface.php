<?php

namespace App\Bus\Contract;

interface CommandBusInterface
{
    public function execute(CommandInterface $command);
}
