<?php


namespace app\app\config;


use app\app\Application;
use app\app\Model;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    abstract public function KeyPrimary(): string;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr)=> ":$attr",$attributes);
        $statements = self::prepare("INSERT INTO $tableName (".implode(',',$attributes).")  VALUES(".implode(',',$params).")");
        foreach ($attributes as $attribute){
            $statements->bindValue(":$attribute",$this->{$attribute});
        }
        $statements->execute();
        return true;
    }

    public function findFirst($query)
    {
        $tableName = static::tableName();
        $attributes = array_keys($query);
        $sql = implode('AND ',array_map(fn($attr) => "$attr = :$attr",$attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($query as $key=>$value){
            $statement->bindValue(":$key",$value);
        }
        $statement->execute();

        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql)
    {
        return Application::$app->data->pdo->prepare($sql);
    }
}