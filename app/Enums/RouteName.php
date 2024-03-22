<?php

namespace App\Enums;

enum RouteName
{
    const LOGIN = 'api.login';
    const REGISTER = 'api.register';
    const RESET_PASSWORD = 'api.reset.password';
    const FORGET_PASSWORD = 'api.forget.password';
    const LOGOUT = 'api.logout';
}
