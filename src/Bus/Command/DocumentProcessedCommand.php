<?php

namespace App\Bus\Command;

use App\Bus\Contract\CommandInterface;

class DocumentProcessedCommand implements CommandInterface
{
    public function __construct(
        public string $id
    ) {
    }
}
