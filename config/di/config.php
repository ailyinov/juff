<?php

return [
    Juff\Kernel\EventListener\InitSessionEventListener::class => DI\autowire(),
    Juff\Kernel\EventListener\InitEloquentEventListener::class => DI\autowire(),
    Juff\Kernel\EventListener\InitUserEventListener::class => DI\autowire(),
];