<?php

namespace App\Message;

class DocumentMessage
{
    public function __construct(
        public readonly string $id
    ) {
    }
}
