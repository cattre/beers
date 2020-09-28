<?php

$db = new PDO ('mysql:host=db; dbname=beers', 'root', 'password');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$queryString = 'SELECT `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`
FROM `beers`
	INNER JOIN `breweries`
	ON `beers`.`brewery_id` = `breweries`.`id`
	INNER JOIN `locations`
	ON `breweries`.`location_id` = `locations`.`id`;';

$query = $db -> prepare($queryString);
$query->execute();
$beers = $query->fetch();


?>


<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>A world of beers</title>
		<link rel='stylesheet' type= 'text/css' href='normalize.css'>
		<link rel='stylesheet' type= 'text/css' href='beers.css'>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
	</head>
	<body>
		<header>

		</header>
		<main>

		</main>
		<footer>

		</footer>
	</body>
</html>