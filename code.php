<?php

require_once 'functions.php';

$beerFormVisibility = false;
$breweryFormVisibility = false;
$mainVisibility = true;
$nameError = '';

// Returns all beers, any linked breweries, and any linked locations
$getBeers = '
    SELECT `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`;
';

$getBreweries = '
    SELECT `id`, `name` as `brewery`
    FROM `breweries`
';

$getStyles = '
    SELECT DISTINCT `style`
    FROM `beers`
    ORDER BY `style` ASC;
';

$addBeer = '
    INSERT INTO `beers` (`name`, `brewery_id`, `style`, `abv`, `image`)
    VALUES (:beer, :brewery, :beerstyle, :abv, :photo);
';

$getLocations = '
    SELECT DISTINCT `county`, `country`, `id`
    FROM `locations`
    ORDER BY `county` ASC;
';

$addBrewery = '
    INSERT INTO `breweries` (`name`, `url`, `location_id`)
    VALUES (:brewery, :url, :location);
';

// Create db connection
$db = connectDB();
// Query db for beers
$beers = queryDB($db, $getBeers);
// Get section letters
$letters = getLetters($beers, 'beer');
// Get breweries
$breweries = queryDB($db, $getBreweries);
// Get styles
$styles = queryDB($db, $getStyles);
// Get locations
$locations = queryDB($db, $getLocations);


// Action on selecting add a new beer button
if (isset($_POST['addBeer'])) {
    $beerFormVisibility = true;
    $mainVisibility = false;
    $breweryFormVisibility = false;
}

// Action on going back from add new beer page
if (isset($_POST['back'])) {
    $beerFormVisibility = false;
    $mainVisibility = true;
    $breweryFormVisibility = false;
}

// Action on saving from add a new beer page
if (isset($_POST['saveBeer'])) {
    if (empty($_POST['beer'])) {
        $nameError = 'Please enter a name';
        $beerFormVisibility = true;
        $mainVisibility = false;
        $breweryFormVisibility = false;
    } else {
        addBeer($db, $addBeer);
        $beers = queryDB($db, $getBeers);
        $beerFormVisibility = false;
        $mainVisibility = true;
        $breweryFormVisibility = false;
        if (!empty($targetFile)) {
            require_once 'upload.php';
        }
    }
}

// Action on selecting add a new brewery button
if (isset($_POST['addBrewery'])) {
    $beerFormVisibility = false;
    $mainVisibility = false;
    $breweryFormVisibility = true;
}

// Action on going back from add new brewery page
if (isset($_POST['backOne'])) {
    $beerFormVisibility = true;
    $mainVisibility = false;
    $breweryFormVisibility = false;
}

// Action on saving from add a new brewery page
if (isset($_POST['saveBrewery'])) {
    if (empty($_POST['brewery'])) {
        $nameError = 'Please enter a name';
        $beerFormVisibility = false;
        $mainVisibility = false;
        $breweryFormVisibility = true;
    } else {
        addBrewery($db, $addBrewery);
        $breweries = queryDB($db, $getBreweries);
        $beerFormVisibility = true;
        $mainVisibility = false;
        $breweryFormVisibility = false;
    }
}