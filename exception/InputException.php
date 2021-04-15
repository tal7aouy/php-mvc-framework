<?php


namespace app\app\exception;


class InputException extends \Exception
{
    protected $message = 'Error in field !';
    protected $code = 404;
}