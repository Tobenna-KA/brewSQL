# brewSQL
Sql Synthesizer library... Built as a standalone sql brewing library, aimed at automation of some simple SQL tasks

<b>mind you, this is a work in progress<b>
  
#### Usage:
```php
<?php
 require brewsql.php;
 $q = new brewSQL\DB();
 
 echo $q->table("users")->select("*")
	->where(['email' => 'user@example.com'])
	->where(['id' => '001'], 'OR')
	->toSql();	
 
```
#### Output:
```sql
SELECT * FROM users WHERE email = :email OR id = :id 
```
 
