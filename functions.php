<?php

function connectDB() :object {
    $db = new PDO ('mysql:host=db; dbname=beers', 'root', 'password');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

$queryString = '
    SELECT `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`
    FROM `beers`
        INNER JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        INNER JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`;
';

function queryDB(object $db, string $query) {
    $query = $db->prepare($query);
    $query->execute();

    return $query->fetchAll();
}

function getLetters(array $array, string $value) :array {
    $letters = [];
    foreach ($array as $item) {
        $letters[] = $item[$value][0];
    }
    asort($letters);
    return array_values(array_unique($letters));
}