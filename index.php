<?php

	
ini_set("display_errors", true);
error_reporting(E_ALL);

	require "brewsql.php";

	$q = new brewSQL\DB();

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
					// ->get()
					->toSql()
			);

	$data = ["email" => "kabanofor", 'id' => '001', 'uid' => '001'];
	print_r($data);
	echo "<br>";
	// $colums = implode(" ,", array_keys($data));
	// $placeholers = ":" . implode(", :", array_keys($data));

	// print_r("(" .$colums . ") VALUES (". $placeholers .")");

	print_r($q->table("causer_tb")
			->insertOne($data)
			->toSql()
		);
?>