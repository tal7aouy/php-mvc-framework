<?php


namespace talhaouy\phpmvc\exception;


class ForbiddenException extends \Exception
{
    protected $code = 403;
    protected $message = 'unauthorized';
}