<?php

namespace Juff\Kernel\Event;

use Juff\Kernel\EventListener\EventListener;

class RouteFoundEvent implements Event
{
    const EVENT_NAME ='kernel.event.route-found';

    /**
     * @var EventListener[]
     */
    private static $listeners = [];

    public function getName(): string
    {
        return self::class;
    }

    public static function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    public static function getListeners(): array
    {
        return self::$listeners;
    }

    public static function setListeners(array $listeners): void
    {
        self::$listeners = array_merge(self::getListeners(), $listeners);
    }
}