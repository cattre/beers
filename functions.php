<?php

/**
 * Creates connection to database
 *
 * @return object
 *               Database object
 */
function connectDB() :object {
    $db = new PDO ('mysql:host=db; dbname=beers', 'root', 'password');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

// Returns all beers, any linked breweries, and any linked locations
$beersQuery = '
    SELECT `beers`.`name` as `beer`, `abv`, `style`, `breweries`.`name` as `brewery`, `url`, `county`, `country`, `image`
    FROM `beers`
        LEFT JOIN `breweries`
        ON `beers`.`brewery_id` = `breweries`.`id`
        LEFT JOIN `locations`
        ON `breweries`.`location_id` = `locations`.`id`;
';

/**
 * Queries database and returns results
 *
 * @param object $db
 *                  Database object
 * @param string $query
 *                     Query to be executed
 *
 * @return array
 *              Array of query results
 */
function queryDB(object $db, string $query) :array {
    $query = $db->prepare($query);
    $query->execute();

    return $query->fetchAll();
}

/**
 * Iterates through database items to get section letters
 *
 * @param array  $dbArray
 *                       Database array
 * @param string $value
 *                     Value to use for section letters
 *
 * @return array
 *              Array of section letters
 */
function getLetters(array $dbArray, string $value) :array {
    $letters = [];
    foreach ($dbArray as $item) {
        $letters[] = $item[$value][0];
    }
    asort($letters);
    return array_values(array_unique($letters));
}

/**
 * Builds beer summary based on what values are available
 *
 * @param array $beer
 *                   Single beer database entry (row)
 * @return string
 *               Summary text
 */
function getSummary (array $beer) :string {
    if (isset($beer['brewery']) && isset($beer['county']) && isset($beer['country'])) {
        return $beer['brewery'] . ' - ' . $beer['county'] . ', ' . $beer['country'];
    } else if (isset($beer['brewery']) && isset($beer['county']) && !isset($beer['country'])) {
        return $beer['brewery'] . ' - ' . $beer['county'];
    } else if (isset($beer['brewery']) && !isset($beer['county']) && isset($beer['country'])) {
        return $beer['brewery'] . ' - ' . $beer['country'];
    } else if (isset($beer['brewery']) && !isset($beer['county']) && !isset($beer['country'])) {
        return $beer['brewery'];
    } else {
        return '';
    }
}