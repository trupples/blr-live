<?php

declare(strict_types=1);

namespace BLRLive\Schemas;

/*readonly*/ class UpdateCurrentStatusRequest
{
    use ValidatedSchema;

    public ?string $stage;
    public ?int $match;
    public ?string $livestream;
}
