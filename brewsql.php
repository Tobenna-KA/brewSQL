<?php ini_set("display_errors", true);
error_reporting(E_ALL);

/**
* File Created by: Abanofor K.O Tobenna
* Project: HomeBrew
* Description:  
*				HomeBrew is a stand-alone Mysql Query Builder, focused on reducing the
*				ammount of lines of code usually needed to come up with complex queries.
*				The hope is that it	will take up a life of its own in Automating queries.
*				Inspired By the Laravels own Query Builder.
*
* Date: Jan-05-2018
*/


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

class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'campafia_db';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'red@1night';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
}

 class Db_Commands extends DB_Connect {

 	protected static $table;
 	protected static $select;
 	protected static $statement;
 	protected static $where;
 	protected static $data;

	public function __construct(){


	}

	/**
	*@param $db_where: where query in string/array form
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
	*@param $selectS: select query in string form
	*@return return an object of this
	*/
	public function select($selectS){

		self::$select = $selectS;
		self::$statement = "SELECT " . self::$select .  " FROM " . self::$table;
		return $this;
	}

	/**
	*Executes Query 
	*/
	public function get(){

		$conn = static::connect();
		$stmt = $conn->prepare(self::$statement);
		//$stmt->bindParam(self::$data);
		$result = $stmt->execute(self::$data);
		if($result){
			return $result;
		}
	}

	/**
	*Returns Query in prure sql statement
	*/
	public function toSql(){
		print_r(self::$data);
		return self::$statement;
	}


	/**
	*@param string: where query in string form
	*@param $array: where query in array form
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


$q = new DB;

//foreach (key([`email` => 'kabanofor@yahoo.com']) as $key => $value) {
	//print_r(key([`email` => 'kabanofor@yahoo.com']);
//}
print_r(	$q->table("causer_tb")->select("*")
				->where([
					'email' => 'kabanofor@yahoo.com'
				])
				->where([
					'id' => '001'
				], 'OR')
				->toSql()
		);
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

