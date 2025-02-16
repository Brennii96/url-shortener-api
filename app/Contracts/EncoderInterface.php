<?php

namespace App\Contracts;

interface EncoderInterface
{
    public function encode(string $value): array;

    public function decode(string $shortCode): null|string;
}
