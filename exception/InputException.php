<?php


namespace talhaouy\phpmvc\exception;


class InputException extends \Exception
{
    protected $message = 'Error in field !';
    protected $code = 404;
}