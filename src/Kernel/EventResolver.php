<?php


namespace Juff\Kernel;


use Juff\Kernel\EventListener\EventListener;
use Psr\Container\ContainerInterface;

class EventResolver
{
    /**
     * @var array
     */
    private $definitions;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * EventResolver constructor.
     *
     * @param array $definitions
     * @param ContainerInterface $container
     */
    public function __construct(array $definitions, ContainerInterface $container)
    {
        $this->definitions = $definitions;
        $this->container = $container;
    }

    /**
     * @param array $args
     * @return boolean
     */
    public function fire(...$args): bool
    {
        [$event] = $args;
        foreach ($this->definitions[$event->getName()] as $subscriber) {
            /** @var EventListener $subscriber */
            $subscriber = $this->container->get($subscriber);
            if (false === call_user_func_array([$subscriber, 'fire'], $args)) {
                return false;
            }
        }

        return true;
    }
}