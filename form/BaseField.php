<?php


namespace talhaouy\phpmvc\form;


use talhaouy\phpmvc\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }
    public function __toString()
    {
        return sprintf('
            <div class="mb-3">
                 <label>%s</label>
                    %s
                <div class="invalid-feedback">%s</div>
            </div>
        ',$this->model->getLabel($this->attribute),
            $this->renderField(),
            $this->model->getFirstError($this->attribute)
        );
    }
    abstract public function renderField():string;
}