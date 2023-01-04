<?php

declare(strict_types=1);

namespace App\DTO;

interface CreatableFromArray
{
    public static function createFromArray(array $data): self;
}
