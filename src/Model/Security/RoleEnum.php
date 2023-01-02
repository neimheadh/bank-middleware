<?php

namespace App\Model\Security;

/**
 * Security roles.
 */
enum RoleEnum: string
{

    /**
     * Authenticated user.
     */
    case ROLE_USER = 'ROLE_USER';

    /**
     * Administrator.
     */
    case ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Super administrator.
     */
    case ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
}
