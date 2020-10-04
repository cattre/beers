<?php

require 'functions.php';
require 'uploadFunctions.php';

session_start();

$beerFormVisibility = false;
$breweryFormVisibility = false;
$updateBeerFormVisibility = false;
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
    SELECT `beers`.`id` as `beer_id`, `beers`.`name` AS `beer`, `abv`, `style_id`, `styles`.`style`, `brewery_id`, `breweries`.`name`AS `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`
        LEFT JOIN `styles`
        ON `beers`.`style_id` = `styles`.`id`;
';

$getBreweries = '
    SELECT `id`, `name` as `brewery`
    FROM `breweries`
    ORDER BY `name` ASC;
';

$getStyles = '
    SELECT `id`, `style`
    FROM `styles`
    ORDER BY `style` ASC;
';

$addBeer = '
    INSERT INTO `beers` (`name`, `brewery_id`, `style_id`, `abv`, `image`)
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
    WHERE `id` = :id AND (`protected` IS null OR `protected` = 0);
';

$getBeer = '
    SELECT `beers`.`id` as `beer_id`, `beers`.`name` AS `beer`, `abv`, `style_id`, `styles`.`style`, `brewery_id`, `breweries`.`name`AS `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`
        LEFT JOIN `styles`
        ON `beers`.`style_id` = `styles`.`id`
    WHERE `beers`.`id` = :id;
';

$updateBeer = '
    UPDATE `beers`
    SET
        `name` = :beer,
        `brewery_id` = :brewery,
        `style_id` = :beerstyle,
        `abv` = :abv,
        `image` = :photo
    WHERE `id` = :id AND (`protected` IS null OR `protected` = 0);
';

// Returns all beers, any linked breweries, and any linked locations, based on search terms
$search = '
    SELECT `beers`.`id` as `beer_id`, `beers`.`name` AS `beer`, `abv`, `style_id`, `styles`.`style`, `brewery_id`, `breweries`.`name`AS `brewery`, `url`, `county`, `country`, `image`, `protected`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`
        LEFT JOIN `styles`
        ON `beers`.`style_id` = `styles`.`id`;
    WHERE `beers`.`name` LIKE :searchTerm
        OR `breweries`.`name` LIKE :searchTerm
        OR `style` LIKE :searchTerm
        OR `abv` LIKE :searchTerm
        OR `url` LIKE :searchTerm
        OR `county` LIKE :searchTerm
        OR `country` LIKE :searchTerm;
';

// Create db connection
$db = connectDB();

// Query db for beers
if ($_SESSION['searchTerm'] !== '') {
    $beers = search($db, $search, $_SESSION['searchTerm']);
} else {
    $beers = queryDB($db, $getBeers);
}

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
            addBeer($db, $addBeer, $targetFile);
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
if (isset($_POST['deleteBeer'])) {
    $beer = getBeer($db, $getBeer, $_POST['deleteBeer']);
    if (isset($beer['image']) && is_writable($beer['image']) && $beer['image'] != 'media/placeholder.jpg') {
        unlink($beer['image']);
    }
    deleteBeer($db, $deleteBeer, $_POST['deleteBeer']);
    header('Location: beers.php');
}

// Action on selecting update beer button
if (isset($_POST['updateBeer'])) {
    $beer = getBeer($db, $getBeer, $_POST['updateBeer']);
    $beerFormVisibility = false;
    $mainVisibility = false;
    $breweryFormVisibility = false;
    $updateBeerFormVisibility = true;
}

// Action on selecting save changes button
if (isset($_POST['saveChanges'])) {
    if (empty($_POST['beer'])) {
        $nameError = 'Please enter a name';
        $beerFormVisibility = false;
        $mainVisibility = false;
        $breweryFormVisibility = false;
        $updateBeerFormVisibility = true;
    } else if ($_FILES['photo']['name']) {
        $imageError = checkForImage($imageError);
        $imageError = checkNewImage($imageError, $targetFile);
        $imageError = checkFileSize($imageError);
        $imageError = checkFileType($imageError, $imageFileType);
        $imageError = uploadFile($imageError, $targetFile);
        if ($imageError) {
            $beerFormVisibility = false;
            $mainVisibility = false;
            $breweryFormVisibility = false;
            $updateBeerFormVisibility = true;
        } else {
            updateBeer($db, $updateBeer, '', $_POST['id']);
            $beers = queryDB($db, $getBeers);
            header('Location: beers.php');
        }
    } else {
        updateBeer($db, $updateBeer, '', $_POST['id']);
        $beers = queryDB($db, $getBeers);
        header('Location: beers.php');
    }
}

// Action on selecting search button
if (isset($_POST['searchTerm'])) {
    $_SESSION['searchTerm'] = $_POST['searchTerm'];
    header('Location: beers.php');
}

// Action on selecting reset button
if (isset($_POST['reset'])) {
    $_SESSION['searchTerm'] = '';
    header('Location: beers.php');
}