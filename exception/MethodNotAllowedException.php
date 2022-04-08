<?php


namespace talhaouy\phpmvc\exception;

/**
 * Class MethodNotAllowedException
 *
 * @package talhaouy\phpmvc\exception
 * 
 */
class MethodNotAllowedException extends \Exception
{
  protected $message = 'Request method  Not Allowed';
  protected $code = 405;
}
