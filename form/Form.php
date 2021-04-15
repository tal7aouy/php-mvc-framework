<?php


namespace app\app\form;


use app\app\Model;

class Form
{
    public static function begin($action,$method)
    {
        echo sprintf('<form action="%s" method="%s">',$action,$method);
        return new Form();
    }
    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model,$attribute,$tag)
    {
        return new Input($model,$attribute,$tag);
    }
}