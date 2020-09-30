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
 * @param array  $array
 * @param string $value
 *
 * @return array
 */
function getLetters(array $array, string $value) :array {
    $letters = [];
    foreach ($array as $item) {
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

/**
 * Adds new beer to database
 *
 * @param object $db
 *                  Database object
 * @param string $queryString
 *                           Insert item query
 */
function addBeer(object $db, string $queryString) {
    $brewery = $_POST['brewery'] !== '' ? $_POST['brewery'] : null;
    $beerstyle = $_POST['style'] !== '' ? $_POST['style'] : null;
    $abv = $_POST['abv'] !== '' ? $_POST['abv'] : null;
    $photo = empty($targetFile) ?? 'media/placeholder.jpg';

    $query = $db->prepare($queryString);
    $query->execute([
        ':beer' => $_POST['beer'],
        ':brewery' => $brewery,
        ':beerstyle' => $beerstyle,
        ':abv' => $abv,
        ':photo' => $photo
    ]);
}