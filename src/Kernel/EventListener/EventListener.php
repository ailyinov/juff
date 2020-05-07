<?php


namespace Juff\Kernel\EventListener;

use DI\Factory\RequestedEntry;

interface EventListener extends RequestedEntry
{
    public function fire(): bool;
}