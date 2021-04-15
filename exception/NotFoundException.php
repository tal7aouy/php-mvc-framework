<?php


namespace talhaouy\phpmvc\exception;


class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = "Not Found";
}