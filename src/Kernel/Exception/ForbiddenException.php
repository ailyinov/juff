<?php


namespace Juff\Kernel\Exception;


use Throwable;

class ForbiddenException extends \Exception
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Forbidden', 403, $previous);
    }
}