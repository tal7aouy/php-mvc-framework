<?php


namespace app\app\form;


use app\app\exception\InputException;
use app\app\Model;

class Input extends BaseField
{
    public const TYPE_TEXT = "text";
    public const TYPE_NUMBER = "number";
    public const TYPE_PASSWORD = "password";
    public string $type;
    public string $tag;


    public function __construct(Model $model, string $attribute,string $tag)
    {
        $this->type = self::TYPE_TEXT;
        $this->tag = $tag;
       parent::__construct($model,$attribute);
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }


    public function renderField(): string
    {
        $field = null;

        switch ($this->tag){
            case 'textarea':{
                $field = sprintf('<textarea  rows="5" cols="5" name="%s" class="form-control%s" >%s</textarea>',
                    $this->attribute,
                    $this->model->hasError($this->attribute) ? ' is-invalid': '',
                    $this->model->{$this->attribute});
            }break;
            case 'input':{
                $field = sprintf('<input type="%s" name="%s" value="%s" class="form-control%s" >',
                    $this->type,
                    $this->attribute,
                    $this->model->{$this->attribute},
                    $this->model->hasError($this->attribute) ? ' is-invalid': ''
                );
            }break;

            default:{
                throw  new InputException();
            }break;

        }
       return $field;

    }
}