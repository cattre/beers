<?php

require_once 'functions.php';
require_once 'uploadFunctions.php';

$beerFormVisibility = false;
$breweryFormVisibility = false;
$mainVisibility = true;
$nameError = '';
$imageError = '';

// Image upload variables
if ($_FILES) {
    $targetDir = 'media' . DIRECTORY_SEPARATOR;
    $targetFile = $targetDir . basename($_FILES['photo']['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
}

// Returns all beers, any linked breweries, and any linked locations
$getBeers = '
    SELECT `beers`.`id`, `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`, `protected`
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

$deleteBeer = '
    DELETE FROM `beers`
    WHERE `id` = :id AND `protected` != 1;
';

$getBeer = '
    SELECT `beers`.`id`, `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`;
    WHERE `id` = :id
';

$updateBeer = '
    UPDATE `beers`
    SET
        `name` = :beer,
        `brewery_id` = :brewery,
        `style` = :beerstyle,
        `abv` = :abv,
        `image` = :photo
    WHERE `id` = :id;
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
    $beer = getBeer($db, $getBeer, $_POST['addBeer']);
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
    } else if ($_FILES['photo']['name']) {
        $imageError = checkForImage($imageError);
        $imageError = checkNewImage($imageError, $targetFile);
        $imageError = checkFileSize($imageError);
        $imageError = checkFileType($imageError, $imageFileType);
        $imageError = uploadFile($imageError, $targetFile);
        if ($imageError) {
            $beerFormVisibility = true;
            $mainVisibility = false;
            $breweryFormVisibility = false;
        } else {
            addBeer($db, $addBeer, '');
            $beers = queryDB($db, $getBeers);
            header('Location: beers.php');
            $beerFormVisibility = false;
            $mainVisibility = true;
            $breweryFormVisibility = false;
        }
    } else {
        addBeer($db, $addBeer, '');
        $beers = queryDB($db, $getBeers);
        header('Location: beers.php');
        $beerFormVisibility = false;
        $mainVisibility = true;
        $breweryFormVisibility = false;
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

// Action on selecting delete button
if (isset($_POST['delete'])) {
    deleteBeer($db, $deleteBeer);
    header('Location: beers.php');
}

