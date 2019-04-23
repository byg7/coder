<?php
namespace System\DB;
use PDO;
use PDOException;

class Connection{
    static function MySql(){
        try{
            $pdo = new PDO(
                MYSQL_HOST,
                MYSQL_LOGIN,
                MYSQL_PASSWORD,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                ));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            return null;
        }
        return $pdo;
    }
}