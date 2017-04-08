<?php

namespace CommonServices\UserServiceBundle\Security\Roles;

/**
 * Class UserRolesManager
 * @package CommonServices\UserServiceBundle\Security\Roles
 */
class UserRolesManager
{
    const ROLE_USER         = 'ROLE_USER';
    const ROLE_ACTIVE_USER  = 'ROLE_ACTIVE_USER';
    const ROLE_INACTIVE     = 'ROLE_INACTIVE';
    const ROLE_CLIENT       = 'ROLE_CLIENT';
    const ROLE_ADMIN        = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN  = 'ROLE_SUPER_ADMIN';

    /**
     * @return array
     */
    public static function getInactiveUserRoles()
    {
        return [
            self::ROLE_INACTIVE,
        ];
    }

    /**
     * @return array
     */
    public static function getStandardActiveUserRoles()
    {
        return [
            self::ROLE_ACTIVE_USER,
        ];
    }

    /**
     * @return array
     */
    public static function getAdminUserRoles()
    {
        $adminRoles = [
            self::ROLE_ADMIN
        ];

        return array_merge(
            $adminRoles,
            self::getStandardActiveUserRoles()
        );
    }

    /**
     * @return array
     */
    public static function getSuperAdminUserRoles()
    {
        $superAdminRoles = [
            self::ROLE_ADMIN
        ];

        return array_merge(
            $superAdminRoles,
            self::getAdminUserRoles()
        );
    }

    /**
     * @return array
     */
    public static function getDeletedUserRoles()
    {
        return [];
    }

}