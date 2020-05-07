<?php


namespace Juff\Kernel\EventListener;

use Juff\Kernel\Config;
use Juff\Kernel\Event\Event;
use Juff\Kernel\Event\RouteFoundEvent;
use Juff\Service\AuthService;
use Lcobucci\JWT\Builder;
use Symfony\Component\HttpFoundation\Request;

class InitUserEventListener implements EventListener
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var Config
     */
    private $config;

    /**
     * InitUserEventListener constructor.
     *
     * @param Request $request
     */
    public function __construct(Event $event, Config $config, Request $request)
    {
        $this->request = $request;
        $this->event = $event;
        $this->config = $config;
    }


    public function fire(): bool
    {
        $this->initUser($this->request);

        return true;
    }

    private function initUser(Request $request): void
    {
        $session = $request->getSession();
        $user = AuthService::createUserFromSession($session);
        $request->attributes->set('current_user', $user);
    }

    public function getName(): string
    {
        return self::class;
    }
}