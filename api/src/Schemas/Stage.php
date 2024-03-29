<?php

declare(strict_types=1);

namespace BLRLive\Schemas;

/*readonly*/ class Stage
{
    public function __construct(
        public string $name,
        public ?int $bracket,
        public array $scoreboard,
        public array $matches
    ) {
    }
}
