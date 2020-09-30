<?php

$formVisibility = false;
$mainVisibility = true;

// Returns all beers, any linked breweries, and any linked locations
$beersQuery = '
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

// Create db connection
$db = connectDB();
// Query db for beers
$beers = queryDB($db, $beersQuery);
// Get section letters
$letters = getLetters($beers, 'beer');
// Get breweries
$breweries = queryDB($db, $breweriesQuery);

if (isset($_POST['addBeer'])) {
    $formVisibility = true;
    $mainVisibility = false;
}

if (isset($_POST['save'])) {

}