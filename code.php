<?php

require 'functions.php';
require 'uploadFunctions.php';
require 'queries.php';
require '../connectDB.php';

session_start();

$display = setDisplay('list');

$nameError = '';
$imageError = '';

// Image upload variables
if ($_FILES) {
    $targetDir = 'media' . DIRECTORY_SEPARATOR;
    $targetFile = $targetDir . basename($_FILES['photo']['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
}

// Create db connection
$db = connectDB();

// Query db for beers
if (isset($_SESSION['searchTerm'])) {
    if ($_SESSION['searchTerm'] !== '') {
        $beers = search($db, $search, $_SESSION['searchTerm']);
    } else {
        $beers = queryDB($db, $getBeers);
    }
}

//Get beers
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
    $display = setDisplay('beer');
}

// Action on going back from add new beer page
if (isset($_POST['back'])) {
    header('Location: beers.php');
}

// Action on saving a beer
if (isset($_POST['save'])) {
    // No beer name provided
    if (empty($_POST['beer'])) {
        $nameError = 'Please enter a name';
        $display = setDisplay('beer');
    }
    // Image provided to upload
    else if ($_FILES['photo']['name']) {
        $imageError = checkForImage($imageError);
        $imageError = checkNewImage($imageError, $targetFile);
        $imageError = checkFileSize($imageError);
        $imageError = checkFileType($imageError, $imageFileType);
        $imageError = uploadFile($imageError, $targetFile);
        // Error with image provided
        if ($imageError) {
            $display = setDisplay('beer');
        }
        // No error with image
        else {
            // Create new beer
            if (empty($_POST['id'])) {
                addBeer($db, $addBeer, $targetFile);
                $beers = queryDB($db, $getBeers);
                header('Location: beers.php');
            }
            // Update existing beer
            else {
                $beer = getBeer($db, $getBeer, $_POST['id']);
                if (isset($beer['image']) && is_writable($beer['image']) && $beer['image'] != 'media/placeholder.jpg') {
                    unlink($beer['image']);
                }
                updateBeer($db, $updateBeer, $targetFile, $_POST['id']);
                $beers = queryDB($db, $getBeers);
                header('Location: beers.php');
            }
        }
    }
    // No image provided
    else {
        // Create new beer
        if (empty($_POST['id'])) {
            addBeer($db, $addBeer, '');
            $beers = queryDB($db, $getBeers);
            header('Location: beers.php');
        }
        // Update existing beer
        else {
            $beer = getBeer($db, $getBeer, $_POST['id']);
            updateBeer($db, $updateBeer, $beer['image'], $_POST['id']);
            $beers = queryDB($db, $getBeers);
            header('Location: beers.php');
        }
    }
}

// Action on selecting add a new brewery button
if (isset($_POST['addBrewery'])) {
    if (isset($_POST['id']) && $_POST['id'] != '') {
        $beer = getBeer($db, $getBeer, $_POST['id']);
    }
    $display = setDisplay('brewery');
}

// Action on going back from add new brewery page
if (isset($_POST['backOne'])) {
    if (isset($_POST['id']) && $_POST['id'] != '') {
        $beer = getBeer($db, $getBeer, $_POST['id']);
    }
    $display = setDisplay('beer');
}

// Action on saving from add a new brewery page
if (isset($_POST['saveBrewery'])) {
    if (empty($_POST['brewery'])) {
        $nameError = 'Please enter a name';
        $display = setDisplay('brewery');

    } else {
        addBrewery($db, $addBrewery);
        $breweries = queryDB($db, $getBreweries);
        if (isset($_POST['id']) && $_POST['id'] != '') {
            $beer = getBeer($db, $getBeer, $_POST['id']);
        }
        $display = setDisplay('beer');
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
    $display = setDisplay('beer');
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