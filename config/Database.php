<?php


namespace talhaouy\phpmvc\config;


use talhaouy\phpmvc\Application;
use PDO;

class Database
{
    public PDO $pdo;
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user= $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn,$user,$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigration()
    {
        $this->createMigrationTable();
      $appliedMigrations = $this->getAppliedMigrations();
      $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR."/database/migrations");
        $toApplayMigrations = array_diff($files,$appliedMigrations);
        foreach ($toApplayMigrations as $migration){
            if($migration === '.' || $migration === '..'){
                continue;
            }
            require_once Application::$ROOT_DIR."/database/migrations/".$migration;
            $fileName = pathinfo($migration,PATHINFO_FILENAME);
            $instance = new $fileName();
            $this->log("Applaying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;


        }
        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        }else{
            $this->log("All Migrations are applied");
        }
    }


    public function createMigrationTable()
    {
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS migrations(
                id INT AUTO_INCREMENT PRIMARY KEY ,
                migration VARCHAR (255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=INNODB;');
    }

    public function getAppliedMigrations()
    {
    $query = $this->pdo->prepare("SELECT migration FROM migrations");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $records = implode(',',array_map(fn($migration)=>"('$migration')",$migrations));
        $query = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $records");
        $query->execute();

    }

    public function prepare($query)
    {
        return $this->pdo->prepare($query);
    }
    protected function log($message){
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}