<?php


namespace Juff\Kernel;


use Dotenv\Dotenv;

class Config
{
    private $config = [];

    public function put(string $name, string $vale)
    {
        $this->config[$name] = $vale;
    }

    public function get(string $var)
    {
        return getenv($var) ?: $this->config[$var];
    }
}