<?php 

/**
*@author File Created by: Abanofor K.O Tobenna
*@version 1.0
*Project: HomeBrew
*Description:  
*				HomeBrew is a stand-alone Mysql Query Builder, focused on reducing the
*				ammount of lines of code usually needed to come up with complex queries.
*				The hope is that it	will take up a life of its own in Automating queries.
*				Inspired By the Laravels own Query Builder.
*
*Date: Jan-05-2018
*/ 

namespace brewSQL;

use brewSQL\Config;
use PDO;
require "Config.php";


/**
* 
*/
abstract class DB_Connect
{
	
	function __construct()
	{
		# code...
	}

	public static function Dconnect(){

		$conn = mysqli_connect(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME);

		return $conn;
	}

	protected static function connect()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}

?>