<?php


namespace Juff\Entity;


class User
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var array
     */
    private $permissionsGranted = [];

    /**
     * User constructor.
     * @param string $role
     */
    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function grantPermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            $this->permissionsGranted[] = $permission;
        }
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissionsGranted, true);
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}