<?php


namespace Juff\Kernel\EventListener;

use Juff\Kernel\Config;
use Juff\Kernel\Event\Event;
use Juff\Kernel\Event\RouteFoundEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class InitSessionEventListener implements EventListener
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Event
     */
    private $event;

    /**
     * InitSessionEventListener constructor.
     *
     * @param Config $config
     * @param Request $request
     */
    public function __construct(Event $event, Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
        $this->event = $event;
    }

    public function fire(): bool
    {
        $this->initSession($this->request);

        return true;
    }

    private function initSession(Request $request)
    {
        $sessionOptions = array('name' => $this->config->get('SESSION_ID'), 'cookie_path' => '/');
        $savePath = realpath(__DIR__ . '/../../../' . $this->config->get('SESSION_FILE_STORAGE'));
        $storage = new NativeSessionStorage($sessionOptions, new NativeFileSessionHandler($savePath));

        $session = new Session($storage);
        $session->start();
        $request->setSession($session);
    }

    public function getName(): string
    {
        return self::class;
    }
}