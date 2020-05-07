<?php

namespace Juff\Kernel\Event;

use DI\Factory\RequestedEntry;
use Juff\Kernel\EventListener\EventListener;

interface Event extends RequestedEntry
{
    public function getName(): string;

    public static function getEventName(): string;

    /**
     * @return EventListener[]
     */
    public static function getListeners(): array;

    /**
     * @param EventListener[] $listeners
     * @return void
     */
    public static function setListeners(array $listeners): void;
}