<?php

namespace brewSQL;

use brewSQL\DB_Connect;
require "Connect.php";

class Db_Commands extends DB_Connect {

 	protected static $table;
 	protected static $select;
 	protected static $statement;
 	protected static $where;
 	protected static $data;

	public function __construct(){


	}

	/**
	*@param string $db_where: where query in string/array form
	*@return return an object of this
	*/
	public function where($db_where, $opt = []){

		//check if value has been set for $where

		//if yes brew new query and concat it with old query

		//if no brew query
		
		if(empty(self::$where)) {

			if(is_array($db_where))
			{
				//if db_where is array, brew it into a query string
				self::$where = $this->WhereArrayBrewToString($db_where, 0, $opt);
			}else{
				//elese if its a plain query string, then plug in directly 
				self::$where = $db_where;
			}

			self::$statement = self::$statement . " WHERE " . self::$where;

		}else{

			if(is_array($db_where))
			{
				//if db_where is array, brew it into a query string
				self::$where = $this->WhereArrayBrewToString($db_where, 1, $opt);
			}else{
				//elese if its a plain query string, then plug in directly 
				self::$where = $db_where;
			}

			self::$statement = self::$statement . self::$where;
		}
		
		return $this;
	}

	/**
	*@param string $selectS: select query in string form
	*@return return an object of this
	*/
	public function select($selectS){

		self::$select = $selectS;
		self::$statement = "SELECT " . self::$select .  " FROM " . self::$table;
		return $this;
	}

	/**
	*Executes Query
	*@return returns an array of data
	*/
	public function get(){

		$conn = DB_Connect::connect();
		$stmt = $conn->prepare(self::$statement);
		//$stmt->bindParam(self::$data);
		// print_r(self::$data);
		$result = $stmt->execute(self::$data);
		
		if($result){
			return $stmt->fetchAll();
		}
	}

	/**
	*Returns generated sql statement
	*/
	public function toSql(){
		// print_r(self::$data);
		return self::$statement;
	}


	/**
	*@param string $array where query in array form
	*@param integer $node simple 1 or 0 integer that tells the function how to brew a string
	*@param 
	*@return returns as query string 
	*/

/*	public function WhereArrayBrewToString($whereArr, $node ,$opt = []){
		//print_r($opt);
		//variables
		$i = 1;
		$query = "";
		$count = count($whereArr);
		$cond = (!empty($opt))? $opt :"AND"; //if array is greater than 1 attach AND condition 
		
		if($node == 1){
			$cond = (!empty($opt))? $opt :"AND"; 
		}

		while ($row = current($whereArr)) {
			//get current key
			$key = key($whereArr);
			//brew query
			$query .= (($node == 1)? " {$cond} ": " ") . $key . " = " . $whereArr[$key] . (($count != $i)? " {$cond} ": " ");
			next($whereArr);
			$i++;
		}

		return $query;	
	}*/

	/**
	*@param string: where query in string form
	*@param node: w
	*@return returns as query string 
	*/
	public function WhereArrayBrewToString($whereArr, $node ,$opt = []){
		//print_r($opt);
		//variables
		$i = 1;
		$query = "";
		$count = count($whereArr);
		$cond = (!empty($opt))? $opt :"AND"; //if array is greater than 1 attach AND condition 
		
		if($node == 1){
			$cond = (!empty($opt))? $opt :"AND"; 
		}

		while ($row = current($whereArr)) {

			//get current key
			$key = key($whereArr);
			//brew query
		
			$query .= (($node == 1)? " {$cond} ": " ") . $key . " = :" . $key . (($count != $i)? " {$cond} ": " ");

			self::$data[":".$key] = $whereArr[$key];

			next($whereArr);
			$i++;
		}

		return $query;	
	}
}


/**
* 
*/
class DB extends Db_Commands
{
	private $db;
	protected $conn;
	

	public function __construct()
	{
		$this->db = new Db_Commands;
		
	}

	public function table($q){
		parent::$table = $q;
		parent::$statement = parent::$table;

		return $this->db;
	}

}


/*
$array = [
    'fruit1' => 'apple',
    'fruit2' => 'orange',
    'fruit3' => 'grape',
    'fruit4' => 'apple',
    'fruit5' => 'apple'];

// this cycle echoes all associative array
// key where value equals "apple"
while ($fruit_name = current($array)) {
    
        echo key($array).'<br />';
    
    next($array);
}*/

