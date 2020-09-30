<?php

$formVisibility = false;
$mainVisibility = true;

// Returns all beers, any linked breweries, and any linked locations
$getBeersQuery = '
    SELECT `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`;
';

$breweriesQuery = '
    SELECT `id`, `name` as `brewery`
    FROM `breweries`
';

$stylesQuery = '
    SELECT DISTINCT `style`
    FROM `beers`
    ORDER BY `style` ASC;
';

$addBeerQuery = '
    INSERT INTO `beers` (`name`, `brewery_id`, `style`, `abv`, `image`)
    VALUES (:beer, :brewery, :beerstyle, :abv, media/placeholder.jpg);
';

// Create db connection
$db = connectDB();
// Query db for beers
$beers = queryDB($db, $getBeersQuery);
// Get section letters
$letters = getLetters($beers, 'beer');
// Get breweries
$breweries = queryDB($db, $breweriesQuery);
// Get styles
$styles = queryDB($db, $stylesQuery);

if (isset($_POST['addBeer'])) {
    $formVisibility = true;
    $mainVisibility = false;
}

if (isset($_POST['cancel'])) {
    $formVisibility = false;
    $mainVisibility = true;
}

if (isset($_POST['save'])) {
    addBeer($db, $addBeerQuery);
}