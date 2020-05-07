<?php


namespace Juff\Kernel;


use Juff\Kernel\Exception\ForbiddenException;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment as Twig;

class RouteHandler
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $routePermissions = [];

    /**
     * @var Twig
     */
    private $twig;

    /**
     * RouteHandler constructor.
     * @param Twig $twig
     * @param array $handler
     */
    public function __construct(Twig $twig, array $handler)
    {
        if (count($handler) < 3) {
            $handler[] = [];
        }
        [$class, $method, $permissions] = $handler;
        $this->method = $method;
        $this->class =$class;
        $this->routePermissions = $permissions;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @return string
     * @throws ForbiddenException
     */
    public function handle(Request $request): string
    {
        $this->checkPermissions($request);
        $handlerInstance = new $this->class($this->twig, $request);
        $response = call_user_func([$handlerInstance, $this->method]);

        return $response;
    }

    /**
     * @param Request $request
     * @throws ForbiddenException
     */
    private function checkPermissions(Request $request): void
    {
        foreach ($this->routePermissions as $permission) {
            if (!$request->get('current_user')->hasPermission($permission)) {
                throw new ForbiddenException();
            }
        }
    }
}