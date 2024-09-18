<?php

class DataBase{
    private $dbh;
    private static $_instance;

    private function __construct(){
        $dbhDatas = parse_ini_file('config.ini');

        try{
            $this->dbh = new PDO(
                "mysql:host={$dbhDatas['DB_HOST']};
                dbname={$dbhDatas['DB_NAME']};
                charset=utf8",
                $dbhDatas['DB_USER'],
                $dbhDatas['DB_PASS']
            );
        }catch(PDOException $exception){
            echo $exception->getMessage()." t'es pas co à ta base mon chat :'(";
            die;
        }
    }

    public static function connectPDO(){
        if (empty(self::$_instance)){
            self::$_instance = new DataBase();
        }
        return self::$_instance->dbh;
    }
}