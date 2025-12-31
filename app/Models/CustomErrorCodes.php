<?php
namespace App\Models;

class CustomErrorCodes
{
    const UserExists = 1;
    const AuthenticationFailure = 2;
    const ValidationError = 422;
    const UnhandledException = 500;
}