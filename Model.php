<?php


namespace app\app;

abstract class Model
{
    public const RULE_REQUIRED = "required";
    public const RULE_MIN = "min";
    public const RULE_MAX = "max";
    public const RULE_EMAIL = "email";
    public const RULE_MATCH= "match";
    public const RULE_UNIQUE= "unique";
    public function loadData($data)
    {
        foreach ($data as $key => $value){
            if(property_exists($this,$key)){
                $this->{$key} = $value;
            }
        }
    }
    abstract public function  rules():array;

    public function labels():array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }
    public array $errors = [];
    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach ($rules as $rule){
                $ruleName = $rule;
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }
                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attribute,self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute,self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorForRule($attribute,self::RULE_MIN,$rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addErrorForRule($attribute,self::RULE_MAX,$rule);
                }
                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute,self::RULE_MATCH,$rule);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->data->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attribute ");
                    $statement->bindValue(":attribute",$value);
                    $statement->execute();
                    $obj = $statement->fetchObject();
                    if($obj){
                        $this->addErrorForRule($attribute,self::RULE_UNIQUE,['field'=>$this->getLabel($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRule(string $attributes, string $rule, $args = [])
    {

     $message = $this->errorMessages()[$rule] ?? '';
     foreach ($args as $key => $value){
        $message = str_replace("{{$key}}",$value,$message);
     }
     $this->errors[$attributes][] = $message;
    }
    public function addError(string $attributes, string $message)
    {
     $this->errors[$attributes][] = $message;
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email adress',
            self::RULE_MIN => 'Min length of this field must be {min} ?',
            self::RULE_MAX => 'Max length of this field must be {max} ?',
            self::RULE_MATCH => 'This field must be the same as {match} ?',
            self::RULE_UNIQUE => 'The {field} has already been taken.'
        ];
    }

  public function hasError($attribute)
 {
 return $this->errors[$attribute] ?? false;
 }

    public function getFirstError($attribute)
    {
    return $this->errors[$attribute][0] ?? false;
    }
}