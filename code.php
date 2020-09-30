<?php

require_once 'functions.php';

$beerFormVisibility = false;
$breweryFormVisibility = false;
$mainVisibility = true;
$nameError = '';

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
    VALUES (:beer, :brewery, :beerstyle, :abv, :photo);
';

$countiesQuery = '
    SELECT DISTINCT `county`, `id`
    FROM `locations`
    ORDER BY `county` ASC;
';

$countriesQuery = '
    SELECT DISTINCT `country`, `id`
    FROM `locations`
    ORDER BY `country` ASC;
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
// Get counties
$counties = queryDB($db, $countiesQuery);
// Get countries
$countries = queryDB($db, $countriesQuery);

if (isset($_POST['addBeer'])) {
    $beerFormVisibility = true;
    $mainVisibility = false;
    $breweryFormVisibility = false;
}

if (isset($_POST['back'])) {
    $beerFormVisibility = false;
    $mainVisibility = true;
    $breweryFormVisibility = false;
}

if (isset($_POST['saveBeer'])) {
    if (empty($_POST['beer'])) {
        $nameError = 'Please enter a name';
        $beerFormVisibility = true;
        $mainVisibility = false;
        $breweryFormVisibility = false;
    } else {
        addBeer($db, $addBeerQuery);
        $beers = queryDB($db, $getBeersQuery);
        $beerFormVisibility = false;
        $mainVisibility = true;
        $breweryFormVisibility = false;
        if (!empty($targetFile)) {
            require_once 'upload.php';
        }
    }
}

if (isset($_POST['addBrewery'])) {
    $beerFormVisibility = false;
    $mainVisibility = false;
    $breweryFormVisibility = true;
}

if (isset($_POST['backOne'])) {
    $beerFormVisibility = true;
    $mainVisibility = false;
    $breweryFormVisibility = false;
}

if (isset($_POST['saveBrewery'])) {
    $beerFormVisibility = true;
    $mainVisibility = false;
    $breweryFormVisibility = false;
}