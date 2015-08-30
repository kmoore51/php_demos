
<?php
	// Would like to make this more dynamic - allow input for database name, table name, and file name.

	//MODIFY THESE VARS IN ORDER TO WORK WITH YOUR DATABASE!
	$host = "localhost:3308";
	$username = "root"; 
	$secret = "8Margie8";
	$db = "population2";
	
	//DATABASE CONNECTION VARS
	$dsn = 'mysql:host=' . $host;
	$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);//Error Handling
	
	//Creates database
	try {
		$dbh = new PDO($dsn, $username, $secret, $options);
		$db_sql = "CREATE DATABASE IF NOT EXISTS `$db`;";
		$dbh->exec(db_sql);
		
		echo "$db created!<br />";	
	}
	catch (PDOException $e) {
		die("DB ERROR 1: " . $e->getMessage() . "<br />");
	}
	
	$dsn .= ';dbname=' . $db;
	$db_table= "new_population";
	//Creates table in database
	try {
		$dbh = new PDO($dsn, $username, $secret, $options);
		$table_sql = "CREATE TABLE IF NOT EXISTS `$db_table` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`city` varchar(150) NOT NULL,
		`state` varchar(150) NOT NULL,
		`population` int(10) unsigned NOT NULL,
		PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		$dbh->exec($table_sql);
		
		echo "Created $db_table table!<br />";
	}
		
	catch(PDOException $e) {
		die("DB ERROR 2: " . $e->getMessage() . "<br />");
	}

	$options = [$options, PDO::MYSQL_ATTR_LOCAL_INFILE => true];//Allows for local file use
	
	//MODIFY THIS VARIABLE FOR CORRECT FILE NAME AND LOCATION!
	$csvfile = "../assets/data.csv";
	
	//Inserts the data into the database
	if(!file_exists($csvfile)) {
		die("File not found. Make sure you specified the correct path.<br />");
	}

	try {
		$dbh = new PDO($dsn, $username, $secret, $options);
		$data_sql = "LOAD DATA LOCAL INFILE " . $dbh->quote($csvfile) . " INTO TABLE new_population FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
		$dbh->exec($data_sql);
		
		echo "Data has been inserted into " . $db . "." . $db_table . "!<br />";
	}
	catch (PDOException $e) {
		die("DB ERROR 3: " . $e->getMessage() . "<br />");
	}
?>