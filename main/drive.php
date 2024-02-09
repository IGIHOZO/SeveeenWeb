<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


date_default_timezone_set('Africa/Kigali');

//====================================================================================================== CONNECTION
    $dbname = 'seveeen_web';
    $user = 'root';
    $pass = '';

    // $dbname = 'mpjusdko_seveeen_web';
    // $user = 'mpjusdko';
    // $pass = 'z0HpWFx1%@48';


    $con = new PDO("mysql:host=localhost;dbname=$dbname", $user, $pass);

class DbConnect
{
    private $host='localhost';
    private $dbName = 'seveeen_web';
    private $user = 'root';
    private $pass = '';


    // private $host='localhost';
    // private $dbName = 'mpjusdko_seveeen_web';
    // private $user = 'mpjusdko';
    // private $pass = 'z0HpWFx1%@48';

    public $conn;
    



    public function connect()
    {
        try {
         $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName, $this->user, $this->pass);
         return $conn;
        } catch (PDOException $e) {
            echo "Database Error ".$e->getMessage();
            return null;
        }
    }
}












?>