<?php


namespace app\app;


use app\app\config\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function displayName(): string;
}