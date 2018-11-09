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
					->get()
					// ->toSql()
			);
?>