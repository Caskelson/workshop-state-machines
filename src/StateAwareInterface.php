<?php

namespace App;

interface StateAwareInterface
{
    public function can(string $transition): bool;

    public function apply(string $transition): void;
}