<?php

namespace App;

enum UserRole: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
}
