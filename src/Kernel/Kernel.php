<?php


namespace Juff\Kernel;

use DI\ContainerBuilder;
use FastRoute\Dispatcher as RouteDispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use Juff\Kernel\Event\Event;
use Juff\Kernel\Event\RouteFoundEvent;
use Juff\Kernel\EventListener\EventListener;
use Juff\Kernel\Exception\ForbiddenException;
use ReflectionClass;
use Sabre\Event\EmitterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

class Kernel
{
    /**
     * @var GroupCountBased
     */
    private $routeDispatcher;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Twig
     */
    private $twig;

    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    /**
     * @var \DI\Container
     */
    private $container;

    /**
     * @var array
     */
    private $events;

    /**
     * Kernel constructor.
     *
     * @param GroupCountBased $routeDispatcher
     * @param EmitterInterface $eventEmitter
     * @param array $events
     * @param Config $config
     * @param Twig $twig
     * @throws \Exception
     */
    public function __construct(GroupCountBased $routeDispatcher, EmitterInterface $eventEmitter, array $events, Config $config, Twig $twig)
    {
        $this->routeDispatcher = $routeDispatcher;
        $this->eventEmitter = $eventEmitter;
        $this->config = $config;
        $this->twig = $twig;

        $builder = new ContainerBuilder();
        $builder->addDefinitions(realpath(__DIR__ . '/../../config/di/config.php'));
        $this->container = $builder->build();

        $this->events = $events;
        $this->loadEvents();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function handle(Request $request): Response
    {
        $httpMethod = $request->getMethod();
        $pathInfo = $request->getPathInfo();

        $routeInfo = $this->routeDispatcher->dispatch($httpMethod, $pathInfo);
        switch ($routeInfo[0]) {
            case RouteDispatcher::NOT_FOUND:
                return new Response('Not found', 404);

            case RouteDispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                return new Response('Method Not Allowed. Allowed methods are: ' . implode(', ', $allowedMethods), 405);

            case RouteDispatcher::FOUND:
                $this->eventEmitter->emit(RouteFoundEvent::getEventName(), [new RouteFoundEvent(), $this->config, $request]);
                [, $handler, $vars] = $routeInfo;
                try {
                    $responseData = $this->handleRoute($request, $handler, $vars);
                } catch (ForbiddenException $ex) {
                    return new Response($ex->getMessage(), 403);
                }

                return new Response($responseData);
        }

        throw new \Exception('Can\'t handle uri ' . $request->getRequestUri());
    }

    /**
     * @param Request $request
     * @param array $handler
     * @param array $vars
     * @return string
     * @throws ForbiddenException
     */
    private function handleRoute(Request $request, array $handler, array $vars): string
    {
        $routeHandler = new RouteHandler($this->twig, $handler);
        $request->attributes->add($vars);

        return $routeHandler->handle($request);
    }

    private function loadEvents()
    {
        /** @var Event $event */
        foreach ($this->events as $event) {
            $this->eventEmitter->on($event::getEventName(), function(...$args) use ($event) {
                foreach ($event::getListeners() as $listenerName) {
                    $listenerParams = [];
                    $reflection = new ReflectionClass($listenerName);
                    foreach ($reflection->getConstructor()->getParameters() as $param) {
                        $listenerParams[] = $param->getName();
                    }
                    $args = array_combine($listenerParams, $args);
                    /** @var EventListener $listener */
                    $listener = $this->container->make($listenerName, $args);
                    $this->eventEmitter->on($event::getEventName(), [$listener, 'fire']);
                    $listener->fire();
                }
            });
        }
    }
}