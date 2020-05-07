<?php

use Juff\Kernel\Event\RouteFoundEvent;
use Juff\Kernel\EventListener\InitEloquentEventListener;
use Juff\Kernel\EventListener\InitSessionEventListener;
use Juff\Kernel\EventListener\InitUserEventListener;

return [
    RouteFoundEvent::class => function() {
        RouteFoundEvent::setListeners([
            InitSessionEventListener::class,
            InitEloquentEventListener::class,
            InitUserEventListener::class,
        ]);
    },
];