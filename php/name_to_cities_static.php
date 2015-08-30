
<?php
//MODIFY THESE VARIABLES IN ORDER TO WORK WITH YOUR DATABASE!
	$host = "localhost:3308";
	$username = "root"; 
	$secret = "8Margie8";
	$db = "population2";
	$dbTable= "new_population";
	
	//DATABASE CONNECTION VARS
	$dsn = 'mysql:host=' . $host . ';dbname=' . $db;
	$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);//Error Handling
	
	$name = "kandy";
	$nameArray = str_split(strtoupper($name), 1);
	$nameStr = "";
	foreach($nameArray as $k=>$v) {
		$nameStr .= "'" . $v . "', ";
	}
	$nameStr = rtrim($nameStr, ', ');
	
	//Selects and displays the cities with highest population when city's first letter is in name ordered by letters in name	
	//would like to get next city when name contains subsequent same letters, ex., 1st 'a' in martha would return 1st 'a' city with highest population and 2nd 'a' would return next 'a' city with next highest population
	try {
		$dbh = new PDO($dsn, $username, $secret, $options);
		$requestSql = $dbh->prepare("SELECT city, state, population FROM (SELECT * FROM  new_population order by population DESC) as t WHERE LEFT(city, 1) IN (" . $nameStr . ") GROUP BY LEFT(city, 1) HAVING MAX(population);");
		$requestSql->execute();

		// Set the resulting array to associative
		$result = $requestSql->setFetchMode(PDO::FETCH_ASSOC);
		$tempArray = array();
		if($result) {
			foreach($requestSql->fetchAll() as $k=>$v) {
				$tempArray[$v['city'][0]] = ['city' => ltrim($v['city'], $v['city'][0]), 'state' => $v['state'], 'pop' => $v['population']];
			}
			
			$tempStr = str_split(str_replace("'", "", str_replace(", ", "", $nameStr)));
			foreach($tempStr as $char) {
				echo "<span style='font-size: 24px; font-weight: bold; color: #0000FF;'>" . $char . "</span>" . $tempArray[$char]['city'] . ", " . $tempArray[$char]['state'] . " - " . number_format($tempArray[$char]['pop'], 0, ".", ",") . " people";
				echo "<br />";
			}
		}
		$dbh = null;
	}
	catch (PDOException $e) {
		die("DB ERROR 1: " . $e->getMessage() . "<br />");
	}
?>