<?php


namespace Juff\Kernel\EventListener;

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use Juff\Kernel\Config;
use Juff\Kernel\Event\Event;
use Juff\Kernel\Event\RouteFoundEvent;
use Symfony\Component\HttpFoundation\Request;

class InitEloquentEventListener implements EventListener
{
    /**
     * @var Config
     */
    private $config;

    /**
     * InitEloquentEventListener constructor.
     *
     * @param Config $config
     */
    public function __construct(Event $event, Config $config, Request $request)
    {
        $this->config = $config;
    }

    public function fire(): bool
    {
        $this->initEloquent();

        return true;
    }

    private function initEloquent()
    {
        $capsule = new CapsuleManager();

        $capsule->addConnection([
            "driver" => $this->config->get('DB_DRIVER'),
            "host" => $this->config->get('DB_HOST'),
            "database" => $this->config->get('DB_NAME'),
            "username" => $this->config->get('DB_USER'),
            "password" => $this->config->get('DB_PASSWORD')

        ]);

        $capsule->getDatabaseManager();
        $capsule->bootEloquent();
    }

    public function getName(): string
    {
        return self::class;
    }
}