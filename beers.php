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
$beers = $query->fetchAll();


foreach ($beers as $beer) {
    $letters[] = $beer['beer'][0];
}

?>


<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>A world of beers</title>
        <link href='https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Mansalva&display=swap' rel='stylesheet'>
        <link rel='stylesheet' type= 'text/css' href='normalize.css'>
		<link rel='stylesheet' type= 'text/css' href='beers.css'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<meta charset='UTF-8'>
	</head>
	<body>
		<header>
            <h1>A world of beer</h1>
            <h3>(starting in the UK)</h3>
		</header>
		<main>
            <?php if (isset($letters)) {
                $letters = array_unique($letters);
                foreach ($letters as $letter): ?>
                    <section class='letter'>
                        <h1><?php echo $letter ?></h1>
                        <?php foreach ($beers as $beer): ?>

                        <?php endforeach; ?>
                    </section>
                <?php endforeach; }?>
		</main>
		<footer>

		</footer>
	</body>
</html>