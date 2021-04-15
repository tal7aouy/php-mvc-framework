<?php


namespace talhaouy\phpmvc;


use talhaouy\phpmvc\config\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function displayName(): string;
}