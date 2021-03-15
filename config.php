<?php
	require("install.php");
	global $db;
	$db = new Db(
				array	(
						"servername"	=> "localhost",
						"username"		=> "root",
						"password"		=> "123456",
						"dbname"		=> "CAMAGRU"
						)
				);
	$usercolumns = array	(
						"userID INT not NULL AUTO_INCREMENT PRIMARY KEY",
						"username VARCHAR(20) not NULL",
						"`password` VARCHAR(255) not NULL",
						"email VARCHAR(50) not NULL",
						"token VARCHAR(255) not NULL",
						"verified TINYINT(1) NOT NULL DEFAULT '0'"
						);
	$db->createTABLE(
					array	(	"name"		=>"USERS",
								"columns"	=>$usercolumns
							)
					);

	$imagecolumns = array(
						"id INT NOT NULL AUTO_INCREMENT PRIMARY KEY",
						"image LONGBLOB not NULL",
						"userID INT NOT NULL default '0'",
						"`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
	);

	$db->createTABLE(
					array	(	"name"=>"IMAGES",
								"columns"=>$imagecolumns
							)
					);

	function toQuote($string)
	{
		return "'".$string."'";
	}
?>