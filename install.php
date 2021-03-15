<?php
class Db
{
	private $servername ;
	private $username  	;
	private $password	;
	private $dbname 	;
	private $sconn		;
	private $dbconn		;
	private static $gcount;
	function __construct($database)
	{
		$this->servername = $database["servername"];
		$this->username = $database["username"];
		$this->password = $database["password"];
		$this->dbname = $database["dbname"];
		$this->sconn = new PDO("mysql:host=$this->servername;", $this->username, $this->password);
		$this->sconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->createDB();
		$this->dbconn = new PDO("mysql:host=$this->servername;dbname=$this->dbname;", $this->username, $this->password);
		$this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	function createDB()
	{
		 try
		 {
			$this->runStatement($this->sconn,"CREATE DATABASE IF NOT EXISTS ".$this->dbname);
			Self::$gcount = 0;
		 }
		 catch(PDOException $e)
		 {
			echo "Connection failed: " . $e->getMessage()."<br>";
		 }
	}

	function createTABLE($table)
	{
		try
		{
			$statement = "CREATE TABLE  IF NOT EXISTS ".$table["name"];
			$statement = $statement."(";
			$count = 0;
			foreach($table["columns"] as $column)
			{
				if ($count != 0)
					$statement = $statement.",".$column;
				else
					$statement = $statement.$column;
				$count++;
			}
			$statement = $statement.")";
			$this->runStatement($this->dbconn, $statement);
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage()."<br>";
		}
		
	}
	function getDBConn()
	{
		return ($this->dbconn);
	}

	function runStatement($pdo,$statement)
	{
		try
		{
			$run = $pdo->prepare($statement);
			$return = $run->execute();
			if($return)
				return($run);
			else
				return (0);
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage()."<br>";
		}
	}
	
	function closeConnections()
	{
		$this->sconn	= null;
		$this->dbconn	= null;
	}
	function gallerycount()
	{
		return(Self::$gcount++);
	}
	function returnRecord($statement)
	{
		$something = $this->runStatement($this->dbconn, $statement);
		
		return($something->fetchAll());
	}
	function insertRecord($record)
	{
		$count 		= 0;
		$statement 	= "INSERT INTO ".$record["table"]["name"];
		$statement 	= $statement."(";
		foreach($record["table"]["fields"] as $column)
		{
			if($count != 0)
				$statement = $statement.",".$column;
			else
				$statement = $statement.$column;
			$count++;
		}
		$count = 0;
		$statement = $statement.") VALUES (";
		foreach($record['values'] as $values)
		{
			if($count != 0)
				$statement = $statement.",".$values;
			else
				$statement = $statement.$values;
			$count++;
		}
		$statement 	= $statement.");";
		$this->runStatement($this->dbconn, $statement);
	}
	function appendRecord($bla){
		$username = toQuote($bla["username"]);
		$token = $bla["token"];
		$statement = "SELECT token FROM users WHERE username = $username";
		$out = $this->returnRecord($statement);
		if ($out[0]["token"] == $token){
			$statement = "UPDATE users SET verified=1 WHERE username = $username";
			$this->runStatement($this->dbconn, $statement);
		}
	}
}
?>